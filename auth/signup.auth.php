<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {    

    $user_name = $_POST["user_name"];
    $student_id = $_POST["student_id"];
    $student_email = $_POST["user_email"];
    $student_phone_num = $_POST["user_phone_num"];
    
    $options = [
        'cost' => 12
    ]; 
    $user_pass = password_hash($_POST["user_pass"], PASSWORD_BCRYPT, $options);

//Extra security for bypass
    if ($user_name == '')
        $error = "Please fill out all field";
    else if ($student_id == '')
        $error = "Please fill out all field";
    else if ($student_email == '')
        $error = "Please fill out all field";
    else if ($student_phone_num == '')
        $error = "Please fill out all field";
    else if ($user_pass == '')
        $error = "Please fill out all field";

    try {
        require_once "../database/dbh.php";
        require_once 'user_management.php';

        if (!filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        }
        else if (get_instance('users', $pdo, 'user_email', $student_id)) {
            $error = "Email is already used!";
        }
        else if (get_instance('users', $pdo, 'student_id', $student_id)) {
            $error = "User Already Exist";
        }

        if(isset($error)) {
            $_SESSION['signup_error'] = $error;
            header("Location: ../pages/signup.php?error"); 
            exit();
        }

        //Get the user type id
        $query = "SELECT user_type_id FROM user_type WHERE type = 'student'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $user_type = $stmt->fetch(PDO::FETCH_ASSOC);

        //Get user status id
        $query = "SELECT user_status_id FROM user_status WHERE status = 'active'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $user_status = $stmt->fetch(PDO::FETCH_ASSOC);

        
        $query = "INSERT INTO users (student_id, user_type, user_status, user_name, user_email, user_phone_num, user_pass)
        VALUES ('".$student_id."', '".$user_type['user_type_id']."', '".$user_status['user_status_id']."', '".$user_name."', '".$student_email."', '".$student_phone_num."', '".$user_pass."');";

        $stmt = $pdo->prepare($query);
        $stmt->execute();


        $_SESSION['signup_successful'] = "Account Created Successfully";
        header("Location: ../pages/signup.php"); 
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