<?php

    function Login($username,$password){

        include "database.php";

        $query = "SELECT *,COUNT(*) as count FROM users WHERE username = :username AND password = :password";
    
        $statement = $pdo->prepare($query);

        $statement->execute(['username' => $username, 'password' => $password]);

        $result = $statement->fetch();

        $pdo = null;

        return $result;
    }

    function getid(){

        global $pdo;
        
        $query = "SELECT id FROM questions";

        $statement = $pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }
    function getall(){

        global $pdo;

        $query = "SELECT * FROM questions";

        $statement = $pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function GetUsers(){
        include "database.php";

        $query = "SELECT * FROM users";

        $statement = $pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }
    function getAQuestions($id){

        global $pdo;
        include "functions/database.php";
        $query = "SELECT soru, cevap1, cevap2, cevap3, cevap4, dogruCevap FROM questions WHERE id = :id";

        $statement = $pdo->prepare($query);

        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    function DeleteQuestions($id){
        include "functions/database.php";

        $query = "DELETE FROM questions WHERE id = $id";

        $statement = $pdo->prepare($query);

        $statement->execute();

        $pdo = null;
    }

    function addQuestions($soru,$cevap1,$cevap2,$cevap3,$cevap4,$dogruCevap,$zorluk){
        include 'database.php';
        
        $query = "INSERT INTO questions (soru, cevap1, cevap2, cevap3, cevap4, dogruCevap, zorluk) VALUES ('$soru', '$cevap1', '$cevap2', '$cevap3', '$cevap4', '$dogruCevap', '$zorluk')";

        $statement = $pdo->prepare($query);

        $statement->execute();
        
        }

   
    function soruGuncelle($id,$soru,$cevap1,$cevap2,$cevap3,$cevap4,$dogruCevap,$zorluk,$degisecekSoru){

        include "functions/database.php";

        $query = "UPDATE questions SET soru = '$soru', cevap1 = '$cevap1', cevap2 = '$cevap2', cevap3 = '$cevap3', cevap4 = '$cevap4', dogruCevap = '$dogruCevap', zorluk = '$zorluk' WHERE id = '$degisecekSoru'";

        $statement = $pdo->prepare($query);

        $statement->execute();

        $pdo = null;
    }


    function duzenlenecekSoru($id){
        include "functions/database.php";

        $query = "SELECT * FROM questions WHERE id = :id";

        $statement = $pdo->prepare($query);

        $statement ->execute(['id' => $id]);

        $result = $statement->fetch();
        $pdo = null;
        
        return $result;
    }

    function puanEkle($puan){

        include "functions/database.php";

        $query = "INSERT INTO submissions (id, username, puan) VALUES (NULL, '$_SESSION[username]', '$puan')";

        $statement = $pdo->prepare($query);

        $statement->execute();
        $pdo = null;

    }


function scoreBoard(){
    include "functions/database.php";

    $query = 'SELECT u.id, u.username, SUM(sl.puan) AS toplam FROM users u JOIN submissions sl ON u.username = sl.username   GROUP BY u.id, u.username ORDER BY toplam DESC LIMIT 10;';

    $statement = $pdo->prepare($query);

    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}





?>