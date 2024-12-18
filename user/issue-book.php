<?php
    session_start();
    if(!isset($_SESSION["user_logged_in"])) {
        header("Location: ../index.php");
    }
    $user_id = $_SESSION["user_id"];

    require_once "../database/dbh.php";

    $query = "SELECT 
        b.book_id,
        b.book_name,
        b.book_image,
        c.book_categories_name AS book_category,
        a.author_name AS book_author,
        -- b.book_status
        s.status AS book_status,
        s.book_status_id AS book_status_id

        FROM 
            books b
        JOIN 
            book_categories c ON b.book_category = c.book_categories_id
        JOIN 
            book_authors a ON b.book_author = a.book_authors_id
        JOIN 
            book_status s ON b.book_status = s.book_status_id
        WHERE b.book_status = 1 AND c.book_categories_status = 1
        ORDER BY book_name ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin: 15px;
        }

        /* .card-img-top {
            width: 100%; 
            height: auto; 
            max-height: 300px; 
            object-fit: cover;
        } */
    </style>
    <script src="https://kit.fontawesome.com/6151c1ffe2.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-primary text-white">
    <div class="container-fluid">
        <a href="./issue-book.php" class="logo text-decoration-none text-white"> 
            <i class="fa-solid fa-book"></i> BookHaven 
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            <div class="ms-auto">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="./book-issued.php">Issued Books</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link text-white" href="./pending-requests.php">Pending Requests</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <a href="../includes/log-out-user.php" class="dropdown-item">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-4">
    <h1 class="text-center">Available Books</h1>
    
    <!-- Search Form -->
    <form method="GET" action="" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Search by book name, author, or category" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="row justify-content-center">
        <?php
        // Check if a search query is set
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Modify the query to include search functionality
        $query = "SELECT 
            b.book_id,
            b.book_name,
            b.book_image,
            c.book_categories_name AS book_category,
            a.author_name AS book_author,
            s.status AS book_status,
            s.book_status_id AS book_status_id
            FROM 
            books b
            JOIN 
            book_categories c ON b.book_category = c.book_categories_id
            JOIN 
            book_authors a ON b.book_author = a.book_authors_id
            JOIN 
            book_status s ON b.book_status = s.book_status_id
            WHERE b.book_status = 1 AND c.book_categories_status = 1";

        // Add search condition if search term is provided
        if (!empty($search)) {
            $query .= " AND (b.book_name LIKE :search OR a.author_name LIKE :search OR c.book_categories_name LIKE :search)";
        }

        $query .= " ORDER BY book_name ASC";
        $stmt = $pdo->prepare($query);

        // Bind the search parameter if it exists
        if (!empty($search)) {
            $searchParam = '%' . $search . '%';
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <?php
        if ($books) {
            foreach ($books as $index => $book) {
                $imagePath = '../uploads/books/' . $book['book_image'];
                ?>
                <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center mb-4">
                    <div class="card" style="width: 12rem;">
                        <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="Book Image">
                        <div class="card-body">
                            <h5 class="card-title text-truncate text-capitalize"><?php echo htmlspecialchars($book['book_name']); ?></h5>
                            <p class="card-text text-truncate text-capitalize">By: <?php echo htmlspecialchars($book['book_author']); ?></p>
                            <button class="btn issue-book btn-success" data-book-id="<?php echo $book['book_id']; ?>" data-user-id="<?php echo $user_id; ?>">Issue</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No books available.</p>";
        }
        ?>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let book_id, user_id;
            $('.issue-book').on('click', function() {
                book_id = $(this).data('book-id');
                user_id = $(this).data('user-id');

                $.ajax({
                    url: '../database/issue-book-db.php',
                    type: 'POST',
                    data: { book_id: book_id,
                        user_id: user_id,
                     },
                    success: function(data) {
                        var json = JSON.parse(data);
                        result = json.status;
                        message = json.message;
                        if (result == 'success') {
                            let modalHtml = `
                                <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="responseModalLabel">${result ? 'Success' : 'Error'}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ${message}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            // Append modal to body
                            $('body').append(modalHtml);
                            // Show the modal
                            var modal = new bootstrap.Modal(document.getElementById('responseModal'));
                            modal.show();
                            // Handle modal close event
                            $(document).on('hidden.bs.modal', '#responseModal', function () {
                                console.log('Modal closed'); // Debugging statement
                                $(this).remove();
                                console.log('Data success:', data.success); // Debugging statement
                                if (result) {
                                    location.reload();
                                }
                                });
                        } else {
                            let modalHtml = `
                                <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="responseModalLabel">${result ? 'Success' : 'Error'}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ${message}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            // Append modal to body
                            $('body').append(modalHtml);
                            // Show the modal
                            var modal = new bootstrap.Modal(document.getElementById('responseModal'));
                            modal.show();
                            // Handle modal close event
                            $('#responseModal').on('hidden.bs.modal', function () {
                                // Remove modal from DOM after it's closed
                                $(this).remove();
                                if (data.success) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function() {
                        alert('An error occurred while trying to delete the book.');
                    }
                });
            });
            
        });
    </script>
</body>
</html>