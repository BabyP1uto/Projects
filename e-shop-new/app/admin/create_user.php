<h1>Добавить пользователя</h1>
<p><a href='/admin'>Назад в админку</a></p>
<form action="save_user" method="post">
    <div>
        <label>Логин:</label> 
        <input type="text" name="login" size="25">
    </div>
    <div>
        <label>Пароль:</label> 
        <input type="password" name="password" size="25">
    </div>
    <div>
        <label>Email:</label> 
        <input type="email" name="email" size="25">
    </div>
    <div>
        <input type="submit" value="Создать">
    </div>
</form>