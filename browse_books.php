<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

// Fetch all books
$query = "SELECT * FROM books WHERE quantity > 0";
if (isset($_GET['search'])) {
    $search = clean_input($_GET['search']);
    $query = "SELECT * FROM books WHERE quantity > 0 AND (title LIKE '%$search%' OR author LIKE '%$search%')";
}
$books = $conn->query($query);
?>

<?php include 'includes/header.php'; ?>

<div class="main-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content-area">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); margin-bottom: 0;">Explore Library</h2>
            
            <form method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Search title or author..." 
                       value="<?= $_GET['search'] ?? '' ?>"
                       style="padding: 10px 15px; border-radius: 8px; border: none; width: 250px; outline: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
            <?php while($row = $books->fetch_assoc()): ?>
                <div class="glass-card" style="display: flex; flex-direction: column; justify-content: space-between; transition: 0.3s; height: 100%;">
                    <div>
                        <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-book" style="font-size: 60px; color: rgba(0,0,0,0.1);"></i>
                        </div>
                        <h3 style="font-size: 18px; margin-bottom: 5px; color: #333;"><?= $row['title'] ?></h3>
                        <p style="color: #666; font-size: 14px; margin-bottom: 15px;">by <?= $row['author'] ?></p>
                        <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; color: #888;"><i class="fas fa-cubes"></i> <?= $row['quantity'] ?> copies left</span>
                            <span class="badge badge-success">Available</span>
                        </div>
                    </div>
                    
                    <a href="actions/rent_action.php?book_id=<?= $row['id'] ?>" 
                       class="btn btn-primary" 
                       style="margin-top: 20px; text-align: center; width: 100%;"
                       onclick="return confirm('Are you sure you want to rent this book?')">
                        <i class="fas fa-hand-holding-heart"></i> Rent This Book
                    </a>
                </div>
            <?php endwhile; ?>
            
            <?php if($books->num_rows == 0): ?>
                <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <i class="fas fa-search-minus" style="font-size: 50px; color: #ccc; margin-bottom: 20px;"></i>
                    <h3>No books found.</h3>
                    <p style="color: #666;">Try searching for a different title or author.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<style>
.glass-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}
</style>

<?php include 'includes/footer.php'; ?>
