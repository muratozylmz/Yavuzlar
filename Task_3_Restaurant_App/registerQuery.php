<?php
session_start();
include "functions/functions.php";
include "functions/db.php";
if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['username']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    Register($name, $surname, $username, $password);
    header("Location: login.php");
    exit();
} else {
    header("Location: login.php?message=Eksik bilgi girdiniz.");
    exit();
}