<?php
session_start();
include "functions/functions.php";

if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
} else if ($_SESSION['role'] != 0) {
    header("Location: index.php?message=403 Yetkisiz Giriş");
}

$companies = GetAllCompanies();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şirketler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .centerDiv {
            text-align: center;
        }

        #searchbox {
            width: 80%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        #results {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 300px;
            overflow-y: auto;
            position: relative;
            z-index: 1000;
        }

        .result-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            transition: background 0.3s;
        }

        .result-item:hover {
            background: #f4f4f4;
        }

        .company-card {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            text-align: left;
            flex: 1;
        }

        .company-card:hover {
            transform: translateY(-2px);
        }

        .company-card h2 {
            font-size: 20px;
            margin: 0 0 10px;
        }

        .company-card p {
            font-size: 14px;
            margin: 0 0 10px;
        }

        .company-logo {
            width: 80px;
            height: auto;
            margin-right: 15px;
        }

        .delete-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 15px;
        }

        .delete-button:hover {
            background-color: #ff1a1a;
        }

        .edit-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 15px;
        }

        .edit-button:hover {
            background-color: #45a049;
        }

        .flex-container {
            display: flex;
            align-items: center;
        }

        /* Anasayfa butonu için stil */
        .home-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .home-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>

<body>
    <div class="centerDiv">
        <h1>Şirketler</h1>
        <button onclick="window.location.href='addFirma.php'" 
            style="float: right; margin-bottom: 20px; 
                   padding: 10px 20px; 
                   background-color: #4CAF50; 
                   color: white; 
                   border: none; 
                   border-radius: 5px; 
                   font-size: 16px; 
                   cursor: pointer; 
                   transition: background-color 0.3s;">
            Şirket Ekle
        </button>

        <button class="home-button" onclick="window.location.href='index.php'">Anasayfa</button>

        <input type="text" id="searchbox" placeholder="Şirket Ara" onkeyup="searchCompanies()" />
        <div id="results"></div>
        <div id="allCompanies" class="container">
            <?php if (empty($companies)) {
                echo "<p class='centerDiv'>Herhangi bir şirket bulunamadı.</p>";
            } else {
                foreach ($companies as $company) {
                    echo "<div class='result-item flex-container'>";
                    echo "<img src='" . $company['logo_path'] . "' alt='Firma Logosu' class='company-logo'>";
                    echo "<div class='company-card'>";
                    echo "<h2>" . htmlspecialchars($company['name']) . "</h2>";
                    echo "<p><strong>Açıklama:</strong> " . htmlspecialchars($company['description']) . "</p>";
                    echo "</div>";
                    echo "<button class='edit-button' onclick='editCompany(" . $company['id'] . ")'>Düzenle</button>";
                    echo "<button class='delete-button' onclick='deleteCompany(" . $company['id'] . ")'>Sil</button>";
                    echo "</div>";
                }
            } ?>
        </div>
    </div>

    <script>
        const companies = <?php echo json_encode($companies); ?>;

        function searchCompanies() {
            const input = document.getElementById("searchbox").value.toLowerCase();
            const resultsDiv = document.getElementById("results");
            resultsDiv.innerHTML = "";

            if (input === "") {
                resultsDiv.style.display = "none";
                return;
            }

            let found = false;
            companies.forEach(company => {
                const name = company.name.toLowerCase();
                if (name.startsWith(input)) {
                    found = true;
                    const resultDiv = document.createElement("div");
                    resultDiv.className = "result-item flex-container";
                    resultDiv.innerHTML = `
                        <img src="${company.logo_path}" alt="Firma Logosu" class="company-logo">
                        <div class="company-card">
                            <h2>${company.name}</h2>
                            <p><strong>Açıklama:</strong> ${company.description}</p>
                        </div>
                        <button class="edit-button" onclick="editCompany(${company.id})">Düzenle</button>
                        <button class="delete-button" onclick="deleteCompany(${company.id})">Sil</button>
                    `;
                    resultsDiv.appendChild(resultDiv);
                }
            });

            resultsDiv.style.display = found ? "block" : "none";
            if (!found) {
                resultsDiv.innerHTML = "<div class='no-results'>Hiçbir sonuç bulunamadı.</div>";
            }
        }

        function deleteCompany(companyId) {
            if (confirm("Bu şirketi silmek istediğinize emin misiniz?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "deleteFirma.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert("Şirket silindi.");
                        window.location.reload(); // Sayfayı yenile
                    } else {
                        alert("Bir hata oluştu: " + xhr.responseText);
                    }
                };
                xhr.send("id=" + companyId);
            }
        }

        function editCompany(companyId) {
            window.location.href = "designFirma.php?id=" + companyId;
        }
    </script>
</body>

</html>
