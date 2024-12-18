<?php 
    require_once "../database/dbh.php";

    $options = [
        'cost' => 12
    ]; 
    $user_pass = password_hash("admin123", PASSWORD_BCRYPT, $options);

        $query = "INSERT INTO users (user_type, user_full_name, user_email, user_phone_num, user_pass)
        VALUES (20, 'admin', 'admin@gmail.com', '09090909090', '".$user_pass."');";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

   
        $stmt->execute();