<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
    if (isset($_POST['o_id']) && ($_POST['value'] == 1 || $_POST['value'] == -1)) {
        $order_id = $_POST['o_id'];
        $value = $_POST['value'];
        UpdateOrderStatus($order_id, $value);
        header("Location: customerOrders.php");
    }
    header("Location: customerOrders.php?message=Eksik veya hatalı bilgi girdiniz.");
    exit();
}
