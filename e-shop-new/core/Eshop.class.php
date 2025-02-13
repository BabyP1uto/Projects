<?php
class Eshop {
    private static $db;

    public static function init(array $dbConfig) {
        // Проверка наличия всех необходимых параметров
        if (empty($dbConfig['HOST']) || empty($dbConfig['USER']) || empty($dbConfig['PASS']) || empty($dbConfig['NAME'])) {
            throw new Exception("Недостаточно данных для подключения к базе данных.");
        }

        // Установка соединения с базой данных
        self::$db = new mysqli($dbConfig['HOST'], $dbConfig['USER'], $dbConfig['PASS'], $dbConfig['NAME']);

        // Проверка на ошибки соединения
        if (self::$db->connect_error) {
            throw new Exception("Ошибка подключения: " . self::$db->connect_error);
        }

        // Установка кодировки
        self::$db->set_charset("utf8");
    }

    public static function getDb() {
        return self::$db;
    }

    public static function addItemToCatalog(Book $book) {
        // Получаем данные книги
        $title = $book->getTitle();
        $author = $book->getAuthor();
        $pubyear = $book->getPubyear();
        $price = $book->getPrice();

        // Вызов хранимой процедуры для добавления товара в каталог
        $stmt = self::getDb()->prepare("CALL spAddItemToCatalog(?, ?, ?, ?)");

        if ($stmt) {
            // Привязка параметров
            $stmt->bind_param("ssds", $title, $author, $pubyear, $price);
            
            // Выполнение запроса
            if ($stmt->execute()) {
                return true; // Успех
            } else {
                throw new Exception("Ошибка при добавлении товара: " . self::getDb()->error);
            }
            
            // Закрытие подготовленного выражения
            $stmt->close();
        } else {
            throw new Exception("Ошибка подготовки запроса: " . self::getDb()->error);
        }
    }


public static function getItemsFromCatalog() {
    $books = [];
    
    // Вызов хранимой процедуры для получения всех товаров из каталога
    $stmt = self::getDb()->prepare("CALL spGetCatalog()");
    
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        // Создание объектов Book на основе полученных данных
        while ($row = $result->fetch_assoc()) {
            $books[] = new Book($row['title'], $row['author'], $row['pubyear'], $row['price']);
        }

        // Закрытие подготовленного выражения
        $stmt->close();
    } else {
        throw new Exception("Ошибка подготовки запроса: " . self::getDb()->error);
    }

    // Возвращаем итератор для массива книг
    return new IteratorIterator(new ArrayIterator($books));
}
public static function addItemToBasket($itemId, $quantity) {
    // Инициализация корзины
    $basket = new Basket();
    $basket->init(); // Загружаем существующую корзину или создаем новую

    // Добавление товара в корзину
    $basket->add($itemId, $quantity);
    
    echo 'Добавление товара в корзину покупателя';
}

public static function removeItemFromBasket($itemId) {
    // Инициализация корзины
    $basket = new Basket();
    $basket->init(); // Загружаем существующую корзину

    // Удаление товара из корзины
    $basket->remove($itemId);
    
    echo 'Удаление товара из корзины покупателя';
}

public static function getItemsFromBasket() {
    // Инициализация корзины
    $basket = new Basket();
    $basket->init(); // Загружаем существующую корзину

    return $basket->getItems(); // Возвращаем товары из корзины
}

public static function saveOrder(Order $order) {
    // Получаем данные о заказе
    $customer = $order->getCustomer();
    $email = $order->getEmail();
    $phone = $order->getPhone();
    $address = $order->getAddress();
    
    // Вызов хранимой процедуры для сохранения заказа
    self::getDb()->begin_transaction(); // Начинаем транзакцию
    try {
        // Сохраняем данные о заказе
        $stmt = self::getDb()->prepare("CALL spSaveOrder(?, ?, ?, ?, ?)");
        if ($stmt) {
            // Привязка параметров
            $stmt->bind_param("sssss", $customer, $email, $phone, $address);
            if (!$stmt->execute()) {
                throw new Exception("Ошибка при сохранении заказа: " . self::getDb()->error);
            }
            // Получаем ID последнего вставленного заказа
            $orderId = self::getDb()->insert_id;
            // Закрываем подготовленное выражение
            $stmt->close();
            
            // Сохраняем товары в заказе
            foreach ($order->getItems() as $itemId => $quantity) {
                self::saveOrderedItems($orderId, (int)$itemId, (int)$quantity);
            }
            
            // Очищаем корзину после успешного сохранения заказа
            (new Basket())->create(); // Создаем новую корзину (очищаем)
            
            self::getDb()->commit(); // Подтверждаем транзакцию
            return true; // Успех
        } else {
            throw new Exception("Ошибка подготовки запроса: " . self::getDb()->error);
        }
        
    } catch (Exception $e) {
        self::getDb()->rollback(); // Откатываем транзакцию в случае ошибки
        throw new Exception("Ошибка при сохранении заказа: " . htmlspecialchars($e->getMessage()));
    }
}

