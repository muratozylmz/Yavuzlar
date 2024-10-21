<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}
$food_id = $_GET['food_id'];
$food = GetFoodById($food_id);
$restaurants = GetRestaurantByCId($_SESSION['company_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Food <?php echo $food['name']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .login h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .medPhoto {
            display: block;
            margin: 0 auto 20px;
            max-width: 100%;
            border-radius: 8px;
        }

        .container_obj {
            margin-bottom: 15px;
        }

        .container_obj label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .container_obj input[type="text"],
        .container_obj input[type="number"],
        .container_obj input[type="file"],
        .container_obj select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        .container_obj input[type="text"]:focus,
        .container_obj input[type="number"]:focus,
        .container_obj input[type="file"]:focus,
        .container_obj select:focus {
            border-color: #28a745;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .t1 button,
        .t1 select,
        .t1 input {
            border-color: #007bff;
        }

        .t1 button:hover {
            background-color: #0056b3;
        }

        .t2 button,
        .t2 select,
        .t2 input {
            border-color: #ffc107;
        }

        .t2 button:hover {
            background-color: #e0a800;
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
            <a href="index.php" class="cleanText"><button>Ana Sayfa</button></a>
        </div>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h1><?php echo $food['name']; ?> Yemeğini Güncelle</h1>
            <img src="<?php echo $food['image_path']; ?>" alt="Yemek Fotoğrafı" class="medPhoto">
            <form action="designFoodQuery.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
                
                <div class="container_obj">
                    <label for="name">Yemek Adı:</label><br>
                    <input type="text" name="name" placeholder="Yemek Adı" required value="<?php echo $food['name']; ?>" />
                </div>

                <div class="container_obj">
                    <label for="description">Açıklama:</label><br>
                    <input type="text" name="description" placeholder="Açıklama" required value="<?php echo $food['description']; ?>" />
                </div>

                <div class="container_obj">
                    <label for="image">Yemek Fotoğrafı:</label><br>
                    <input type="file" name="image" accept="image/*" />
                </div>

                <div class="container_obj">
                    <label for="price">Yemek Fiyatı:</label><br>
                    <input type="number" min="1" name="price" placeholder="Yemek Fiyatı" required value="<?php echo $food['price']; ?>" />
                </div>

                <div class="container_obj">
                    <label for="discount">İndirim:</label><br>
                    <input type="number" min="0" max="100" name="discount" placeholder="İndirim" required value="<?php echo $food['discount']; ?>" />
                </div>

                <div class="container_obj">
                    <label for="restaurant_id">Restoran:</label><br>
                    <select name="restaurant_id" id="restaurant_id">
                        <?php foreach ($restaurants as $restaurant): ?>
                            <option value="<?php echo $restaurant['id']; ?>" <?php echo ($restaurant['id'] == $food['restaurant_id']) ? 'selected' : ''; ?>>
                                <?php echo $restaurant['name']; ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <button type="submit">Güncelle</button>
            </form>
            <!-- Silme Butonu -->
            <form action="deleteFood.php" method="post">
                <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
                <button type="submit" class="delete-btn">Sil</button>
            </form>
        </div>
    </div>
</body>

</html>
