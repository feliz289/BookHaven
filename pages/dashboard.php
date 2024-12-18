<?php
    session_start();
    if(!isset($_SESSION["admin_librarian_logged_in"])) {
        header("Location: ../index.php?loggedin");
    }
    // else if($_SESSION["user_type"] !== 'admin' && $_SESSION["user_type"] !== "librarian" ) {
    //     header("Location: ../index.php");
    // }

    require_once "../database/dbh.php";

    $query = "SELECT issued_books_status_id 
    FROM issued_books_status 
    WHERE status = 'issued';";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $status_id = $stmt->fetchColumn();

    $query = "SELECT 
    i.issued_books_id,
    i.book_id,
    b.book_name AS book_name,
    u.user_name AS user,
    i.issue_date,
    i.return_date,
    s.status AS status,
    s.issued_books_status_id
FROM 
    issued_books i
LEFT JOIN 
    books b ON i.book_id = b.book_id
LEFT JOIN 
    users u ON i.user_id = u.user_id
LEFT JOIN 
    issued_books_status s ON i.status = s.issued_books_status_id
WHERE i.status = $status_id
ORDER BY 
    b.book_name ASC;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="wrapper">
        <!-- SIDE BAR -->
        <aside id="sidebar" class="js-sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="./dashboard.php" class="logo"> <i class="fa-solid fa-book"></i> BookHaven </a>
                </div>
                <ul class="sidebar-nav">
                    <!-- <li class="sidebar-header">
                        Admin Elements
                    </li> -->
                    <li class="sidebar-item">
                        <a href="./dashboard.php" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-file-lines pe-2"></i>
                            Manage Books
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="../manage/add-books.php" class="sidebar-link">Add Book</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="../manage/view-books.php" class="sidebar-link">View Book</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-sliders pe-2"></i>
                            Manage Categories
                        </a>
                        <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="../manage/add-category.php" class="sidebar-link">Add Category</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="../manage/view-category.php" class="sidebar-link">View Category</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="./book-issue-requests.php" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Book Issue Requests
                        </a>
                    </li>
                    <?php
                        $user_type = $_SESSION["user_type"];
                        $user_full_name = $_SESSION["user_full_name"];
                        if ($user_type === "admin") {
                            echo    '<li class="sidebar-item">
                                    <a href="#" class="sidebar-link collapsed" data-bs-target="#auth" data-bs-toggle="collapse"
                                        aria-expanded="false"><i class="fa-regular fa-user pe-2"></i>
                                        Manage Users
                                    </a>
                                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                        <li class="sidebar-item">
                                            <a href="../manage/add-users.php" class="sidebar-link">Add Users</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="../manage/view-users.php" class="sidebar-link">View Users</a>
                                        </li>
                                    </ul>
                                </li>';
                        }
                    ?>
                </ul>
            </div>
        </aside>
        <div class="main">
            <!-- SIDE BAR -->
            <?php include('../includes/navbar.php'); ?>
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <!-- <div class="mb-3">
                        <h4>Admin Dashboard</h4>
                    </div> -->
                    <div class="row">
                        <div class="col-12 col-md-6 d-flex">
                            <div class="card flex-fill border-0 illustration">
                                <div class="card-body p-0 d-flex flex-fill">
                                    <div class="row g-0 w-100">
                                        <div class="col-8">
                                            <div class="p-3 m-1">
                                                <?php
                                                    $user_type = $_SESSION["user_type"];
                                                    $user_full_name = $_SESSION["user_full_name"];
                                                    if ($user_type === "admin") {
                                                        echo    '<h5>Welcome Back, '. $user_type .'</h5>
                                                                <p class="mb-0">Admin Dashboard, BookHaven</p>';
                                                    }
                                                    else if ($user_type === "librarian") {
                                                        echo    '<h5>Welcome Back, '. $user_type .'</h5>
                                                                <p class="mb-0">Librarian Dashboard, BookHaven</p>';
                                                    }
                                                    else {
                                                        echo    '<h4>Welcome Back, User</h4>
                                                                <p class="mb-0">'. $user_type .' Dashboard, BookHaven</p>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-4 align-self-end text-end">
                                            <img src="../image/customer-support.jpg" class="img-fluid illustration-img"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 d-flex" style="cursor: pointer;">
                            <div class="card flex-fill border-0 pending-requests-container">
                                <div class="card-body py-4">
                                    <div class="d-flex align-items-start">
                                        <a href="./book-issue-requests.php" class="flex-grow-1">
                                            <h4 class="mb-2">
                                                Pending Issue Request
                                            </h4>
                                            <!-- <p class="mb-2">
                                                Number of Pending Requests: 
                                            </p> -->
                                            <?php
                                                $query = "SELECT COUNT(status) FROM issued_books WHERE status = 1;";
                                                $stmt = $pdo->prepare($query);
                                                $stmt->execute();
                                                $book_status_count = $stmt->fetchColumn();
                                                echo    '<h3 pl-2>'. $book_status_count .'</h3>';
                                            ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table Element -->
                    <div class="card border-0">
                        <div class="card-header">
                            <h5 class="card-title">
                                Borrowed Books
                            </h5>
                        </div>
                        <div class="card-body">
                            <table id="myTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <!-- <th scope="col">Image</th> -->
                                        <th scope="col">Book Name</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Issue Date</th>
                                        <th scope="col">Return Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($books) > 0): ?>
                                        <?php foreach ($books as $index => $book): ?>
                                            <tr>
                                                <th scope="row"><?php echo $index + 1; ?></th>
                                                <!-- <td class="d-flex justify-content-center align-items-center" style="height: 150px;">
                                                    <img class="product_images img-fluid" src="../uploads/books/<?= $book['book_image'] ?>" alt="Book Image">
                                                </td> -->
                                                <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['book_name']); ?></td>
                                                <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['user']); ?></td>
                                                <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['issue_date']); ?></td>
                                                <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['return_date']); ?></td>
                                                <td class="d-flex flex-column flex-sm-row justify-content-center">
                                                    <button class="btn return-book btn-success btn-sm mb-2 mb-sm-0 me-sm-2" data-bs-toggle="modal" data-bs-target="#editBookModal" data-issued-books-id="<?php echo $book['issued_books_id']; ?>" 
                                                    data-issued-books-status-id="<?php echo $book['issued_books_status_id']; ?>"
                                                    data-book-id="<?php echo $book['book_id']; ?>">Return</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No records found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-12 text-start">
                            <p class="mb-0">
                                <a href="#" class="text-muted">
                                    <strong>BookHaven</strong>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="../js/main.js"></script>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable({
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        });
    } );
</script>
<script>
        $(document).ready(function() {
            let book_id, user_id;
            $('.return-book').on('click', function() {
                issued_books_id = $(this).data('issued-books-id');
                status = $(this).data('issued-books-status-id');
                book_id = $(this).data('book-id');

                $.ajax({
                    url: '../database/return-borrowed-book.php',
                    type: 'POST',
                    data: { issued_books_id: issued_books_id,
                        status: status,
                        book_id: book_id,
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