<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
    if (isset($_POST['name']) && isset($_POST['description']) && isset($_FILES["image"])) {
       
        $name = $_POST['name'];
        $description = $_POST['description'];
        $company_id = $_SESSION['company_id'];

        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $new_file_name = time() . "." . $image_file_type;
        $image_path = $target_dir . $new_file_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            AddRestaurant($company_id, $name, $description, $image_path);
            header("Location: restaurant-list.php?message=Başarıyla kaydedildi!");
            exit();
        } else {
            header("Location: addRestaurant.php?message=Resim yüklenirken bir hata oluştu!");
            exit();
        }
    }
}
