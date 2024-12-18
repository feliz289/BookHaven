<?php
    session_start();
    if(!isset($_SESSION["admin_librarian_logged_in"])) {
        header("Location: ../index.php");
    }
    
    require_once "../database/dbh.php";

    $query = "SELECT issued_books_status_id 
    FROM issued_books_status 
    WHERE status = 'pending';";
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
    <script defer src="https://kit.fontawesome.com/6151c1ffe2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
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
                <div class="container mt-2">
                    <h2 class="mb-4">Book Issue Requests</h2>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <!-- <th scope="col">Image</th> -->
                                    <th scope="col">Book Name</th>
                                    <th scope="col">User</th>
                                    <th scope="col">status</th>
                                    <!-- <th scope="col">Issue Date</th>
                                    <th scope="col">Return Date</th> -->
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
                                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['status']); ?></td>
                                            <!-- <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['issue_date']); ?></td>
                                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['return_date']); ?></td> -->
                                            <td class="d-flex flex-column flex-sm-row justify-content-center">
                                                <button class="btn accept-issue btn-primary btn-sm mb-2 mb-sm-0 me-sm-2" data-bs-toggle="modal" data-bs-target="#editBookModal" data-issued-books-id="<?php echo $book['issued_books_id']; ?>" 
                                                data-issued-books-status-id="<?php echo $book['issued_books_status_id']; ?>"
                                                data-book-id="<?php echo $book['book_id']; ?>">Accept</button>
                                                <button class="btn reject-issue btn-danger btn-sm" data-issued-books-id="<?php echo $book['issued_books_id']; ?>" 
                                                data-issued-books-status-id="<?php echo $book['issued_books_status_id']; ?>"
                                                data-book-id="<?php echo $book['book_id']; ?>">Reject</button>
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
                                <a href="../pages/dashboard.php" class="text-muted">
                                    <strong>BookHaven</strong>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
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
            $('.accept-issue').on('click', function() {
                issued_books_id = $(this).data('issued-books-id');
                status = $(this).data('issued-books-status-id');
                book_id = $(this).data('book-id');

                $.ajax({
                    url: '../database/accept-issue-requests.php',
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
        $(document).ready(function() {
            let book_id, user_id;
            $('.reject-issue').on('click', function() {
                issued_books_id = $(this).data('issued-books-id');
                status = $(this).data('issued-books-status-id');
                book_id = $(this).data('book-id');

                $.ajax({
                    url: '../database/reject-issue-requests.php',
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
<script src="../js/main.js"></script>
</body>
</html>