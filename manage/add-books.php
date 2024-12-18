<?php
    session_start();
    if(!isset($_SESSION["admin_librarian_logged_in"])) {
        header("Location: ../index.php");
    }
    $_SESSION['table_name'] = 'books';
    $_SESSION['redirect_to'] = 'add-books.php';
    
    require_once "../database/dbh.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHaven</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6151c1ffe2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="wrapper">
        <!-- SIDE BAR -->
        <?php include('../includes/sidebar.php'); ?>
        <div class="main">
            <!-- SIDE BAR -->
            <?php include('../includes/navbar.php'); ?>
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <h5>Add New Book</h5>
                    <div class="row justify-content-center">
                        <div class="col-12 mt-5 px-5" style="width: 100%;">
                            <form action="../database/add-books-db.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="bookName" class="form-label">Book Name</label>
                                    <input type="text" class="form-control" id="bookName" name="book_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="bookName" class="form-label">Book Image</label>
                                    <input type="file" class="form-control" id="bookImage" name="book_image" required>
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" id="category" name="book_category" required>
                                        <option value="" disabled selected>Select a category</option>
                                        <?php
                                            $query = "SELECT 
                                                bc.book_categories_id, 
                                                bc.book_categories_name, 
                                                bcs.book_categories_status_name
                                            FROM 
                                                book_categories bc
                                            INNER JOIN 
                                                book_categories_status bcs ON bc.book_categories_status = bcs.book_categories_status_id
                                            WHERE 
                                                bcs.book_categories_status_name = 'active';";
                                            $stmt = $pdo->prepare($query);
                                            $stmt->execute();
                                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category) {
                                                echo "<option value='{$category['book_categories_id']}'>{$category['book_categories_name']}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="author" class="form-label">Author</label>
                                    <input type="text" class="form-control" id="author" name="book_author" required>
                                </div>
                                <div class="mb-3">
                                    <?php if (isset($_SESSION["error_adding_book"])): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo htmlspecialchars($_SESSION["error_adding_book"]); ?>
                                        </div>
                                    <?php unset($_SESSION["error_adding_book"]); endif; ?>
                                </div>
                                <div class="mb-3">
                                    <?php if (isset($_SESSION["successfull_adding_book"])): ?>
                                        <div class="alert alert-success" role="alert">
                                            <?php echo htmlspecialchars($_SESSION["successfull_adding_book"]); ?>
                                        </div>
                                    <?php unset($_SESSION["successfull_adding_book"]); endif; ?>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-12 text-start">
                            <p class="mb-0">
                                <a href="../pages/dashboard.php" class="text-muted">
                                    <strong>BookHaven</strong>
                                </a>
                            </p>
                        </div>
                        <!--  -->
                    </div>
                </div>
            </footer>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="../js/main.js"></script>

</body>
</html>
<?php
// Close the database connection
    $pdo = null;
    $stmt = null;

?>