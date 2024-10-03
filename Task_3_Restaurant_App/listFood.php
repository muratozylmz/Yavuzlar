<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
}

$foods = GetFoods();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemekler</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery Kütüphanesi -->
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

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .searchbox {
            margin-bottom: 20px;
            text-align: center;
        }

        .searchbox input[type="search"] {
            width: 60%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        .searchbox input[type="search"]:focus {
            border-color: #28a745;
            outline: none;
        }

        .foodSection {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .dataElement {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 15px;
            padding: 15px;
            width: 300px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .dataElement:hover {
            transform: translateY(-5px);
        }

        .foodPhoto {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .discount {
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .foodSection p {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .description {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .highlight1, .highlight2 {
            font-weight: bold;
            color: green;
            font-size: 16px;
        }

        .button-container {
            text-align: right;
            margin-bottom: 20px;
        }

        .button-container a,
        button,
        .edit-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .button-container a:hover,
        button:hover,
        .edit-button:hover {
            background-color: #0056b3;
        }

        .edit-button {
            background-color: #ffc107;
        }

        .edit-button:hover {
            background-color: #e0a800;
        }

        button.add-to-basket {
            background-color: #28a745;
            margin-top: 10px;
        }

        button.add-to-basket:hover {
            background-color: #218838;
        }

        .centerDiv {
            text-align: center;
            margin-bottom: 20px;
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
    <!-- Sağ üst köşedeki butonlar -->
    <div class="home-button-container">
            <a href="index.php" class="cleanText"><button>Ana Sayfa</button></a>
        </div>

    <div class="centerDiv">
        <h1>Firmanın Yemekleri</h1>
        <a href="addFood.php"><button>Yemek Ekle</button></a>
    </div>

    <div>
        <div class="searchbox">
            <input type="search" id="searchbox" placeholder="Yemek Ara" />
        </div>
        <div class="foodSection" id="foodSection">
            <?php if (empty($foods)) { ?>
                <p>Hiçbir yemek bulunamadı.</p>
            <?php } else { ?>
                <?php foreach ($foods as $food): ?>
                    <div class="dataElement">
                        <img class="foodPhoto" src="<?php echo $food["image_path"]; ?>" alt="Yemek Fotoğrafı">
                        <?php if ($food['discount']) { ?>
                            <div class="discount">%<?php echo $food["discount"]; ?> indirim</div>
                        <?php } ?>
                        <p><?php echo $food["name"]; ?></p>
                        <span class="description"><?php echo $food["description"]; ?></span>
                        <p class="<?php echo $food['discount'] ? 'highlight' . $_SESSION['role'] : ''; ?>">
                            <?php echo $food['discount'] ? $food["price"] * (100 - $food['discount']) / 100 : $food['price']; ?> TL
                        </p>
                        <form class="add-to-basket-form">
                            <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                            <button type="button" class="add-to-basket">Ekle</button>
                        </form>
                        <a href="designFood.php?food_id=<?php echo $food['id']; ?>" class="edit-button">Düzenle</a>
                    </div>
                <?php endforeach ?>
            <?php } ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.add-to-basket').click(function() {
                var form = $(this).closest('.add-to-basket-form');
                var foodId = form.find('input[name="food_id"]').val();

                $.post('basket.php', { food_id: foodId }, function(data) {
                    alert('Yemek sepete eklendi!');
                }).fail(function() {
                    alert('Bir hata oluştu, lütfen tekrar deneyin.');
                });
            });

            $('#searchbox').on('input', function() {
                var searchText = $(this).val().toLowerCase();
                $('.dataElement').each(function() {
                    var foodName = $(this).find('p:first').text().toLowerCase();
                    if (foodName.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
</body>

</html>
