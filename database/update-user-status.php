<?php
session_start();
require_once '../database/dbh.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Validate inputs
    if (empty($id) || empty($status)) {
        $data = array(
            'status' => 'failed',
            'message' => 'Update Unsuccessful!'
        );
        echo json_encode($data);
        exit;
    }

    // Prepare the SQL statement
    $stmt = $pdo->prepare("UPDATE users SET user_status = :status WHERE user_id = :id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->execute()) {
        $data = array(
            'status' => 'success',
            'message' => 'Successfully Updated'
        );
        echo json_encode($data);
    } else {
        $data = array(
            'status' => 'failed',
            'message' => 'Update Unsuccessful!'
        );
        echo json_encode($data);
    }
}