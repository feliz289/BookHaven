<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Table name
    $tableName = $_SESSION['table_name'];

    $category_name = $_POST["category_name"];
    $category_status = $_POST["category_status"];

    try {
        require_once "../database/dbh.php";
        require_once './user-management.php';
        
        // ERROR HANDLERS - To check if the user filled up the form before submitting.
        if (get_instance($tableName, $pdo, 'book_categories_name', $category_name)) {
            $error = "Category already exists!";
        }

        if (isset($error)) {
            $_SESSION["error_adding_category"] = $error;
            header("Location: ../manage/" . $_SESSION['redirect_to'] . "?error");
            die();
        }

        // Use prepared statements with bound parameters
        $query = "INSERT INTO book_categories (book_categories_name, book_categories_status) VALUES (:category_name, :category_status)";
        $stmt = $pdo->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':category_status', $category_status);
        
        // Execute the statement
        $stmt->execute();

        $_SESSION["successfull_adding_category"] = "Category Added Successfully!";
        header("Location: ../manage/" . $_SESSION['redirect_to']);

        $pdo = null;
        $stmt = null;

        die;
    } catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }

} else {
    header("Location: ../add_operations/" . $_SESSION['redirect_to']);
    die();
}