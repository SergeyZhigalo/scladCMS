<?php
require "php/rb.php";
R::setup('mysql:host=localhost;dbname=sclad','root','root');
session_start();
if (!isset($_SESSION['logged_user']))
    header('Location: /login.php');
?>
<!doctype html>
<html lang="ru">
<head>
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sclad CMS</title>
</head>
<body>
<div class="changeMain" id="change">
    <h2>Создание пользователя</h2>
    <p>Логин: <input type="text" id="login" placeholder="Логин" value="" maxlength="100"></p>
    <p>Роль:
        <select id="role">
            <option disabled selected value="zero">Не выбранно</option>
            <option v-for="role in ollRole" :value="role.id">{{role.role}}</option>
        </select>
    </p>
    <p>Пароль: <input type="text" id="password" placeholder="Пароль" value="" maxlength="100"></p>
    <div class="action">
        <button onclick="result()">СОЗДАТЬ</button>
        <a href="/admin.php"><button>ОТМЕНИТЬ</button></a>
    </div>
</div>
</body>
</html>
<script src="js/JQuery.js"></script>
<script src="js/vue.js"></script>
<script>
    $(document).ready(function(){
        search.getOllRole()
    });
    let search = new Vue({
        el: '#change',
        data: {
            ollRole: [],
        },
        methods: {
            getOllRole: function () {
                let appVue = this;
                const xhr = new XMLHttpRequest();
                xhr.open('GET','xhr.php?getOllRole=true',true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.send();
                xhr.onreadystatechange = function()
                {
                    if(xhr.readyState !== 4)
                        return;
                    if(xhr.status === 200)
                        appVue.ollRole = JSON.parse(xhr.responseText);
                }
                return false;
            },
        }
    })
    function result() {
        let data = {
            addUser: true,
            login: $('#login').val(),
            role: $('#role').val(),
            password: $('#password').val(),
        }
        let err = [];
        if (data.login === "" || data.login === null)
            err.push('Поле штрих-код пустое')
        if (data.role === "" || data.role === null)
            err.push('Категория не выбрана')
        if (data.password === "" || data.password === null)
            err.push('Поле названия пустое')
        if (!err.length){
            data = JSON.stringify(data);
            const xhr = new XMLHttpRequest();
            xhr.open('POST','xhr/xhrNewUser.php?',true);
            xhr.setRequestHeader('Content-type', 'application/json');
            xhr.send(data);
            xhr.onreadystatechange = function()
            {
                if(xhr.readyState !== 4)
                    return;
                if(xhr.status === 200){
                    alert(JSON.parse(xhr.responseText));
                    location="/admin.php";
                }
            }
            return false;
        }else{
            alert(err[0]);
        }
    }
</script>