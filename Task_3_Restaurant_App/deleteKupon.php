<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
    include "../controllers/admin-controller.php";

    if (isset($_POST['cupon_id']) && !empty($_POST['cupon_id'])) {
        $cupon_id = $_POST['cupon_id'];
        DeleteCupon($cupon_id);
        header("Location: listKupon.php");
        exit();
    } else {
        header("Location: listKupon.php?message=Eksik bilgi girdiniz.");
        exit();
    }
}
