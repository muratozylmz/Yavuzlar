<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        DeleteCompanyById($id);
        header("Location: firma.php");
        exit();
    } else {
        header("Location: firma.php?message=Eksik bilgi girdiniz.");
        exit();
    }
}