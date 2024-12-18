<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHaven</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login-signup.css">

    <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
    <link rel="manifest" href="favicon_io/site.webmanifest">

</head>
<body>
    
<!-- header section starts  -->
<!-- header section starts  -->

<header class="header">

    <div class="header-1">

        <a href="../index.php" class="logo"> <i class="fa-solid fa-book"></i> BookHaven </a>

        <form action="" class="search-form">
            
        </form>

        <div class="icons">
            <div class="log-in-sign-up-div">
                <a href="./login.php" id="login-btn">Log in</a>
                <a href="./signup.php" id="signup-btn">Sign up</a>
            </div>
        </div>

    </div>

    <div class="header-2">
        <nav class="navbar">
            <a href="../index.php">home</a>
            <a href="../index.php">featured</a>
            <a href="../index.php">reviews</a>
            <a href="./feedback.html">feedback</a>
            <a href="../index.php">about us</a>
        </nav>
    </div>

</header>

<!-- header section ends -->

<section>
        <div class="successful_message_container">
            <?php
                if (isset($_SESSION["signup_successful"])) {
                    $success =  $_SESSION["signup_successful"];
            
                    echo '<p class="successful_message">' . $success . '</p>';  
            
                    unset($_SESSION["signup_successful"]);
                }
            ?>
        </div>
        <div class="container">
        <form action="../auth/signup.auth.php" method="POST">
            <h1>Sign up</h1>
            <div class="form-group">
                <label for="">User name</label>
                <input type="text" name="user_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Student ID</label>
                <input type="text" name="student_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input  type="email" name="user_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Phone Number</label>
                <input type="tel" pattern="0\d{10}" name="user_phone_num" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" name="user_pass" class="form-control" required>
            </div>
            <div class="error_message_container">
                <?php
                    if (isset($_SESSION["signup_error"])) {
                        $error =  $_SESSION["signup_error"];
                
                        echo '<p class="error_message">' . $error . '</p>';  
                
                        unset($_SESSION["signup_error"]);
                    }
                ?>
            </div>
            <div class="bottom-container">
                <input type="submit" class="btn" value="Signup">
                <a href="./login.php" class="nav-link">Already have an account?</a>
            </div>
        </form>
    </div>
</section>

<!-- sign up -->

<!-- sign up  -->

<section class="footer" id="footer">

    <div class="box-container">

        <div class="box">
            <h3>our location</h3>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> philippines </a>
        </div>

        <div class="box">
            <h3>available time</h3>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> open: 8:00 AM - 5:00 PM </a>
        </div>

        <div class="box">
            <h3>quick links</h3>
            <a href="../index.php"> <i class="fas fa-arrow-right"></i> home </a>
            <a href="../index.php"> <i class="fas fa-arrow-right"></i> featured </a>
            <a href="../index.php"> <i class="fas fa-arrow-right"></i> reviews </a>
            <a href="./feedback.html"> <i class="fas fa-arrow-right"></i> feedback </a>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <a href="#"> <i class="fas fa-phone"></i> 09099688950 </a>
            <a href="#"> <i class="fas fa-phone"></i> 09653814301 </a>
            <a href="#"> <i class="fas fa-envelope"></i> felizanub@gmail.com </a>
        </div>
        
    </div>

    <div class="share">
        <a href="#" class="fab fa-facebook-f"></a>
        <a href="https://twitter.com/priyankakorde" class="fab fa-twitter"></a>
        <a href="https://www.instagram.com/im_priyankak_/" class="fab fa-instagram"></a>
        <a href="https://www.linkedin.com/in/priyanka-korde-2029521a1/" class="fab fa-linkedin"></a>
    </div>

    <div class="credit"> created by <span>FJRS </span>copyright ©2024 all rights reserved! </div>

</section>

<!-- footer section ends -->

<!-- loader  -->

<!-- <div class="loader-container">
    <img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/loader-img.gif" alt="">
</div> -->


<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="../js/script.js"></script>

</body>
</html>