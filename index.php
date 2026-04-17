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

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative; overflow: hidden;">
    <!-- Abstract background elements -->
    <div style="position: absolute; top: -10%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(58, 123, 213, 0.2) 0%, transparent 70%); border-radius: 50%; filter: blur(60px);"></div>
    <div style="position: absolute; bottom: -10%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(106, 17, 203, 0.2) 0%, transparent 70%); border-radius: 50%; filter: blur(60px);"></div>

    <div class="glass-card" style="width: 100%; max-width: 440px; text-align: center; padding: 50px 40px; position: relative; z-index: 10;">
        <div style="width: 80px; height: 80px; background: var(--primary-gradient); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 36px; color: white; margin: 0 auto 25px; box-shadow: 0 10px 20px rgba(58, 123, 213, 0.3);">
            <i class="fas fa-book-reader"></i>
        </div>
        
        <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 10px; letter-spacing: -1px;">Welcome Back</h1>
        <p style="color: var(--text-muted); margin-bottom: 40px; font-weight: 500;">Secure login to your library portal</p>

        <?php if($error): ?>
            <div style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; padding: 14px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; border: 1px solid rgba(231, 76, 60, 0.2); font-weight: 600;">
                <i class="fas fa-circle-exclamation" style="margin-right: 8px;"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="text-align: left; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Email Address</label>
                <div style="position: relative;">
                    <i class="fas fa-envelope" style="position: absolute; left: 16px; top: 16px; color: rgba(255,255,255,0.3);"></i>
                    <input type="email" name="email" placeholder="email@example.com" required 
                           style="width: 100%; padding: 15px 15px 15px 48px;">
                </div>
            </div>

            <div style="text-align: left; margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Password</label>
                <div style="position: relative;">
                    <i class="fas fa-lock" style="position: absolute; left: 16px; top: 16px; color: rgba(255,255,255,0.3);"></i>
                    <input type="password" name="password" placeholder="••••••••" required 
                           style="width: 100%; padding: 15px 15px 15px 48px;">
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; justify-content: center; border-radius: 16px;">
                Sign In <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
            </button>
        </form>

        <p style="margin-top: 35px; font-size: 14px; color: var(--text-muted); font-weight: 500;">
            Don't have an account? <a href="register.php" style="color: #3498db; font-weight: 700; text-decoration: none; border-bottom: 2px solid rgba(52, 152, 219, 0.3); padding-bottom: 2px;">Register Now</a>
        </p>

        <div style="margin-top: 40px; padding-top: 25px; border-top: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 11px; color: rgba(255,255,255,0.2); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">Quick Access Info</div>
            <p style="font-size: 12px; color: rgba(255,255,255,0.4);">
                Admin: admin@library.com | pass: admin123
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
