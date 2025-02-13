<?php
require_once '../core/init.php'; // Подключаем файл инициализации

try {
    // Проверяем, что данные были отправлены методом POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemId = (int)$_POST['item_id']; // Получаем ID товара
        $quantity = (int)$_POST['quantity']; // Получаем количество товара

        // Инициализация корзины
        $basket = new Basket();
        $basket->init(); // Загружаем существующую корзину

        // Добавляем товар в корзину
        $basket->add($itemId, $quantity);

        echo 'Добавление товара в корзину покупателя';
    } else {
        throw new Exception('Неверный метод запроса.');
    }
} catch (Exception $e) {
    echo 'Ошибка: ' . htmlspecialchars($e->getMessage());
}
