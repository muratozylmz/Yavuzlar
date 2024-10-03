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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .centerDiv {
            width: 100%;
            max-width: 900px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .searchbox input {
            width: 80%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .food_photo {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }

        .description {
            max-width: 300px;
            word-wrap: break-word;
        }

        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .editQuantity {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 6px;
            border-radius: 5px;
            cursor: pointer;
        }

        .editQuantity:hover {
            background-color: #1976D2;
        }

        .quantityContainer form {
            display: inline;
        }

        .t0 button, .b0 {
            background-color: #4CAF50;
        }

        .t1 button, .b1 {
            background-color: #FF5733;
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
    <title>Customer Orders</title>
</head>

<body>
<div class="home-button-container">
            <a href="index.php" class="cleanText"><button>Ana Sayfa</button></a>
        </div>
    <div class="centerDiv">
        <h1>Müşteri Siparişleri</h1>
        <a href="oldCustomerOrders.php" class="container_obj b<?php echo $_SESSION['role']; ?>"><button>Müşterilerin Eski Siparişleri</button></a>
        <?php if (empty($datas[0]['order_items_id'])) {
            echo "<p>Herhangi bir sipariş bulunamadı.</p>";
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
                    <?php foreach ($datas as $data): if ($data['order_order_status'] == 1 || $data['order_order_status'] == 0) { ?>
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
                                    <?php switch ($data["order_order_status"]) {
                                        case 0:
                                            echo "<p style='margin-top:1rem;'>Hazırlanıyor</p>"; ?>
                                            <form action="designOrder.php" method="post">
                                                <input type="hidden" name="o_id" value="<?php echo $data['order_id']; ?>">
                                                <input type="hidden" name="value" value="1">
                                                <button type="submit" class="editQuantity">→</button>
                                            </form>
                                        <?php
                                            break;
                                        case 1: ?>
                                            <form action="designOrder.php" method="post">
                                                <input type="hidden" name="o_id" value="<?php echo $data['order_id']; ?>">
                                                <input type="hidden" name="value" value="-1">
                                                <button type="submit" class="editQuantity">←</button>
                                            </form><?php
                                                    echo "<p>Yola Çıktı</p>"; ?>
                                            <form action="designOrder.php" method="post">
                                                <input type="hidden" name="o_id" value="<?php echo $data['order_id']; ?>">
                                                <input type="hidden" name="value" value="1">
                                                <button type="submit" class="editQuantity">→</button>
                                            </form><?php
                                                    break;
                                                case 2: ?>
                                            <form action="designOrder.php" method="post">
                                                <input type="hidden" name="o_id" value="<?php echo $data['order_id']; ?>">
                                                <input type="hidden" name="value" value="-1">
                                                <button type="submit" class="editQuantity">←</button>
                                            </form><?php
                                                    echo "<p style='margin-bottom:1rem;' >Teslim Edildi</p>";
                                                    break;
                                                default:
                                                    echo "<p>Hata!</p>";
                                                    break;
                                            } ?>
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
