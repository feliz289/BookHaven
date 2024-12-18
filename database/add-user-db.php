<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //Table name
    $tableName = $_SESSION['table_name'];

    $user_name = $_POST["user_name"];
    $user_email = $_POST["email"];
    $user_phone_num = $_POST["user_phone_num"];
    
    $options = [
        'cost' => 12
    ]; 
    $user_pass = password_hash($_POST["password"], PASSWORD_BCRYPT, $options);

    $user_type = $_POST["type"];
    $user_status = $_POST["status"];

    if ($user_name == '')
        $error = "Please fill out all field";
    else if ($user_email == '')
        $error = "Please fill out all field";
    else if ($user_pass == '')
        $error = "Please fill out all field";
    else if ($user_type == '')
        $error = "Please fill out all field";
    else if ($user_status == '')
        $error = "Please fill out all field";
    else if ($user_phone_num == '')
        $error = "Please fill out all field";

    try {
        require_once "../database/dbh.php";
        require_once './user-management.php';

        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        }
        else if (get_instance('users', $pdo, 'user_name', $user_name)) {
            $error = "User Already Exist";
        }

        if(isset($error)) {
            $_SESSION['error_adding_user'] = $error;
            header("Location: ../manage/" . $_SESSION['redirect_to']);
            exit();
        }

        $query = "INSERT INTO users (user_type, user_status, user_name, user_email, user_phone_num, user_pass)
        VALUES (:user_type, :user_status, :user_name, :user_email, :user_phone_num, :user_pass);";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_type', $user_type);
        $stmt->bindParam(':user_status', $user_status);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_email', $user_email);
        $stmt->bindParam(':user_phone_num', $user_phone_num);
        $stmt->bindParam(':user_pass', $user_pass);
        $stmt->execute();

            $_SESSION["successfull_adding_user"] = "User Added Successfully!";
            header("Location: ../manage/" . $_SESSION['redirect_to']);
            exit();
        
    }
    catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }
}
else {
    header("Location: ../index.php?");
    die(); 
}