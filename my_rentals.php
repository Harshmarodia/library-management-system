<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch my rentals
$rentals = $conn->query("
    SELECT rentals.*, books.title, books.author 
    FROM rentals 
    JOIN books ON rentals.book_id = books.id 
    WHERE rentals.user_id = $user_id 
    ORDER BY rentals.status DESC, rentals.rent_date DESC
");
?>

<?php include 'includes/header.php'; ?>

<div class="main-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content-area">
        <h2 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">My Reading History</h2>

        <div class="glass-card">
            <h3><i class="fas fa-list-ul"></i> Borrowed Books</h3>
            <table>
                <thead>
                    <tr>
                        <th>Book Info</th>
                        <th>Borrowed On</th>
                        <th>Returned On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $rentals->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 500;"><?= $row['title'] ?></div>
                            <div style="font-size: 12px; color: #888;">by <?= $row['author'] ?></div>
                        </td>
                        <td><?= $row['rent_date'] ?></td>
                        <td><?= $row['return_date'] ? $row['return_date'] : '<span style="color: #aaa;">Not yet returned</span>' ?></td>
                        <td>
                            <span class="badge badge-<?= $row['status'] == 'rented' ? 'danger' : 'success' ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['status'] == 'rented'): ?>
                                <a href="actions/return_action.php?id=<?= $row['id'] ?>" 
                                   class="btn btn-warning" 
                                   style="padding: 5px 15px; font-size: 13px; color: #333;"
                                   onclick="return confirm('Do you want to return this book?')">
                                    <i class="fas fa-undo"></i> Return
                                </a>
                            <?php else: ?>
                                <span style="color: #28a745; font-size: 14px;"><i class="fas fa-check-circle"></i> Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if($rentals->num_rows == 0): ?>
                        <tr><td colspan="5" style="text-align: center; color: #999; padding: 40px;">You haven't borrowed any books yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
