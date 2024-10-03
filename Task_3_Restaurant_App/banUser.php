<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
    

    if (isset($_POST['user_id']) && !empty($_POST['user_id']) ) {
        $user_id = $_POST['user_id']; 
        BanUser($user_id);
        header("Location: customer.php");
        exit();
    } else {
        header("Location: customer.php?message=Eksik bilgi girdiniz.");
        exit();
    }
}
