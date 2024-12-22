-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 21, 2024 at 10:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookhaven`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `book_image` varchar(100) NOT NULL,
  `book_category` int(11) NOT NULL,
  `book_author` int(11) NOT NULL,
  `book_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `book_name`, `book_image`, `book_category`, `book_author`, `book_status`) VALUES
(64, 'To Kill a Mockingbird', 'product-1734400221.jpg', 6, 9, 1),
(65, '1984', 'product-1734400723.jpg', 6, 10, 1),
(66, 'The Great Gatsby', 'product-1734400835.jpg', 6, 11, 1),
(67, 'Pride and Prejudice', 'product-1734400956.jpg', 6, 12, 1),
(68, 'Sapiens: A Brief History of Humankind', 'product-1734401534.jpg', 7, 13, 1),
(69, 'Educated', 'product-1734401726.jpg', 7, 14, 1),
(70, 'The Immortal Life of Henrietta Lacks', 'product-1734401819.jpg', 7, 15, 1),
(71, 'Becoming', 'product-1734401874.jpg', 7, 16, 1),
(72, 'Dune', 'product-1734401981.jpg', 8, 17, 1),
(73, 'Ender\'s Game', 'product-1734402034.jpg', 8, 18, 1),
(74, 'Neuromancer ', 'product-1734402101.jpg', 8, 19, 1),
(75, 'The Left Hand of Darkness', 'product-1734402185.png', 8, 20, 1),
(76, 'Harry Potter and the Sorcerer\'s Stone', 'product-1734402254.jpg', 9, 21, 1),
(77, 'The Hobbit', 'product-1734402318.jpg', 9, 22, 1),
(78, 'A Song of Ice and Fire', 'product-1734402409.jpg', 9, 23, 1),
(79, 'The Name of the Wind', 'product-1734402483.jpg', 9, 24, 1),
(80, 'The Girl with the Dragon Tattoo', 'product-1734402577.jpg', 10, 25, 1),
(81, 'Gone Girl', 'product-1734402629.jpg', 10, 26, 1),
(82, 'The Da Vinci Code', 'product-1734402731.jpg', 10, 27, 1),
(83, 'Big Little Lies', 'product-1734402794.jpg', 10, 28, 1),
(84, 'The Girl On the Train', 'product-1734404312.jpg', 10, 29, 1),
(85, 'The Shining', 'product-1734418932.jpg', 13, 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_authors`
--

CREATE TABLE `book_authors` (
  `book_authors_id` int(11) NOT NULL,
  `author_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_authors`
--

INSERT INTO `book_authors` (`book_authors_id`, `author_name`) VALUES
(9, 'Harper Lee'),
(10, 'George Orwell'),
(11, 'F. Scott Fitzgerald'),
(12, 'Jane Austen'),
(13, 'Yuval Noah Harari'),
(14, 'Tara Westover'),
(15, 'Rebecca Skloot'),
(16, 'Michelle Obama'),
(17, 'Frank Herbert'),
(18, 'Orson Scott Card'),
(19, 'William Gibson'),
(20, 'Ursula K. Le Guin'),
(21, 'J.K. Rowling'),
(22, 'J.R.R. Tolkien'),
(23, 'George R.R. Martin'),
(24, 'Patrick Rothfuss'),
(25, 'Stieg Larsson'),
(26, 'Gillian Flynn'),
(27, 'Dan Brown'),
(28, 'Liane Moriarty'),
(29, 'Paula Hawkins'),
(30, 'Stephen King');

-- --------------------------------------------------------

--
-- Table structure for table `book_categories`
--

CREATE TABLE `book_categories` (
  `book_categories_id` int(11) NOT NULL,
  `book_categories_name` varchar(50) NOT NULL,
  `book_categories_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_categories`
--

INSERT INTO `book_categories` (`book_categories_id`, `book_categories_name`, `book_categories_status`) VALUES
(6, 'Fiction', 1),
(7, 'Non-Fiction', 1),
(8, 'Science Fiction', 1),
(9, 'Fantasy', 1),
(10, 'Mystery/Thriller', 1),
(11, 'Romance', 1),
(13, 'Horror', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_categories_status`
--

CREATE TABLE `book_categories_status` (
  `book_categories_status_id` int(11) NOT NULL,
  `book_categories_status_name` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_categories_status`
--

INSERT INTO `book_categories_status` (`book_categories_status_id`, `book_categories_status_name`) VALUES
(1, 'active'),
(2, 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `book_status`
--

CREATE TABLE `book_status` (
  `book_status_id` int(11) NOT NULL,
  `status` enum('available','borrowed','unavailable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_status`
--

INSERT INTO `book_status` (`book_status_id`, `status`) VALUES
(1, 'available'),
(2, 'borrowed'),
(3, 'unavailable');

-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

CREATE TABLE `issued_books` (
  `issued_books_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `issue_date` datetime NOT NULL DEFAULT current_timestamp(),
  `return_date` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued_books`
--

INSERT INTO `issued_books` (`issued_books_id`, `book_id`, `user_id`, `issue_date`, `return_date`, `status`) VALUES
(76, 71, 34, '2024-12-17 06:23:52', '2024-12-31 06:23:52', 3),
(78, 65, 44, '2024-12-17 07:44:43', '2024-12-31 07:44:43', 3),
(79, 78, 44, '2024-12-17 08:08:59', '2024-12-31 08:08:59', 4),
(80, 71, 44, '2024-12-17 08:11:55', '2024-12-31 08:11:55', 4),
(81, 71, 44, '2024-12-17 08:14:47', '2024-12-31 08:14:47', 3),
(82, 78, 44, '2024-12-17 08:29:52', '2024-12-31 08:29:52', 3);

-- --------------------------------------------------------

--
-- Table structure for table `issued_books_status`
--

CREATE TABLE `issued_books_status` (
  `issued_books_status_id` int(11) NOT NULL,
  `status` enum('pending','issued','returned','cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued_books_status`
--

INSERT INTO `issued_books_status` (`issued_books_status_id`, `status`) VALUES
(1, 'pending'),
(2, 'issued'),
(3, 'returned'),
(4, 'cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `user_status` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_phone_num` char(11) NOT NULL,
  `user_pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_type`, `user_status`, `student_id`, `user_name`, `user_email`, `user_phone_num`, `user_pass`) VALUES
(23, 20, 1, NULL, 'admin', 'admin@gmail.com', '09090909090', '$2y$12$xfyQlFk52Ay/uCj5h4gY..GQZxFQXtPQ3OMaVUDWeT98xuJGUitby'),
(32, 22, 1, NULL, 'librarian', 'librarian@gmail.com', '09111111112', '$2y$12$x1z5.fRvpv3fitk3mrueiu5xpTekjvlP2j5YwRYgFXhj4SrkOskYm'),
(34, 21, 1, 827821, 'felipa', 'felipa.orain@gmail.com', '09000000000', '$2y$12$cZkPC.2uysnkEQYchf2tzOc0L.jgG9wgchK5hqHupi11cn1WbIE3O'),
(36, 20, 1, NULL, 'admin3', 'admin3@gmail.com', '09000000002', '$2y$12$PeELmmBX9AQ5mgH4jIgmC.t4v/mlxhUZXiPr7zS0NrFsyx4nkeVxi'),
(39, 20, 1, NULL, 'admin2', 'admin2@gmail.com', '09000000000', '$2y$12$9xnC/EmqxajSxbDJYae2vepYs2lmUCWjYnmJMtmZNmlWpAWTuUj6C'),
(40, 21, 1, 895634, 'kenneth', 'kenneth@bisu.edu.ph', '09997865432', '$2y$12$iCSrnBW3C/QGooWKcixDpOOR8nOEhLC5DdZKGtJrvvgqM3G66cG02'),
(41, 20, 1, NULL, 'Admin4', 'admin4@gmail.com', '09098765434', '$2y$12$2AjUY1uzqBEyIqcxFxU6MOmhcXycCgtibN8oVP/9pWh/QPcL3LmQi'),
(44, 21, 1, 909505, 'jerson', 'jersonlaroya@gmail.com', '09102991629', '$2y$12$ceVzGO41uot5Y/p2cDkW8e3q2mvv6lTmBCd160ddxe580rSVsKJne');

-- --------------------------------------------------------

--
-- Table structure for table `user_status`
--

CREATE TABLE `user_status` (
  `user_status_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_status`
--

INSERT INTO `user_status` (`user_status_id`, `status`) VALUES
(1, 'active'),
(2, 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `user_type_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`user_type_id`, `type`) VALUES
(20, 'admin'),
(21, 'student'),
(22, 'librarian');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `books_book_authors_fk` (`book_author`),
  ADD KEY `books_book_categories_fk` (`book_category`),
  ADD KEY `books_book_status_fk` (`book_status`);

--
-- Indexes for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD PRIMARY KEY (`book_authors_id`);

--
-- Indexes for table `book_categories`
--
ALTER TABLE `book_categories`
  ADD PRIMARY KEY (`book_categories_id`),
  ADD KEY `book_categories_status_fk` (`book_categories_status`);

--
-- Indexes for table `book_categories_status`
--
ALTER TABLE `book_categories_status`
  ADD PRIMARY KEY (`book_categories_status_id`);

--
-- Indexes for table `book_status`
--
ALTER TABLE `book_status`
  ADD PRIMARY KEY (`book_status_id`);

--
-- Indexes for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`issued_books_id`),
  ADD KEY `issued_books_book_id` (`book_id`),
  ADD KEY `issued_books_user_id` (`user_id`),
  ADD KEY `issued_books_status_fk` (`status`);

--
-- Indexes for table `issued_books_status`
--
ALTER TABLE `issued_books_status`
  ADD PRIMARY KEY (`issued_books_status_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `users_user_type_fk` (`user_type`),
  ADD KEY `users_user_status_fk` (`user_status`);

--
-- Indexes for table `user_status`
--
ALTER TABLE `user_status`
  ADD PRIMARY KEY (`user_status_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `book_authors`
--
ALTER TABLE `book_authors`
  MODIFY `book_authors_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `book_categories`
--
ALTER TABLE `book_categories`
  MODIFY `book_categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `book_categories_status`
--
ALTER TABLE `book_categories_status`
  MODIFY `book_categories_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `book_status`
--
ALTER TABLE `book_status`
  MODIFY `book_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `issued_books`
--
ALTER TABLE `issued_books`
  MODIFY `issued_books_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `issued_books_status`
--
ALTER TABLE `issued_books_status`
  MODIFY `issued_books_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `user_status`
--
ALTER TABLE `user_status`
  MODIFY `user_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_book_authors_fk` FOREIGN KEY (`book_author`) REFERENCES `book_authors` (`book_authors_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `books_book_categories_fk` FOREIGN KEY (`book_category`) REFERENCES `book_categories` (`book_categories_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `books_book_status_fk` FOREIGN KEY (`book_status`) REFERENCES `book_status` (`book_status_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `book_categories`
--
ALTER TABLE `book_categories`
  ADD CONSTRAINT `book_categories_status_fk` FOREIGN KEY (`book_categories_status`) REFERENCES `book_categories_status` (`book_categories_status_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD CONSTRAINT `issued_books_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issued_books_status_fk` FOREIGN KEY (`status`) REFERENCES `issued_books_status` (`issued_books_status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issued_books_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_user_status_fk` FOREIGN KEY (`user_status`) REFERENCES `user_status` (`user_status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_user_type_fk` FOREIGN KEY (`user_type`) REFERENCES `user_type` (`user_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
