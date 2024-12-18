<?php

session_start();
header("Location: ../index.php");
if(isset($_SESSION["user_id"])){
    unset($_SESSION["user_id"]);
}
if(isset($_SESSION["user_logged_in"])){
    unset($_SESSION["user_logged_in"]);
}