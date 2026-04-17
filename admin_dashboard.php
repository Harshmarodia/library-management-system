<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch stats
$total_books = $conn->query("SELECT SUM(quantity) as total FROM books")->fetch_assoc()['total'] ?? 0;
$total_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'")->fetch_assoc()['total'] ?? 0;
$active_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'rented'")->fetch_assoc()['total'] ?? 0;
$returned_today = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'returned' AND return_date = CURDATE()")->fetch_assoc()['total'] ?? 0;

// Recent Activity
$recent_rentals = $conn->query("
    SELECT rentals.*, users.name as user_name, books.title as book_title 
    FROM rentals 
    JOIN users ON rentals.user_id = users.id 
    JOIN books ON rentals.book_id = books.id 
    ORDER BY rentals.id DESC LIMIT 5
");
?>

<?php include 'includes/header.php'; ?>

<div class="main-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content-area">
        <h2 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Admin Overview</h2>

        <!-- Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="glass-card" style="display: flex; align-items: center; border-left: 5px solid var(--primary-color);">
                <div style="font-size: 30px; color: var(--primary-color); margin-right: 20px;"><i class="fas fa-book"></i></div>
                <div>
                    <div style="font-size: 14px; color: #666;">Total Stock</div>
                    <div style="font-size: 24px; font-weight: 700;"><?= $total_books ?></div>
                </div>
            </div>
            
            <div class="glass-card" style="display: flex; align-items: center; border-left: 5px solid var(--success-color);">
                <div style="font-size: 30px; color: var(--success-color); margin-right: 20px;"><i class="fas fa-users"></i></div>
                <div>
                    <div style="font-size: 14px; color: #666;">Total Members</div>
                    <div style="font-size: 24px; font-weight: 700;"><?= $total_users ?></div>
                </div>
            </div>

            <div class="glass-card" style="display: flex; align-items: center; border-left: 5px solid var(--warning-color);">
                <div style="font-size: 30px; color: var(--warning-color); margin-right: 20px;"><i class="fas fa-hand-holding"></i></div>
                <div>
                    <div style="font-size: 14px; color: #666;">Active Rentals</div>
                    <div style="font-size: 24px; font-weight: 700;"><?= $active_rentals ?></div>
                </div>
            </div>

            <div class="glass-card" style="display: flex; align-items: center; border-left: 5px solid var(--info-color);">
                <div style="font-size: 30px; color: var(--info-color); margin-right: 20px;"><i class="fas fa-undo"></i></div>
                <div>
                    <div style="font-size: 14px; color: #666;">Returned Today</div>
                    <div style="font-size: 24px; font-weight: 700;"><?= $returned_today ?></div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="glass-card">
            <h3><i class="fas fa-clock"></i> Recent Activity</h3>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Book</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $recent_rentals->fetch_assoc()): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= $row['user_name'] ?></td>
                        <td><?= $row['book_title'] ?></td>
                        <td style="font-size: 14px; color: #666;"><?= $row['rent_date'] ?></td>
                        <td>
                            <span class="badge badge-<?= $row['status'] == 'rented' ? 'danger' : 'success' ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if($recent_rentals->num_rows == 0): ?>
                        <tr><td colspan="4" style="text-align: center; color: #999;">No recent activity found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
