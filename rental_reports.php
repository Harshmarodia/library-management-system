<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch all rentals with user and book details
$rentals = $conn->query("
    SELECT rentals.*, users.name as user_name, books.title as book_title, books.author as book_author 
    FROM rentals 
    JOIN users ON rentals.user_id = users.id 
    JOIN books ON rentals.book_id = books.id 
    ORDER BY rentals.status DESC, rentals.rent_date DESC
");

?>

<?php include 'includes/header.php'; ?>

<div class="main-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content-area">
        <h2 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Rental Reports & Monitoring</h2>

        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3><i class="fas fa-file-invoice"></i> All Rental Transactions</h3>
                <div>
                    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Export Report</button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>User Name</th>
                        <th>Book Title</th>
                        <th>Rent Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $rentals->fetch_assoc()): ?>
                    <tr>
                        <td>#RL701<?= $row['id'] ?></td>
                        <td style="font-weight: 500;"><?= $row['user_name'] ?></td>
                        <td>
                            <div><?= $row['book_title'] ?></div>
                            <div style="font-size: 11px; color: #888;">by <?= $row['book_author'] ?></div>
                        </td>
                        <td><?= $row['rent_date'] ?></td>
                        <td><?= $row['return_date'] ? $row['return_date'] : '<span style="color: #aaa;">Pending</span>' ?></td>
                        <td>
                            <span class="badge badge-<?= $row['status'] == 'rented' ? 'danger' : 'success' ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['status'] == 'rented'): ?>
                                <button class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;" onclick="showForceReturn(<?= $row['id'] ?>)">
                                    <i class="fas fa-key"></i> Force Return
                                </button>
                                
                                <!-- Hidden Form for Force Return -->
                                <div id="forceForm_<?= $row['id'] ?>" style="display:none; margin-top: 10px;">
                                    <form method="POST" action="actions/force_return_action.php">
                                        <input type="hidden" name="rental_id" value="<?= $row['id'] ?>">
                                        <input type="password" name="admin_pass" placeholder="Admin PIN" required style="padding: 5px; border-radius: 5px; border: 1px solid #ddd; width: 100px;">
                                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 11px;">Confirm</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span style="color: var(--success-color); font-size: 12px;"><i class="fas fa-check-double"></i> Verified</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
function showForceReturn(id) {
    const form = document.getElementById('forceForm_' + id);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>

<?php include 'includes/footer.php'; ?>
