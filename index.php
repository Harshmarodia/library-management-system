<?php
session_start();
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];

    // Professional Prepared Statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "User not found with this email.";
    }
}
?>
<?php include 'includes/header.php'; ?>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="glass-card" style="width: 100%; max-width: 400px; text-align: center; background: rgba(255, 255, 255, 0.9);">
        <div style="font-size: 40px; color: var(--primary-color); margin-bottom: 10px;">
            <i class="fas fa-book-reader"></i>
        </div>
        <h2 style="margin-bottom: 10px;">Welcome Back</h2>
        <p style="color: #666; margin-bottom: 30px;">Login to manage your library</p>

        <?php if($error): ?>
            <div style="background: rgba(220, 53, 69, 0.1); color: var(--danger-color); padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="text-align: left; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555; font-weight: 500;">Email Address</label>
                <div style="position: relative;">
                    <i class="fas fa-envelope" style="position: absolute; left: 15px; top: 18px; color: #aaa;"></i>
                    <input type="email" name="email" placeholder="email@example.com" required 
                           style="width: 100%; padding: 15px 15px 15px 45px; border-radius: 10px; border: 1px solid #ddd; outline: none; transition: 0.3s;">
                </div>
            </div>

            <div style="text-align: left; margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #555; font-weight: 500;">Password</label>
                <div style="position: relative;">
                    <i class="fas fa-lock" style="position: absolute; left: 15px; top: 18px; color: #aaa;"></i>
                    <input type="password" name="password" placeholder="••••••••" required 
                           style="width: 100%; padding: 15px 15px 15px 45px; border-radius: 10px; border: 1px solid #ddd; outline: none; transition: 0.3s;">
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">
                Login Now
            </button>
        </form>

        <p style="margin-top: 25px; font-size: 14px; color: #666;">
            Don't have an account? <a href="register.php" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Register here</a>
        </p>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
        <p style="font-size: 12px; color: #999;">
            Admin: admin@library.com | pass: admin123
        </p>
    </div>
</div>

<style>
    input:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }
</style>

<?php include 'includes/footer.php'; ?>
