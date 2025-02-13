<?php
require_once '../core/init.php'; 

try {
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       // Получаем данные из формы логина
       $login = Cleaner::str($_POST['login']);
       $password = Cleaner::str($_POST['password']);
       
       // Создаем объект User для проверки логина и пароля
       $user = new User($login, password_hash('', PASSWORD_DEFAULT)); 
       if (Eshop::logIn($user)) { 
           header('Location: /admin'); // Переадресация на страницу админки после успешного входа 
           exit(); 
       }
   }
} catch (Exception $e) { 
   echo 'Ошибка: ' . htmlspecialchars($e->getMessage()); 
}
?>

<!-- HTML форма для входа -->
<h1>Вход в админку</h1>
<form action="login.php" method="post">
   <div>
       <label>Логин:</label>
       <input type="text" name="login" required>
   </div>
   <div>
       <label>Пароль:</label>
       <input type="password" name="password" required>
   </div>
   <div>
       <input type="submit" value="Войти">
   </div>
</form>
