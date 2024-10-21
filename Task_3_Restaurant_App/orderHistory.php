<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
}
$orders = GetOrders($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .centerDiv {
            width: 100%;
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .searchbox input {
            width: 80%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .dataTable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .dataTable th,
        .dataTable td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .dataTable th {
            background-color: #4CAF50;
            color: white;
            text-align: left;
        }

        .dataTable td {
            text-align: left;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .t0 button {
            background-color: #4CAF50;
        }

        .t1 button {
            background-color: #FF5733;
        }

        .b0 {
            background-color: #4CAF50;
        }

        .b1 {
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
    <title>Old Orders</title>
</head>

<body>
<div class="home-button-container">
            <a href="index.php" class="cleanText"><button>Ana Sayfa</button></a>
        </div>
    <div class="centerDiv">
        <h1>Eski Siparişleriniz</h1>
        <a href="orders.php" class="container_obj b<?php echo $_SESSION['role']; ?>"><button>Siparişler</button></a>
        <?php
        $completed_orders = array_filter($orders, function ($order) {
            return $order['order_status'] == 2;
        });
        if (empty($completed_orders)) {
            echo "<p>Geçmişe dair herhangi bir eski sipariş bulunamadı.</p>";
        } else {
        ?>
            <div class="searchbox">
                <input type="search" id="searchbox" placeholder="Sipariş Ara" />
            </div>
            <table class="dataTable">
                <thead>
                    <tr>
                        <th>Sipariş Durumu</th>
                        <th>Toplam Fiyat</th>
                        <th>Sipariş Tarihi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): if ($order['order_status'] == 2) { ?>
                            <tr class="dataElement t<?php echo $_SESSION['role']; ?>">
                                <td>
                                    <p><?php switch ($order["order_status"]) {
                                            case 0:
                                                echo "Hazırlanıyor";
                                                break;
                                            case 1:
                                                echo "Yola Çıktı";
                                                break;
                                            case 2:
                                                echo "Teslim Edildi";
                                                break;
                                            default:
                                                echo "Hata!";
                                                break;
                                        }
                                        ?></p>
                                </td>
                                <td>
                                    <p><?php echo $order["total_price"]; ?></p>
                                </td>
                                <td>
                                    <p><?php echo $order["created_at"]; ?></p>
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
