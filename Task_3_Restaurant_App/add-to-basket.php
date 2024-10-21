<?php
session_start();
include "functions/functions.php"; 

if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION['user_id'];   
    $foodId = $_POST['food_id'];      
    $quantity = $_POST['quantity'];   
    $note = $_POST['note'];           

    AddToBasket($userId, $foodId, $quantity, $note);  

    header("Location: basket.php");
    exit();
} else {
    echo "Hatalı istek!";
}
