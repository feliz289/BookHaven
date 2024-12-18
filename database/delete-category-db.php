<?php
session_start();
require_once '../database/dbh.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM book_categories WHERE book_categories_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->execute()) {
        $data = array(
            'status' => 'success',
            'message' => 'Deleted Successfully!'
        );
        echo json_encode($data);
    } else {
        $data = array(
            'status' => 'failed',
            'message' => 'Deletion Unsuccessful!'
        );
        echo json_encode($data);
    }
}