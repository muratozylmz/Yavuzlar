<?php
include "db.php";
function FindUser($username)
{
    
    global $pdo;
    $query = "SELECT * FROM users WHERE username=:username";
    $statement = $pdo->prepare($query);
    $statement->execute(['username' => $username]);
    return $statement->fetch(PDO::FETCH_ASSOC);
  
}

function Login($username, $password)
{   
    global $pdo;
    $user = FindUser($username);
    if ($user && $password && empty($user['deleted_at'])) {
        session_regenerate_id();
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['surname'] = $user['surname'];
        $_SESSION['balance'] = $user['balance'];
        $_SESSION['created_at'] = $user['created_at'];
        if (!empty($user['company_id'])) {
            $_SESSION['company_id'] = $user['company_id'];
        }
        return true;
    }
    return false;
}

function Register($name, $surname, $username, $password)
{
    global $pdo;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlclean($name);
        $surname = htmlclean($surname);
        $username = htmlclean($username);
        $password = htmlclean($password);
        //$encrypted_password = password_hash($password, PASSWORD_ARGON2ID);
        $created_at = (new DateTime())->format('Y-m-d H:i:s');
        $query = "INSERT INTO users(name, surname, username, password, created_at) VALUES(:name, :surname, :username, :password, :created_at)";
        $statement = $pdo->prepare($query);
        $statement->execute(['name' => $name, 'surname' => $surname, 'username' => $username, 'password' => $password, 'created_at' => $created_at]);
    }
}

