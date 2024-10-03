<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {

    $cupons = GetCupons();

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kuponlar</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f4f4f4;
            }

            .centerDiv {
                text-align: center;
                margin-bottom: 20px;
            }

            .cleanText {
                text-decoration: none;
                color: black;
            }

            button {
                padding: 10px 20px;
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

            .dataTable {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                background: white;
                border: 1px solid #ddd;
            }

            .dataTable th, .dataTable td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }

            .searchbox {
                margin-bottom: 20px;
                text-align: center;
            }

            #searchbox {
                width: 80%;
                padding: 10px;
                font-size: 16px;
                border: 1px solid #ddd;
                border-radius: 5px;
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
            <h1>Kuponlar</h1>
            <a href="addKupon.php" class="cleanText"><button>Kupon Ekle</button></a>
        </div>

        <?php if (empty($cupons)) { ?>
            <p>Herhangi bir kupon bulunamadı.</p>
        <?php } else { ?>
            <div class="searchbox">
                <input type="search" id="searchbox" placeholder="Kupon Ara" />
            </div>

            <table class="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Restoran ID</th>
                        <th>Ad</th>
                        <th>İndirim</th>
                        <th>Kayıt</th>
                        <th>Güncelle</th>
                        <th>Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cupons as $cupon): ?>
                        <tr>
                            <td><?php echo $cupon['id']; ?></td>
                            <td><?php echo $cupon['restaurant_id'] ?: "Genel"; ?></td>
                            <td><?php echo $cupon['name']; ?></td>
                            <td><?php echo "%" . $cupon['discount']; ?></td>
                            <td><?php echo $cupon['created_at']; ?></td>
                            <td><a href="designKupon.php?c_id=<?php echo $cupon['id']; ?>"><button>Güncelle</button></a></td>
                            <td>
                                <form action="deleteKupon.php" method="post">
                                    <input type="hidden" name="cupon_id" value="<?php echo $cupon['id']; ?>" />
                                    <button type="submit">X</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } ?>
    </body>

    </html>
<?php } ?>
