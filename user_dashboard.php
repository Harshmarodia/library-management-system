<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['name'];

// Fetch stats for the specific user
$active_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE user_id = $user_id AND status = 'rented'")->fetch_assoc()['total'] ?? 0;
$total_borrowed = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0;

// Recent my rentals
$my_recent = $conn->query("
    SELECT rentals.*, books.title, books.author 
    FROM rentals 
    JOIN books ON rentals.book_id = books.id 
    WHERE rentals.user_id = $user_id 
    ORDER BY rentals.id DESC LIMIT 3
");
?>

<?php include 'includes/header.php'; ?>

<div class="main-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content-area">
        <h2 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Welcome, <?= htmlspecialchars($username) ?> ✨</h2>
        <p style="color: #eee; margin-bottom: 30px;">"A reader lives a thousand lives before he dies." - George R.R. Martin</p>

        <!-- Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px;">
            <div class="glass-card" style="display: flex; align-items: center; border-bottom: 5px solid var(--primary-color);">
                <div style="font-size: 40px; color: var(--primary-color); margin-right: 25px;"><i class="fas fa-book-reader"></i></div>
                <div>
                    <div style="font-size: 14px; color: #666;">Currently Reading</div>
                    <div style="font-size: 32px; font-weight: 700;"><?= $active_rentals ?></div>
                </div>
            </div>
            
            <div class="glass-card" style="display: flex; align-items: center; border-bottom: 5px solid var(--success-color);">
                <div style="font-size: 40px; color: var(--success-color); margin-right: 25px;"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div style="font-size: 14px; color: #666;">Total Borrowed</div>
                    <div style="font-size: 32px; font-weight: 700;"><?= $total_borrowed ?></div>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- My Current Books -->
            <div class="glass-card">
                <h3><i class="fas fa-bookmark"></i> My Recent Activity</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Rent Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $my_recent->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 500;"><?= $row['title'] ?></div>
                                <div style="font-size: 12px; color: #888;">by <?= $row['author'] ?></div>
                            </td>
                            <td><?= $row['rent_date'] ?></td>
                            <td>
                                <span class="badge badge-<?= $row['status'] == 'rented' ? 'danger' : 'success' ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if($my_recent->num_rows == 0): ?>
                            <tr><td colspan="3" style="text-align: center; color: #999; padding: 30px;">You haven't rented any books yet. Explore the library!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <a href="my_rentals.php" class="btn btn-primary" style="margin-top: 20px;">View Full History</a>
            </div>

            <!-- Quick Actions -->
            <div class="glass-card" style="background: rgba(0, 123, 255, 0.05);">
                <h3><i class="fas fa-magic"></i> Quick Actions</h3>
                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                    <a href="browse_books.php" class="btn btn-success" style="text-align: center;">
                        <i class="fas fa-search"></i> Rent a New Book
                    </a>
                    <a href="my_rentals.php" class="btn btn-warning" style="text-align: center; color: #333;">
                        <i class="fas fa-undo"></i> Return a Book
                    </a>
                    <a href="#" class="btn btn-info" style="text-align: center; color: white;">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
