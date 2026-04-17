<?php
include 'config.php';

$message = "";
$messageType = "";

if (isset($_POST['register'])) {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = clean_input($_POST['role']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $message = "Email already registered!";
        $messageType = "danger";
    } else {
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        
        if ($stmt->execute()) {
            $message = "Registration successful! <a href='index.php' style='color: white; font-weight: 700;'>Login here</a>";
            $messageType = "success";
        } else {
            $message = "Error occurred: " . $conn->error;
            $messageType = "danger";
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative; overflow: hidden;">
    <!-- Abstract background elements -->
    <div style="position: absolute; top: -10%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(155, 89, 182, 0.15) 0%, transparent 70%); border-radius: 50%; filter: blur(60px);"></div>
    <div style="position: absolute; bottom: -10%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(46, 204, 113, 0.1) 0%, transparent 70%); border-radius: 50%; filter: blur(60px);"></div>

    <div class="glass-card" style="width: 100%; max-width: 480px; padding: 50px 40px; position: relative; z-index: 10;">
        <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 10px; letter-spacing: -1px; text-align: center;">Join LibX</h1>
        <p style="color: var(--text-muted); margin-bottom: 40px; font-weight: 500; text-align: center;">Create your professional reader profile</p>

        <?php if($message): ?>
            <div style="background: rgba(<?= $messageType=='success'?'46, 204, 113':'231, 76, 60' ?>, 0.1); color: <?= $messageType=='success'?'#2ecc71':'#e74c3c' ?>; padding: 14px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; border: 1px solid rgba(<?= $messageType=='success'?'46, 204, 113':'231, 76, 60' ?>, 0.2); font-weight: 600; text-align: center;">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Full Name</label>
                <div style="position: relative;">
                    <i class="fas fa-user" style="position: absolute; left: 16px; top: 16px; color: rgba(255,255,255,0.3);"></i>
                    <input type="text" name="name" placeholder="John Doe" required style="width: 100%; padding: 15px 15px 15px 48px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Email Address</label>
                <div style="position: relative;">
                    <i class="fas fa-envelope" style="position: absolute; left: 16px; top: 16px; color: rgba(255,255,255,0.3);"></i>
                    <input type="email" name="email" placeholder="john@example.com" required style="width: 100%; padding: 15px 15px 15px 48px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Password</label>
                <div style="position: relative;">
                    <i class="fas fa-lock" style="position: absolute; left: 16px; top: 16px; color: rgba(255,255,255,0.3);"></i>
                    <input type="password" name="password" placeholder="••••••••" required style="width: 100%; padding: 15px 15px 15px 48px;">
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Select Role</label>
                <div style="position: relative;">
                    <i class="fas fa-user-tag" style="position: absolute; left: 16px; top: 16px; color: rgba(255,255,255,0.3);"></i>
                    <select name="role" style="width: 100%; padding: 15px 15px 15px 48px; appearance: none;">
                        <option value="user">Student / Reader</option>
                        <option value="admin">Library Admin</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="register" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; justify-content: center; border-radius: 16px;">
                Create Account <i class="fas fa-user-plus" style="margin-left: 8px;"></i>
            </button>
        </form>

        <p style="text-align: center; margin-top: 35px; font-size: 14px; color: var(--text-muted); font-weight: 500;">
            Already have an account? <a href="index.php" style="color: #3498db; font-weight: 700; text-decoration: none; border-bottom: 2px solid rgba(52, 152, 219, 0.3); padding-bottom: 2px;">Sign In</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
