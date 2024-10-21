<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: index.php");
    exit();
} else if ($_SESSION['role'] == 2) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}
$user_id = $_SESSION['user_id'];
$cupon = $_SESSION['cupon'];
ConfirmBasket($user_id,$cupon);
header("Location: orders.php");
exit();
