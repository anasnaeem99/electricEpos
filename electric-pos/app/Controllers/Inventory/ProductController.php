<?php
namespace App\Controllers\Inventory;

use App\Controllers\EposController;
use App\Models\ProductModel;

class ProductController extends EposController
{
    protected $productModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | Products Management';

        $filters = [
            'category_id' => $this->request->getGet('category_id'),
            'brand_id'    => $this->request->getGet('brand_id'),
        ];

        $data = [
            'products'   => $this->productModel->getAllProducts($this->businessId, $filters),
            'categories' => $this->productModel->getCategories($this->businessId),
            'brands'     => $this->productModel->getBrands($this->businessId),
            'filters'    => $filters,
        ];

        return $this->loadEposView('inventory/products_list', $data);
    }

    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name'           => 'required|min_length[2]|max_length[200]',
                'sku'            => 'required|max_length[60]',
                'base_unit_id'   => 'required|numeric',
                'retail_price'   => 'required|numeric',
                'purchase_price' => 'permit_empty|numeric',
            ];

            if (!$this->validate($rules)) {
                $this->session->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
                return redirect()->back()->withInput();
            }

            // Check unique SKU per business
            $sku = trim($this->request->getPost('sku'));
            $exists = $this->productModel->where('business_id', $this->businessId)->where('sku', $sku)->first();
            if ($exists) {
                $this->session->setFlashdata('error', 'SKU "' . esc($sku) . '" already exists. Choose a unique SKU.');
                return redirect()->back()->withInput();
            }

            $productData = [
                'business_id'       => $this->businessId,
                'name'              => trim($this->request->getPost('name')),
                'sku'               => $sku,
                'internal_code'     => $this->request->getPost('internal_code'),
                'description'       => $this->request->getPost('description'),
                'category_id'       => $this->request->getPost('category_id') ?: null,
                'subcategory_id'    => $this->request->getPost('subcategory_id') ?: null,
                'brand_id'          => $this->request->getPost('brand_id') ?: null,
                'manufacturer_id'   => $this->request->getPost('manufacturer_id') ?: null,
                'base_unit_id'      => (int)$this->request->getPost('base_unit_id'),
                'tax_id'            => $this->request->getPost('tax_id') ?: null,
                'purchase_price'    => (float)($this->request->getPost('purchase_price') ?: 0),
                'retail_price'      => (float)($this->request->getPost('retail_price') ?: 0),
                'wholesale_price'   => (float)($this->request->getPost('wholesale_price') ?: 0),
                'min_stock'         => (float)($this->request->getPost('min_stock') ?: 0),
                'max_stock'         => $this->request->getPost('max_stock') ? (float)$this->request->getPost('max_stock') : null,
                'reorder_level'     => (float)($this->request->getPost('reorder_level') ?: 0),
                'batch_tracking'    => $this->request->getPost('batch_tracking') ? 1 : 0,
                'expiry_tracking'   => $this->request->getPost('expiry_tracking') ? 1 : 0,
                'is_weighing'       => $this->request->getPost('is_weighing') ? 1 : 0,
                'weight_unit'       => $this->request->getPost('weight_unit'),
                'price_per_weight'  => $this->request->getPost('price_per_weight') ? (float)$this->request->getPost('price_per_weight') : null,
                'scale_plu'         => $this->request->getPost('scale_plu'),
                'is_active'         => 1,
            ];

            // Image Upload
            $imgFile = $this->request->getFile('image');
            if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
                $newName = $imgFile->getRandomName();
                $imgFile->move(FCPATH . 'uploads/products', $newName);
                $productData['image'] = 'uploads/products/' . $newName;
            }

            $newProductId = $this->productModel->insert($productData);

            // Save default barcode
            $barcode = trim($this->request->getPost('barcode') ?? '');
            if (!empty($barcode)) {
                $this->productModel->saveBarcode($newProductId, $productData['base_unit_id'], $barcode, 1);
            } else {
                // Auto generate barcode matching SKU
                $this->productModel->saveBarcode($newProductId, $productData['base_unit_id'], $sku, 1);
            }

            // Save multiple extra barcodes if submitted
            $extraBarcodes = $this->request->getPost('extra_barcodes');
            if (is_array($extraBarcodes)) {
                foreach ($extraBarcodes as $bc) {
                    if (!empty($bc['barcode'])) {
                        $this->productModel->saveBarcode($newProductId, $bc['unit_id'] ?? $productData['base_unit_id'], trim($bc['barcode']), 0);
                    }
                }
            }

            $this->session->setFlashdata('success', 'Product created successfully!');
            return redirect()->to(base_url('inventory/products'));
        }

        $this->global['pageTitle'] = 'EPOS | Add New Product';
        $data = [
            'categories'    => $this->productModel->getCategories($this->businessId),
            'brands'        => $this->productModel->getBrands($this->businessId),
            'manufacturers' => $this->productModel->getManufacturers($this->businessId),
            'units'         => $this->productModel->getUnits($this->businessId),
            'taxRates'      => $this->productModel->getTaxRates($this->businessId),
        ];

        return $this->loadEposView('inventory/product_form', $data);
    }

    public function edit($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            $this->session->setFlashdata('error', 'Product not found.');
            return redirect()->to(base_url('inventory/products'));
        }

        if ($this->request->getMethod() === 'post') {
            $productData = [
                'name'              => trim($this->request->getPost('name')),
                'sku'               => trim($this->request->getPost('sku')),
                'internal_code'     => $this->request->getPost('internal_code'),
                'description'       => $this->request->getPost('description'),
                'category_id'       => $this->request->getPost('category_id') ?: null,
                'subcategory_id'    => $this->request->getPost('subcategory_id') ?: null,
                'brand_id'          => $this->request->getPost('brand_id') ?: null,
                'manufacturer_id'   => $this->request->getPost('manufacturer_id') ?: null,
                'base_unit_id'      => (int)$this->request->getPost('base_unit_id'),
                'tax_id'            => $this->request->getPost('tax_id') ?: null,
                'purchase_price'    => (float)($this->request->getPost('purchase_price') ?: 0),
                'retail_price'      => (float)($this->request->getPost('retail_price') ?: 0),
                'wholesale_price'   => (float)($this->request->getPost('wholesale_price') ?: 0),
                'min_stock'         => (float)($this->request->getPost('min_stock') ?: 0),
                'max_stock'         => $this->request->getPost('max_stock') ? (float)$this->request->getPost('max_stock') : null,
                'reorder_level'     => (float)($this->request->getPost('reorder_level') ?: 0),
                'batch_tracking'    => $this->request->getPost('batch_tracking') ? 1 : 0,
                'expiry_tracking'   => $this->request->getPost('expiry_tracking') ? 1 : 0,
                'is_weighing'       => $this->request->getPost('is_weighing') ? 1 : 0,
                'weight_unit'       => $this->request->getPost('weight_unit'),
                'price_per_weight'  => $this->request->getPost('price_per_weight') ? (float)$this->request->getPost('price_per_weight') : null,
                'scale_plu'         => $this->request->getPost('scale_plu'),
                'is_active'         => $this->request->getPost('is_active') ? 1 : 0,
            ];

            $imgFile = $this->request->getFile('image');
            if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
                $newName = $imgFile->getRandomName();
                $imgFile->move(FCPATH . 'uploads/products', $newName);
                $productData['image'] = 'uploads/products/' . $newName;
            }

            $this->productModel->update($id, $productData);

            $newBarcode = trim($this->request->getPost('new_barcode') ?? '');
            if (!empty($newBarcode)) {
                $this->productModel->saveBarcode($id, $productData['base_unit_id'], $newBarcode);
            }

            $this->session->setFlashdata('success', 'Product updated successfully!');
            return redirect()->to(base_url('inventory/products'));
        }

        $this->global['pageTitle'] = 'EPOS | Edit Product: ' . esc($product['name']);
        $data = [
            'product'       => $product,
            'barcodes'      => $this->productModel->getBarcodes($id),
            'categories'    => $this->productModel->getCategories($this->businessId),
            'subcategories' => $product['category_id'] ? $this->productModel->getSubcategories($product['category_id']) : [],
            'brands'        => $this->productModel->getBrands($this->businessId),
            'manufacturers' => $this->productModel->getManufacturers($this->businessId),
            'units'         => $this->productModel->getUnits($this->businessId),
            'taxRates'      => $this->productModel->getTaxRates($this->businessId),
        ];

        return $this->loadEposView('inventory/product_form', $data);
    }

    public function view($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $this->session->setFlashdata('error', 'Product not found.');
            return redirect()->to(base_url('inventory/products'));
        }

        $this->global['pageTitle'] = 'EPOS | Product Detail: ' . esc($product->name);
        $data = [
            'product'        => $product,
            'barcodes'       => $this->productModel->getBarcodes($id),
            'batches'        => $this->productModel->getBatches($id),
            'stockOverview'  => $this->productModel->getProductStockOverview($id, $this->storeId),
            'stockMovements' => $this->db->table('tbl_stock_movement sm')
                                ->select('sm.*, s.name as store_name, u.full_name as user_name')
                                ->join('tbl_store s', 's.id = sm.store_id', 'left')
                                ->join('tbl_user u', 'u.id = sm.created_by', 'left')
                                ->where('sm.product_id', $id)
                                ->orderBy('sm.id', 'DESC')
                                ->limit(20)
                                ->get()->getResult(),
        ];

        return $this->loadEposView('inventory/product_view', $data);
    }

    public function delete($id)
    {
        $this->productModel->update($id, ['is_active' => 0]);
        if ($this->request->isAJAX()) {
            return $this->jsonSuccess([], 'Product archived successfully');
        }
        $this->session->setFlashdata('success', 'Product archived successfully');
        return redirect()->to(base_url('inventory/products'));
    }

    // AJAX Endpoint for POS
    public function search()
    {
        $q = $this->request->getGet('q');
        $items = $this->productModel->searchProductsForPos($q, $this->businessId, $this->storeId);
        return $this->response->setJSON($items);
    }

    // Subcategories by category ID AJAX
    public function getSubcategoriesAjax($catId)
    {
        $subs = $this->productModel->getSubcategories($catId);
        return $this->response->setJSON($subs);
    }
}
