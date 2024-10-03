<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: index.php");
    exit();
}
if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['username'])) {
   
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    UpdateProfile($name, $surname, $username);
    header("Location: profile.php");
    exit();
} else {
    header("Location: profile.php?message=Eksik bilgi girdiniz.");
    exit();
}
