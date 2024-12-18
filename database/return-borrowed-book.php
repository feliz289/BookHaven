<?php
session_start();
require_once '../database/dbh.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['issued_books_id'];
    $status = $_POST['status'];
    $book_id = $_POST['book_id'];

    // Validate inputs
    if (empty($id) || empty($status)) {
        $data = array(
            'status' => 'failed',
            'message' => 'Update Unsuccessful!'
        );
        echo json_encode($data);
        exit;
    }

    // Fetch the status ID for 'returned'
    $query = "SELECT issued_books_status_id 
    FROM issued_books_status 
    WHERE status = 'returned';";
    $stmt = $pdo->prepare($query);
    $stmt->execute();   
    $status_id = $stmt->fetchColumn();

    $stmt = $pdo->prepare("UPDATE issued_books SET status = :status WHERE issued_books_id = :id");
    $stmt->bindParam(':status', $status_id);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the status ID for 'available'
    $query = "SELECT book_status_id 
    FROM book_status 
    WHERE status = 'available';";
    $stmt = $pdo->prepare($query);
    $stmt->execute();   
    $book_status_id = $stmt->fetchColumn();

    $stmt = $pdo->prepare("UPDATE books SET book_status = :status WHERE book_id = :id");
    $stmt->bindParam(':status', $book_status_id);
    $stmt->bindParam(':id', $book_id);
    $stmt->execute();

    if ($stmt->execute()) {
        $data = array(
            'status' => 'success',
            'message' => 'Book Successfully Returned!'
        );
        echo json_encode($data);
    } else {
        $data = array(
            'status' => 'failed',
            'message' => 'Error Occured!'
        );
        echo json_encode($data);
    }
}