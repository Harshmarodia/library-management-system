<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Stats for user
$total_rented = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0;
$currently_holding = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE user_id = $user_id AND status = 'rented'")->fetch_assoc()['total'] ?? 0;
$available_books = $conn->query("SELECT COUNT(*) as total FROM books WHERE quantity > 0")->fetch_assoc()['total'] ?? 0;

// Current rentals
$my_rentals = $conn->query("
    SELECT rentals.*, books.title, books.author 
    FROM rentals 
    JOIN books ON rentals.book_id = books.id 
    WHERE rentals.user_id = $user_id AND rentals.status = 'rented'
    ORDER BY rentals.id DESC LIMIT 5
");
?>

<?php include 'includes/header.php'; ?>

<div class="main-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content-area">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 5px;">Member Workspace</h1>
                <p style="color: var(--text-muted); font-weight: 500;">Manage your reading list and discover new books</p>
            </div>
            <a href="browse_books.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Rent New Book
            </a>
        </div>

        <!-- Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; margin-bottom: 40px;">
            <div class="glass-card" style="border-left: 4px solid #3498db;">
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Total Borrowed</div>
                <div style="font-size: 36px; font-weight: 800; line-height: 1;"><?= $total_rented ?></div>
                <div style="margin-top: 15px; font-size: 13px; color: var(--text-muted);">Books you've enjoyed</div>
            </div>
            
            <div class="glass-card" style="border-left: 4px solid #e74c3c;">
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Holding Now</div>
                <div style="font-size: 36px; font-weight: 800; line-height: 1;"><?= $currently_holding ?></div>
                <div style="margin-top: 15px; font-size: 13px; color: var(--text-muted);">Return within 14 days</div>
            </div>

            <div class="glass-card" style="border-left: 4px solid #2ecc71;">
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Catalog Size</div>
                <div style="font-size: 36px; font-weight: 800; line-height: 1;"><?= $available_books ?></div>
                <div style="margin-top: 15px; font-size: 13px; color: var(--text-muted);">Unique titles available</div>
            </div>
        </div>

        <!-- Current Rentals Section -->
        <div class="glass-card" style="padding: 0;">
            <div style="padding: 30px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-book-reader" style="color: var(--primary-color);"></i> My Active Books
                </h3>
            </div>
            <div class="table-container" style="padding: 20px 30px 30px;">
                <table>
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Rental Date</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $my_rentals->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 600; color: white;"><?= $row['title'] ?></td>
                            <td style="color: var(--text-muted);"><?= $row['author'] ?></td>
                            <td style="font-size: 14px; color: var(--text-muted);">
                                <i class="far fa-clock" style="margin-right: 6px;"></i>
                                <?= date('M d, Y', strtotime($row['rent_date'])) ?>
                            </td>
                            <td style="text-align: right;">
                                <a href="actions/return_action.php?id=<?= $row['id'] ?>&book_id=<?= $row['book_id'] ?>" 
                                   class="btn" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71; padding: 8px 16px; font-size: 13px; border: 1px solid rgba(46, 204, 113, 0.2);">
                                    Return Now
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if($my_rentals->num_rows == 0): ?>
                            <tr><td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <div style="font-size: 40px; margin-bottom: 10px; opacity: 0.2;"><i class="fas fa-ghost"></i></div>
                                No active rentals found. Why not pick something to read?
                            </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
