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

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kupon Ekle</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f4f4f4;
            }

            .container {
                max-width: 600px;
                margin: auto;
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            h3 {
                text-align: center;
            }

            .container_obj {
                margin-bottom: 15px;
            }

            label {
                display: block;
                margin-bottom: 5px;
            }

            input[type="text"],
            input[type="number"],
            select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-sizing: border-box;
            }

            button {
                width: 100%;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s;
            }

            button:hover {
                background-color: #45a049;
            }

            /* Ana Sayfa butonunu sağ üst köşeye konumlandır */
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
        <!-- Ana Sayfa butonunu sağ üst köşeye yerleştir -->
        <div class="home-button-container">
            <a href="index.php" style="text-decoration: none; color: white;"><button>Ana Sayfa</button></a>
        </div>

        <div class="container">
            <div class="login t<?php echo $_SESSION['role']; ?>">
                <h3>Kupon Ekle</h3>
                <form action="addKuponQuery.php" method="post" enctype="multipart/form-data">
                    <div class="container_obj">
                        <label for="name">Kupon Adı</label>
                        <input type="text" name="name" placeholder="Kupon Adı" required />
                    </div>
                    <div class="container_obj">
                        <label for="discount">İndirim</label>
                        <input type="number" min="1" max="100" name="discount" placeholder="İndirim" required />
                    </div>
                    <div class="container_obj">
                        <label for="restaurant">Restoran:</label>
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
