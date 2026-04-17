<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';
$name = $_SESSION['name'] ?? 'User';
?>
<aside class="sidebar" style="width: 260px; background: rgba(0,0,0,0.4); backdrop-filter: blur(15px); color: white; display: flex; flex-direction: column; transition: all 0.3s; border-right: 1px solid rgba(255,255,255,0.1);">
    <div style="padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div style="font-size: 24px; font-weight: bold; margin-bottom: 15px;">
            <i class="fas fa-book-reader"></i> BookRental
        </div>
        <div style="font-size: 14px; color: #ffc107; font-weight: 500;">
            👋 Hello, <?= htmlspecialchars($name) ?>
        </div>
    </div>
    
    <nav style="flex: 1; padding: 20px 0;">
        <ul style="list-style: none;">
            <?php if($role == 'admin'): ?>
                <!-- Admin Menu -->
                <li style="margin-bottom: 5px;">
                    <a href="admin_dashboard.php" style="display: flex; align-items: center; padding: 15px 25px; color: <?= ($current_page == 'admin_dashboard.php') ? '#fff' : '#ccc' ?>; text-decoration: none; transition: 0.3s; background: <?= ($current_page == 'admin_dashboard.php') ? 'rgba(255,255,255,0.1)' : 'transparent' ?>;">
                        <i class="fas fa-th-large" style="width: 25px;"></i> Dashboard
                    </a>
                </li>
                <li style="margin-bottom: 5px;">
                    <a href="manage_books.php" style="display: flex; align-items: center; padding: 15px 25px; color: <?= ($current_page == 'manage_books.php') ? '#fff' : '#ccc' ?>; text-decoration: none; transition: 0.3s; background: <?= ($current_page == 'manage_books.php') ? 'rgba(255,255,255,0.1)' : 'transparent' ?>;">
                        <i class="fas fa-book" style="width: 25px;"></i> Manage Books
                    </a>
                </li>
                <li style="margin-bottom: 5px;">
                    <a href="rental_reports.php" style="display: flex; align-items: center; padding: 15px 25px; color: <?= ($current_page == 'rental_reports.php') ? '#fff' : '#ccc' ?>; text-decoration: none; transition: 0.3s; background: <?= ($current_page == 'rental_reports.php') ? 'rgba(255,255,255,0.1)' : 'transparent' ?>;">
                        <i class="fas fa-history" style="width: 25px;"></i> Rental Reports
                    </a>
                </li>
            <?php else: ?>
                <!-- User Menu -->
                <li style="margin-bottom: 5px;">
                    <a href="user_dashboard.php" style="display: flex; align-items: center; padding: 15px 25px; color: <?= ($current_page == 'user_dashboard.php') ? '#fff' : '#ccc' ?>; text-decoration: none; transition: 0.3s; background: <?= ($current_page == 'user_dashboard.php') ? 'rgba(255,255,255,0.1)' : 'transparent' ?>;">
                        <i class="fas fa-home" style="width: 25px;"></i> Home
                    </a>
                </li>
                <li style="margin-bottom: 5px;">
                    <a href="browse_books.php" style="display: flex; align-items: center; padding: 15px 25px; color: <?= ($current_page == 'browse_books.php') ? '#fff' : '#ccc' ?>; text-decoration: none; transition: 0.3s; background: <?= ($current_page == 'browse_books.php') ? 'rgba(255,255,255,0.1)' : 'transparent' ?>;">
                        <i class="fas fa-search" style="width: 25px;"></i> Browse Books
                    </a>
                </li>
                <li style="margin-bottom: 5px;">
                    <a href="my_rentals.php" style="display: flex; align-items: center; padding: 15px 25px; color: <?= ($current_page == 'my_rentals.php') ? '#fff' : '#ccc' ?>; text-decoration: none; transition: 0.3s; background: <?= ($current_page == 'my_rentals.php') ? 'rgba(255,255,255,0.1)' : 'transparent' ?>;">
                        <i class="fas fa-book-open" style="width: 25px;"></i> My Rentals
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
        <a href="logout.php" style="display: flex; align-items: center; padding: 12px 20px; background: rgba(220, 53, 69, 0.2); color: #ff6b6b; text-decoration: none; border-radius: 10px; transition: 0.3s;">
            <i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i> Logout
        </a>
    </div>
</aside>

<style>
    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        transform: translateX(5px);
    }
</style>
