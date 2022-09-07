<?php
if ($_GET['getOllCategories'] == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', 'root');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM `categories`";
        $result = $db -> query($sql);
        $result = ($result -> fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    }catch (PDOException $e){
        echo json_encode($e -> getMessage());
    }
}
if ($_GET['getOllManufactures'] == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', 'root');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM `manufacturers`";
        $result = $db -> query($sql);
        $result = ($result -> fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    }catch (PDOException $e){
        echo json_encode($e -> getMessage());
    }
}
if ($_GET['getOllCauses'] == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', 'root');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM `reasonoperation`";
        $result = $db -> query($sql);
        $result = ($result -> fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    }catch (PDOException $e){
        echo json_encode($e -> getMessage());
    }
}
if ($_GET['getOllProducts'] == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', 'root');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM `products`";
        $result = $db -> query($sql);
        $result = ($result -> fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    }catch (PDOException $e){
        echo json_encode($e -> getMessage());
    }
}
if ($_GET['getOllRole'] == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', 'root');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM `role` ";
        $result = $db -> query($sql);
        $result = ($result -> fetchAll(PDO::FETCH_ASSOC));
        echo json_encode($result);
    }catch (PDOException $e){
        echo json_encode($e -> getMessage());
    }
}
if ($_GET['deleteUser'] == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', 'root');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'DELETE FROM `users` WHERE `users`.`id` ='.$_GET['deleteUser'];
        $result = $db -> exec($sql);
        if ($result)
            echo json_encode('операция прошла успешно');
        else
            echo json_encode('возникла непредвиденная ошибка');
    }catch (PDOException $e){
        echo json_encode($e -> getMessage());
    }
}
if ($_GET['deleteProduct'] == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', 'root');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM `products` WHERE `products`.`barcode` =".$_GET['deleteProduct'];
        $result = $db -> exec($sql);
        if ($result)
            echo json_encode('операция прошла успешно');
        else
            echo json_encode('возникла непредвиденная ошибка');
    }catch (PDOException $e){
        echo json_encode($e->getMessage());
    }
}
if ($_GET['deleteOperation'] == true) {
    try {
        $db = new PDO('mysql:host=localhost;dbname=sclad', 'root', 'root');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM `warehouseoperations` WHERE `warehouseoperations`.`operationNumber` =".$_GET['deleteOperation'];
        $result = $db->exec($sql);
        if ($result)
            echo json_encode('операция прошла успешно');
        else
            echo json_encode('возникла непредвиденная ошибка');
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}