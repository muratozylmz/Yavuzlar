<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}

$restaurant_id = $_GET['r_id'];
$restaurant = GetRestaurantById($restaurant_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update <?php echo $restaurant['name']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container_obj {
            margin-bottom: 15px;
        }

        .container_obj label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .container_obj input[type="text"],
        .container_obj input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .container_obj input[type="file"] {
            padding: 3px;
        }

        .medPhoto {
            display: block;
            margin: 0 auto 20px;
            max-width: 150px;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .login.t<?php echo $_SESSION['role']; ?> {
            /* Specific styles for the user role if needed */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h1><?php echo $restaurant['name']; ?> Restoranını Güncelle</h1>
            <img src="<?php echo $restaurant['image_path']; ?>" alt="Firma Logosu" class="medPhoto">
            <form action="designRestaurantQuery.php" method="post" enctype="multipart/form-data">
                <div class="container_obj">
                    <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>" />
                </div>
                <div class="container_obj">
                    <label for="name">Ad</label><br>
                    <input type="text" value="<?php echo $restaurant['name']; ?>" placeholder="Restoran Adı" name="name" required />
                </div>
                <div class="container_obj">
                    <label for="description">Açıklama</label><br>
                    <input type="text" value="<?php echo $restaurant['description']; ?>" name="description" placeholder="Açıklama" required />
                </div>
                <div class="container_obj">
                    <label for="image">Restoran Logosu:</label><br>
                    <input type="file" name="image" accept="image/*" required />
                </div>
                <button type="submit">Güncelle</button>
            </form>
        </div>
    </div>
</body>

</html>
