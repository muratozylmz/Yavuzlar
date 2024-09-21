<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {


    if (isset($_POST['restaurant_id']) && !empty($_POST['restaurant_id'])) {
        $restaurant_id = $_POST['restaurant_id'];
        DeleteRestaurant($restaurant_id);
        header("Location: listRestaurant.php");
        exit();
    } else {
        header("Location: listRestaurant.php?message=Eksik bilgi girdiniz.");
        exit();
    }
}
