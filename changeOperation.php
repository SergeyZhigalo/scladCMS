<?php
require "php/rb.php";
R::setup('mysql:host=localhost;dbname=sclad', 'root', 'root');
session_start();
if (!isset($_SESSION['logged_user']))
    header('Location: //login.php');
$changeOperation = false;
$changeOperation = $_GET['changeOperation'];
if (!$changeOperation)
    header('Location: /operation.php');
if ($changeOperation == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', '');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT warehouseoperations.operationNumber, warehouseoperations.reasonOperationNumber, reasonoperation.cause, warehouseoperations.dateOperation, warehouseoperations.barcode, warehouseoperations.numderBatch, warehouseoperations.quantity FROM warehouseoperations, reasonoperation WHERE warehouseoperations.reasonOperationNumber = reasonoperation.id AND warehouseoperations.operationNumber ='.$changeOperation;
        $result = $db -> query($sql);
        $result = ($result -> fetchAll(PDO::FETCH_ASSOC));
    }catch (PDOException $e){
        header('Location: /operation.php');
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
echo '<h2>Изменение операции под номером '.$changeOperation.'</h2>';
echo '<input type="text" id="operationNumber" style="display:none" value="'.$result['0']['operationNumber'].'">';
echo '<p>Причина операции:
        <select id="reasonOperation">
            <option value="'.$result['0']['reasonOperationNumber'].'">'.$result['0']['cause'].'</option>
            <option v-for="cause in allCauses" :value="cause.id">{{cause.cause}}</option>
        </select>
    </p>';
echo '<p>Дата: <input type="date" id="calendar" value="'.$result['0']['dateOperation'].'"></p>';
echo '<p>Артикул товара:
        <select id="barcode">
            <option value="'.$result['0']['barcode'].'">'.$result['0']['barcode'].'</option>
            <option v-for="product in allProducts" :value="product.barcode">{{product.barcode}}</option>
        </select>
    </p>';
echo '<p>Номер партии: <input type="number" id="numderBatch" placeholder="Номер партии" value="'.$result['0']['numderBatch'].'" maxlength="11"></p>';
echo '<p>Количестово: <input type="number" id="quantity" placeholder="Количество" value="'.$result['0']['quantity'].'" maxlength="11"></p>';
?>
<div class="action">
    <button onclick="result()">ИЗМЕНИТЬ</button>
    <a href="/operation.php"><button>ОТМЕНИТЬ</button></a>
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
        search.getOllCauses()
        search.getOllProducts()
    });
    let search = new Vue({
        el: '#change',
        data: {
            allCauses: [],
            allProducts: [],
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
                        appVue.allProducts = JSON.parse(xhr.responseText);
                }
                return false;
            },
        }
    })
    function result() {
        let data = {
            changeOperation: true,
            operationNumber: $('#operationNumber').val(),
            reasonOperation: $('#reasonOperation').val(),
            calendar: $('#calendar').val(),
            barcode: $('#barcode').val(),
            numderBatch: $('#numderBatch').val(),
            quantity: $('#quantity').val(),
        }
        data = JSON.stringify(data);
        const xhr = new XMLHttpRequest();
        xhr.open('POST','xhr/xhrChangeOperation.php?',true);
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
    }
</script>