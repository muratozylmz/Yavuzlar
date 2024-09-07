<?php
include "functions/functions.php";
if(isset($_POST["id"])){
    $id=$_POST["id"];
    DeleteQuestions($id);
    header("Location: admin.php");
}
else{
    header("Location:admin.php?message=silme işlemi sırasında hata oluştu");
}
?>