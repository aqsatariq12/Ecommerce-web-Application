<?php 
$host = "localhost";
$dbname = "ecommerce";
$username = "root";
$password = "";

//utf8mb4: support many characyers like emojis, english characters, character which has a cap on their head
try{
    $pdo = new PDO("mysql:host=$host; dbname=$dbname;charset=utf8mb4", $username, $password);

    //PDO, if something goes wrong with the database give me an error/exception instead of silently failing
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //PDO, whenever I fetch database data, return each row as an associative array using the column names
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    die("Database Connection Failed.");
}
?>