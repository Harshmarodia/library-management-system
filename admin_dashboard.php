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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 5px;">Dashboard Overview</h1>
                <p style="color: var(--text-muted); font-weight: 500;">Real-time library insights and analytics</p>
            </div>
            <div style="display: flex; gap: 15px;">
                <div style="padding: 12px 20px; background: rgba(255,255,255,0.05); border-radius: 14px; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-calendar" style="color: var(--primary-color);"></i>
                    <span style="font-size: 14px; font-weight: 600;"><?= date('M d, Y') ?></span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; margin-bottom: 40px;">
            <div class="glass-card" style="position: relative; overflow: hidden; border-left: 4px solid #3498db;">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; color: rgba(52, 152, 219, 0.05); transform: rotate(-15deg);">
                    <i class="fas fa-books"></i>
                </div>
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Total Stock</div>
                <div style="display: flex; align-items: flex-end; gap: 12px;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;"><?= number_format($total_books) ?></div>
                    <div style="font-size: 12px; color: var(--success-color); font-weight: 700; margin-bottom: 5px;"><i class="fas fa-arrow-up"></i> +2%</div>
                </div>
            </div>
            
            <div class="glass-card" style="position: relative; overflow: hidden; border-left: 4px solid #9b59b6;">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; color: rgba(155, 89, 182, 0.05); transform: rotate(-15deg);">
                    <i class="fas fa-users"></i>
                </div>
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Active Members</div>
                <div style="display: flex; align-items: flex-end; gap: 12px;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;"><?= number_format($total_users) ?></div>
                    <div style="font-size: 12px; color: var(--primary-color); font-weight: 700; margin-bottom: 5px;"><i class="fas fa-user-plus"></i> New</div>
                </div>
            </div>

            <div class="glass-card" style="position: relative; overflow: hidden; border-left: 4px solid #f1c40f;">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; color: rgba(241, 196, 15, 0.05); transform: rotate(-15deg);">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Books Out</div>
                <div style="display: flex; align-items: flex-end; gap: 12px;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;"><?= number_format($active_rentals) ?></div>
                    <div style="font-size: 12px; color: var(--warning-color); font-weight: 700; margin-bottom: 5px;"><i class="fas fa-clock"></i> Active</div>
                </div>
            </div>

            <div class="glass-card" style="position: relative; overflow: hidden; border-left: 4px solid #2ecc71;">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; color: rgba(46, 204, 113, 0.05); transform: rotate(-15deg);">
                    <i class="fas fa-rotate-left"></i>
                </div>
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Returns Today</div>
                <div style="display: flex; align-items: flex-end; gap: 12px;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;"><?= number_format($returned_today) ?></div>
                    <div style="font-size: 12px; color: var(--success-color); font-weight: 700; margin-bottom: 5px;"><i class="fas fa-check-double"></i> Verified</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="glass-card" style="padding: 0;">
            <div style="padding: 30px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-receipt" style="color: var(--secondary-color);"></i> Recent Transactions
                </h3>
                <a href="rental_reports.php" style="font-size: 13px; font-weight: 700; color: var(--primary-color); text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">View All Reports <i class="fas fa-chevron-right" style="margin-left: 5px;"></i></a>
            </div>
            <div class="table-container" style="padding: 20px 30px 30px;">
                <table>
                    <thead>
                        <tr>
                            <th>Reader Name</th>
                            <th>Book Title</th>
                            <th>Transaction Date</th>
                            <th style="text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recent_rentals->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 32px; height: 32px; background: rgba(155, 89, 182, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; color: var(--secondary-color); font-weight: 700;">
                                        <?= strtoupper(substr($row['user_name'], 0, 1)) ?>
                                    </div>
                                    <span style="font-weight: 600;"><?= $row['user_name'] ?></span>
                                </div>
                            </td>
                            <td style="font-weight: 500; color: var(--text-muted);"><?= $row['book_title'] ?></td>
                            <td style="font-size: 14px; color: var(--text-muted);">
                                <i class="far fa-calendar-alt" style="margin-right: 6px; font-size: 12px;"></i>
                                <?= date('M d, Y', strtotime($row['rent_date'])) ?>
                            </td>
                            <td style="text-align: right;">
                                <span class="badge badge-<?= $row['status'] == 'rented' ? 'danger' : 'success' ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if($recent_rentals->num_rows == 0): ?>
                            <tr><td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">No recent activity reported.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/header.php'; ?>
