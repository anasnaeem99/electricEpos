<?php
namespace App\Controllers\Inventory;

use App\Controllers\EposController;
use App\Models\ProductModel;

class CategoryController extends EposController
{
    protected $productModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'EPOS | Categories, Brands & Units';

        $data = [
            'categories'    => $this->db->table('tbl_product_category')->where('business_id', $this->businessId)->get()->getResult(),
            'subcategories' => $this->db->table('tbl_product_subcategory s')
                                ->select('s.*, c.name as category_name')
                                ->join('tbl_product_category c', 'c.id = s.category_id')
                                ->where('s.business_id', $this->businessId)
                                ->get()->getResult(),
            'brands'        => $this->db->table('tbl_product_brand')->where('business_id', $this->businessId)->get()->getResult(),
            'units'         => $this->db->table('tbl_product_unit')->where('business_id', $this->businessId)->get()->getResult(),
        ];

        return $this->loadEposView('inventory/categories', $data);
    }

    public function saveCategory()
    {
        $id = $this->request->getPost('id');
        $name = trim($this->request->getPost('name'));

        if (empty($name)) {
            return $this->jsonError('Category name is required.');
        }

        $data = [
            'business_id' => $this->businessId,
            'name'        => $name,
            'slug'        => url_title($name, '-', true),
            'is_active'   => 1
        ];

        if ($id) {
            $this->db->table('tbl_product_category')->where('id', $id)->update($data);
        } else {
            $this->db->table('tbl_product_category')->insert($data);
        }

        return $this->jsonSuccess([], 'Category saved successfully');
    }

    public function saveSubcategory()
    {
        $id = $this->request->getPost('id');
        $catId = $this->request->getPost('category_id');
        $name = trim($this->request->getPost('name'));

        if (empty($name) || empty($catId)) {
            return $this->jsonError('Name and Parent Category are required.');
        }

        $data = [
            'business_id' => $this->businessId,
            'category_id' => $catId,
            'name'        => $name,
            'slug'        => url_title($name, '-', true),
            'is_active'   => 1
        ];

        if ($id) {
            $this->db->table('tbl_product_subcategory')->where('id', $id)->update($data);
        } else {
            $this->db->table('tbl_product_subcategory')->insert($data);
        }

        return $this->jsonSuccess([], 'Subcategory saved successfully');
    }

    public function saveBrand()
    {
        $id = $this->request->getPost('id');
        $name = trim($this->request->getPost('name'));

        if (empty($name)) {
            return $this->jsonError('Brand name is required.');
        }

        $data = [
            'business_id' => $this->businessId,
            'name'        => $name,
            'is_active'   => 1
        ];

        if ($id) {
            $this->db->table('tbl_product_brand')->where('id', $id)->update($data);
        } else {
            $this->db->table('tbl_product_brand')->insert($data);
        }

        return $this->jsonSuccess([], 'Brand saved successfully');
    }

    public function saveUnit()
    {
        $id = $this->request->getPost('id');
        $name = trim($this->request->getPost('name'));
        $abbr = trim($this->request->getPost('abbreviation'));

        if (empty($name) || empty($abbr)) {
            return $this->jsonError('Unit Name and Abbreviation are required.');
        }

        $data = [
            'business_id'  => $this->businessId,
            'name'         => $name,
            'abbreviation' => $abbr,
            'is_active'    => 1
        ];

        if ($id) {
            $this->db->table('tbl_product_unit')->where('id', $id)->update($data);
        } else {
            $this->db->table('tbl_product_unit')->insert($data);
        }

        return $this->jsonSuccess([], 'Unit saved successfully');
    }
}
