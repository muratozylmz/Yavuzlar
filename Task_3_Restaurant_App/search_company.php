<?php
session_start();
include "functions/functions.php";

if (!IsUserLoggedIn()) {
    header("Location: login.php?message=Lütfen giriş yapınız.");
    exit();
}

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    $companies = GetCompaniesBySearch($query);
    echo json_encode($companies);
}

function GetCompaniesBySearch($query) {
    global $pdo;
    $query = htmlclean($query); // XSS ve SQL Injection koruması
    $stmt = $pdo->prepare("SELECT name, description FROM company WHERE name LIKE :name");
    $stmt->execute(['name' => '%' . $query . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
