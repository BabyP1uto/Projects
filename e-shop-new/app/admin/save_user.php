<?php
require_once '../core/init.php'; 

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Получаем данные из формы
        $login = Cleaner::str($_POST['login']);
        $password = Cleaner::str($_POST['password']);
        $email = Cleaner::str($_POST['email']);
        
        // Создаем объект User с хэшем пароля
        $hashedPassword = Eshop::createHash($password);
        $user = new User($login, $hashedPassword, $email);

        // Добавляем пользователя через Eshop
        Eshop::userAdd($user);

        header('Location: /admin'); // Переадресация на страницу админки после успешного создания пользователя
        exit();
        
    } else {
        throw new Exception('Неверный метод запроса.');
    }
} catch (Exception $e) {
   echo 'Ошибка: ' . htmlspecialchars($e->getMessage());
}
