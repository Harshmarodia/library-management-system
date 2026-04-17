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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 5px;">Explore Library</h1>
                <p style="color: var(--text-muted); font-weight: 500;">Discover your next great read from our curated collection</p>
            </div>
            
            <form method="GET" style="display: flex; gap: 12px; min-width: 320px;">
                <div style="position: relative; flex: 1;">
                    <i class="fas fa-search" style="position: absolute; left: 16px; top: 16px; color: rgba(255,255,255,0.3);"></i>
                    <input type="text" name="search" placeholder="Search title or author..." 
                           value="<?= $_GET['search'] ?? '' ?>"
                           style="width: 100%; padding: 14px 14px 14px 48px; border-radius: 14px;">
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 14px 20px;">Search</button>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 30px;">
            <?php while($row = $books->fetch_assoc()): ?>
                <div class="glass-card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; padding: 0; overflow: hidden; border: 1px solid rgba(255,255,255,0.08);">
                    <div style="position: relative;">
                        <!-- Book Cover Placeholder -->
                        <div style="width: 100%; height: 200px; background: var(--accent-gradient); opacity: 0.8; display: flex; align-items: center; justify-content: center; position: relative;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/carbon-fibre.png'); opacity: 0.1;"></div>
                            <i class="fas fa-book-bookmark" style="font-size: 70px; color: white; opacity: 0.2; transform: rotate(-10deg);"></i>
                            <div style="position: absolute; bottom: 15px; left: 20px; background: rgba(0,0,0,0.3); backdrop-filter: blur(8px); padding: 6px 14px; border-radius: 10px; font-size: 11px; font-weight: 700; color: white; border: 1px solid rgba(255,255,255,0.1); text-transform: uppercase; letter-spacing: 1px;">
                                <i class="fas fa-box" style="margin-right: 6px; font-size: 10px;"></i> <?= $row['quantity'] ?> Left
                            </div>
                        </div>
                    </div>

                    <div style="padding: 25px;">
                        <h3 style="font-size: 19px; margin-bottom: 8px; font-weight: 700;"><?= $row['title'] ?></h3>
                        <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 20px; font-weight: 500;">by <?= $row['author'] ?></p>
                        
                        <div style="display: flex; gap: 8px; margin-bottom: 25px;">
                            <span class="badge badge-success">Available</span>
                            <span class="badge" style="background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid rgba(255,255,255,0.1);">Fiction</span>
                        </div>

                        <a href="actions/rent_action.php?book_id=<?= $row['id'] ?>" 
                           class="btn btn-primary" 
                           style="width: 100%; justify-content: center; padding: 14px;"
                           onclick="return confirm('Confirm rental for: <?= addslashes($row['title']) ?>?')">
                            <i class="fas fa-hand-holding-heart"></i> Rent Now
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if($books->num_rows == 0): ?>
                <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 80px 40px; background: rgba(255,255,255,0.03);">
                    <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; font-size: 40px; color: var(--text-muted); opacity: 0.5;">
                        <i class="fas fa-search-minus"></i>
                    </div>
                    <h2 style="margin-bottom: 10px;">No matches found</h2>
                    <p style="color: var(--text-muted); font-weight: 500;">Try adjusting your search criteria or explore our categories.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
