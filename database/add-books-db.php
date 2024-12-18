<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //Table name
    $tableName = $_SESSION['table_name'];

        $book_name = $_POST["book_name"];
        $book_category = $_POST["book_category"];
        $book_author = $_POST["book_author"];

    try {

        require_once "../database/dbh.php";
        require_once './user-management.php';

        //Upload or move the file to our directory
        $target_dir = '../uploads/books/';
        $file_data = $_FILES['book_image'];

        $file_name = $file_data['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        $file_name = 'product-' . time() . '.' . $file_ext;
        $check = getimagesize($file_data['tmp_name']);
        
        //Move the file
        if ($check) {
            if(move_uploaded_file($file_data['tmp_name'], $target_dir . $file_name)) {
                $value = $file_name;
            }
        }

        // Checks if author already exist
        $query = "SELECT book_authors_id FROM book_authors WHERE author_name = :author_name";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':author_name', $book_author);
        $stmt->execute();
        $author_exist = $stmt->fetch(PDO::FETCH_ASSOC);

        //ERROR HANDLERS - To check if the user fillup the form before submitting.
        $query = "SELECT book_name FROM books WHERE book_name = :book_name AND book_author = :book_author";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':book_name', $book_name);
        $stmt->bindParam(':book_author', $author_exist['book_authors_id']);
        $stmt->execute();
        $book_exist = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book_exist) {
            $error = "Book already Exist!";
        }

        if (isset($error)) {
            $_SESSION["error_adding_user"] = $error;

            header("Location: ../manage/" . $_SESSION['redirect_to'] . "?error");
            die();
        }

        // Checks if author already exist
        $query = "SELECT book_authors_id FROM book_authors WHERE author_name = :author_name";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':author_name', $book_author);
        $stmt->execute();
        $author_exist = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get book status
        $query = "SELECT book_status_id FROM book_status WHERE status = 'available'";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $book_status = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if author already exist
        if (!$author_exist) {
            // Insert Author
            $query = "INSERT INTO book_authors (author_name)
            VALUES (:author_name);";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':author_name', $book_author);
            $stmt->execute();

            $query = "SELECT book_authors_id FROM book_authors WHERE book_authors_id = LAST_INSERT_ID()";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $book_author_id = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "INSERT INTO books (book_name, book_image, book_category, book_author, book_status)
                VALUES (:book_name, :book_image, :book_category, :book_author, :book_status);";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':book_name', $book_name);
                $stmt->bindParam(':book_image', $value);
                $stmt->bindParam(':book_category', $book_category);
                $stmt->bindParam(':book_author', $book_author_id['book_authors_id']);
                $stmt->bindParam(':book_status', $book_status['book_status_id']);
                $stmt->execute();
        }
        else {
            $query = "INSERT INTO books (book_name, book_image, book_category, book_author, book_status)
                VALUES (:book_name, :book_image, :book_category, :book_author, :book_status);";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':book_name', $book_name);
                $stmt->bindParam(':book_image', $value);
                $stmt->bindParam(':book_category', $book_category);
                $stmt->bindParam(':book_author', $author_exist['book_authors_id']);
                $stmt->bindParam(':book_status', $book_status['book_status_id']);
                $stmt->execute();
        }

        $_SESSION["successfull_adding_book"] = "Book Added Successfully!";
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