private static function saveOrderedItems($orderId, int $itemId, int $quantity) {
    // Вызов хранимой процедуры для сохранения позиций заказа
    $stmt = self::getDb()->prepare("CALL spSaveOrderedItems(?, ?, ?)");
    
    if ($stmt) {
        // Привязка параметров
        $stmt->bind_param("iii", $orderId, $itemId, $quantity);
        
        if (!$stmt->execute()) {
            throw new Exception("Ошибка при сохранении позиций заказа: " . self::getDb()->error);
        }
        
        // Закрытие подготовленного выражения
        $stmt->close();
    } else {
        throw new Exception("Ошибка подготовки запроса: " . self::getDb()->error);
    }
}

public static function getOrders() {
    // Вызов хранимой процедуры для получения всех заказов
    return new IteratorIterator(new ArrayIterator(self::fetchOrders()));
}

private static function fetchOrders() {
    // Массив для хранения заказов
    $orders = [];

    // Вызов хранимой процедуры для получения всех заказов
    if ($stmt = self::getDb()->prepare("CALL spGetOrders()")) {
        
        if ($stmt->execute()) {
            // Получение результата запроса
            $result_set = $stmt->get_result();

            while ($row = $result_set->fetch_assoc()) {
                // Создание объекта Order и добавление его в массив заказов
                if (!isset($orders[$row['id']])) { 
                    // Проверяем, существует ли уже заказ с таким ID в массиве 
                    $orders[$row['id']] = new Order(
                        $row['customer'], 
                        $row['email'], 
                        $row['phone'], 
                        $row['address']
                    );
                    $orders[$row['id']]->setId($row['id']); 
                }
                // Добавляем позицию товара к заказу
                $orders[$row['id']]->addItem($row['item_id'], $row['quantity']); 
                // Устанавливаем дату создания заказа
                $orders[$row['id']]->setCreated($row['created']); 
            }
            
            $stmt->close(); // Закрываем подготовленное выражение
            
            return $orders; // Возвращаем массив заказов
            
        } else { 
            throw new Exception("Ошибка при получении заказов: " . self::getDb()->error); 
        } 
        
    } else { 
        throw new Exception("Ошибка подготовки запроса: " . self::getDb()->error); 
    } 
}

public static function userAdd(User $user) {
    // Вызов хранимой процедуры для добавления пользователя
    $stmt = self::getDb()->prepare("CALL spSaveAdmin(?, ?, ?)");
    
    if ($stmt) {
        // Привязка параметров
        $stmt->bind_param("sss", $user->getLogin(), $user->getPassword(), $user->getEmail());
        
        if (!$stmt->execute()) {
            throw new Exception("Ошибка при добавлении пользователя: " . self::getDb()->error);
        }

        // Закрытие подготовленного выражения
        $stmt->close();
    } else {
        throw new Exception("Ошибка подготовки запроса: " . self::getDb()->error);
    }
}

public static function userCheck(User $user): bool {
    // Проверяем, существует ли пользователь в базе данных
    if ($stmt = self::getDb()->prepare("CALL spGetAdmin(?)")) {
        $stmt->bind_param("s", $user->getLogin());
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return ($result->num_rows > 0); // Если есть хотя бы одна запись, пользователь существует
        } else {
            throw new Exception("Ошибка при проверке пользователя: " . self::getDb()->error);
        }
        
        $stmt->close();
    } else {
        throw new Exception("Ошибка подготовки запроса: " . self::getDb()->error);
    }
}

public static function userGet(User $user): User {
    // Получаем пользователя из базы данных
    if ($stmt = self::getDb()->prepare("CALL spGetAdmin(?)")) {
        $stmt->bind_param("s", $user->getLogin());
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                // Создаем объект User с данными из базы
                $fetchedUser = new User($row['login'], $row['password'], $row['email']);
                $fetchedUser->setId($row['id']);
                return $fetchedUser;
            }
            throw new Exception("Пользователь не найден.");
        } else {
            throw new Exception("Ошибка при получении пользователя: " . self::getDb()->error);
        }
        
        $stmt->close();
    } else {
        throw new Exception("Ошибка подготовки запроса: " . self::getDb()->error);
    }
}

public static function createHash(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT); // Хэшируем пароль
}

public static function isAdmin(): bool {
    return isset($_SESSION['admin']); // Проверяем, залогинен ли администратор
}

public static function logIn(User $user): bool {
    // Проверяем, существует ли пользователь и совпадает ли пароль
    if (self::userCheck($user)) {
        // Получаем данные о пользователе из базы данных
        $fetchedUser = self::userGet($user);
        
        if (password_verify($user->getPassword(), $fetchedUser->getPassword())) { 
            $_SESSION['admin'] = true; // Устанавливаем сессию для администратора
            return true; 
        }
        
        throw new Exception("Неверный пароль.");
    }
    
    throw new Exception("Пользователь не найден.");
}

public static function logOut() {
    unset($_SESSION['admin']); // Удаляем сессию администратора
}


}