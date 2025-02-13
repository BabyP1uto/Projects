<?php
require_once '../core/init.php'; // Подключаем файл инициализации

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Получаем данные из формы
        $customer = Cleaner::str($_POST['customer']);
        $email = Cleaner::str($_POST['email']);
        $phone = Cleaner::str($_POST['phone']);
        $address = Cleaner::str($_POST['address']);
        
        // Получаем товары из корзины
        $basket = new Basket();
        $basket->init(); // Загружаем существующую корзину
        
        if (empty($basket->getItems())) {
            throw new Exception("Корзина пуста. Невозможно оформить заказ.");
        }

        // Создаем объект Order с полученными данными и товарами из корзины
        $order = new Order($customer, $email, $phone, $address, $basket->getItems());

        // Сохраняем заказ через Eshop
        Eshop::saveOrder($order);

        header('Location: /catalog'); // Переадресация на каталог после успешного оформления заказа
        exit();
        
    } else {
        throw new Exception('Неверный метод запроса.');
    }
} catch (Exception $e) {
    echo 'Ошибка: ' . htmlspecialchars($e->getMessage());
}
