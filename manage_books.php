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
        <h2 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Inventory Management</h2>

        <?php if($message): ?>
            <div style="background: rgba(<?= $messageType=='success'?'40, 167, 69':'220, 53, 69' ?>, 0.1); color: var(--<?= $messageType ?>-color); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
            <!-- Add Book Form -->
            <div class="glass-card">
                <h3><i class="fas fa-plus-circle"></i> Add New Book</h3>
                <form method="POST" action="">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500;">Book Title</label>
                        <input type="text" name="title" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500;">Author</label>
                        <input type="text" name="author" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500;">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                    <button type="submit" name="add_book" class="btn btn-primary" style="width: 100%;">Add To Library</button>
                </form>
            </div>

            <!-- Books List -->
            <div class="glass-card">
                <h3><i class="fas fa-list"></i> Current Inventory</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Book Info</th>
                            <th>Author</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $books->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 600;"><?= $row['title'] ?></div>
                                <div style="font-size: 12px; color: #888;">ID: #<?= $row['id'] ?></div>
                            </td>
                            <td><?= $row['author'] ?></td>
                            <td>
                                <span class="badge badge-<?= $row['quantity'] > 0 ? 'success' : 'danger' ?>">
                                    <?= $row['quantity'] ?> Units
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-info" style="padding: 5px 10px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
