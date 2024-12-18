<?php
    require_once "./database/dbh.php";
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
    <link rel="stylesheet" href="./css/styles.css">

<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="manifest" href="favicon_io/site.webmanifest">
</head>
<body>
    
<!-- header section starts  -->
<!-- header section starts  -->

<header class="header">

    <div class="header-1">

        <a href="./index.php" class="logo"> <i class="fa-solid fa-book"></i> BookHaven </a>

        <form action="" class="search-form">
            
        </form>

        <div class="icons">
            <div class="log-in-sign-up-div">
                <a href="./pages/login.php" id="login-btn">Log in</a>
                <a href="./pages/signup.php" id="signup-btn">Sign up</a>
            </div>
        </div>

    </div>

    <div class="header-2">
        <nav class="navbar">
            <a href="./index.php">home</a>
            <a href="#featured">featured</a>
            <a href="#learn-more">learn more</a>
            <!-- <a href="#reviews">reviews</a>
            <a href="./feedback.html">feedback</a> -->
            <a href="#footer">about us</a>
        </nav>
    </div>

</header>

<div class="login-form-container">

    <div id="close-login-btn" class="fas fa-times"></div>

    <form action="">
        <h3>log in</h3>
        <span>username</span>
        <input type="email" name="" class="box" placeholder="enter your email" id="">
        <span>password</span>
        <input type="password" name="" class="box" placeholder="enter your password" id="">
        <div class="checkbox">
            <input type="checkbox" name="" id="remember-me">
            <label for="remember-me"> remember me</label>
        </div>
        <input type="submit" value="sign in" class="btn">
        <p>don't have an account ? <a id="open-signup-form-btn">create one</a></p>
    </form>

</div>

<section class="home" id="home">

    <div class="row">

        <div class="content">
            <h3>Welcome to BookHaven</h3>
            <p>Your Digital Library at Your Fingertips
                Easily manage your borrowed books, explore new arrivals, and access a world of resources.</p>
            <a href="./pages/login.php" id="login-btn-2" class="btn">Let's get started</a>
        </div>

        <div class="swiper books-slider">
            <div class="swiper-wrapper">
                <?php
                        $query = "SELECT * FROM books;";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute();
                        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($books as $book) {
                            echo "<a href='#' class='swiper-slide'><img src='./uploads/books/". $book['book_image'] ."' alt='Book Image'></a>";
                        }
                    ?>
                <!-- <a href="#" class="swiper-slide"><img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/book-1.png" alt=""></a>
                <a href="#" class="swiper-slide"><img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/book-2.png" alt=""></a>
                <a href="#" class="swiper-slide"><img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/book-3.png" alt=""></a>
                <a href="#" class="swiper-slide"><img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/book-4.png" alt=""></a>
                <a href="#" class="swiper-slide"><img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/book-5.png" alt=""></a>
                <a href="#" class="swiper-slide"><img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/book-6.png" alt=""></a> -->
            </div>
            <img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/stand.png" class="stand" alt="">
        </div>

    </div>

</section>

<section class="icons-container">

    <div class="icons">
        <i class="fa-solid fa-bookmark"></i>
        <div class="content">
            <h3>Easy book reservations and renewals</h3>
        </div>
    </div>

    <div class="icons">
        <i class="fas fa-lock"></i>
        <div class="content">
            <h3>Online account management for borrowers</h3>
        </div>
    </div>

    <div class="icons">
        <i class="fa-solid fa-file-lines"></i>
        <div class="content">
            <h3>Access to e-books, journals, and multimedia</h3>
        </div>
    </div>

    <div class="icons">
        <i class="fas fa-headset"></i>
        <div class="content">
            <h3>Stay updated with library news and events</h3>
        </div>
    </div>

</section>

<!-- icons section ends -->

<!-- featured section starts  -->

<section class="featured" id="featured">

    <h1 class="heading"> <span>featured books</span> </h1>

    <div class="swiper featured-slider">

        <div class="swiper-wrapper">

            <!-- <div class="swiper-slide box">
                <div class="image">
                   <a href="./product.html"> <img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/book-1.png" alt=""> </a>
                </div>
            </div> -->

            <?php
                $query = "SELECT * FROM books;";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($books as $book) {
                    echo "<div class='swiper-slide box'>
                    <div class='image'>
                    <a href='#'><img src='./uploads/books/". $book['book_image'] ."' alt='Book Image'></a>
                    </div>
                    </div>";
                }
            ?>

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

    </div>

</section>

<!-- featured section ends -->


<!-- deal section starts  -->

<section class="deal" id="learn-more">
    <div class="content">
        <h1>Learn How to Borrow & Return Books</h1>
        <p>Borrowing books from BookHaven is easy! Learn more about our borrowing process, including how to place holds, renew items, and return books.</p>
        <a href="./pages/learn_more.php" class="btn">Learn More</a>
    </div>

    <div class="image">
        <img src="https://raw.githubusercontent.com/KordePriyanka/Books4MU-Book-Store-Website-/main/image/deal-img.jpg" alt="">
    </div>

</section>

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
            <a href="./index.html"> <i class="fas fa-arrow-right"></i> home </a>
            <a href="#featured"> <i class="fas fa-arrow-right"></i> featured </a>
            <a href="#reviews"> <i class="fas fa-arrow-right"></i> reviews </a>
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

<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="./js/script.js"></script>

</body>
</html>