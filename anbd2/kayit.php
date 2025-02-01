<?php

require __DIR__."/vendor/autoload.php";

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $dotenv=Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $database=new Database(
         $_ENV["DB_HOST"],
        $_ENV["DB_NAME"],
        $_ENV["DB_USER"],
        $_ENV["DB_PASS"]
    );
    $conn=$database->getConnection();
    $sql ="INSERT INTO user(name ,username,password_hash) VALUES(:name,:username,:password_hash)";
    $stmt=$conn->prepare($sql);

    $password_hash=password_hash($_POST["password"],PASSWORD_DEFAULT);
    
    $stmt->bindValue(":name",$_POST["name"],PDO::PARAM_STR);
    $stmt->bindValue(   ":username",$_POST["username"],PDO::PARAM_STR);
    $stmt->bindValue(":password_hash",$password_hash,PDO::PARAM_STR);

    $stmt->execute();



    echo"thanks";
    exit;

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>


    <div class="container">
        <h2> kullanıcı kayıt</h2>
        <form action="kayit.php" method="post">
            <div class="form-group">
                <label for="name">name:</label>
                <input type="text" id="name" name="name"required>
            </div>
            <div class="form-group">
                <label for="username">username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form_group">
                <label for="password">sifre:</label>
                <input type="password" id="password"name="password"required>
            </div>
            <div class="form-group">
                <input type="submit" value="kayit">
            </div>

        </form>



    </div>
    
</body>
</html>