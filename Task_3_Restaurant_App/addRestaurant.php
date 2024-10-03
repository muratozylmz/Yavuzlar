<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Ekle - Şirket <?php echo $_SESSION['company_id']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            position: relative;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .container_obj {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="file"],
        button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .home-button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .home-button-container button {
                background-color: #007bff;
            }

            .home-button-container button:hover {
                background-color: #0056b3;
            }
    </style>
</head>

<body>
    <div class="home-button-container">
        <a href="index.php" style="text-decoration: none; color: white;"><button>Ana Sayfa</button></a>
    </div>

    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h1>Restoran Ekle</h1>
            <form action="addRestaurantQuery.php" method="post" enctype="multipart/form-data">
                <div class="container_obj">
                    <label for="name">Restoran Adı:</label><br>
                    <input type="text" name="name" placeholder="Restoran Adı" required />
                </div>

                <div class="container_obj">
                    <label for="description">Açıklama:</label><br>
                    <input type="text" name="description" placeholder="Açıklama" required />
                </div>
                <div class="container_obj">
                    <label for="image">Restoran Fotoğrafı:</label><br>
                    <input type="file" name="image" accept="image/*" required>
                </div>

                <button type="submit">Ekle</button>
            </form>
        </div>
    </div>
</body>

</html>
