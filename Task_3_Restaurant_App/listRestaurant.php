<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
}

$restaurants = GetRestaurantByCId($_SESSION['company_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmanın Restoranları</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .centerDiv {
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

        .dataTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .dataTable th, .dataTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .dataTable th {
            background-color: #f2f2f2;
        }

        .company_logo {
            width: 100px;
            height: auto;
        }

        .searchbox {
            margin: 20px 0;
            text-align: center;
        }

        .searchbox input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
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

    <div class="centerDiv">
        <div class="centerDiv">
            <h1>Firmanın Restoranları</h1>
            <a href="addRestaurant.php" class="t<?php echo $_SESSION['role']; ?>"><button>Restoran Ekle</button></a>
        </div>
        <?php if (empty($restaurants)) {
            echo "<p>Firmaya ait hiçbir restoran bulunamadı.</p>";
        } else { ?>
            <div class="searchbox">
                <input type="search" id="searchbox" placeholder="Yemek Ara" />
            </div>

            <table class="dataTable">
                <thead>
                    <tr>
                        <th>Restoran</th>
                        <th>Açıklama</th>
                        <th>Fotoğraf</th>
                        <th>Kayıt</th>
                        <th>Güncelle</th>
                        <th>Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <tr class="t<?php echo $_SESSION['role']; ?> dataElement dataTable">
                            <td>
                                <p><?php echo $restaurant["name"]; ?></p>
                            </td>
                            <td>
                                <p><?php echo $restaurant["description"]; ?></p>
                            </td>
                            <td>
                                <img src="<?php echo $restaurant["image_path"]; ?>" alt="Restoran Fotoğrafı" class="company_logo">
                            </td>
                            <td>
                                <p><?php echo $restaurant["created_at"]; ?></p>
                            </td>
                            <td>
                                <a href="designRestaurant.php?r_id=<?php echo $restaurant['id']; ?>"><button>Güncelle</button></a>
                            </td>
                            <td>
                                <form action="deleteRestaurant.php" method="post">
                                    <input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo $restaurant['id']; ?>">
                                    <button type="submit">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</body>

</html>
