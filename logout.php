<?php
require "php/rb.php";
R::setup('mysql:host=localhost;dbname=sclad','root','root');
session_start();
unset($_SESSION['logged_user']);
header('Location: /index.php');