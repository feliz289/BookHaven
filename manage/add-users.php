<?php
    session_start();
    if(!isset($_SESSION["admin_librarian_logged_in"])) {
        header("Location: ../index.php");
    }
    $_SESSION['table_name'] = 'users';
    $_SESSION['redirect_to'] = 'add-users.php';
    
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
                    <h5>Add New User</h5>
                    <div class="row justify-content-center">
                        <div class="col-12 mt-3 px-5" style="width: 100%;">
                            <form action="../database/add-user-db.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="user_name" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="user_name" name="user_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="">Phone Number</label>
                                    <input type="tel" pattern="0\d{10}" name="user_phone_num" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <select class="form-select text-capitalize" id="type" name="type" required>
                                        <option value="" disabled selected>User Type</option>
                                        <?php
                                            $query = "SELECT * FROM user_type WHERE NOT type = 'student';";
                                            $stmt = $pdo->prepare($query);
                                            $stmt->execute();
                                            $user_type = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($user_type as $type) {
                                                echo "<option class='text-capitalize' value='{$type['user_type_id']}'>{$type['type']}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select class="form-select text-capitalize" id="status" name="status" required>
                                        <option value="" disabled selected>User Status</option>
                                        <?php
                                            $query = "SELECT * FROM user_status;";
                                            $stmt = $pdo->prepare($query);
                                            $stmt->execute();
                                            $user_status = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($user_status as $status) {
                                                echo "<option class='text-capitalize' value='{$status['user_status_id']}'>{$status['status']}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <?php if (isset($_SESSION["error_adding_user"])): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo htmlspecialchars($_SESSION["error_adding_user"]); ?>
                                        </div>
                                    <?php unset($_SESSION["error_adding_user"]); endif; ?>
                                </div>
                                <div class="mb-3">
                                    <?php if (isset($_SESSION["successfull_adding_user"])): ?>
                                        <div class="alert alert-success" role="alert">
                                            <?php echo htmlspecialchars($_SESSION["successfull_adding_user"]); ?>
                                        </div>
                                    <?php unset($_SESSION["successfull_adding_user"]); endif; ?>
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