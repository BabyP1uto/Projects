<?php
class Basket {
    private $items = []; // Массив для хранения товаров в корзине
    private const COOKIE_NAME = 'eshop'; // Имя cookie для хранения корзины

    public function init() {
        // Проверяем, существует ли cookie с корзиной
        if (isset($_COOKIE[self::COOKIE_NAME])) {
            $this->read(); // Читаем корзину из cookie
        } else {
            $this->create(); // Создаем новую корзину
        }
    }

    public function add($itemId, $quantity) {
        // Добавляем товар в корзину
        if (isset($this->items[$itemId])) {
            $this->items[$itemId] += $quantity; // Увеличиваем количество, если товар уже есть в корзине
        } else {
            $this->items[$itemId] = $quantity; // Иначе добавляем новый товар
        }
        $this->save(); // Сохраняем изменения в cookie
    }

    public function remove($itemId) {
        // Удаляем товар из корзины, если он существует
        if (isset($this->items[$itemId])) {
            unset($this->items[$itemId]); // Удаляем товар из массива
            $this->save(); // Сохраняем изменения в cookie
        }
    }

    public function save() {
        // Сохраняем текущую корзину в cookie
        setcookie(self::COOKIE_NAME, json_encode($this->items), time() + 86400, '/'); // Храним на 1 день
    }

    public function create() {
        // Создаем пустую корзину
        $this->items = [];
        $this->save(); // Сохраняем пустую корзину в cookie
    }

    public function read() {
        // Читаем корзину из cookie и декодируем JSON в массив
        $this->items = json_decode($_COOKIE[self::COOKIE_NAME], true);
    }

    public function getItems() {
        return $this->items; // Возвращаем массив товаров в корзине
    }
}
