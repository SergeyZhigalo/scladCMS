<?php
require "php/rb.php";
R::setup('mysql:host=localhost;dbname=sclad','root','root');
session_start();
$data = $_POST;
if (isset($data['do_login']))
{
    $errors = array();
    $user = R::findOne('users', 'login = ?', array($data['login']));
    if ($user) {
        if ($data['password'] != $user['password']) {
            $errors[] = 'Неверно введен пароль!';
        }else{
            $_SESSION['logged_user'] = $user;
            header('Location: /index.php');
        }
    }else{
        $errors[] = 'Пользователь с таким логином не найден!';
    }
    if (!empty($errors)) {
        echo'<div style="color: red;">'.array_shift($errors).'</div><hr>';
    }
}
?>
<style>
    .login{
        text-align: center;
        box-shadow: 0 24px 38px 3px rgba(0,0,0,0.14), 0 9px 46px 8px rgba(0,0,0,0.12), 0 11px 15px -7px rgba(0,0,0,0.2);
        background-color: lightcyan;
        border-radius: 20px;
        width: 380px;
        margin: 0 auto;
        margin-top: 15%;
    }
    .login h2{
        padding-top: 10px;
    }
    .login strong{
        font-size: 18px;
    }
    .login input{
        width: 90%;
        font-size: 18px;
        padding: 5px 10px;
    }
    .login button{
        margin-top: 20px;
        margin-bottom: 10px;
        padding: 15px 20px;
        border: none;
        background-color: green;
        color: white;
        font-size: 20px;
        border-radius: 20px;
    }
    @media all and (max-width:770px){
        .login{
            margin-top: 50%;
        }
    }
</style>
<div class="login">
    <h2>Форма входа</h2>
    <form action="login.php" method="POST">
        <p><strong>Ваш логин</strong></p>
        <input type="text" name="login" value="<?php echo @$data['login'];?>">
        <p><strong>Ваш пароль</strong></p>
        <input type="password" name="password" value="<?php echo @$data['password'];?>"><br>
        <button type="submit" name="do_login">Войти</button>
    </form>
</div>