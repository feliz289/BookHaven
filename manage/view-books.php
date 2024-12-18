<?php
    session_start();
    if(!isset($_SESSION["admin_librarian_logged_in"])) {
        header("Location: ../index.php");
    }
    
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
            book_status s ON b.book_status = s.book_status_id  -- Join with book_status table
        ORDER BY book_name ASC";
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
                    <h2 class="mb-4">Book List</h2>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <!-- <th scope="col">Image</th> -->
                                    <th scope="col">Book Name</th>
                                    <th scope="col">Author</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Status</th>
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
                                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['book_author']); ?></td>
                                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['book_category']); ?></td>
                                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($book['book_status']); ?></td>
                                            <td class="d-flex flex-column flex-sm-row justify-content-center">
                                                <button class="btn edit-book btn-primary btn-sm mb-2 mb-sm-0 me-sm-2" data-bs-toggle="modal" data-bs-target="#editBookModal" data-book-id="<?php echo $book['book_id']; ?>" data-book-status="<?php echo $book['book_status_id']; ?>">Edit</button>
                                                <button class="btn delete-book btn-danger btn-sm" data-book-id="<?php echo $book['book_id']; ?>">Delete</button>
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

    <!-- Modal for Editing Book Status -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookModalLabel">Edit Book Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStatusForm">
                    <input type="hidden" id="bookId" value="">
                    <div class="mb-3">
                        <label for="bookStatus" class="form-label">Status</label>
                        <select class="form-select" id="bookStatus" required>
                            <option value="" disabled selected>Select status</option>
                                <?php
                                    $query = "SELECT * FROM book_status WHERE NOT status = 2;";
                                    $stmt = $pdo->prepare($query);
                                    $stmt->execute();
                                    $book_status = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($book_status as $status) {
                                        echo "<option value='{$status['book_status_id']}' style='text-transform: capitalize;'>" . ucfirst($status['status']) . "</option>";
                                    }
                                ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
            </div>
        </div>
    </div>
</div>
    <!-- Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this book?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
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
    // Event listener for the Edit button
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.getAttribute('data-book-id');
            const bookStatus = this.getAttribute('data-book-status');

            // Set the current status in the modal
            const statusSelect = document.getElementById('bookStatus');
            statusSelect.value = bookStatus;

            const bookSelectedId = document.getElementById('bookId');
            bookSelectedId.value = bookId;
        });
    });

    // Event listener for the Save changes button
    document.getElementById('saveChanges').addEventListener('click', function() {
        const status = document.getElementById('bookStatus').value;
        const id = document.getElementById('bookId').value;
        
        $.ajax({
            url: '../database/update-book-status.php',
            data: {
                id: id,
                status: status
            },
            type: 'post',
            success: function(data) {
                var json = JSON.parse(data);
                status_result = json.status;
                message = json.message;
                if(status_result == 'success') {
                    let modalHtml = `
                        <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="responseModalLabel">${status_result ? 'Success' : 'Error'}</h5>
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
                        if (status_result) {
                            location.reload();
                        }
                        });
                }
                else {
                    let modalHtml = `
                        <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="responseModalLabel">${status_result ? 'Success' : 'Error'}</h5>
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
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
        // Close the modal after saving changes
        const modal = bootstrap.Modal.getInstance(document.getElementById('editBookModal'));
        modal.hide();
    });

    $(document).ready(function() {
        let bookIdToDelete;
        $('.delete-book').on('click', function() {
            bookIdToDelete = $(this).data('book-id'); // Get the book ID from the data attribute
            $('#deleteConfirmationModal').modal('show'); // Show the confirmation modal
        });

        $('#confirmDelete').on('click', function() {
            $.ajax({
                url: '../database/delete-book-db.php', // The PHP file that will handle the deletion
                type: 'POST',
                data: { id: bookIdToDelete }, // Send the book ID
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
                            $(this).remove();
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
                            if (result) {
                                location.reload();
                            }
                        });
                    }
                },
                error: function() {
                    alert('An error occurred while trying to delete the book.');
                }
            });

            $('#deleteConfirmationModal').modal('hide'); // Hide the modal after confirming
        });
    });
</script>
</script>
</script>
<script src="../js/main.js"></script>
</body>
</html>