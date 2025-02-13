<?php
class Order {
    private $id;
    private $customer;
    private $email;
    private $phone;
    private $address;
    private $created;
    private $items; // Массив с товарами (item_id => quantity)
    

    public function __construct($customer, $email, $phone, $address, $items = []) {
        $this->customer = $customer;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->items = $items;
        $this->created = date('Y-m-d H:i:s'); // Устанавливаем дату создания заказа
    }

    public function getCustomer() {
        return $this->customer;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCreated() {
        return $this->created;
    }

    public function addItem($itemId, $quantity) {
        if (isset($this->items[$itemId])) {
            $this->items[$itemId] += $quantity; // Увеличиваем количество, если товар уже есть
        } else {
            $this->items[$itemId] = $quantity; // Иначе добавляем новый товар
        }
    }

    public function getId() {
        return $this->id; // ID будет установлен после сохранения
    }

    public function setId($id) {
        $this->id = $id; // Устанавливаем ID заказа
    }
}
