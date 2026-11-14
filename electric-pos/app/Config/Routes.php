<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require_once SYSTEMPATH . 'Config/Routes.php';
}

/*
|--------------------------------------------------------------------------
| Route Definitions
|--------------------------------------------------------------------------
*/

// Default route
//$routes->get('/', 'Home::index');

$routes->get('/', 'Auth\AuthController::loginForm');
$routes->get('/login', 'Auth\AuthController::loginForm');
$routes->get('login', 'Auth\AuthController::loginForm');
$routes->post('login', 'Auth\AuthController::doLogin');
$routes->set404Override('Login::pageNotFound'); // 404 override
$routes->setTranslateURIDashes(false); // Translate URI dashes

// ####################### API Related Routes #######################
$routes->get('CronJob/viewUploader/(:any)/(:any)', 'CronJob::viewUploader/$1/$2');
$routes->post('CronJob/viewUploader/(:any)/(:any)', 'CronJob::viewUploader/$1/$2');
$routes->match(['get', 'post'], 'CronJob/viewUploader/(:any)/(:any)/(:num)/(:any)/(:num)', 'CronJob::viewUploader/$1/$2/$3/$4/$5');
// ####################### .................. #######################

// ####################### BASE ROUTES #######################

$routes->post('loginMe', 'Login::loginMe');
$routes->get('registerCaptcha', 'Login::registerCaptcha');
$routes->get('logout', 'Login::logout');
$routes->get('lockMe', 'Login::lockMe');
$routes->post('unlockMe', 'Login::unlockMe');
$routes->get('accessDeniedMain', 'Login::accessDeniedMain');
$routes->get('register', 'Login::register');
$routes->post('registerCaptcha', 'Login::registerCaptcha');
$routes->post('registerMe', 'Login::registerMe');
$routes->post('registerOutputPreview', 'Login::registerOutputPreview');
$routes->get('forgotPassword', 'Login::forgotPassword');
$routes->post('resetPassword', 'Login::resetPassword');
$routes->get('generatePassword/(:any)', 'Login::generatePassword/$1');
$routes->get('structure', 'Login::structure');
$routes->get('Login/backupDB', 'Login::backupDB');
$routes->post('Login/backupDB', 'Login::backupDB');
$routes->get('Login/backupSoftware', 'Login::backupSoftware');
$routes->post('Login/backupSoftware', 'Login::backupSoftware');
$routes->match(['get', 'post'], 'permission/(:any)', 'Login::permission/$1');
$routes->match(['get', 'post'], 'permission/(:any)/(:num)', 'Login::permission/$1/$2');
$routes->match(['get', 'post'], 'helpdesk/(:any)', 'Login::helpdesk/$1');
$routes->match(['get', 'post'], 'helpdesk/(:any)/(:num)', 'Login::helpdesk/$1/$2');
$routes->match(['get', 'post'], 'setting/(:any)', 'Login::setting/$1');
$routes->match(['get', 'post'], 'profile/(:any)', 'Login::profile/$1');
$routes->match(['get', 'post'], 'user/(:any)', 'Login::user/$1');
$routes->match(['get', 'post'], 'user/(:any)/(:num)', 'Login::user/$1/$2');
$routes->match(['get', 'post'], 'auditlog/(:any)', 'Login::auditlog/$1');
$routes->match(['get', 'post'], 'auditlog/(:any)/(:num)', 'Login::auditlog/$1/$2');
$routes->match(['get', 'post'], 'alert/(:any)', 'Login::alert/$1');
$routes->match(['get', 'post'], 'alert/(:any)/(:num)', 'Login::alert/$1/$2');
$routes->match(['get', 'post'], 'alerts/(:any)', 'Login::alerts/$1');
$routes->match(['get', 'post'], 'report/(:any)', 'Login::report/$1');
$routes->match(['get', 'post'], 'report/(:any)/(:any)', 'Login::report/$1/$2');
$routes->match(['get', 'post'], 'report/(:any)/(:any)/(:num)', 'Login::report/$1/$2/$3');
$routes->match(['get', 'post'], 'userguide/(:any)', 'Login::userguide/$1');
$routes->match(['get', 'post'], 'userguide/(:any)/(:num)', 'Login::userguide/$1/$2');
$routes->match(['get', 'post'], 'viewLoader/(:any)', 'MyController::viewLoader/$1');
$routes->match(['get', 'post'], 'viewLoader/(:any)/(:any)', 'MyController::viewLoader/$1/$2');
$routes->match(['get', 'post'], 'viewLoader/(:any)/(:any)/(:num)', 'MyController::viewLoader/$1/$2/$3');
$routes->match(['get', 'post'], 'viewLoader/(:any)/(:any)/(:num)/(:any)/(:num)', 'MyController::viewLoader/$1/$2/$3/$4/$5');
$routes->match(['get', 'post'], 'contactus/(:any)', 'Login::contactus/$1');
$routes->get('exportWord/(:segment)/(:num)', 'MyController::exportWord/$1/$2');
$routes->get('exportExcel/(:segment)/(:num)', 'MyController::exportExcel/$1/$2');
$routes->post('Login/systemUpdate', 'Login::systemUpdate');
$routes->get('Login/systemUpdate', 'Login::systemUpdate');
$routes->match(['get', 'post'], 'documentation/(:any)', 'Login::documentation/$1');
$routes->match(['get', 'post'], 'documentation/(:any)/(:num)', 'Login::documentation/$1/$2');



