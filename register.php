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
            $message = "Registration successful! <a href='index.php'>Login here</a>";
            $messageType = "success";
        } else {
            $message = "Error occurred: " . $conn->error;
            $messageType = "danger";
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="glass-card" style="width: 100%; max-width: 450px; background: rgba(255, 255, 255, 0.95);">
        <h2 style="text-align: center; margin-bottom: 10px;">Create Account</h2>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">Join our reading community</p>

        <?php if($message): ?>
            <div style="background: rgba(<?= $messageType=='success'?'40, 167, 69':'220, 53, 69' ?>, 0.1); color: var(--<?= $messageType ?>-color); padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500;">Full Name</label>
                <input type="text" name="name" placeholder="John Doe" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; outline: none;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500;">Email Address</label>
                <input type="email" name="email" placeholder="john@example.com" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; outline: none;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500;">Password</label>
                <input type="password" name="password" placeholder="••••••••" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; outline: none;">
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500;">Select Role</label>
                <select name="role" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; outline: none; background: #fff;">
                    <option value="user">Student / Reader</option>
                    <option value="admin">Library Admin</option>
                </select>
            </div>

            <button type="submit" name="register" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">
                Register Account
            </button>
        </form>

        <p style="text-align: center; margin-top: 25px; font-size: 14px; color: #666;">
            Already have an account? <a href="index.php" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Login here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
