<?php
session_start();
include "functions/functions.php";
if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
} else {
    $datas = GetUser();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }

            .container {
                background-color: white;
                width: 100%;
                max-width: 800px;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h3 {
                text-align: center;
                margin-bottom: 20px;
            }

            #searchbox {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .customerDiv {
                background-color: #f9f9f9;
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 8px;
                display: grid;
                grid-template-columns: repeat(8, 1fr);
                gap: 10px;
                align-items: center;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .customerDiv p {
                margin: 0;
                padding: 0;
            }

            button {
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 8px 12px;
                border-radius: 4px;
                cursor: pointer;
            }

            button:hover {
                background-color: #c82333;
            }

            a button {
                background-color: #007bff;
                padding: 10px;
                text-align: center;
                display: block;
                margin: 20px auto 0;
                width: 100px;
            }

            a button:hover {
                background-color: #0056b3;
            }

            /* Checkbox and label styling */
            div label {
                font-weight: bold;
            }

            #isBanned {
                margin-left: 10px;
                transform: scale(1.2);
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
        <div class="container">
            <h3>Müşteriler</h3>
            <div>
                <input
                    type="search"
                    id="searchbox"
                    onchange="liveSearch()"
                    placeholder="Müşteri Ara" />
                <div>
                    <label for="isBanned">Banlı mı?</label>
                    <input type="checkbox" id="isBanned" />
                </div>
            </div>

            <?php if (empty($datas)) {
                echo "No users found.";
            } ?>
            <?php foreach ($datas as $data): ?>
                <div class="customerDiv" is-banned="<?php echo $data['user_deleted_at'] ? 'true' : 'false'; ?>">
                    <p> <?php echo $data['user_id']; ?> </p>
                    <p> <?php echo $data['user_company_id']; ?> </p>
                    <p> <?php echo $data['user_name']; ?> </p>
                    <p> <?php echo $data['user_surname']; ?> </p>
                    <p> <?php echo $data['user_username']; ?> </p>
                    <p> <?php echo $data['user_balance']; ?> </p>
                    <p> <?php echo $data['user_created_at']; ?> </p>
                    <p> <?php echo $data['user_deleted_at'] ? 'Banlı (' . $data['user_deleted_at'] . ")" : 'Değil'; ?> </p>
                    <form action="banUser.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>" />
                        <button type="submit">X</button>
                    </form>
                </div>
            <?php endforeach ?>
        </div>

        
    </body>

    </html>

<?php } ?>
