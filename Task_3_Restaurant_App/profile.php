<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        h3 {
            color: #333;
            margin-bottom: 20px;
        }

        .profile h4 {
            color: #555;
            margin: 10px 0;
            font-weight: normal;
        }

        .profile h4 span {
            font-weight: bold;
            color: #333;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 10px 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .action-buttons {
            margin-top: 20px;
        }

        a {
            text-decoration: none;
        }

        .anasayfa {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .anasayfa button {
            background-color: #28a745;
        }

        .anasayfa button:hover {
            background-color: #218838;
        }
    </style>
    <title>Profile</title>
</head>

<body>
    <div class="container">
        <div class="anasayfa">
            <a href="index.php">
                <button type="button" aria-required="true">Ana Sayfa</button>
            </a>
        </div>
        <h3>Profil</h3>
        
        <div class="profile">
            <h4>Ad: <span><?php echo $_SESSION['name']; ?></span></h4>
            <h4>Soyad: <span><?php echo $_SESSION['surname']; ?></span></h4>
            <h4>Kullanıcı Adı: <span><?php echo $_SESSION['username']; ?></span></h4>
            <h4>Bakiyeniz: <span><?php echo $_SESSION['balance']; ?></span></h4>
            <h4>Kayıt Tarihi: <span><?php echo $_SESSION['created_at']; ?></span></h4>
        </div>
        
        <div class="action-buttons">
            <a href="designProfile.php"><button type="button">Profili Güncelle</button></a>
            <a href="designPassword.php"><button type="button">Şifreyi Güncelle</button></a>
            <a href="addBalance.php"><button type="button">Bakiye Ekle</button></a>
        </div>
    </div>
</body>

</html>
