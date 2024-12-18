<?php

//For Database Connection

$host = "localhost:3307";
$dbname = "bookhaven"; //database name
$dbusername = "root";
$dbpassword = '';


try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    die("Connection Failed: ". $e->getMessage());
};