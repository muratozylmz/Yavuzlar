<?php
include "functions/functions.php";
include "functions/database.php";
session_start();
if(isset($_GET["id"])){
    $id=$_GET["id"];
    DeleteQuestions($id);
    header("Location: admin.php");
}
else{
    header("Location:admin.php?message=silme işlemi sırasında hata oluştu");
}
?> 
