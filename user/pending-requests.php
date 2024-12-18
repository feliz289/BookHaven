<?php
    session_start();
    if(!isset($_SESSION["user_logged_in"])) {
        header("Location: ../index.php");
    }
    $user_id = $_SESSION["user_id"];

    require_once "../database/dbh.php";

    $query = "SELECT issued_books_status_id 
    FROM issued_books_status 
    WHERE status = 'pending';";
    $stmt = $pdo->prepare($query);
    $stmt->execute();   
    $pending_id = $stmt->fetchColumn();

    $query = "SELECT issued_books_status_id 
    FROM issued_books_status 
    WHERE status = 'cancelled';";
    $stmt = $pdo->prepare($query);
    $stmt->execute();   
    $cancelled_id = $stmt->fetchColumn();

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
WHERE i.status = $pending_id OR i.status = $cancelled_id  AND i.user_id = $user_id
ORDER BY 
    s.status ASC;";
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
            min-height: 100px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
    <script src="https://kit.fontawesome.com/6151c1ffe2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
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
<div class="container-fluid">
    <h1 class="text-center">Requested Books</h1>
    <div class="container mt-4">
        <div class="table-responsive">
            <table id="myTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Book Title</th>
                        <th scope="col">Issue Date</th>
                        <th scope="col">Return Date</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($books) > 0): ?>
                    <?php foreach ($books as $index => $book): ?>
                        <tr>
                            <th scope="row"><?php echo $index + 1; ?></th>
                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['book_name']); ?></td>
                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['issue_date']); ?></td>
                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['return_date']); ?></td>
                            <td class="text-truncate text-capitalize">
                                <?php 
                                    $status = htmlspecialchars($book['status']);
                                    if ($status === 'cancelled') {
                                        echo '<div class="bg-danger text-white p-2 rounded">Rejected</div>'; // Red block for rejected
                                    } else {
                                        echo '<div class="bg-warning text-dark p-2 rounded">' . $status . '</div>'; // Yellow block for other statuses
                                    }
                                ?>
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

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
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