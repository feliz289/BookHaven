<?php
    session_start();
    if(!isset($_SESSION["admin_librarian_logged_in"])) {
        header("Location: ../index.php");
    }
    
    require_once "../database/dbh.php";

    $query = "SELECT
        u.user_id,
        u.user_name,
        u.student_id,
        u.user_email,
        u.user_phone_num,
        t.type AS user_type,
        s.status AS user_status,
        t.user_type_id AS user_type_id,
        s.user_status_id AS user_status_id

        FROM 
            users u
        JOIN 
            user_type t ON u.user_type = t.user_type_id
        JOIN 
            user_status s ON u.user_status = s.user_status_id
        ORDER BY user_name ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <h2 class="mb-4">User List</h2>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <!-- <th scope="col">Image</th> -->
                                    <th scope="col">Username</th>
                                    <th class="text-truncate" scope="col">Student ID</th>
                                    <th scope="col">Email</th>
                                    <th class="text-truncate" scope="col">Phone Number</th>
                                    <th class="text-truncate" scope="col">User Type</th>
                                    <th class="text-truncate" scope="col">User Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($users) > 0): ?>
                                    <?php foreach ($users as $index => $user): ?>
                                        <tr>
                                            <th scope="row"><?php echo $index + 1; ?></th>
                                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($user['user_name']); ?></td>
                                            <td class="text-truncate"><?php echo htmlspecialchars(!empty($user['student_id']) ? $user['student_id'] : 'N/A'); ?></td>
                                            <td class="text-truncate"><?php echo htmlspecialchars($user['user_email']); ?></td>
                                            <td class="text-truncate"><?php echo htmlspecialchars($user['user_phone_num']); ?></td>
                                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($user['user_type']); ?></td>
                                            <td class="text-truncate text-capitalize"><?php echo htmlspecialchars($user['user_status']); ?></td>
                                            <td class="d-flex flex-column flex-sm-row justify-content-center">
                                                <button class="btn edit-user btn-primary btn-sm mb-2 mb-sm-0 me-sm-2" data-bs-toggle="modal" data-bs-target="#editUserModal" data-user-id="<?php echo $user['user_id']; ?>" data-user-status="<?php echo $user['user_status_id']; ?>">Edit</button>
                                                <button class="btn delete-user btn-danger btn-sm" data-user-id="<?php echo $user['user_id']; ?>">Delete</button>
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
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit Book Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStatusForm">
                    <input type="hidden" id="userId" value="">
                    <div class="mb-3">
                        <label for="userStatus" class="form-label">Status</label>
                        <select class="form-select" id="userStatus" required>
                            <option value="" disabled selected>Select status</option>
                                <?php
                                    $query = "SELECT * FROM user_status;";
                                    $stmt = $pdo->prepare($query);
                                    $stmt->execute();
                                    $user_status = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($user_status as $status) {
                                        echo "<option value='{$status['user_status_id']}' style='text-transform: capitalize;'>" . ucfirst($status['status']) . "</option>";
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
                Are you sure you want to delete this user?
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
            const userId = this.getAttribute('data-user-id');
            const userStatus = this.getAttribute('data-user-status');

            // Set the current status in the modal
            const statusSelect = document.getElementById('userStatus');
            statusSelect.value = userStatus;

            const userSelectedId = document.getElementById('userId');
            userSelectedId.value = userId;
        });
    });

    // Event listener for the Save changes button
    document.getElementById('saveChanges').addEventListener('click', function() {
        const status = document.getElementById('userStatus').value;
        const id = document.getElementById('userId').value;
        
        $.ajax({
            url: '../database/update-user-status.php',
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
        const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
        modal.hide();
    });

    $(document).ready(function() {
        let bookIdToDelete;
        $('.delete-user').on('click', function() {
            bookIdToDelete = $(this).data('user-id');
            $('#deleteConfirmationModal').modal('show');
        });

        $('#confirmDelete').on('click', function() {
            $.ajax({
                url: '../database/delete-user-db.php',
                type: 'POST',
                data: { id: bookIdToDelete },
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