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
    <h2>Создание товара</h2>
    <p>Штрих-код: <input type="number" id="barcode" placeholder="Штрих-код" value="" maxlength="11"></p>
    <p>Название: <input type="text" id="title" placeholder="Название" value="" maxlength="255"></p>
    <p>Категория:
    <select id="category">
        <option disabled selected value="zero">Не выбранно</option>
        <option v-for="category in ollCategories" :value="category.id">{{category.category}}</option>
    </select></p>
    <p>Производитель: <select id="manufactures">
        <option disabled selected value="zero">Не выбранно</option>
        <option v-for="manufacturer in ollManufactures" :value="manufacturer.id">{{manufacturer.id}} ({{manufacturer.manufacturer}})</option>
    </select></p>
    <p>Закупочная цена: <input type="number" id="purchasePrice" placeholder="Закупочная цена" value="" maxlength="11"></p>
    <p>Отпускная цена: <input type="number" id="sellingPrice" placeholder="Отпускная цена" value="" maxlength="11"></p>
    <div class="action">
        <button onclick="result()">СОЗДАТЬ</button>
        <a href="/"><button>ОТМЕНИТЬ</button></a>
    </div>
</div>
</body>
</html>
<script src="js/JQuery.js"></script>
<script src="js/vue.js"></script>
<script>
    $(document).ready(function(){
        search.getOllCategories()
        search.getOllManufactures()
    });
    let search = new Vue({
        el: '#change',
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
    function result() {
        let data = {
            addProduct: true,
            barcode: $('#barcode').val(),
            category: $('#category').val(),
            name: $('#title').val(),
            manufactures: $('#manufactures').val(),
            purchasePrice: $('#purchasePrice').val(),
            sellingPrice: $('#sellingPrice').val()
        }
        let err = [];
        if (data.barcode === "" || data.barcode === null)
            err.push('Поле штрих-код пустое')
        if (data.category === "" || data.category === null)
            err.push('Категория не выбрана')
        if (data.name === "" || data.name === null)
            err.push('Поле названия пустое')
        if (data.manufactures === "" || data.manufactures === null)
            err.push('Производитель не выбран')
        if (data.purchasePrice === "" || data.purchasePrice === null)
            err.push('Поле закупочная цена пустое')
        if (data.sellingPrice === "" || data.sellingPrice === null)
            err.push('Поле отпускная цена пустое')
        if (!err.length){
            data = JSON.stringify(data);
            const xhr = new XMLHttpRequest();
            xhr.open('POST','xhr/xhrNewProduct.php?',true);
            xhr.setRequestHeader('Content-type', 'application/json');
            xhr.send(data);
            xhr.onreadystatechange = function()
            {
                if(xhr.readyState !== 4)
                    return;
                if(xhr.status === 200){
                    alert(JSON.parse(xhr.responseText));
                    location="/";
                }
            }
            return false;
        }else{
            alert(err[1]);
        }
    }
</script>