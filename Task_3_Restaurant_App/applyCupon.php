<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: index.php");
    exit();
} else if ($_SESSION['role'] != 2) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}
if (isset($_POST['c_name'])) {
    $c_name = $_POST['c_name'];
    $user_id = $_SESSION['user_id'];
    $cupon = GetCuponByName($c_name);
    if (!$cupon) {
        header("Location: basket.php?message=Kupon bulunamadı");
        exit();
    }
    $_SESSION['cupon'] = [
        'name' => $cupon['name'],
        'discount' => $cupon['discount'],
        'restaurant_id' => $cupon['restaurant_id']
    ];
    header("Location: basket.php?message=Kupon uygulandı!");
    exit();
} else {
    header("Location: basket.php?message=Eksik veya hatali bilgi girdiniz.");
    exit();
}
