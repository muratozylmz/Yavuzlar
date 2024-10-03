<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}

$restaurants = GetRestaurantByCId($_SESSION['company_id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food for <?php echo $_SESSION['company_id']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login {
            margin: 0 auto;
            padding: 20px;
        }

        .login h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .container_obj {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #45a049;
        }

        .t1 {
            background-color: #ffeb3b;
        }

        .t2 {
            background-color: #e0e0e0;
        }

        .t3 {
            background-color: #c8e6c9;
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
            <form action="addFoodQuery.php" method="post" enctype="multipart/form-data">
                <h1>Yemek Ekle</h1>
                <div>
                    <div class="container_obj">
                        <label for="name">Yemek Adı:</label><br>
                        <input type="text" name="name" placeholder="Yemek Adı" required />
                    </div>

                    <div class="container_obj">
                        <label for="description">Açıklama:</label><br>
                        <input type="text" name="description" placeholder="Açıklama" required />
                    </div>

                    <div class="container_obj">
                        <label for="image">Yemek Fotoğrafı:</label><br>
                        <input type="file" name="image" accept="image/*" required>
                    </div>

                    <div class="container_obj">
                        <label for="price">Yemek Fiyatı:</label><br>
                        <input type="number" min="1" name="price" placeholder="Yemek Fiyatı" required />
                    </div>

                    <div class="container_obj">
                        <label for="discount">İndirim:</label><br>
                        <input type="number" min="0" max="100" name="discount" placeholder="İndirim" required />
                    </div>

                    <div class="container_obj">
                        <label for="restaurant_id">Restoran:</label><br>
                        <select name="restaurant_id" id="restaurant_id">
                            <?php foreach ($restaurants as $restaurant): ?>
                                <option value="<?php echo $restaurant['id']; ?>"><?php echo $restaurant['name']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <button type="submit">Ekle</button>
            </form>
        </div>
    </div>
</body>

</html>
