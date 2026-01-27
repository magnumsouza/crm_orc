<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/ClientsController.php';
require_once __DIR__ . '/controllers/ProductsController.php';
require_once __DIR__ . '/controllers/QuotesController.php';
require_once __DIR__ . '/controllers/InventoryController.php';
require_once __DIR__ . '/controllers/SchedulesController.php';
require_once __DIR__ . '/controllers/InvoicesController.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_login();
    }
    show_login();
    exit;
}

if ($action === 'google_callback') {
    handle_google_callback();
    exit;
}

if ($action === 'logout') {
    handle_logout();
}

require_login();

$restricted_actions = [
    'clients_create',
    'clients_store',
    'clients_edit',
    'clients_update',
    'clients_delete',
    'products_create',
    'products_store',
    'products_edit',
    'products_update',
    'products_delete',
    'quotes_create',
    'quotes_store',
    'quotes_update_status',
    'inventory_create',
    'inventory_store',
    'inventory_edit',
    'inventory_update',
    'inventory_delete',
    'schedules_create',
    'schedules_store',
    'schedules_edit',
    'schedules_update',
    'schedules_delete',
    'schedules_update_status',
];

if (!is_admin() && in_array($action, $restricted_actions, true)) {
    flash_set('error', 'Acesso restrito: apenas administradores podem alterar dados.');
    redirect('index.php');
}

switch ($action) {
    case 'clients':
        clients_index();
        break;
    case 'clients_create':
        clients_create();
        break;
    case 'clients_store':
        clients_store();
        break;
    case 'clients_edit':
        clients_edit();
        break;
    case 'clients_update':
        clients_update();
        break;
    case 'clients_delete':
        clients_delete();
        break;
    case 'clients_history':
        clients_history();
        break;
    case 'products':
        products_index();
        break;
    case 'products_create':
        products_create();
        break;
    case 'products_store':
        products_store();
        break;
    case 'products_edit':
        products_edit();
        break;
    case 'products_update':
        products_update();
        break;
    case 'products_delete':
        products_delete();
        break;
    case 'quotes':
        quotes_index();
        break;
    case 'quotes_create':
        quotes_create();
        break;
    case 'quotes_store':
        quotes_store();
        break;
    case 'quotes_view':
        quotes_view();
        break;
    case 'quotes_update_status':
        quotes_update_status();
        break;
    case 'quotes_pdf':
        quotes_generate_pdf();
        break;
    case 'inventory':
        inventory_index();
        break;
    case 'inventory_create':
        inventory_create();
        break;
    case 'inventory_store':
        inventory_store();
        break;
    case 'inventory_view':
        inventory_view();
        break;
    case 'inventory_edit':
        inventory_edit();
        break;
    case 'inventory_update':
        inventory_update();
        break;
    case 'inventory_delete':
        inventory_delete();
        break;
    case 'schedules':
        schedules_index();
        break;
    case 'schedules_create':
        schedules_create();
        break;
    case 'schedules_store':
        schedules_store();
        break;
    case 'schedules_view':
        schedules_view();
        break;
    case 'schedules_edit':
        schedules_edit();
        break;
    case 'schedules_update':
        schedules_update();
        break;
    case 'schedules_delete':
        schedules_delete();
        break;
    case 'schedules_update_status':
        schedules_update_status();
        break;
    case 'schedules_api_slots':
        schedules_api_slots();
        break;
    case 'invoices':
        invoices_index();
        break;
    case 'invoices_pdf':
        invoices_pdf();
        break;
    case 'invoices_xml':
        invoices_xml();
        break;
    default:
        show_dashboard();
        break;
}
