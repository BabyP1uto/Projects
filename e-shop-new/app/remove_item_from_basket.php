<?php
require_once '../core/init.php'; // Подключаем файл инициализации

try {
    // Проверяем, что данные были отправлены методом POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemId = (int)$_POST['item_id']; // Получаем ID товара

        // Инициализация корзины
        $basket = new Basket();
        $basket->init(); // Загружаем существующую корзину

        // Удаляем товар из корзины
        $basket->remove($itemId);

        echo 'Удаление товара из корзины покупателя';
    } else {
        throw new Exception('Неверный метод запроса.');
    }
} catch (Exception $e) {
    echo 'Ошибка: ' . htmlspecialchars($e->getMessage());
}
