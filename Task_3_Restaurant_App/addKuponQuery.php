<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
    if (isset($_POST['name']) && isset($_POST['discount']) && $_POST['discount'] > 0 && $_POST['discount'] <= 100) {
       
        $restaurant_id = !empty($_POST['restaurant']) ? $_POST['restaurant'] : null;
        $name = $_POST['name'];
        $discount = $_POST['discount'];
        AddCupon($restaurant_id, $name, $discount);
        header("Location: listKupon.php?Kupon başarıyla eklendi!");
        exit();
    }
    header("Location: listKupon.php?Kupon eklenirken bir hata oluştu");
    exit();
}
