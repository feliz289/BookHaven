<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {    

    $student_email = $_POST["user_email"];
    $user_pass = $_POST["user_pass"];

    if ($student_email == '')
        $error = "Please fill out all field";
    else if ($user_pass == '')
        $error = "Please fill out all field";

        try {
            require_once "../database/dbh.php";
    
            $query = "SELECT * FROM users WHERE user_email = :user_email;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":user_email", $student_email);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            //Authentication
            if (!$result["user_email"]) {
                $error = "The email address is not registered!";
            }
            else if (!password_verify($user_pass, $result["user_pass"])) {
                $error = "Password is incorrect!";
            }
    
            if(isset($error)) {
                $_SESSION['login_error'] = $error;
                header("Location: ../pages/login.php?error"); 
                exit();
            }
            $query = "SELECT 
                ut.type AS user_type_name
            FROM 
                users u
            INNER JOIN 
                user_type ut ON u.user_type = ut.user_type_id
            WHERE u.user_id = :user_id;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":user_id", $result['user_id']);
            $stmt->execute();
            $result2 = $stmt->fetch(PDO::FETCH_ASSOC);

            //Get user status
            $query = "SELECT s.status AS user_status
                FROM users u 
                JOIN 
                user_status s ON u.user_status = s.user_status_id
                WHERE user_id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $result['user_id']);
            $stmt->execute();
            $user_status = $stmt->fetch(PDO::FETCH_ASSOC);

            // Checks if account is deactivated
            if($user_status['user_status'] == 'inactive') {
                $_SESSION['login_error'] = "Your Account Is Deactivated!";
                header("Location: ../pages/login.php?error"); 
                exit();
            }

            if($result2["user_type_name"] == 'admin' || $result2["user_type_name"] == 'librarian'){
                $_SESSION["user_type"] = $result2["user_type_name"];
                $_SESSION["user_full_name"] = htmlspecialchars($result['user_name']);
                $_SESSION["id"] = $result["user_id"];
                $_SESSION["admin_librarian_logged_in"] = true;
                header("Location: ../pages/dashboard.php");
            }  
            else{
                $_SESSION["user_full_name"] = htmlspecialchars($result['user_name']);
                $_SESSION["user_id"] = $result["user_id"];
                $_SESSION["user_logged_in"] = true;
                header("Location: ../user/issue-book.php");
            }
                
            $stmt = null;
            $pdo = null;
            die();
            
        }
        catch (PDOException $e) {
            die("Query failed: ". $e->getMessage());
        }
}
else {
    header("Location: ../index.php?");
    die(); 
}