function IsUserLoggedIn()
{
    return isset($_SESSION['username']);
}

    function GetUsers(){
        include "db.php";

        $query = "SELECT * FROM users";

        $statement = $pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }

    function GetBlogs(){
        include "db.php";

        $query = "SELECT * FROM bilgiler";

        $statement = $pdo->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }

    function AddUser($name,$username,$email,$phone,$password,$unit,$isAdmin,$imageName){

        include "db.php";

        $query = "INSERT INTO users(name,username,email,password,profilePhoto,isAdmin,groupName,phoneNumber) VALUES('$name','$username','$email','$password','$imageName','$isAdmin','$unit','$phone')";

        $statement = $pdo->prepare($query);

        $statement->execute();
    }

    function AddPost($title,$info,$imageName){

        include "db.php";

        $query = "INSERT INTO bilgiler(title, info, imageName) VALUES(:title, :info, :imageName)";

        $statement = $pdo->prepare($query);

        $statement->execute(['title' => $title, 'info' => $info, 'imageName' => $imageName]);
    }

    function DeleteUser($id){
        include "db.php";

        $query = "DELETE FROM users WHERE id = $id";

        $statement = $pdo->prepare($query);

        $statement->execute();
    }

    function htmlclean($text){
        $text = preg_replace("'<script[^>]*>.*?</script>'si", '', $text );
        $text = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
        $text = preg_replace('/<!--.+?-->/', '', $text ); 
        $text = preg_replace('/{.+?}/', '', $text ); 
        $text = preg_replace('/&nbsp;/', ' ', $text );
        $text = preg_replace('/&amp;/', ' ', $text ); 
        $text = preg_replace('/&quot;/', ' ', $text );
        $text = strip_tags($text);
        $text = htmlspecialchars($text); 

        return $text;
    }

    function secureFileUpload($file, $target_dir, $allowed_types = ['jpg', 'jpeg', 'png', 'gif']){
        if($file['error'] !== UPLOAD_ERR_OK){
            return ['status' => false, 'message' => "File Upload Error!"];
        }

        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(!in_array($file_ext, $allowed_types)){
            return ['status' => false, 'message' => "Invalid file type!"];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed_types = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif'
        ];

        if(!array_key_exists($mime, $allowed_types)){
            return ['status' => false, 'message' => "Invalid MIME Type!"];
        }

        $magic_bytes = [
            'jpg' => "\xFF\xD8\xFF",
            'jpeg' => "\xFF\xD8\xFF",
            'png' => "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
            'gif' => "GIF"
        ];

        $fh = fopen($file['tmp_name'], 'rb');
        $bytes=fread($fh,8);
        fclose($fh);

        if(strpos($bytes, $magic_bytes[$file_ext]) !== 0){
            return ['status' => false, 'message' => "File failed magic byte check!"];
        }

        $random_number = rand(1,1000);
        $new_filename = $random_number . '_' . basename($file['name']);
        $target_file = $target_dir . $new_filename;

        if(!move_uploaded_file($file['tmp_name'], $target_file)){
            return ['status' => false, 'message' => "Error moving the uploaded file!"];
        }

        return ['status' => true, 'filename' => $new_filename];
        

        
    }

    function AddBalance($amount){
        global $pdo;
        if($_SERVER['REQUEST_METHOD']=="POST"){
            include "db.php";
            $id = $_SESSION['user_id'];
            $amount = htmlclean($amount);
            $query = "UPDATE users SET balance = balance+ :amount WHERE id = :id";
            $statement = $pdo->prepare($query);
            $statement->execute(['amount'=>$amount, 'id'=>$id]);
            $_SESSION['balance'] += $amount;
        }
    }


    
    
    function UpdateProfile($name, $surname, $username)
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $id = $_SESSION['user_id'];
            $name = htmlclean($_POST['name']);
            $surname = htmlclean($_POST['surname']);
            $username = htmlclean($_POST['username']);
            $query = "UPDATE users SET name = :name, surname = :surname, username = :username WHERE id = :id";
            $statement = $pdo->prepare($query);
            $statement->execute(['name' => $name, 'surname' => $surname, 'username' => $username, 'id' => $id]);
            $_SESSION['name'] = $name;
            $_SESSION['surname'] = $surname;
            $_SESSION['username'] = $username;
        }
    }
    
    
    // function UpdatePassword($current_password, $new_password)
    // {
    //     global $pdo;
    //     if ($_SERVER['REQUEST_METHOD'] == "POST") {
           
    //         $user = FindUser($_SESSION['username']);
    //         if ($current_password == $user['password']) {
    //             $id = $_SESSION['user_id'];
    //             $password = $new_password);
    //             $query = "UPDATE users SET password = password WHERE id = :id";
    //             $statement = $pdo->prepare($query);
    //             $statement->execute(['password' => $password, 'id' => $id]);
    //         } else {
    //             header("Location: profile.php?message=Lütfen mevcut şifrenizi doğru giriniz.");
    //             exit();
    //         }
    //     }
    // }
    
    function GetFoods()
    {
        global $pdo;
        $query = "SELECT * FROM food";
        $statement = $pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function AddBasket($user_id, $food_id)
    {
        global $pdo;
        $user_id = htmlclean($user_id);
        $food_id = htmlclean($food_id);
        $created_at = (new DateTime())->format('Y-m-d H:i:s');
        $result = CheckQuantity($user_id, $food_id);
        if ($result) {
            $query = "UPDATE basket SET quantity = quantity+1 WHERE user_id = :user_id AND food_id = :food_id";
            $statement = $pdo->prepare($query);
            $statement->execute(["user_id" => $user_id, "food_id" => $food_id]);
        } else {
            $query = "INSERT INTO basket(user_id, food_id, note, quantity, created_at) VALUES(:user_id, :food_id, :note, :quantity, :created_at)";
            $statement = $pdo->prepare($query);
            $statement->execute(["user_id" => $user_id, "food_id" => $food_id, "note" => "test", "quantity" => 1, "created_at" => $created_at]);
        }
    }
    
    function CheckQuantity($user_id, $food_id)
    {
        global $pdo;
        $query = "SELECT quantity FROM basket WHERE user_id = :user_id AND food_id = :food_id";
        $statement = $pdo->prepare($query);
        $statement->execute(["user_id" => $user_id, "food_id" => $food_id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    function GetBasket($user_id)
    {
        global $pdo;
        $user_id = htmlclean($user_id);
        $query = "SELECT
        food.id AS food_id,
        food.restaurant_id AS food_restaurant_id,
        food.name AS food_name,
        food.description AS food_description,
        food.image_path AS food_image_path,
        food.price AS food_price,
        food.discount AS food_discount,
        basket.id AS basket_id,
        basket.user_id AS basket_user_id,
        basket.food_id AS basket_food_id,
        basket.note AS basket_note,
        basket.quantity AS basket_quantity FROM food INNER JOIN basket ON food.id = basket.food_id WHERE user_id = :user_id";
        $statement = $pdo->prepare($query);
        $statement->execute(["user_id" => $user_id]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function DeleteBasket($basket_id)
    {
        global $pdo;
        $basket_id = htmlclean($basket_id);
        $query = "DELETE FROM basket WHERE id = :basket_id";
        $statement = $pdo->prepare($query);
        $statement->execute(["basket_id" => $basket_id]);
    }
    
    //-----------------------------------FİRMA-------------------------

    function GetRestaurantByCId($company_id)
{
    global $pdo;
    $query = "SELECT * FROM restaurant WHERE company_id = :company_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["company_id" => $company_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function AddRestaurant($company_id, $name, $description, $image_path)
{
    global $pdo;
    $company_id = htmlclean($company_id);
    $name = htmlclean($name);
    $description = htmlclean($description);
    $image_path = htmlclean($image_path);
    $created_at = (new DateTime())->format('Y-m-d H:i:s');

    $query = "INSERT INTO restaurant(company_id, name, description, image_path, created_at) VALUES(:company_id, :name, :description, :image_path, :created_at)";
    $statement = $pdo->prepare($query);
    $statement->execute(["company_id"=>$company_id, "name" => $name, "description" => $description, "image_path" => $image_path, "created_at"=>$created_at]);
}

function AddFood($restaurant_id, $name, $description, $image_path, $price, $discount){
    global $pdo;
    $restaurant_id = htmlclean($restaurant_id);
    $name = htmlclean($name);
    $description = htmlclean($description);
    $image_path = htmlclean($image_path);
    $price = htmlclean($price);
    $discount = htmlclean($discount);
    $created_at = (new DateTime())->format('Y-m-d H:i:s');

    $query = "INSERT INTO food(restaurant_id, name, description, image_path, price, discount, created_at) VALUES(:restaurant_id, :name, :description, :image_path, :price, :discount, :created_at)";
    $statement = $pdo->prepare($query);
    $statement->execute(["restaurant_id"=>$restaurant_id, "name"=>$name, "description"=>$description, "image_path"=>$image_path, "price"=>$price, "discount"=>$discount, "created_at"=>$created_at]);
}

function DeleteFood($food_id){
    global $pdo;
    $food_id = htmlclean($food_id);
    $deleted_at = (new DateTime())->format('Y-m-d H:i:s');
    $query="UPDATE food SET deleted_at = :deleted_at WHERE id = :food_id";
    $statement=$pdo->prepare($query);
    $statement->execute(["deleted_at"=>$deleted_at, "food_id"=>$food_id]);
}

function GetFoodById($food_id){
    global $pdo;
    $query = "SELECT * FROM food WHERE id = :food_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["food_id"=>$food_id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function UpdateFood($food_id, $restaurant_id, $name, $description, $image_path, $price, $discount){
    global $pdo;
    $food_id = htmlclean($food_id);
    $restaurant_id = htmlclean($restaurant_id);
    $name = htmlclean($name);
    $description = htmlclean($description);
    $image_path = htmlclean($image_path);
    $price = htmlclean($price);
    $discount = htmlclean($discount);
    $created_at = (new DateTime())->format('Y-m-d H:i:s');

    $query = "UPDATE food SET restaurant_id = :restaurant_id, name = :name, description = :description, image_path = :image_path, price = :price, discount = :discount, created_at = :created_at WHERE id = :food_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["restaurant_id"=>$restaurant_id, "name"=>$name, "description"=>$description, "image_path"=>$image_path, "price"=>$price, "discount"=>$discount, "created_at"=>$created_at, "food_id" => $food_id]);
}

function GetRestaurantById($restaurant_id){
    global $pdo;
    $restaurant_id = htmlclean($restaurant_id);
    $query = "SELECT * FROM restaurant WHERE id = :restaurant_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["restaurant_id"=>$restaurant_id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function UpdateRestaurant($restaurant_id, $name, $description, $image_path){
    global $pdo;
    $restaurant_id = htmlclean($restaurant_id);
    $name = htmlclean($name);
    $description = htmlclean($description);
    $image_path = htmlclean($image_path);

    $query = "UPDATE restaurant SET name = :name, description = :description, image_path = :image_path WHERE id = :restaurant_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["name"=>$name, "description"=>$description, "image_path"=>$image_path, "restaurant_id"=>$restaurant_id]);
}

function DeleteRestaurant($restaurant_id){
    global $pdo;
    $restaurant_id = htmlclean($restaurant_id);
    $query="DELETE FROM restaurant WHERE id = :restaurant_id";
    $statement=$pdo->prepare($query);
    $statement->execute(["restaurant_id"=>$restaurant_id]);
}

function ReaddFood($food_id){
    global $pdo;
    $food_id = htmlclean($food_id);
    $query="UPDATE food SET deleted_at = NULL WHERE id = :food_id";
    $statement=$pdo->prepare($query);
    $statement->execute(["food_id"=>$food_id]);
}

// -----------ADMİN------------------

// function GetUsers()
// {
//     global $pdo;
//     $query = "SELECT
//         users.id AS user_id,
//         users.company_id AS user_company_id,
//         users.name AS user_name,
//         users.surname AS user_surname,
//         users.username AS user_username,
//         users.balance AS user_balance,
//         users.created_at AS user_created_at,
//         users.deleted_at AS user_deleted_at,
//         company.name AS company_name,
//         `order`.id AS order_id,
//         `order`.total_price AS order_total_price,
//         `order`.order_status AS order_status,
//         `order`.created_at AS order_created_at FROM users LEFT JOIN company ON users.company_id = company_id LEFT JOIN `order` ON users.id = `order`.user_id";
//     $statement = $pdo->prepare($query);
//     $statement->execute();
//     $result = $statement->fetchAll();
//     return $result;
// }

function BanUser($user_id)
{
    global $pdo;
    $user_id = htmlclean($user_id);
    $deleted_at = (new DateTime())->format('Y-m-d H:i:s');
    $query = "UPDATE users SET deleted_at = :deleted_at WHERE id = :user_id";
    $statement = $pdo->prepare($query);
    $statement->execute(['deleted_at' => $deleted_at, 'user_id' => $user_id]);
}

function GetCompanies()
{
    global $pdo;
    $query = "SELECT * FROM company";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    return $result;
}

function AddCompany($name, $description, $logo_path)
{
    global $pdo;
    $name = htmlclean($name);
    $description = htmlclean($description);
    $logo_path = htmlclean($logo_path);
    $query = "INSERT INTO company(name, description, logo_path) VALUES(:name, :description, :logo_path)";
    $statement = $pdo->prepare($query);
    $statement->execute(["name" => $name, "description" => $description, "logo_path" => $logo_path]);
}

function BanCompany($company_id)
{
    global $pdo;
    $company_id = htmlclean($company_id);
    $deleted_at = (new DateTime())->format('Y-m-d H:i:s');
    $query = "UPDATE company SET deleted_at = :deleted_at WHERE id = :company_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["deleted_at" => $deleted_at, "company_id" => $company_id]);
}

function GetCompanyFoods($company_id)
{
    global $pdo;
    $company_id = htmlclean($company_id);
    $query = "SELECT
    restaurant.id AS restaurant_id,
    restaurant.company_id AS restaurant_company_id,
    restaurant.name AS restaurant_name,
    restaurant.description AS restaurant_description,
    restaurant.image_path AS restaurant_image_path,
    restaurant.created_at AS restaurant_created_at,
    food.id AS food_id,
    food.name AS food_name,
    food.description AS food_description,
    food.image_path AS food_image_path,
    food.price AS food_price,
    food.discount AS food_discount,
    food.deleted_at AS food_deleted_at,
    food.created_at AS food_created_at FROM restaurant LEFT JOIN food ON restaurant.id = food.restaurant_id WHERE company_id = :company_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["company_id" => $company_id]);
    $result = $statement->fetchAll();
    return $result;
}

function GetCupons()
{
    global $pdo;
    $query = "SELECT * FROM cupon";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    return $result;
}

function DeleteCupon($cupon_id)
{
    global $pdo;
    $cupon_id = htmlclean($cupon_id);
    $query = "DELETE FROM cupon WHERE id = :cupon_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["cupon_id" => $cupon_id]);
}

function GetRestaurants()
{
    global $pdo;
    $query = "SELECT * FROM restaurant";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    return $result;
}

function AddCupon($restaurant_id, $name, $discount)
{
    global $pdo;
    $name = htmlclean($name);
    $discount = htmlclean($discount);
    $created_at = (new DateTime())->format('Y-m-d H:i:s');
    $query = "INSERT INTO cupon(restaurant_id, name, discount, created_at) VALUES(:restaurant_id, :name, :discount, :created_at)";
    $statement = $pdo->prepare($query);
    $statement->execute(["restaurant_id" => $restaurant_id, "name" => $name, "discount" => $discount, "created_at" => $created_at]);
}

function GetCompanyById($company_id)
{
    global $pdo;
    $company_id = htmlclean($company_id);
    $query = "SELECT * FROM company WHERE id = :company_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["company_id" => $company_id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function UpdateCompany($company_id, $name, $description, $logo_path)
{
    global $pdo;
    $company_id = htmlclean($company_id);
    $name = htmlclean($name);
    $description = htmlclean($description);
    $logo_path = htmlclean($logo_path);
    $query = "UPDATE company SET name = :name, description = :description, logo_path = :logo_path WHERE id = :company_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["name" => $name, "description" => $description, "logo_path" => $logo_path, "company_id" => $company_id]);
}

function MakeEmployee($user_id, $company_id)
{
    global $pdo;
    $user_id = htmlclean($user_id);
    $company_id = htmlclean($company_id);
    $query = "UPDATE users SET company_id = :company_id, role = 1 WHERE id = :user_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["company_id" => $company_id, "user_id" => $user_id]);
}

function GetCuponById($cupon_id)
{
    global $pdo;
    $cupon_id = htmlclean($cupon_id);
    $query = "SELECT * FROM cupon WHERE id = :cupon_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["cupon_id" => $cupon_id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function UpdateCupon($cupon_id, $restaurant_id, $name, $discount)
{
    global $pdo;
    $cupon_id = htmlclean($cupon_id);
    $restaurant_id = htmlclean($restaurant_id);
    $name = htmlclean($name);
    $discount = htmlclean($discount);
    $query = "UPDATE cupon SET restaurant_id = :restaurant_id, name = :name, discount = :discount WHERE id = :cupon_id ";
    $statement = $pdo->prepare($query);
    $statement->execute(["restaurant_id" => $restaurant_id, "name" => $name, "discount" => $discount, "cupon_id" => $cupon_id]);
}

?>