<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$message = "";
$messageType = "";

// Handle Add Book
if (isset($_POST['add_book'])) {
    $title = clean_input($_POST['title']);
    $author = clean_input($_POST['author']);
    $quantity = (int)$_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $author, $quantity);
    
    if ($stmt->execute()) {
        $message = "Book added successfully!";
        $messageType = "success";
    } else {
        $message = "Error adding book.";
        $messageType = "danger";
    }
}

// Fetch all books
$books = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>

<?php include 'includes/header.php'; ?>

<div class="main-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content-area">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 5px;">Inventory System</h1>
                <p style="color: var(--text-muted); font-weight: 500;">Manage catalog, stock levels, and book entries</p>
            </div>
        </div>

        <?php if($message): ?>
            <div style="background: rgba(<?= $messageType=='success'?'46, 204, 113':'231, 76, 60' ?>, 0.1); color: <?= $messageType=='success'?'#2ecc71':'#e74c3c' ?>; padding: 18px; border-radius: 14px; margin-bottom: 30px; border: 1px solid rgba(<?= $messageType=='success'?'46, 204, 113':'231, 76, 60' ?>, 0.2); font-weight: 600;">
                <i class="fas <?= $messageType=='success'?'fa-check-circle':'fa-exclamation-triangle' ?>" style="margin-right: 10px;"></i> <?= $message ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 350px 1fr; gap: 40px; align-items: start;">
            <!-- Add Book Form -->
            <div class="glass-card" style="position: sticky; top: 40px;">
                <h3 style="display: flex; align-items: center; gap: 12px; margin-bottom: 30px;">
                    <div style="width: 38px; height: 38px; background: rgba(52, 152, 219, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                        <i class="fas fa-plus"></i>
                    </div>
                    New Catalog Entry
                </h3>
                <form method="POST" action="">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Book Title</label>
                        <input type="text" name="title" placeholder="Enter book title" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Author Name</label>
                        <input type="text" name="author" placeholder="Enter author name" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 30px;">
                        <label style="display: block; margin-bottom: 10px; font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Initial Stock</label>
                        <input type="number" name="quantity" value="1" min="1" required style="width: 100%;">
                    </div>
                    <button type="submit" name="add_book" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 16px;">
                        Add Book <i class="fas fa-save" style="margin-left: 8px;"></i>
                    </button>
                </form>
            </div>

            <!-- Books List -->
            <div class="glass-card" style="padding: 0;">
                <div style="padding: 30px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h3 style="margin: 0; font-size: 20px; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-boxes-stacked" style="color: var(--secondary-color);"></i> Current Stock
                    </h3>
                </div>
                <div class="table-container" style="padding: 20px 30px 30px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Catalog ID</th>
                                <th>Title & Details</th>
                                <th>Author</th>
                                <th>Stock Status</th>
                                <th style="text-align: right;">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $books->fetch_assoc()): ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);">#<?= $row['id'] ?></td>
                                <td>
                                    <div style="font-weight: 700; color: white; margin-bottom: 4px;"><?= $row['title'] ?></div>
                                    <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-tag"></i> Fiction / Hardcover
                                    </div>
                                </td>
                                <td style="font-weight: 500;"><?= $row['author'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $row['quantity'] > 0 ? 'success' : 'danger' ?>">
                                        <i class="fas <?= $row['quantity'] > 0 ? 'fa-check' : 'fa-times' ?>" style="margin-right: 6px;"></i>
                                        <?= $row['quantity'] ?> Units
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <button class="btn" style="padding: 8px; background: rgba(52, 152, 219, 0.1); color: var(--primary-color); border: 1px solid rgba(52, 152, 219, 0.2); border-radius: 10px;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn" style="padding: 8px; background: rgba(231, 76, 60, 0.1); color: var(--danger-color); border: 1px solid rgba(231, 76, 60, 0.2); border-radius: 10px;">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($books->num_rows == 0): ?>
                                <tr><td colspan="5" style="text-align: center; padding: 60px; color: var(--text-muted);">Inventory is empty. Add your first book to get started.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