//$routes->post('exportWord/(:segment)/(:num)', 'MyController::exportWord/$1/$2');

// ####################### BASE ROUTES #######################

//####################### COMPANY ROUTES #######################
$routes->get('dashboard', 'Master\DashboardController::index');
$routes->get('old-dashboard', 'MyController::dashboard');
$routes->get('dashboard/(:any)', 'MyController::dashboard/$1');

// Define routes
$routes->get('dashboardSuper', 'MyController::dashboardSuper');

$routes->match(['get', 'post'], 'uploaddata/(:any)', 'MyController::uploaddata/$1');
$routes->match(['get', 'post'], 'uploadDataUpdate/(:any)', 'MyController::uploadDataUpdate/$1');
$routes->post('selfAssessmentUpload', 'MyController::selfAssessmentUpload');

try {
    // Load the database service
    $db = \Config\Database::connect();

    // Query the database
    $builder = $db->table('tbls_page as BaseTbl');
    $builder->where('BaseTbl.isDeleted', 0);
    $builder->where('BaseTbl.status', 'Active');
    $query = $builder->get();
    $count = $query->getNumRows();

    if ($count === 0) {
        session()->setFlashdata('error', 'No record found.');
    }

    if ($count > 0) {
        foreach ($query->getResult() as $row) {
            $id = $row->id;
            $url = $row->url;

            // Define dynamic routes based on the database results
            $routes->match(['get', 'post'], $url . '/(:any)', 'MyController::' . $row->url . '/$1');
            $routes->match(['get', 'post'], $url . '/(:any)/(:any)', 'MyController::' . $row->url . '/$1/$2');
            $routes->match(['get', 'post'], $url . '/(:any)/(:any)/(:num)', 'MyController::' . $row->url . '/$1/$2/$3');
            $routes->match(['get', 'post'], $url . '/(:any)/(:any)/(:num)/(:any)/(:num)', 'MyController::' . $row->url . '/$1/$2/$3/$4/$5');

        }
    }
} catch (\Throwable $e) {
    // DB not connected during CLI or migrations
}

//####################### COMPANY ROUTES #######################

//####################### API ROUTES #######################

//####################### API ROUTES #######################

// Additional routing files
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require_once APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}



// ==========================================================
//  EPOS SYSTEM ROUTES
// ==========================================================
$routes->get('/', 'Auth\AuthController::loginForm');
$routes->get('login', 'Auth\AuthController::loginForm');
$routes->post('login', 'Auth\AuthController::doLogin');
$routes->get('logout', 'Auth\AuthController::logout');
$routes->get('switch-store/(:num)', 'Auth\AuthController::switchStore/$1');

// Dashboard
$routes->get('dashboard', 'Master\DashboardController::index');

// Products & Taxonomy
$routes->group('inventory', function($routes) {
    $routes->get('products', 'Inventory\ProductController::index');
    $routes->match(['get', 'post'], 'products/add', 'Inventory\ProductController::add');
    $routes->match(['get', 'post'], 'products/edit/(:num)', 'Inventory\ProductController::edit/$1');
    $routes->get('products/view/(:num)', 'Inventory\ProductController::view/$1');
    $routes->get('products/delete/(:num)', 'Inventory\ProductController::delete/$1');
    $routes->get('products/search', 'Inventory\ProductController::search');
    $routes->get('products/get-subcategories/(:num)', 'Inventory\ProductController::getSubcategoriesAjax/$1');

    $routes->get('categories', 'Inventory\CategoryController::index');
    $routes->post('categories/save-category', 'Inventory\CategoryController::saveCategory');
    $routes->post('categories/save-subcategory', 'Inventory\CategoryController::saveSubcategory');
    $routes->post('categories/save-brand', 'Inventory\CategoryController::saveBrand');
    $routes->post('categories/save-unit', 'Inventory\CategoryController::saveUnit');
});

