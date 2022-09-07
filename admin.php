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
<style>
    table{
        width: 60%;
        margin: 0 auto;
    }
</style>
<body>
<!--шапка-->
<nav class="dws-menu">
    <ul>
        <li><a href="/">Наименования</a></li>
        <li><a href="/operation.php">Операции</a></li>
        <li><a href="/admin.php" class="active">Сотрудники</a></li>
        <li><button onclick="exit()">ВЫЙТИ</button></li>
    </ul>
</nav>
<!--поиск-->
<div class="search" id="search">
    <select id="searchRole" onchange="table.getOllUsers()">
        <option value="">все роли</option>
        <option v-for="role in ollRole" :value="role.id">{{role.role}}</option>
    </select>
    <input type="text" id="searchLogin" placeholder="Поиск по логину" onchange="table.getOllUsers()">
    <button onclick="table.getOllUsers()">НАЙТИ</button>
</div>
<div class="main">
    <!--таблица товаров-->
    <table id="units">
        <thead>
        <tr v-if="check">
            <th>Роль</th>
            <th>Логин</th>
            <th></th><th></th>
        </tr>
        <tr class="notFound" v-if="!check">
            <th>Совпадений не обнаруженно</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="user in ollUsers">
            <td>{{user.role}}</td>
            <td>{{user.login}}</td>
            <td><div class="change"><button :value="user.id" onclick="change(this.value)">✍</i></button></div></td>
            <td><div class="delete"><button :value="user.id" onclick="del(this.value)"><i class="fa fa-times" aria-hidden="true"></i></button></div></td>
        </tr>
        </tbody>
    </table>
</div>
<!--кнопка добавления нового товара-->
<div class="add">
    <a href="/addUser.php">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
</div>
</body>
</html>
<script src="js/JQuery.js"></script>
<script src="js/vue.js"></script>
<script>
    $(document).ready(function(){
        table.getOllUsers();
        search.getOllRole()
    });
    let table = new Vue({
        el: '#units',
        data: {
            ollUsers: [],
            check: false,
        },
        methods: {
            getOllUsers: function () {
                let appVue = this;
                let data = {
                    getOllRole: true,
                    searchRole: $('#searchRole').val(),
                    searchLogin: $('#searchLogin').val()
                }
                data = JSON.stringify(data);
                const xhr = new XMLHttpRequest();
                xhr.open('POST','xhr/xhrTableAllRole.php?',true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.send(data);
                xhr.onreadystatechange = function()
                {
                    if(xhr.readyState !== 4)
                        return;
                    if(xhr.status === 200){
                        appVue.ollUsers = JSON.parse(xhr.responseText);
                        table.checkVoid();
                    }
                }
                return false;
            },
            checkVoid: function () {
                let appVue = this;
                (appVue.ollUsers.length) ? appVue.check = true : appVue.check = false;
            }
        }
    })
    let search = new Vue({
        el: '#search',
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
    function exit() {
        location="/logout.php";
    }
    function change(id) {
        location="changeUser.php?changeUser="+id;
    }
    function del(id) {
        let password = prompt('Для удаления товара введите delete');
        (password === 'delete') ? del(id) : alert('Проверочное слово введено не верно')
        function del(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET','xhr.php?deleteUser='+id,true);
            xhr.setRequestHeader('Content-type', 'application/json');
            xhr.send();
            xhr.onreadystatechange = function()
            {
                if(xhr.readyState !== 4)
                    return;
                var response  = JSON.parse(xhr.responseText);
                if(xhr.status === 200){
                    alert(response)
                    table.getOllUsers();
                }
            }
            return false;
        }
    }
</script>