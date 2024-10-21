<?php
session_start();
include "functions/functions.php";

if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Company</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .login h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .container_obj {
            margin-bottom: 15px;
        }

        .container_obj label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .container_obj input[type="text"],
        .container_obj input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h1>Firma Ekle</h1>
            <form action="addFirmaQuery.php" method="post" enctype="multipart/form-data">
                <div class="container_obj">
                    <label for="name">Firma Adı</label>
                    <input type="text" name="name" placeholder="Firma Adı" required />
                </div>
                <div class="container_obj">
                    <label for="description">Açıklama</label>
                    <input type="text" name="description" placeholder="Açıklama" required />
                </div>
                <div class="container_obj">
                    <label for="image">Firma Logosu:</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                <button type="submit">Kaydet</button>
            </form>
        </div>
    </div>
</body>

</html>
