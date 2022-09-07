<?php
require "php/rb.php";
R::setup('mysql:host=localhost;dbname=sclad', 'root', 'root');
session_start();
if (!isset($_SESSION['logged_user']))
    header('Location: /login.php');
$changeProduct = false;
$changeProduct = $_GET['changeProduct'];
if (!$changeProduct)
    header('Location: /');
if ($changeProduct == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', '');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT products.barcode, categories.id AS categoriesId, categories.category, categories.id AS categoriesId, products.title, manufacturers.id AS manufacturersId, manufacturers.manufacturer, products.purchasePrice, products.sellingPrice FROM products, categories, manufacturers WHERE `products`.`barcode` = '.$changeProduct.' AND categories.id = products.categoryNumber AND manufacturers.id = products.manufacturerNumber ';
        $result = $db -> query($sql);
        $result = ($result -> fetchAll(PDO::FETCH_ASSOC));
    }catch (PDOException $e){
        header('Location: /');
    }
}
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
<?php

echo '<div class="changeMain" id="change">';
echo '<h2>Изменение информации о товаре с артикулом '.$changeProduct.'</h2>';
echo '<input type="text" id="oldBarcode" style="display:none" value="'.$result['0']['barcode'].'">';
echo '<p>Штрих-код: <input type="text" id="barcode" placeholder="Штрих-код" value="'.$result['0']['barcode'].'"></p>';
echo '<p>Название: <input type="text" id="title" placeholder="Название" value="'.$result['0']['title'].'"></p>';
echo '<p>Категория: <select id="category">
        <option value="'.$result['0']['categoriesId'].'">'.$result['0']['category'].'</option>
        <option v-for="category in ollCategories" :value="category.id">{{category.category}}</option>
      </select></p>';
echo '<p>Производитель: <select id="manufactures">
        <option value="'.$result['0']['manufacturersId'].'">'.$result['0']['manufacturersId'].' ('.$result['0']['manufacturer'].')</option>
        <option v-for="manufacturer in ollManufactures" :value="manufacturer.manufacturer">{{manufacturer.id}} ({{manufacturer.manufacturer}})</option>
      </select></p>';
echo '<p>Закупочная цена: <input type="text" id="purchasePrice" placeholder="Закупочная цена" value="'.$result['0']['purchasePrice'].'"></p>';
echo '<p>Отпускная цена: <input type="text" id="sellingPrice" placeholder="Отпускная цена" value="'.$result['0']['sellingPrice'].'"></p>';
?>
<div class="action">
    <button onclick="result()">ИЗМЕНИТЬ</button>
    <a href="/"><button>ОТМЕНИТЬ</button></a>
</div>
<?php
echo '</div>';
?>
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
            changeProductData: true,
            oldBarcode: $('#oldBarcode').val(),
            barcode: $('#barcode').val(),
            category: $('#category').val(),
            name: $('#title').val(),
            manufactures: $('#manufactures').val(),
            purchasePrice: $('#purchasePrice').val(),
            sellingPrice: $('#sellingPrice').val()
        }
        data = JSON.stringify(data);
        const xhr = new XMLHttpRequest();
        xhr.open('POST','xhr/xhrChangeProduct.php?',true);
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
    }
</script>