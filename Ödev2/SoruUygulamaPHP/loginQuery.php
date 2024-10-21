<?php
    session_start();
    include "functions/functions.php";
    if(!isset($_POST['username']) || !isset($_POST['password'])) {
        header("Location: login.php?message=Kullanıcı adı ve şifre boş bırakılamaz!");
        die();
    }
    else 
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $result = Login($username,$password);
        $rowCount = $result['count'];
        if($rowCount==1)
        {
            $_SESSION["isAdmin"]=$result["isAdmin"];
            $_SESSION["username"]=$result["username"];
            header("Location:index.php");
            exit();
        }
        else
        {
            header("Location:login.php");
            exit();
        }
        
    }


?>