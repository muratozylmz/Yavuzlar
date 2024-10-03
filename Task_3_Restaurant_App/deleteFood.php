<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
   

    if (isset($_POST['food_id']) && !empty($_POST['food_id'])) {
        $food_id = $_POST['food_id'];
        DeleteFood($food_id);
        header("Location: listFood.php");
        exit();
    } else {
        header("Location: listFood.php?message=Eksik bilgi girdiniz.");
        exit();
    }
}
