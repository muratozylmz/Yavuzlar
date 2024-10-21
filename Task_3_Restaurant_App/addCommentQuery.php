<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: index.php");
    exit();
} else if ($_SESSION['role'] == 2) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}
if (isset($_POST['restaurant_id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['score']) && $_POST['score'] >= 0 && $_POST['score'] <= 10) {
    $restaurant_id = $_POST['restaurant_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $score = $_POST['score'];
    AddComment($restaurant_id, $title, $description, $score);
    header("Location: restaurant.php?r_id=" . $restaurant_id);
    exit();
} else {
    header("Location: restaurant.php?r_id=" . $restaurant_id . "?message=Eksik veya hatali bilgi girdiniz.");
    exit();
}
