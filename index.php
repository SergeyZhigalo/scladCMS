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
<!--шапка-->
<nav class="dws-menu">
    <ul>
        <li><a href="/" class="active">Наименования</a></li>
        <li><a href="/operation.php">Операции</a></li>
        <?php
        if ($_SESSION['logged_user']['role'] == 1)
            echo '<li><a href="/admin.php">Сотрудники</a></li>'
        ?>
        <li><button onclick="exit()">ВЫЙТИ</button></li>
    </ul>
</nav>
<!--поиск-->
<div class="search" id="search">
    <select id="searchByCategory" onchange="table.getOllUnits()">
        <option value="">все категории</option>
        <option v-for="category in ollCategories">{{category.category}}</option>
    </select>
    <select id="searchByManufacturers" onchange="table.getOllUnits()">
        <option value="">все производители</option>
        <option v-for="manufacturer in ollManufactures">{{manufacturer.manufacturer}}</option>
    </select>
    <input type="text" id="searchByName" placeholder="Поиск по названию товара" onchange="table.getOllUnits()">
    <input type="text" id="searchByBarcode" placeholder="Поиск по штрих-коду товара" onchange="table.getOllUnits()">
    <button onclick="table.getOllUnits()">НАЙТИ</button>
</div>
<div class="main">
<!--таблица товаров-->
    <table id="units">
        <thead>
        <tr v-if="check">
            <th>Артикул</th>
            <th>Категория</th>
            <th>Название</th>
            <th>Производитель</th>
            <th>Закупочная цена</th>
            <th>Отпускная цена</th>
            <?php
                if ($_SESSION['logged_user']['role'] == 1 or $_SESSION['logged_user']['role'] == 2)
                    echo '<th></th><th></th>'
            ?>
        </tr>
        <tr class="notFound" v-if="!check">
            <th>Совпадений не обнаруженно</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="unit in ollUnits">
            <td>{{unit.barcode}}</td>
            <td>{{unit.category}}</td>
            <td>{{unit.title}}</td>
            <td>{{unit.manufacturer}}</td>
            <td>{{unit.purchasePrice}}</td>
            <td>{{unit.sellingPrice}}</td>
            <?php
            if ($_SESSION['logged_user']['role'] == 1 or $_SESSION['logged_user']['role'] == 2){
                echo '<td><div class="change"><button :value="unit.barcode" onclick="change(this.value)">✍</i></button></div></td>';
                echo '<td><div class="delete"><button :value="unit.barcode" onclick="del(this.value)"><i class="fa fa-times" aria-hidden="true"></i></button></div></td>';
            }
            ?>
        </tr>
        </tbody>
    </table>
</div>
<!--кнопка добавления нового товара-->
<div class="add">
    <a href="/addProduct.php">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
</div>
</body>
</html>
<script src="js/JQuery.js"></script>
<script src="js/vue.js"></script>
<script>
    $(document).ready(function(){
        table.getOllUnits();
        search.getOllCategories()
        search.getOllManufactures()
    });
    let table = new Vue({
        el: '#units',
        data: {
            ollUnits: [],
            check: false,
        },
        methods: {
            getOllUnits: function () {
                let appVue = this;
                let data = {
                    getOllUnits: true,
                    category: $('#searchByCategory').val(),
                    manufacturer: $('#searchByManufacturers').val(),
                    name: $('#searchByName').val(),
                    barcode: $('#searchByBarcode').val()
                }
                data = JSON.stringify(data);
                const xhr = new XMLHttpRequest();
                xhr.open('POST','xhr/xhrTableAllProducts.php?',true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.send(data);
                xhr.onreadystatechange = function()
                {
                    if(xhr.readyState !== 4)
                        return;
                    if(xhr.status === 200){
                        appVue.ollUnits = JSON.parse(xhr.responseText);
                        table.checkVoid();
                    }
                }
                return false;
            },
            checkVoid: function () {
                let appVue = this;
                (appVue.ollUnits.length) ? appVue.check = true : appVue.check = false;
            }
        }
    })
    let search = new Vue({
        el: '#search',
        data: {
            ollCategories: [],
            ollManufactures: [],
        },
        methods: {
            getOllCategories: function () {
                let appVue = this;
                const xhr = new XMLHttpRequest();
                xhr.open('GET','xhr.php?getOllCategories=true',true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.send();
                xhr.onreadystatechange = function()
                {
                    if(xhr.readyState !== 4)
                        return;
                    if(xhr.status === 200)
                        appVue.ollCategories = JSON.parse(xhr.responseText);
                }
                return false;
            },
            getOllManufactures: function () {
                let appVue = this;
                const xhr = new XMLHttpRequest();
                xhr.open('GET','xhr.php?getOllManufactures=true',true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.send();
                xhr.onreadystatechange = function()
                {
                    if(xhr.readyState !== 4)
                        return;
                    if(xhr.status === 200)
                        appVue.ollManufactures = JSON.parse(xhr.responseText);
                }
                return false;
            },
        }
    })
    function exit() {
        location="/logout.php";
    }
    function change(id) {
        location="changeProduct.php?changeProduct="+id;
    }
    function del(id) {
        let password = prompt('Для удаления товара введите delete');
        (password === 'delete') ? del(id) : alert('Проверочное слово введено не верно')
        function del(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET','xhr.php?deleteProduct='+id,true);
            xhr.setRequestHeader('Content-type', 'application/json');
            xhr.send();
            xhr.onreadystatechange = function()
            {
                if(xhr.readyState !== 4)
                    return;
                var response  = JSON.parse(xhr.responseText);
                if(xhr.status === 200){
                    alert(response)
                    table.getOllUnits();
                }
            }
            return false;
        }
    }
</script>