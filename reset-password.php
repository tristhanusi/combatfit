<?php
include('db.php');
session_start();

if (!isset($_SESSION['reset_user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_id = $_SESSION['reset_user_id'];

    if (strlen($new_password) < 6) {
        $message = "<p class='msg error-msg'>Password must be at least 6 characters long.</p>";
    } elseif ($new_password !== $confirm_password) {
        $message = "<p class='msg error-msg'>Passwords do not match.</p>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($stmt->execute()) {
            unset($_SESSION['reset_user_id']);
            
            echo "<script>
                    alert('Password updated successfully! Please sign in with your new password.');
                    window.location.href='login.php';
                  </script>";
            exit();
        } else {
            $message = "<p class='msg error-msg'>System error occurred. Please try again.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-wrapper { display: flex; justify-content: center; align-items: center; padding: 40px 20px; min-height: calc(100vh - 89px); background-color: var(--bg-color); }
        .auth-container { width: 100%; max-width: 420px; background: var(--panel-bg); padding: 35px; border-radius: 8px; border: 1px solid var(--border-color); }
        .auth-title { margin-bottom: 15px; text-transform: uppercase; font-weight: 900; letter-spacing: -0.5px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.85rem; margin-bottom: 7px; color: var(--text-gray); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 11px; background: #222; border: 1px solid var(--border-color); color: #fff; border-radius: 4px; font-size: 0.95rem; }
        .form-control:focus { border-color: var(--accent-red); outline: none; }
        .msg { padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600; text-align: center; }
        .error-msg { background: rgba(211, 47, 47, 0.1); border-left: 4px solid var(--accent-red); color: #ff5252; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo-container">
            <img src="Images/logo.jpg" alt="Logo" class="navbar-logo">
            <div class="logo-text">BAD BOYS<span class="red-text"> FIT & BRAWL</span></div>
        </div>
    </header>

    <div class="auth-wrapper">
        <div class="auth-container">
            <h2 class="auth-title">CREATE NEW <span class="red-text">PASSWORD</span></h2>
            
            <?php echo $message; ?>
            
            <form action="reset-password.php" method="POST">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required minlength="6">
                </div>
                <button type="submit" class="btn btn-solid" style="width: 100%; padding: 12px;">Save & Update Password</button>
            </form>
        </div>
    </div>
</body>
</html>