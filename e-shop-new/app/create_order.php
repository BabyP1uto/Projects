<h1>Оформление заказа</h1>
<p>Вернуться в <a href='/catalog'>каталог</a></p>
<form action="save_order" method="post">
    <div>
        <label>Заказчик:</label>
        <input type="text" name="customer" size="50" />
    </div>
    <div>
        <label>Email заказчика:</label>
        <input type="email" name="email" size="50" />
    </div>
    <div>
        <label>Телефон для связи:</label>
        <input type="phone" name="phone" size="50" />
    </div>
    <div>
        <label>Адрес доставки:</label>
        <input type="text" name="address" size="50" />
    </div>
    <div>
        <input type="submit" value="Заказать" />
    </div>
</form>