<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // //Table name
    // $tableName = $_SESSION['table_name'];

    $book_id = $_POST["book_id"];
    $user_id = $_POST["user_id"];

    try {
        require_once "../database/dbh.php";
        require_once './user-management.php';

        

        if(isset($error)) {
            $_SESSION['error_adding_user'] = $error;
            header("Location: ../manage/" . $_SESSION['redirect_to']);
            exit();
        }

        // Fetch the status ID for 'pending'
        $query = "SELECT issued_books_status_id 
        FROM issued_books_status
        WHERE status = 'pending';";
        $stmt = $pdo->prepare($query);
        $stmt->execute();   
        $status_id = $stmt->fetchColumn();


        // Fetch the status ID for 'pending'
        $query = "SELECT issued_books_id
        FROM issued_books
        WHERE book_id=:book_id AND user_id=:user_id AND status=:status";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':book_id', $book_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':status', $status_id);
        $stmt->execute();
        $book_already_issued = $stmt->fetchColumn();

        if($book_already_issued) {
            $data = array(
                'status' => 'failed',
                'message' => 'Request is already pending!'
            );
            echo json_encode($data);
            return;
        }

        // Get the current date and time for the issue_date
        $issue_date = date('Y-m-d H:i:s');
        // Calculate the return date as 14 days from the issue_date
        $return_date = date('Y-m-d H:i:s', strtotime($issue_date) + (14 * 24 * 60 * 60)); // 14 days from issue_date

        $query = "INSERT INTO issued_books (book_id, user_id, issue_date, return_date, status)
        VALUES (:book_id, :user_id, :issue_date, :return_date, :status_id);";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':book_id', $book_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':issue_date', $issue_date);
        $stmt->bindParam(':return_date', $return_date);
        $stmt->bindParam(':status_id', $status_id);
        $stmt->execute();

        if ($stmt) {
            $data = array(
                'status' => 'success',
                'message' => 'Thank you for your request! Your book will be issued once the admin has approved it.'
            );
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'An error occured while issuing a book!'
            );
            echo json_encode($data);
        }
        
    }
    catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }
}
else {
    header("Location: ../index.php?");
    die(); 
}