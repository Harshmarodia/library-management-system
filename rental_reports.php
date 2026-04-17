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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 5px;">Rental Reports</h1>
                <p style="color: var(--text-muted); font-weight: 500;">Monitor lending history and transaction statuses</p>
            </div>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>

        <div class="glass-card" style="padding: 0;">
            <div style="padding: 30px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-clock-rotate-left" style="color: var(--warning-color);"></i> All Transactions
                </h3>
            </div>

            <div class="table-container" style="padding: 20px 30px 30px;">
                <table>
                    <thead>
                        <tr>
                            <th>TXID</th>
                            <th>Reader Member</th>
                            <th>Book Details</th>
                            <th>Rent Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $rentals->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--text-muted);">#<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div style="font-weight: 600; color: white;"><?= $row['user_name'] ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 500; font-size: 14px;"><?= $row['book_title'] ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><?= $row['book_author'] ?></div>
                            </td>
                            <td style="font-size: 14px;"><?= date('M d, Y', strtotime($row['rent_date'])) ?></td>
                            <td style="font-size: 14px; color: <?= $row['return_date'] ? 'inherit' : 'var(--text-muted)' ?>">
                                <?= $row['return_date'] ? date('M d, Y', strtotime($row['return_date'])) : '<span style="font-style: italic; opacity: 0.5;">Pending</span>' ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $row['status'] == 'rented' ? 'danger' : 'success' ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <?php if($row['status'] == 'rented'): ?>
                                    <button class="btn" style="padding: 8px 16px; background: rgba(241, 196, 15, 0.1); color: var(--warning-color); border: 1px solid rgba(241, 196, 15, 0.2); font-size: 13px;" onclick="showForceReturn(<?= $row['id'] ?>)">
                                        <i class="fas fa-shield-halved"></i> Force Return
                                    </button>
                                    
                                    <!-- Force Return Modal/Form -->
                                    <div id="forceForm_<?= $row['id'] ?>" style="display:none; margin-top: 15px; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                                        <form method="POST" action="actions/force_return_action.php" style="display: flex; gap: 10px;">
                                            <input type="hidden" name="rental_id" value="<?= $row['id'] ?>">
                                            <input type="password" name="admin_pass" placeholder="Admin PIN" required style="flex: 1; padding: 10px;">
                                            <button type="submit" class="btn btn-primary" style="padding: 10px 15px;">OK</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div style="color: var(--success-color); font-size: 13px; font-weight: 700;">
                                        <i class="fas fa-circle-check"></i> Verified
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
function showForceReturn(id) {
    const form = document.getElementById('forceForm_' + id);
    form.style.display = form.style.display === 'none' ? 'flex' : 'none';
    if(form.style.display === 'flex') form.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

<?php include 'includes/footer.php'; ?>
