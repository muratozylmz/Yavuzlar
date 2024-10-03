<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] == 1) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}
$datas = GetCustomerOrders();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Old Orders of Customers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .centerDiv {
            text-align: center;
            margin: 2rem auto;
        }

        h1 {
            color: #333;
        }

        a {
            text-decoration: none;
            margin-bottom: 1rem;
            display: inline-block;
        }

        a button {
            padding: 0.5rem 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        a button:hover {
            background-color: #45a049;
        }

        .searchbox {
            margin-bottom: 1rem;
            text-align: center;
        }

        #searchbox {
            padding: 0.5rem;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        table {
            width: 90%;
            margin: 1rem auto;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 1rem;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td img.food_photo {
            max-width: 100px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .description {
            font-size: 0.9rem;
            color: #666;
        }

        .quantityContainer {
            text-align: center;
        }

        .editQuantity {
            padding: 0.5rem;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .editQuantity:hover {
            background-color: #d32f2f;
        }

        .dataElement:nth-child(even) {
            background-color: #f9f9f9;
        }

        .dataElement:nth-child(odd) {
            background-color: #fff;
        }

        .b1 {
            background-color: #2196F3;
        }

        .b1:hover {
            background-color: #1976D2;
        }

        .b2 {
            background-color: #FFC107;
        }

        .b2:hover {
            background-color: #FFA000;
        }

        .b3 {
            background-color: #FF5722;
        }

        .b3:hover {
            background-color: #E64A19;
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
    <div class="centerDiv">
        <h1>Müşterilerin Eski Siparişleri</h1>
        <a href="customerOrders.php" class="container_obj b<?php echo $_SESSION['role']; ?>"><button>Müşteri Siparişleri</button></a>
        <?php 
        $completed_orders = array_filter($datas, function ($data) {
            return $data['order_order_status'] == 2;
        });

        if (empty($completed_orders)) {
            echo "<p>Geçmişe dair herhangi bir eski sipariş bulunamadı.</p>";
        } else { ?>
            <div class="searchbox">
                <input type="search" id="searchbox" placeholder="Sipariş Ara" />
            </div>

            <table class="dataTable">
                <thead>
                    <tr>
                        <th>Sipariş No</th>
                        <th>Müşteri</th>
                        <th>Fotoğraf</th>
                        <th>Yemek</th>
                        <th>Açıklama</th>
                        <th>Fiyat</th>
                        <th>Sipariş</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datas as $data): if ($data['order_order_status'] == 2) { ?>
                            <tr class="t<?php echo $_SESSION['role']; ?> dataElement dataTable">
                                <td>
                                    <p><?php echo $data["order_id"]; ?></p>
                                </td>
                                <td>
                                    <p><?php echo $data["users_username"]; ?></p>
                                </td>
                                <td>
                                    <img class="food_photo" src="<?php echo $data["food_image_path"]; ?>" alt="Yemek Fotoğrafı">
                                </td>
                                <td>
                                    <p><?php echo $data["food_name"]; ?></p>
                                </td>
                                <td>
                                    <p class="description"><?php echo $data["food_description"]; ?></p>
                                </td>
                                <td>
                                    <p><?php echo $data["order_items_quantity"] . " x " . $data["order_items_price"] . " = " . $data["order_items_quantity"] * $data["order_items_price"]; ?></p>
                                </td>
                                <td class="quantityContainer">
                                    <p>Teslim Edildi</p>
                                </td>
                            </tr>
                    <?php }
                    endforeach ?>
                </tbody>
            </table>
        <?php } ?>
        
    </div>
</body>

</html>