// Purchases, Suppliers & GRN
$routes->group('purchase', function($routes) {
    $routes->get('suppliers', 'Inventory\SupplierController::index');
    $routes->match(['get', 'post'], 'suppliers/add', 'Inventory\SupplierController::add');
    $routes->match(['get', 'post'], 'suppliers/edit/(:num)', 'Inventory\SupplierController::edit/$1');
    $routes->get('suppliers/view/(:num)', 'Inventory\SupplierController::view/$1');
    $routes->post('suppliers/add-payment', 'Inventory\SupplierController::addPayment');

    $routes->get('po', 'Inventory\PurchaseController::index');
    $routes->match(['get', 'post'], 'po/create', 'Inventory\PurchaseController::createPo');
    $routes->get('po/view/(:num)', 'Inventory\PurchaseController::viewPo/$1');
    $routes->get('po/approve/(:num)', 'Inventory\PurchaseController::approvePo/$1');

    $routes->get('grn', 'Inventory\PurchaseController::grnList');
    $routes->match(['get', 'post'], 'grn/create', 'Inventory\PurchaseController::createGrn');
    $routes->get('grn/view/(:num)', 'Inventory\PurchaseController::viewGrn/$1');
    $routes->get('grn/post/(:num)', 'Inventory\PurchaseController::postGrnAction/$1');
});

// Warehouse & Stock
$routes->group('warehouse', function($routes) {
    $routes->get('stock', 'Warehouse\StockController::index');
    $routes->get('movements', 'Warehouse\StockController::movements');

    $routes->get('transfers', 'Warehouse\TransferController::index');
    $routes->match(['get', 'post'], 'transfers/create', 'Warehouse\TransferController::create');
    $routes->get('transfers/dispatch/(:num)', 'Warehouse\TransferController::dispatch/$1');
    $routes->match(['get', 'post'], 'transfers/receive/(:num)', 'Warehouse\TransferController::receive/$1');

    $routes->get('adjustments', 'Warehouse\AdjustmentController::index');
    $routes->match(['get', 'post'], 'adjustments/create', 'Warehouse\AdjustmentController::create');
    $routes->get('adjustments/post/(:num)', 'Warehouse\AdjustmentController::postAction/$1');
});

// POS Terminal
$routes->group('pos', function($routes) {
    $routes->get('terminal', 'POS\PosController::terminal');
    $routes->match(['get', 'post'], 'open-shift', 'POS\PosController::openShift');
    $routes->match(['get', 'post'], 'close-shift', 'POS\PosController::closeShift');
    $routes->match(['get', 'post'], 'day-close', 'POS\PosController::dayClose');

    $routes->post('sale', 'POS\PosController::sale');
    $routes->post('hold', 'POS\PosController::hold');
    $routes->get('holds-list', 'POS\PosController::holdsList');
    $routes->get('resume/(:num)', 'POS\PosController::resume/$1');
    $routes->get('search-customer', 'POS\PosController::searchCustomer');
    $routes->post('quick-customer', 'POS\PosController::quickCreateCustomer');
    $routes->get('receipt/(:num)', 'POS\PosController::receipt/$1');
    $routes->get('sales', 'POS\PosController::salesList');
    $routes->post('process-return', 'POS\PosController::processReturn');
});

// Loyalty
$routes->group('loyalty', function($routes) {
    $routes->get('/', 'Loyalty\LoyaltyController::index');
    $routes->get('customers', 'Loyalty\LoyaltyController::customers');
    $routes->get('customers/(:num)', 'Loyalty\LoyaltyController::customerDetail/$1');
    $routes->post('adjust', 'Loyalty\LoyaltyController::adjust');
    $routes->match(['get', 'post'], 'settings', 'Loyalty\LoyaltyController::programSettings');
    $routes->get('run-credit-job', 'Loyalty\LoyaltyController::runCreditJob');
    $routes->get('run-expiry-job', 'Loyalty\LoyaltyController::runExpiryJob');
});

// Reports
$routes->group('reports', function($routes) {
    $routes->get('/', 'Reports\ReportController::index');
    $routes->get('sales', 'Reports\ReportController::sales');
    $routes->get('stock', 'Reports\ReportController::stock');
    $routes->get('purchase', 'Reports\ReportController::purchase');
});

// Master Admin & Settings
$routes->group('master', function($routes) {
    $routes->get('users', 'Master\UserController::index');
    $routes->match(['get', 'post'], 'users/add', 'Master\UserController::add');
    $routes->match(['get', 'post'], 'users/edit/(:num)', 'Master\UserController::edit/$1');

    $routes->get('settings', 'Master\SettingsController::index');
    $routes->post('settings/general', 'Master\SettingsController::saveGeneral');
    $routes->post('settings/store', 'Master\SettingsController::saveStore');
});
