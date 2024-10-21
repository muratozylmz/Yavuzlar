<?php
session_start();
include "functions/functions.php"; 

if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_id = $_POST['food_id'];
    $user_id = $_SESSION['user_id'];  
    $quantity = 1;  
    $note = "";  

    $existingBasketItem = GetBasketItem($user_id, $food_id);

    if ($existingBasketItem) {
        $new_quantity = $existingBasketItem['quantity'] + 1;
        UpdateBasketItemQuantity($existingBasketItem['id'], $new_quantity);
    } else {
        AddToBasket($user_id, $food_id, $quantity, $note);
    }

    header("Location: basket.php");
    exit();
}

function AddToBasket($user_id, $food_id, $quantity, $note) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO basket (user_id, food_id, quantity, note) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $food_id, $quantity, $note]);
}

function GetBasketItem($user_id, $food_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM basket WHERE user_id = ? AND food_id = ?");
    $stmt->execute([$user_id, $food_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function UpdateBasketItemQuantity($basket_id, $quantity) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE basket SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $basket_id]);
}

$datas = GetBasket($_SESSION['user_id']);
$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        .dataTable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .dataTable th, .dataTable td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .dataTable th {
            background-color: #f2f2f2;
        }

        .dataElement img {
            max-width: 100px;
        }

        .centerDiv {
            text-align: center;
            margin-top: 20px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .emptyBasket {
            color: #777;
            font-size: 18px;
        }

        .totalPrice {
            font-weight: bold;
            font-size: 20px;
        }

        a {
            text-decoration: none;
        }

        /* Searchbox Styling */
        #searchbox {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        #searchbox:focus {
            border-color: #28a745;
            outline: none;
        }

        /* Ana sayfa butonu sağ üst köşe */
        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .top-right button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .top-right button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Sepetiniz</h1>

    <?php if (empty($datas)) { ?>
        <p class="emptyBasket">Sepetiniz boş.</p>
    <?php } else { ?>
        <table class="dataTable">
            <thead>
                <tr>
                    <th>Fotoğraf</th>
                    <th>Yemek</th>
                    <th>Açıklama</th>
                    <th>Fiyat</th>
                    <th>İndirim</th>
                    <th>Sayı</th>
                    <th>Sil</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datas as $data): 
                    
                    $food = GetFoodById($data["food_id"]); ?>
                    <tr class="dataElement">
                        <td><img src="<?php echo $food["image_path"]; ?>" alt="Yemek Fotoğrafı"></td>
                        <td><?php echo $food["name"]; ?></td>
                        <td><?php echo $food["description"]; ?></td>
                        <td><?php echo $food['discount'] ? ($food["price"] * (100 - $food['discount']) / 100) : $food['price']; ?> TL</td>
                        <td><?php echo $food['discount'] ? "%" . $food['discount'] : "İndirim yok"; ?></td>
                        <td><?php echo isset($data['quantity']) ? $data['quantity'] : 0; ?></td>
                        <td>
                            <form action="deleteBasket.php" method="post">
                                <input type="hidden" name="basket_id" value="<?php echo $data['basket_id']; ?>">
                                <button type="submit">Sil</button>
                            </form>
                        </td>
                    </tr>

                    <?php 
                    $itemPrice = $food['discount'] ? ($food["price"] * (100 - $food['discount']) / 100) : $food['price'];
                    $totalPrice += $itemPrice * $data["quantity"];
                endforeach; ?>
            </tbody>
        </table>
                
        <div class="centerDiv">
        <form action="applyCupon.php" method="post">
                    <label for="c_name">Kupon Giriniz:</label>
                    <input type="text" name="c_name">
                    <button type="submit">Uygula</button>
                </form>
    <p class="totalPrice">Toplam Fiyat: <?php echo $totalPrice; ?> TL</p>

<form action="confirmBasket.php" method="post">
                    <button type="submit">Sipariş Ver</button>
                </form>

</div>


    <?php } ?>

    <!-- Ana sayfa butonu sağ üst köşede -->
    <div class="top-right">
        <a href="index.php"><button>Ana Sayfa</button></a>
    </div>

</body>

</html>
