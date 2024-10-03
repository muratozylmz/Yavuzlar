<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
  
    $restaurants = GetRestaurants();
    $cupon_id = $_GET['c_id'];
    $cupon = GetCuponById($cupon_id);

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

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .login h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container_obj {
            margin-bottom: 15px;
        }

        label {
            font-size: 16px;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
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
    </style>
    <title>Update Cupon <?php echo $cupon['name']; ?></title>
</head>

<body>
    <div class="container">
        <div class="login t<?php echo $_SESSION['role']; ?>">
            <h3><?php echo $cupon['name']; ?> Kuponunu Güncelle</h3>
            <form action="designKuponQuery.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="cupon_id" value="<?php echo $cupon_id; ?>">
                <div class="container_obj">
                    <label for="name">Kupon Adı</label><br>
                    <input type="text" name="name" placeholder="Kupon Adı" value="<?php echo $cupon['name']; ?>" required />
                </div>
                <div class="container_obj">
                    <label for="discount">İndirim</label><br>
                    <input type="number" min="1" max="100" name="discount" placeholder="İndirim" value="<?php echo $cupon['discount']; ?>" required />
                </div>
                <div class="container_obj">
                    <label for="restaurant">Restoran:</label><br>
                    <select name="restaurant">
                        <option value="" selected="selected">Genel</option>
                        <?php foreach ($restaurants as $restaurant): ?>
                            <option value="<?php echo $restaurant['id']; ?>"><?php echo $restaurant['name']; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <button type="submit">Kaydet</button>
            </form>
        </div>
    </div>
</body>

</html>
<?php } ?>
