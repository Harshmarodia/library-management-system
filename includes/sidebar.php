<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';
$name = $_SESSION['name'] ?? 'User';
?>
<aside class="sidebar" style="width: 280px; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.1); display: flex; flex-direction: column; overflow-y: auto;">
    <div style="padding: 40px 30px; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
        <div style="font-size: 26px; font-weight: 800; color: white; margin-bottom: 10px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 45px; height: 45px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fas fa-book-reader"></i>
            </div>
            <span>LibX</span>
        </div>
        <div style="font-size: 14px; color: var(--text-muted); font-weight: 500;">
            Welcome back, <span style="color: white; font-weight: 600;"><?= htmlspecialchars($name) ?></span>
        </div>
    </div>
    
    <nav style="flex: 1; padding: 30px 0;">
        <div style="padding: 0 30px 15px; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 1.5px;">Main Menu</div>
        
        <?php if($role == 'admin'): ?>
            <a href="admin_dashboard.php" class="sidebar-link <?= ($current_page == 'admin_dashboard.php') ? 'active' : '' ?>">
                <i class="fas fa-grid-2"></i> Dashboard
            </a>
            <a href="manage_books.php" class="sidebar-link <?= ($current_page == 'manage_books.php') ? 'active' : '' ?>">
                <i class="fas fa-books"></i> Manage Books
            </a>
            <a href="rental_reports.php" class="sidebar-link <?= ($current_page == 'rental_reports.php') ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Rental Reports
            </a>
        <?php else: ?>
            <a href="user_dashboard.php" class="sidebar-link <?= ($current_page == 'user_dashboard.php') ? 'active' : '' ?>">
                <i class="fas fa-house-chimney"></i> Home
            </a>
            <a href="browse_books.php" class="sidebar-link <?= ($current_page == 'browse_books.php') ? 'active' : '' ?>">
                <i class="fas fa-magnifying-glass"></i> Browse Books
            </a>
            <a href="my_rentals.php" class="sidebar-link <?= ($current_page == 'my_rentals.php') ? 'active' : '' ?>">
                <i class="fas fa-book-open"></i> My Rentals
            </a>
        <?php endif; ?>
    </nav>
    
    <div style="padding: 30px; border-top: 1px solid rgba(255, 255, 255, 0.05); background: rgba(0,0,0,0.2);">
        <a href="logout.php" class="btn" style="width: 100%; justify-content: center; background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.2); border-radius: 12px;">
            <i class="fas fa-power-off"></i> Logout
        </a>
    </div>
</aside>
