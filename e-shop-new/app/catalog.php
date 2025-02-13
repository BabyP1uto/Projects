<?php
require_once 'core/init.php'; // Подключаем файл инициализации

?>

<h1>Каталог товаров</h1>
<p class='admin'><a href='admin'>админка</a></p>
<p>Товаров в <a href='basket'>корзине</a>: </p>
<table>
<tr>
    <th>Название</th>
    <th>Автор</th>
    <th>Год издания</th>
    <th>Цена, руб.</th>
    <th>В корзину</th>
</tr>

<?php
try {
    // Получаем итератор книг из каталога
    $booksIterator = Eshop::getItemsFromCatalog();

    // Заполняем таблицу данными о книгах
    foreach ($booksIterator as $book) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($book->getTitle()) . "</td>";
        echo "<td>" . htmlspecialchars($book->getAuthor()) . "</td>";
        echo "<td>" . htmlspecialchars($book->getPubyear()) . "</td>";
        echo "<td>" . htmlspecialchars(number_format($book->getPrice(), 2, '.', ' ')) . " руб.</td>";
        echo "<td><button onclick=\"addToBasket('" . htmlspecialchars($book->getTitle()) . "')\">В корзину</button></td>"; // Кнопка для добавления в корзину
        echo "</tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='5'>Ошибка: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>

</table>
