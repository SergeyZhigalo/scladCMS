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
    #calendar{
        margin-top: 0;
    }
</style>
<body>
<!--шапка-->
<nav class="dws-menu">
    <ul>
        <li><a href="/">Наименования</a></li>
        <li><a href="/operation.php" class="active">Операции</a></li>
        <?php
        if ($_SESSION['logged_user']['role'] == 1)
            echo '<li><a href="/admin.php">Сотрудники</a></li>'
        ?>
        <li><button onclick="exit()">ВЫЙТИ</button></li>
    </ul>
</nav>
<!--поиск-->
<div class="search" id="search">
    <input type="text" id="operationNumber" placeholder="Поиск по номеру операции" onchange="table.getOllOperation()">
    <select id="reasonOperation" onchange="table.getOllOperation()">
        <option value="">все причичны</option>
        <option v-for="cause in allCauses" :value="cause.id">{{cause.cause}}</option>
    </select>
    <input type="date" id="calendar" onchange="table.getOllOperation()">
    <input type="text" id="barcode" placeholder="Поиск по штрих-коду товара" onchange="table.getOllOperation()">
    <input type="text" id="batchNumber" placeholder="Поиск по номеру партии" onchange="table.getOllOperation()">
    <button id="data" onclick="table.getOllOperation()">НАЙТИ</button>
</div>
<div class="main">
    <!--таблица товаров-->
    <table id="operation">
        <thead>
        <tr v-if="check">
            <th>Номер операции</th>
            <th>Причина операции</th>
            <th>Дата</th>
            <th>Артикул</th>
            <th>Номер партии</th>
            <th>Количество</th>
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
        <tr v-for="operation in ollOperation">
            <td>{{operation.operationNumber}}</td>
            <td>{{operation.cause}}</td>
            <td>{{operation.dateOperation}}</td>
            <td>{{operation.barcode}}</td>
            <td>{{operation.numderBatch}}</td>
            <td>{{operation.quantity}}</td>
            <?php
            if ($_SESSION['logged_user']['role'] == 1 or $_SESSION['logged_user']['role'] == 2){
                echo '<td><div class="change"><button :value="operation.operationNumber" onclick="change(this.value)">✍</i></button></div></td>';
                echo '<td><div class="delete"><button :value="operation.operationNumber" onclick="del(this.value)"><i class="fa fa-times" aria-hidden="true"></i></button></div></td>';
            }
            ?>
        </tr>
        </tbody>
    </table>
</div>
<!--кнопка добавления нового товара-->
<div class="add">
    <a href="/addOperatons.php">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
</div>
</body>
</html>
<script src="js/JQuery.js"></script>
<script src="js/vue.js"></script>
<script>
    $(document).ready(function(){
        table.getOllOperation();
        search.getOllCauses()
    });
    let table = new Vue({
        el: '#operation',
        data: {
            ollOperation: [],
            check: false,
        },
        methods: {
            getOllOperation: function () {
                let appVue = this;
                let data = {
                    getOllOperation: true,
                    operationNumber: $('#operationNumber').val(),
                    reasonOperation: $('#reasonOperation').val(),
                    calendar: $('#calendar').val(),
                    barcode: $('#barcode').val(),
                    batchNumber: $('#batchNumber').val()
                }
                data = JSON.stringify(data);
                const xhr = new XMLHttpRequest();
                xhr.open('POST','xhr/xhrTableAllOperation.php?',true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.send(data);
                xhr.onreadystatechange = function()
                {
                    if(xhr.readyState !== 4)
                        return;
                    if(xhr.status === 200){
                        appVue.ollOperation = JSON.parse(xhr.responseText);
                        table.checkVoid();
                    }
                }
                return false;
            },
            checkVoid: function () {
                let appVue = this;
                (appVue.ollOperation.length) ? appVue.check = true : appVue.check = false;
            }
        }
    })
    let search = new Vue({
        el: '#search',
        data: {
            allCauses: [],
        },
        methods: {
            getOllCauses: function () {
                let appVue = this;
                const xhr = new XMLHttpRequest();
                xhr.open('GET','xhr.php?getOllCauses=true',true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.send();
                xhr.onreadystatechange = function()
                {
                    if(xhr.readyState !== 4)
                        return;
                    if(xhr.status === 200)
                        appVue.allCauses = JSON.parse(xhr.responseText);
                }
                return false;
            },
        }
    })
    function exit() {
        location="/logout.php";
    }
    function change(id) {
        location="/changeOperation.php?changeOperation="+id;
    }
    function del(id) {
        let password = prompt('Для удаления товара введите delete');
        (password === 'delete') ? del(id) : alert('Проверочное слово введено не верно')
        function del(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET','xhr.php?deleteOperation='+id,true);
            xhr.setRequestHeader('Content-type', 'application/json');
            xhr.send();
            xhr.onreadystatechange = function()
            {
                if(xhr.readyState !== 4)
                    return;
                var response  = JSON.parse(xhr.responseText);
                if(xhr.status === 200){
                    alert(response)
                    table.getOllOperation();
                }
            }
            return false;
        }
    }
</script>