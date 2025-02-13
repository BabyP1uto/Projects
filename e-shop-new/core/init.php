<?php
const CORE_DIR = 'core/';
const APP_DIR = 'app/';
const ADMIN_DIR = APP_DIR . 'admin/';

/* 
    ////////////////////////////////////
    ////// ЭТОТ БЛОК ДЛЯ ОТЛАДКИ //////
    ///////////////////////////////////
*/
set_include_path(get_include_path() . PATH_SEPARATOR . CORE_DIR . PATH_SEPARATOR . APP_DIR . PATH_SEPARATOR . ADMIN_DIR);
spl_autoload_extensions('.class.php');
spl_autoload_register();

const ERROR_LOG = ADMIN_DIR . 'error.log';
const ERROR_MSG = 'Срочно обратитесь к администратору! admin@email.info';

function errors_log($msg, $file, $line) {
    $dt = date('d-m-Y H:i:s');
    $message = "$dt - $msg in $file:$line\n";
    error_log($message, 3, ERROR_LOG);
    echo ERROR_MSG;
}

function error_handler($no, $msg, $file, $line) {
    errors_log($msg, $file, $line);
}

set_error_handler('error_handler');

function exception_handler($e) {
    errors_log($e->getMessage(), $e->getFile(), $e->getLine());
}

set_exception_handler('exception_handler');

/* 
    //////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////
*/

// Настройки базы данных
const DB = [
    'HOST' => 'localhost',
    'USER' => 'root',
    'PASS' => '12345',
    'NAME' => 'eshop',
];

// Инициализация приложения
try {
    Eshop::init(DB);
    
    // Инициализация корзины
    $basket = new Basket();
    $basket->init();

    session_start(); // Открываем сессию

// Проверяем, залогинен ли администратор
if (!isset($_SESSION['admin'])) {
   header('Location: /enter.php'); // Переадресация на страницу входа если не залогинен
   exit();
}

} catch (Exception $e) {
    errors_log("Ошибка инициализации базы данных: " . $e->getMessage(), __FILE__, __LINE__);
}
