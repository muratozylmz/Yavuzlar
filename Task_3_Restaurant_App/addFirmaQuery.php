<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
    if (isset($_POST['name']) && isset($_POST['description']) && isset($_FILES["image"])) {
        
        $name = $_POST['name'];
        $description = $_POST['description'];

        
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $new_file_name = time() . "." . $image_file_type;
        $logo_path = $target_dir . $new_file_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $logo_path)) {
            AddCompany($name, $description, $logo_path);
            header("Location: firma.php?message=Resim başarıyla kaydedildi!");
            exit();
        } else {
            header("Location: addFirma.php?message=Resim yüklenemedi.");
            exit();
        }
    }
}
