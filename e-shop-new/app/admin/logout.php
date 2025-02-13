<?php
require_once '../core/init.php'; 

// Выход из системы
Eshop::logOut();

header('Location: /enter.php'); // Переадресация на страницу входа после выхода 
exit();
