<?php
include('db.php');
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $selected_role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "SELECT * FROM users WHERE TRIM(LOWER(email)) = TRIM(LOWER('$email')) AND role = '$selected_role'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Authorized token to access the reset page
        $_SESSION['reset_user_id'] = $user['id'];
        header("Location: reset-password.php");
        exit();
    } else {
        $message = "<p class='msg error-msg'>No matching account found with that email and role combo.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .auth-wrapper { display: flex; justify-content: center; align-items: center; padding: 40px 20px; min-height: calc(100vh - 89px); background-color: var(--bg-color); }
        .auth-container { width: 100%; max-width: 420px; background: var(--panel-bg); padding: 35px; border-radius: 8px; border: 1px solid var(--border-color); }
        .auth-title { margin-bottom: 10px; text-transform: uppercase; font-weight: 900; letter-spacing: -0.5px; text-align: center; }
        .auth-subtitle { font-size: 0.85rem; color: var(--text-gray); text-align: center; margin-bottom: 25px; line-height: 1.4; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.85rem; margin-bottom: 7px; color: var(--text-gray); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 11px; background: #222; border: 1px solid var(--border-color); color: #fff; border-radius: 4px; font-size: 0.95rem; }
        .form-control:focus { border-color: var(--accent-red); outline: none; }
        
        .role-selection { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 25px; }
        .role-option { position: relative; cursor: pointer; }
        .role-option input { position: absolute; opacity: 0; width: 0; height: 0; }
        .role-box { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 15px; background: #222; border: 2px solid var(--border-color); border-radius: 6px; color: var(--text-gray); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; transition: all 0.2s ease; }
        .role-box i { font-size: 1.4rem; margin-bottom: 6px; }
        .role-option input:checked + .role-box { border-color: var(--accent-red); background-color: rgba(211, 47, 47, 0.1); color: var(--text-white); }
        
        .msg { padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600; text-align: center; }
        .error-msg { background: rgba(211, 47, 47, 0.1); border-left: 4px solid var(--accent-red); color: #ff5252; }
    </style>
</head>
<body>
    <header class="navbar">
        <a href="index.php" class="logo-container">
            <img src="Images/logo.jpg" alt="Logo" class="navbar-logo">
            <div class="logo-text">BAD BOYS<span class="red-text"> FIT & BRAWL</span></div>
        </a>
    </header>

    <div class="auth-wrapper">
        <div class="auth-container">
            <h2 class="auth-title">RECOVER <span class="red-text">PASSWORD</span></h2>
            <p class="auth-subtitle">Verify your account details below to reset your password access.</p>
            
            <?php echo $message; ?>
            
            <form action="forgot-password.php" method="POST">
                <label style="display:block; font-size:0.85rem; margin-bottom:7px; color:var(--text-gray); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Select Your Account Type:</label>
                <div class="role-selection">
                    <label class="role-option">
                        <input type="radio" name="role" value="Trainee" checked>
                        <div class="role-box"><i class="fa-solid fa-user"></i>Member</div>
                    </label>
                    <label class="role-option">
                        <input type="radio" name="role" value="Trainer">
                        <div class="role-box"><i class="fa-solid fa-user-shield"></i>Trainer</div>
                    </label>
                </div>

                <div class="form-group">
                    <label>Account Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-solid" style="width: 100%; padding: 12px;">Verify Identity</button>
                <p style="margin-top: 20px; font-size: 0.85rem; color: var(--text-gray); text-align: center;"><a href="login.php" style="color: #fff; text-decoration:none;">Back to Sign In</a></p>
            </form>
        </div>
    </div>
</body>
</html>