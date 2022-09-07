<?php
require "php/rb.php";
R::setup('mysql:host=localhost;dbname=sclad', 'root', 'root');
session_start();
if (!isset($_SESSION['logged_user']))
    header('Location: /login.php');
$changeUser = false;
$changeUser = $_GET['changeUser'];
if (!$changeUser)
    header('Location: /admin.php');
if ($changeUser == true){
    try {
        $db =  new PDO('mysql:host=localhost;dbname=sclad', 'root', '');
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT role.id, role.role, users.login, users.password FROM `users`, `role` WHERE users.role = role.id AND users.id = "'.$changeUser.'" ';
        $result = $db -> query($sql);
        $result = ($result -> fetchAll(PDO::FETCH_ASSOC));
    }catch (PDOException $e){
        header('Location: /admin.php');
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
echo '<h2>Изменение аккаунта сотрудника с идентификатором '.$changeUser.'</h2>';
echo '<input style="display:none" type="text" id="id" value="'.$changeUser.'">';
echo '<p>Логин: <input type="text" id="login" placeholder="Штрих-код" value="'.$result['0']['login'].'"></p>';
echo '<p>Роль: <select id="role">
        <option value="'.$result['0']['id'].'">'.$result['0']['role'].'</option>
        <option v-for="role in ollRole" :value="role.id">{{role.role}}</option>
      </select></p>';
echo '<p>Пароль: <input type="text" id="password" placeholder="Отпускная цена" value="'.$result['0']['password'].'"></p>';
?>
<div class="action">
    <button onclick="result()">ИЗМЕНИТЬ</button>
    <a href="/admin.php"><button>ОТМЕНИТЬ</button></a>
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
            changeUser: true,
            id: $('#id').val(),
            login: $('#login').val(),
            role: $('#role').val(),
            password: $('#password').val(),
        }
        data = JSON.stringify(data);
        const xhr = new XMLHttpRequest();
        xhr.open('POST','xhr/xhrChangeUser.php?',true);
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
    }
</script>