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
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h3 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            margin-bottom: 10px;
        }

        input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input[type="number"]:focus {
            border-color: #007bff;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
        }

        a button {
            background-color: #28a745;
            margin-top: 10px;
        }

        a button:hover {
            background-color: #218838;
        }

        .anasayfa {
            position: absolute;
            top: 20px;
            right: 20px;
        }

    </style>
    <title>Add Balance</title>
</head>

<body>
    <div class="container">
        <h3>Bakiyeniz: <?php echo $_SESSION['balance']; ?></h3>
        <form action="addBalanceQuery.php" method="post">
            <p>Yüklemek istediğiniz miktarı giriniz</p>
            <input type="number" name="balance" min="0" />
            <button type="submit">Yükle</button>
        </form>
    </div>
    <div class="anasayfa">
        <a href="index.php"><button type="button">Ana Sayfa</button></a>
    </div>
</body>

</html>
