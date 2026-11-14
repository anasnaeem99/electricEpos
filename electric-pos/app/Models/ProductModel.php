<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'tbl_product';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'business_id', 'sku', 'internal_code', 'name', 'description', 'category_id',
        'subcategory_id', 'brand_id', 'manufacturer_id', 'base_unit_id', 'tax_id',
        'purchase_price', 'retail_price', 'wholesale_price', 'min_stock', 'max_stock',
        'reorder_level', 'batch_tracking', 'expiry_tracking', 'is_weighing',
        'weight_unit', 'price_per_weight', 'scale_plu', 'barcode_wt_prefix',
        'decimal_precision', 'image', 'is_active'
    ];

    public function getAllProducts($businessId, $filters = [])
    {
        $builder = $this->db->table('tbl_product p')
            ->select('p.*, c.name as category_name, b.name as brand_name, u.name as unit_name, u.abbreviation as unit_abbr, t.rate as tax_rate')
            ->join('tbl_product_category c', 'c.id = p.category_id', 'left')
            ->join('tbl_product_brand b', 'b.id = p.brand_id', 'left')
            ->join('tbl_product_unit u', 'u.id = p.base_unit_id', 'left')
            ->join('tbl_tax_rate t', 't.id = p.tax_id', 'left')
            ->where('p.business_id', $businessId);

        if (!empty($filters['category_id'])) {
            $builder->where('p.category_id', $filters['category_id']);
        }
        if (!empty($filters['brand_id'])) {
            $builder->where('p.brand_id', $filters['brand_id']);
        }
        if (isset($filters['is_active'])) {
            $builder->where('p.is_active', $filters['is_active']);
        }

        return $builder->orderBy('p.id', 'DESC')->get()->getResult();
    }

    public function getProductById($id)
    {
        return $this->db->table('tbl_product p')
            ->select('p.*, c.name as category_name, b.name as brand_name, u.name as unit_name, u.abbreviation as unit_abbr, t.rate as tax_rate')
            ->join('tbl_product_category c', 'c.id = p.category_id', 'left')
            ->join('tbl_product_brand b', 'b.id = p.brand_id', 'left')
            ->join('tbl_product_unit u', 'u.id = p.base_unit_id', 'left')
            ->join('tbl_tax_rate t', 't.id = p.tax_id', 'left')
            ->where('p.id', $id)
            ->get()->getRow();
    }

    public function searchProductsForPos($keyword, $businessId, $storeId, $limit = 25)
    {
        $keyword = trim($keyword);
        if (empty($keyword)) return [];

        // Check if barcode match directly
        $barcodeMatch = $this->db->table('tbl_product_barcode pb')
            ->select('pb.product_id')
            ->where('pb.barcode', $keyword)
            ->get()->getRow();

        $builder = $this->db->table('tbl_product p')
            ->select('p.id, p.name, p.sku, p.retail_price, p.wholesale_price, p.purchase_price, p.batch_tracking, p.expiry_tracking, p.is_weighing, p.scale_plu, u.name as unit_name, u.abbreviation as unit_abbr, t.rate as tax_rate, COALESCE(SUM(sb.quantity), 0) as current_stock')
            ->join('tbl_product_unit u', 'u.id = p.base_unit_id', 'left')
            ->join('tbl_tax_rate t', 't.id = p.tax_id', 'left')
            ->join('tbl_stock_balance sb', 'sb.product_id = p.id AND sb.store_id = ' . (int)$storeId, 'left')
            ->where('p.business_id', $businessId)
            ->where('p.is_active', 1);

        if ($barcodeMatch) {
            $builder->where('p.id', $barcodeMatch->product_id);
        } else {
            $builder->groupStart()
                ->like('p.name', $keyword)
                ->orLike('p.sku', $keyword)
                ->orLike('p.internal_code', $keyword)
            ->groupEnd();
        }

        $builder->groupBy('p.id');
        $builder->limit($limit);
        $results = $builder->get()->getResult();

        // Attach barcodes
        foreach ($results as $item) {
            $item->barcodes = $this->db->table('tbl_product_barcode')
                ->where('product_id', $item->id)
                ->get()->getResult();
        }

        return $results;
    }

    public function getBarcodes($productId)
    {
        return $this->db->table('tbl_product_barcode pb')
            ->select('pb.*, u.name as unit_name')
            ->join('tbl_product_unit u', 'u.id = pb.unit_id', 'left')
            ->where('pb.product_id', $productId)
            ->get()->getResult();
    }

    public function saveBarcode($productId, $unitId, $barcode, $isDefault = 0)
    {
        $existing = $this->db->table('tbl_product_barcode')->where('barcode', $barcode)->get()->getRow();
        if ($existing) {
            if ($existing->product_id != $productId) {
                return false; // already taken
            }
            return true;
        }
        return $this->db->table('tbl_product_barcode')->insert([
            'product_id' => $productId,
            'unit_id'    => $unitId,
            'barcode'    => $barcode,
            'is_default' => $isDefault
        ]);
    }

    public function getBatches($productId)
    {
        return $this->db->table('tbl_product_batch')
            ->where('product_id', $productId)
            ->where('is_active', 1)
            ->orderBy('expiry_date', 'ASC')
            ->get()->getResult();
    }

    public function getProductStockOverview($productId, $storeId = null)
    {
        $builder = $this->db->table('tbl_stock_balance sb')
            ->select('sb.*, s.name as store_name, w.name as warehouse_name, loc.name as location_name, loc.location_type, pb.batch_no, pb.expiry_date')
            ->join('tbl_store s', 's.id = sb.store_id')
            ->join('tbl_warehouse w', 'w.id = sb.warehouse_id')
            ->join('tbl_stock_location loc', 'loc.id = sb.location_id')
            ->join('tbl_product_batch pb', 'pb.id = sb.batch_id', 'left')
            ->where('sb.product_id', $productId);

        if ($storeId) {
            $builder->where('sb.store_id', $storeId);
        }

        return $builder->get()->getResult();
    }

    // Dropdown helpers
    public function getCategories($businessId)
    {
        return $this->db->table('tbl_product_category')
            ->where('business_id', $businessId)->where('is_active', 1)
            ->orderBy('name', 'ASC')->get()->getResult();
    }

    public function getSubcategories($categoryId)
    {
        return $this->db->table('tbl_product_subcategory')
            ->where('category_id', $categoryId)->where('is_active', 1)
            ->orderBy('name', 'ASC')->get()->getResult();
    }

    public function getBrands($businessId)
    {
        return $this->db->table('tbl_product_brand')
            ->where('business_id', $businessId)->where('is_active', 1)
            ->orderBy('name', 'ASC')->get()->getResult();
    }

    public function getUnits($businessId)
    {
        $units = $this->db->table('tbl_product_unit')
            ->where('business_id', $businessId)->where('is_active', 1)
            ->orderBy('name', 'ASC')->get()->getResult();
        
        // Seed default units if empty
        if (empty($units)) {
            $defaults = [
                ['business_id' => $businessId, 'name' => 'Piece', 'abbreviation' => 'pcs'],
                ['business_id' => $businessId, 'name' => 'Pack', 'abbreviation' => 'pk'],
                ['business_id' => $businessId, 'name' => 'Carton', 'abbreviation' => 'ctn'],
                ['business_id' => $businessId, 'name' => 'Kilogram', 'abbreviation' => 'kg'],
                ['business_id' => $businessId, 'name' => 'Litre', 'abbreviation' => 'ltr'],
            ];
            $this->db->table('tbl_product_unit')->insertBatch($defaults);
            return $this->db->table('tbl_product_unit')->where('business_id', $businessId)->get()->getResult();
        }
        return $units;
    }

    public function getTaxRates($businessId)
    {
        $taxes = $this->db->table('tbl_tax_rate')
            ->where('business_id', $businessId)->where('is_active', 1)
            ->get()->getResult();

        if (empty($taxes)) {
            $defaults = [
                ['business_id' => $businessId, 'name' => 'Zero Tax 0%', 'rate' => 0.000],
                ['business_id' => $businessId, 'name' => 'GST 18%', 'rate' => 18.000],
            ];
            $this->db->table('tbl_tax_rate')->insertBatch($defaults);
            return $this->db->table('tbl_tax_rate')->where('business_id', $businessId)->get()->getResult();
        }
        return $taxes;
    }

    public function getManufacturers($businessId)
    {
        return $this->db->table('tbl_manufacturer')
            ->where('business_id', $businessId)->where('is_active', 1)
            ->orderBy('name', 'ASC')->get()->getResult();
    }
}
