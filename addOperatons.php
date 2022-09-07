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
    <h2>Проведение операции</h2>
    <p>Причина операции:
        <select id="reasonOperation">
            <option disabled selected value="">Не выбранно</option>
            <option v-for="operation in ollReasonOperation" :value="operation.id">{{operation.cause}}</option>
        </select>
    </p>
    <p>Дата: <input type="date" id="calendar"></p>
    <p>Артикул товара:
        <select id="barcode">
            <option disabled selected value="zero">Не выбранно</option>
            <option v-for="product in ollProducts" :value="product.barcode">{{product.barcode}}</option>
        </select>
    </p>
    <p>Номер партии: <input type="number" id="numderBatch" placeholder="Номер партии" value="" maxlength="11"></p>
    <p>Количестово: <input type="number" id="quantity" placeholder="Количество" value="" maxlength="11"></p>
    <div class="action">
        <button onclick="result()">СОЗДАТЬ</button>
        <a href="/operation.php"><button>ОТМЕНИТЬ</button></a>
    </div>
</div>
</body>
</html>
<script src="js/JQuery.js"></script>
<script src="js/vue.js"></script>
<script>
    $(document).ready(function(){
        search.getOllProducts()
        search.getOllReasonOperation()
    });
    let search = new Vue({
        el: '#change',
        data: {
            ollProducts: [],
            ollReasonOperation: [],
        },
        methods: {
            getOllProducts: function () {
                let appVue = this;
                const xhr = new XMLHttpRequest();
                xhr.open('GET','xhr.php?getOllProducts=true',true);
                xhr.setRequestHeader('Content-type', 'application/json');
                xhr.send();
                xhr.onreadystatechange = function()
                {
                    if(xhr.readyState !== 4)
                        return;
                    if(xhr.status === 200)
                        appVue.ollProducts = JSON.parse(xhr.responseText);
                }
                return false;
            },
            getOllReasonOperation: function () {
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
                        appVue.ollReasonOperation = JSON.parse(xhr.responseText);
                }
                return false;
            },
        }
    })
    function result() {
        let data = {
            addOperation: true,
            reasonOperation: $('#reasonOperation').val(),
            calendar: $('#calendar').val(),
            barcode: $('#barcode').val(),
            numderBatch: $('#numderBatch').val(),
            quantity: $('#quantity').val(),
        }
        let err = [];
        if (data.reasonOperation === "" || data.reasonOperation === null)
            err.push('Причина операции не выбрана')
        if (data.calendar === "" || data.calendar === null)
            err.push('Дата не выбрана')
        if (data.barcode === "" || data.barcode === null)
            err.push('Товар не выбран')
        if (data.numderBatch === "" || data.numderBatch === null)
            err.push('Поле номер партии пустое')
        if (data.quantity === "" || data.quantity === null)
            err.push('Поле количество товара пустое')
        if (!err.length){
            data = JSON.stringify(data);
            const xhr = new XMLHttpRequest();
            xhr.open('POST','xhr/xhrNewOperation.php?',true);
            xhr.setRequestHeader('Content-type', 'application/json');
            xhr.send(data);
            xhr.onreadystatechange = function()
            {
                if(xhr.readyState !== 4)
                    return;
                if(xhr.status === 200){
                    alert(JSON.parse(xhr.responseText));
                    location="/operation.php";
                }
            }
            return false;
        }else{
            alert(err[0]);
        }
    }
</script>