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

    // Fetch the status ID for 'cancelled'
    $query = "SELECT issued_books_status_id 
    FROM issued_books_status 
    WHERE status = 'cancelled';";
    $stmt = $pdo->prepare($query);
    $stmt->execute();   
    $status_id = $stmt->fetchColumn();

    $stmt = $pdo->prepare("UPDATE issued_books SET status = :status WHERE issued_books_id = :id");
    $stmt->bindParam(':status', $status_id);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->execute()) {
        $data = array(
            'status' => 'success',
            'message' => 'Issue Request is Rejected!'
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