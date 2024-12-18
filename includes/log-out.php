<?php

session_start();
header("Location: ../index.php");
if(isset($_SESSION["user_type"])){
    unset($_SESSION["user_type"]);
}
if(isset($_SESSION["id"])){
    unset($_SESSION["id"]);
}
if(isset($_SESSION["admin_librarian_logged_in"])){
    unset($_SESSION["admin_librarian_logged_in"]);
}