<?php
session_start();
include "functions/functions.php";

if (!IsUserLoggedIn()) {
    header("Location: index.php");
    exit();
} else if ($_SESSION['role'] == 2) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
    exit();
}

if (isset($_POST['basket_id'])) {
    $basket_id = $_POST['basket_id'];
    DecreaseBasketQuantity($basket_id);
    header("Location: basket.php");
    exit();
} else {
    header("Location: basket.php?message=Eksik veya hatali bilgi girdiniz.");
    exit();
}
?>
