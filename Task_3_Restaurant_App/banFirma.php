<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
   

    if (isset($_POST['company_id']) && !empty($_POST['company_id']) ) {
        $company_id = $_POST['company_id']; 
        BanCompany($company_id);
        header("Location: firma.php");
        exit();
    } else {
        header("Location: firma.php?message=Eksik bilgi girdiniz.");
        exit();
    }
}
