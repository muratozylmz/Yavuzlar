<?php

session_start();
include "functions/functions.php";
include "functions/db.php";
if(isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if(Login($username,$password)){
        header("Location: index.php");
        exit();
    }else{
        header("Location: login.php?message=Hatalı kullanıcı adı, şifre veya banlı kullanıcı");
        exit();
    }
}else{
    header("Location: login.php?message=Kullanıcı adı veya şifre boş bırakılamaz");
    exit();
}


?>