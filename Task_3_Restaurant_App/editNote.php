<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: index.php");
    exit();
} else if ($_SESSION['role'] != 2) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}
if (isset($_POST['basket_id']) && isset($_POST['note'])) {
    $basket_id = $_POST['basket_id'];
    $note = $_POST['note'];
    EditNote($basket_id, $note);
    header("Location: basket.php");
    exit();
} else {
    header("Location: basket.php?message=Eksik veya hatali bilgi girdiniz.");
    exit();
}
