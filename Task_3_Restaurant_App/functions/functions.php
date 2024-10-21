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
        
        if (password_verify($password, $user['password'])) {
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

        $encrypted_password = password_hash($password, PASSWORD_ARGON2ID);
        $created_at = (new DateTime())->format('Y-m-d H:i:s');
        
        $query = "INSERT INTO users(name, surname, username, password, created_at) VALUES(:name, :surname, :username, :password, :created_at)";

        $statement = $pdo->prepare($query);
        $statement->execute([
            'name' => $name,
            'surname' => $surname,
            'username' => $username,
            'password' => $encrypted_password, 
            'created_at' => $created_at
        ]);
    }
}
function GetBasketDatas($user_id)
{
    global $pdo;
    $user_id = htmlclean($user_id);
    $query = "SELECT
    food.id AS food_id,
    food.restaurant_id AS food_restaurant_id,
    food.price AS food_price,
    food.discount AS food_discount,
    basket.id AS basket_id,
    basket.user_id AS basket_user_id,
    basket.food_id AS basket_food_id,
    basket.note AS basket_note,
    basket.quantity AS basket_quantity,
    basket.created_at AS basket_created_at FROM food INNER JOIN basket ON food.id = basket.food_id WHERE basket.user_id = :user_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["user_id" => $user_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function CheckQuantityByID($basket_id)
{
    global $pdo;
    $basket_id = htmlclean($basket_id);
    $query = "SELECT quantity FROM basket WHERE id = :basket_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["basket_id" => $basket_id]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function EditQuantity($basket_id, $value)
{
    global $pdo;
    $basket_id = htmlclean($basket_id);
    $value = htmlclean($value);
    $query = "UPDATE basket SET quantity = quantity +:value WHERE id = :basket_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["value" => $value, "basket_id" => $basket_id]);
    $food_quantity = CheckQuantityByID($basket_id);
    if ($food_quantity['quantity'] <= 0) {
        DeleteFromBasket($basket_id);
    }
}

function EditNote($basket_id, $note)
{
    global $pdo;
    $basket_id = htmlclean($basket_id);
    $note = htmlclean($note);
    $query = "UPDATE basket SET note = :note WHERE id = :basket_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["note" => $note, "basket_id" => $basket_id]);
}

function ConfirmBasket($user_id, $cupon = null)
{
    global $pdo;
    $user_id = htmlclean($user_id);
    $datas = GetBasketDatas($user_id);
    $created_at = (new DateTime())->format('Y-m-d H:i:s');
    $total_price = 0;
    $applied_discount = 0;
    foreach ($datas as $data) {
        $data['food_price'] = is_null(value: $data['food_discount']) ?: $data['food_price'] * (100 - $data['food_discount']) / 100;
        if ($cupon) {
            if ($cupon['restaurant_id'] === null || $cupon['restaurant_id'] == $data['food_restaurant_id']) {
                $discount_amount = $data['food_price'] * ($cupon['discount'] / 100);
                $data['food_price'] -= $discount_amount;
                $applied_discount += $discount_amount * $data['basket_quantity'];
            }
        }
        $total_price += $data['food_price'] * $data['basket_quantity'];
    }
    $query = "INSERT INTO `order` (user_id, total_price, created_at) VALUES (:user_id, :total_price, :created_at)";
    $statement = $pdo->prepare($query);
    $statement->execute(["user_id" => $user_id, "total_price" => $total_price, "created_at" => $created_at]);
    $order_id = $pdo->lastInsertId();

    foreach ($datas as $data) {
        $data['food_price'] = is_null(value: $data['food_discount']) ?: $data['food_price'] * (100 - $data['food_discount']) / 100;
        if ($cupon) {
            if ($cupon['restaurant_id'] === null || $cupon['restaurant_id'] == $data['food_restaurant_id']) {
                $discount_amount = $data['food_price'] * ($cupon['discount'] / 100);
                $data['food_price'] -= $discount_amount;
            }
        }
        DeleteFromBasket($data['basket_id']);
        AddOrderItems($data['food_id'], $order_id, $data['basket_quantity'], $data['food_price']);
    }
    if ($cupon) {
        DeleteCuponByName($cupon['name']);
    }
    UpdateBalance($user_id, $total_price);
}

function UpdateBalance($user_id, $total_price)
{
    global $pdo;
    $user_id = htmlclean($user_id);
    $total_price = htmlclean($total_price);
    $query = "UPDATE users SET balance = balance + :total_price WHERE id= :user_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["total_price" => -$total_price, "user_id" => $user_id]);
    $_SESSION['balance'] -= $total_price;
}
function DeleteCuponByName($cupon_name)
{
    global $pdo;
    $cupon_name = htmlclean($cupon_name);
    $query = "DELETE FROM cupon WHERE name = :cupon_name";
    $statement = $pdo->prepare($query);
    $statement->execute(["cupon_name" => $cupon_name]);
}

function AddOrderItems($food_id, $order_id, $quantity, $price)
{
    global $pdo;
    $food_id = htmlclean($food_id);
    $order_id = htmlclean($order_id);
    $quantity = htmlclean($quantity);
    $price = htmlclean($price);
    $query = "INSERT INTO order_items(food_id, order_id, quantity, price) VALUES (:food_id, :order_id, :quantity, :price)";
    $statement = $pdo->prepare($query);
    $statement->execute(["food_id" => $food_id, "order_id" => $order_id, "quantity" => $quantity, "price" => $price]);
}
function GetUserOrders($user_id)
{
    global $pdo;
    $user_id = htmlclean($user_id);
    $query = "SELECT
            restaurant.id AS restaurant_id,
            restaurant.company_id AS restaurant_company_id,
            food.id AS food_id,
            food.restaurant_id AS food_restaurant_id,
            food.name AS food_name,
            food.description AS food_description,
            food.image_path AS food_image_path,
            food.price AS food_price,
            food.discount AS food_discount,
            food.created_at AS food_created_at,
            food.deleted_at AS food_deleted_at,
            order_items.id AS order_items_id,
            order_items.food_id AS order_items_food_id,
            order_items.order_id AS order_items_order_id,
            order_items.quantity AS order_items_quantity,
            order_items.price AS order_items_price,
            `order`.id AS order_id,
            `order`.user_id AS order_user_id,
            `order`.order_status AS order_order_status,
            users.id AS users_id,
            users.username AS users_username 
            FROM restaurant 
        INNER JOIN food ON restaurant.id = food.restaurant_id
        INNER JOIN order_items ON food.id = order_items.food_id 
        INNER JOIN `order` ON order_items.order_id = `order`.id 
        INNER JOIN users ON `order`.user_id = users.id
        WHERE users.id = :user_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["user_id" => $user_id]);
    return $statement->fetchAll();
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
    
    function UpdatePassword($current_password, $new_password)
    {
        global $pdo;
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
          
            $user = FindUser($_SESSION['username']);
    
          
            if (password_verify($current_password, $user['password'])) {
                $id = $_SESSION['user_id'];
    
             
                $hashed_new_password = password_hash($new_password, PASSWORD_ARGON2ID);
    
             
                $query = "UPDATE users SET password = :password WHERE id = :id";
                $statement = $pdo->prepare($query);
                $statement->execute(['password' => $hashed_new_password, 'id' => $id]);
    
                header("Location: profile.php?message=Şifre başarıyla güncellendi.");
                exit();
            } else {
             
                header("Location: profile.php?message=Lütfen mevcut şifrenizi doğru giriniz.");
                exit();
            }
        }
    }
    
    
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
        basket.quantity AS quantity FROM food INNER JOIN basket ON food.id = basket.food_id WHERE user_id = :user_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["user_id" => $user_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

    
function DecreaseBasketQuantity($basket_id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT quantity FROM basket WHERE id = ?");
    $stmt->execute([$basket_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $new_quantity = $item['quantity'] - 1;

        if ($new_quantity > 0) {
            
            $stmt = $pdo->prepare("UPDATE basket SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_quantity, $basket_id]);
        } else {
            DeleteFromBasket($basket_id);
        }
    }
}

function DeleteFromBasket($basket_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM basket WHERE id = ?");
    $stmt->execute([$basket_id]);
}

    
    //-----------------------------------FİRMA-------------------------

    function GetRestaurantByCId($company_id){
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

function GetUser()
 {
     global $pdo;
    $query = "SELECT
         users.id AS user_id,
         users.company_id AS user_company_id,
         users.name AS user_name,
         users.surname AS user_surname,
         users.username AS user_username,
         users.balance AS user_balance,
         users.created_at AS user_created_at,
         users.deleted_at AS user_deleted_at,
         company.name AS company_name,
         `order`.id AS order_id,
         `order`.total_price AS order_total_price,
         `order`.order_status AS order_status,
         `order`.created_at AS order_created_at FROM users LEFT JOIN company ON users.company_id = company_id LEFT JOIN `order` ON users.id = `order`.user_id";
     $statement = $pdo->prepare($query);
     $statement->execute();
     $result = $statement->fetchAll();
     return $result;
 }

function GetCustomerOrders()
{
    global $pdo;
    $company_id = htmlclean($_SESSION['company_id']);
    $query = "SELECT
            restaurant.id AS restaurant_id,
            restaurant.company_id AS restaurant_company_id,
            food.id AS food_id,
            food.restaurant_id AS food_restaurant_id,
            food.name AS food_name,
            food.description AS food_description,
            food.image_path AS food_image_path,
            food.price AS food_price,
            food.discount AS food_discount,
            food.created_at AS food_created_at,
            food.deleted_at AS food_deleted_at,
            order_items.id AS order_items_id,
            order_items.food_id AS order_items_food_id,
            order_items.order_id AS order_items_order_id,
            order_items.quantity AS order_items_quantity,
            order_items.price AS order_items_price,
            `order`.id AS order_id,
            `order`.user_id AS order_user_id,
            `order`.order_status AS order_order_status,
            users.id AS users_id,
            users.username AS users_username 
            FROM restaurant 
        INNER JOIN food ON restaurant.id = food.restaurant_id
        INNER JOIN order_items ON food.id = order_items.food_id 
        INNER JOIN `order` ON order_items.order_id = `order`.id 
        INNER JOIN users ON `order`.user_id = users.id
        WHERE restaurant.company_id = :company_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["company_id" => $company_id]);
    return $statement->fetchAll();
}
function GetAvgScore($restaurant_id)
{
    global $pdo;
    $restaurant_id = htmlclean($restaurant_id);
    $query = "SELECT AVG(score) FROM comments WHERE restaurant_id = :restaurant_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["restaurant_id" => $restaurant_id]);
    return $statement->fetchColumn();
}

function GetComments($restaurant_id)
{
    global $pdo;
    $restaurant_id = htmlclean($restaurant_id);
    $query = "SELECT
    comments.id AS comments_id,
    comments.user_id AS comments_user_id,
    comments.restaurant_id AS comments_restaurant_id,
    comments.username AS comments_username,
    comments.title AS comments_title,
    comments.description AS comments_description,
    comments.score AS comments_score,
    comments.created_at AS comments_created_at,
    comments.updated_at AS comments_updated_at,
    restaurant.name AS restaurant_name,
    restaurant.description AS restaurant_description,
    restaurant.image_path AS restaurant_image_path,
    restaurant.created_at AS restaurant_created_at FROM comments INNER JOIN restaurant ON comments.restaurant_id = restaurant.id WHERE comments.restaurant_id = :restaurant_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["restaurant_id" => $restaurant_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function DeleteComment($comment_id)
{
    global $pdo;
    $comment_id = htmlclean($comment_id);
    $updated_at = (new DateTime())->format('Y-m-d H:i:s');

    $query = "UPDATE comments SET title=:title, description=:description, score=:score, updated_at =:updated_at WHERE id = :comment_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["title" => "[deleted]", "description" => "[deleted]", "score" => null, "updated_at" => $updated_at, "comment_id" => $comment_id]);
}



function AddComment($restaurant_id, $title, $description, $score)
{
    global $pdo;
    $user_id = htmlclean($_SESSION['user_id']);
    $username = htmlclean($_SESSION['username']);
    $restaurant_id = htmlclean($restaurant_id);
    $title = htmlclean($title);
    $description = htmlclean($description);
    $score = htmlclean($score);
    $created_at = (new DateTime())->format('Y-m-d H:i:s');

    $query = "INSERT INTO comments(user_id, restaurant_id, username, title, description, score, created_at, updated_at) VALUES(:user_id, :restaurant_id, :username, :title, :description, :score, :created_at, :updated_at)";
    $statement = $pdo->prepare($query);
    $statement->execute(["user_id" => $user_id, "restaurant_id" => $restaurant_id, "username" => $username, "title" => $title, "description" => $description, "score" => $score, "created_at" => $created_at, "updated_at" => $created_at]);
}


function UpdateOrderStatus($order_id, $value)
{
    global $pdo;
    $order_id = htmlclean($order_id);
    $value = htmlclean($value);
    $query = "UPDATE `order` SET order_status = (order_status + :value)%3 WHERE id = :order_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["value" => $value, "order_id" => $order_id]);
}


function GetOrders($user_id)
{
    global $pdo;
    $user_id = htmlclean($user_id);
    $query = "SELECT * FROM `order` WHERE user_id =:user_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["user_id" => $user_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
function GetCuponByName($cupon_name)
{
    global $pdo;
    $cupon_name = htmlclean($cupon_name);
    $query = "SELECT * FROM cupon WHERE name = :cupon_name";
    $statement = $pdo->prepare($query);
    $statement->execute(["cupon_name" => $cupon_name]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

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
function GetAllCompanies() {
    global $pdo; 
    $query = "SELECT id,name, description, logo_path FROM company"; 
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC); 
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
function deleteCompanyById($id) {
    global $pdo; 

    $stmt = $pdo->prepare("DELETE FROM company WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    return $stmt->execute();
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


function companyRegister($name, $description){

    global $pdo;
    $name = htmlclean($name);
    $description = htmlclean($description);
    $query = "INSERT INTO company(name, description, logo_path) VALUES(:name, :description)";
    $statement = $pdo->prepare($query);
    $statement->execute(["name" => $name, "description" => $description]);

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

function GetRestaurantName($restaurant_id)
{
    global $pdo;
    $restaurant_id = htmlclean($restaurant_id);
    $query = "SELECT name FROM restaurant WHERE id = :restaurant_id";
    $statement = $pdo->prepare($query);
    $statement->execute(["restaurant_id" => $restaurant_id]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result['name'];
}


?>