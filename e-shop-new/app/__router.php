<?php
$path = parse_url($_SERVER['REQUEST_URI'])['path'];
switch (rtrim($path, '/')):
    case '':
    case '/index.php':
        header('Location: /catalog');
        break;
    case '/catalog':
        require_once 'catalog.php';
        break;
    case '/basket':
        require_once 'basket.php';
        break;
    case '/admin':
        require_once 'admin.php';
        break;    
    case '/admin/add_item_to_catalog':
        require_once 'add_item_to_catalog.php';
        break;
    case '/admin/save_item_to_catalog':
        require_once 'save_item_to_catalog.php';
        break;    
    case '/add_item_to_basket':
        require_once 'add_item_to_basket.php';
        break;
    case '/remove_item_from_basket':
        require_once 'remove_item_from_basket.php';
        break;
    case '/create_order':
        require_once 'create_order.php';
        break;
    case '/save_order':
        require_once 'save_order.php';
        break;   
    case '/admin/orders':
        require_once 'orders.php';
        break;
    case '/admin/create_user':
        require_once 'create_user.php';
        break;
    case '/admin/save_user':
        require_once 'save_user.php';
        break;
    case '/admin/logout':
        require_once 'logout.php';
        break;
    default:
        require_once '404.php';
endswitch;
