<?php
require_once '../core/init.php'; 

// Получаем все заказы через Eshop
$ordersIterator = Eshop::getOrders();

?>

<h1>Поступившие заказы:</h1>
<a href='/admin'>Назад в админку</a>
<hr>

<?php foreach ($ordersIterator as $order): ?>
    <h2>Заказ номер: <?php echo htmlspecialchars($order->getId()); ?></h2>
    <p><b>Заказчик</b>: <?php echo htmlspecialchars($order->getCustomer()); ?></p>
    <p><b>Email</b>: <?php echo htmlspecialchars($order->getEmail()); ?></p>
    <p><b>Телефон</b>: <?php echo htmlspecialchars($order->getPhone()); ?></p>
    <p><b>Адрес доставки</b>: <?php echo htmlspecialchars($order->getAddress()); ?></p>
    <p><b>Дата размещения заказа</b>: <?php echo htmlspecialchars($order->getCreated()); ?></p>

    <h3>Купленные товары:</h3>
    <table>
        <tr>
            <th>N п/п</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Год издания</th>
            <th>Цена, руб.</th>
            <th>Количество</th>
        </tr>

        <?php 
        $items = $order->getItems(); // Получаем товары из заказа
        $totalPrice = 0; // Переменная для хранения общей суммы товаров в заказе
        foreach ($items as $itemId => $quantity): 
            // Получаем информацию о товаре из каталога (предполагаем, что у вас есть метод для получения книги по ID)
            $book = Eshop::getBookById($itemId); // Метод для получения книги по ID

            if ($book): // Проверяем, существует ли книга
                $totalPrice += $book->getPrice() * $quantity; // Считаем общую стоимость
        ?>
            <tr>
                <td><?php echo htmlspecialchars($itemId); ?></td>
                <td><?php echo htmlspecialchars($book->getTitle()); ?></td>
                <td><?php echo htmlspecialchars($book->getAuthor()); ?></td>
                <td><?php echo htmlspecialchars($book->getPubyear()); ?></td>
                <td><?php echo htmlspecialchars(number_format($book->getPrice(), 2, '.', ' ')); ?> руб.</td>
                <td><?php echo htmlspecialchars($quantity); ?></td>
            </tr>
        <?php endif; endforeach; ?>
    </table>

    <p>Всего товаров в заказе на сумму: <?php echo htmlspecialchars(number_format($totalPrice, 2, '.', ' ')); ?> руб.</p>

<?php endforeach; ?>
