<aside id="sidebar" class="js-sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="../pages/dashboard.php" class="logo"> <i class="fa-solid fa-book"></i> BookHaven </a>
                </div>
                <ul class="sidebar-nav">
                    <!-- <li class="sidebar-header">
                        Admin Elements
                    </li> -->
                    <li class="sidebar-item">
                        <a href="../pages/dashboard.php" class="sidebar-link">
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
                        <a href="../pages/book-issue-requests.php" class="sidebar-link">
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