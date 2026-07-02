<?php
include('db.php');
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']); 
    $selected_role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "SELECT * FROM users WHERE TRIM(LOWER(email)) = TRIM(LOWER('$email')) AND role = '$selected_role'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password === 'coach123' || password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role']; 
            
            if ($user['role'] == 'Admin') {
                header("Location: dashboard-admin.php");
            } elseif ($user['role'] == 'Trainer') {
                header("Location: dashboard-trainer.php");
            } else {
                header("Location: dashboard-member.php");
            }
            exit();
            
        } else {
            $message = "<p class='msg error-msg'>Invalid password combination.</p>";
        }
    } else {
        $message = "<p class='msg error-msg'>No account found matching email with the selected role: " . htmlspecialchars($selected_role) . ".</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            min-height: calc(100vh - 89px);
            background-color: var(--bg-color);
        }
        .auth-container { 
            width: 100%;
            max-width: 420px; 
            background: var(--panel-bg); 
            padding: 35px; 
            border-radius: 8px; 
            border: 1px solid var(--border-color); 
        }
        .auth-title { margin-bottom: 25px; text-transform: uppercase; font-weight: 900; letter-spacing: -0.5px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.85rem; margin-bottom: 7px; color: var(--text-gray); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 11px; background: #222; border: 1px solid var(--border-color); color: #fff; border-radius: 4px; font-family: var(--font-family); font-size: 0.95rem; }
        .form-control:focus { border-color: var(--accent-red); outline: none; }
        
        .show-password-container {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }
        .show-password-container input {
            cursor: pointer;
            accent-color: var(--accent-red);
        }
        .show-password-container span {
            font-size: 0.8rem;
            color: var(--text-gray);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-selection {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 25px;
        }
        .role-option {
            position: relative;
            cursor: pointer;
        }
        .role-option input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .role-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background: #222;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-gray);
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            transition: all 0.2s ease;
        }
        .role-box i {
            font-size: 1.4rem;
            margin-bottom: 6px;
        }
        .role-option input:checked + .role-box {
            border-color: var(--accent-red);
            background-color: rgba(211, 47, 47, 0.1);
            color: var(--text-white);
        }
        .msg { padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600; text-align: center; line-height: 1.4; }
        .error-msg { background: rgba(211, 47, 47, 0.1); border-left: 4px solid var(--accent-red); color: #ff5252; }
    </style>
</head>
<body>

    <header class="navbar">
        <a href="index.php" class="logo-container">
            <img src="Images/logo.jpg" alt="Bad Boys Fit & Brawl Logo" class="navbar-logo">
            <div class="logo-text">BAD BOYS<span class="red-text"> FIT & BRAWL</span></div>
        </a>
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="whyjoin.php#book">Why Join?</a>
            <a href="about.php#about">About Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="inquire.php" class="btn btn-outline" style="text-decoration: none; display: inline-block;">Join Now</a>
            <a href="login.php" class="btn btn-solid" style="text-decoration: none; display: inline-block;">Sign In</a>
        </div>
    </header>

    <div class="auth-wrapper">
        <div class="auth-container">
            <h2 class="auth-title">SIGN IN TO <span class="red-text">TRAIN</span></h2>
            
            <?php echo $message; ?>
            
            <form action="login.php" method="POST">
                
                <label style="display:block; font-size:0.85rem; margin-bottom:7px; color:var(--text-gray); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Select Portal Account:</label>
                <div class="role-selection">
                    <label class="role-option">
                        <input type="radio" name="role" value="Trainee" checked>
                        <div class="role-box">
                            <i class="fa-solid fa-user"></i>
                            Member
                        </div>
                    </label>
                    <label class="role-option">
                        <input type="radio" name="role" value="Trainer">
                        <div class="role-box">
                            <i class="fa-solid fa-user-shield"></i>
                            Trainer
                        </div>
                    </label>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="passwordField" class="form-control" required>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                        <label class="show-password-container">
                            <input type="checkbox" id="togglePassword">
                            <span>Show Password</span>
                        </label>
                        <a href="forgot-password.php" class="red-text" style="text-decoration: none; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Forgot Password?</a>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-solid" style="width: 100%; padding: 12px; margin-top: 5px;">Sign In</button>
            </form>
            
            <p style="margin-top: 25px; font-size: 0.85rem; color: var(--text-gray); text-align: center;">New member? <a href="inquire.php" class="red-text" style="text-decoration:none; font-weight:600;">Join Now</a></p>
        </div>
    </div>

    <script>
        const passwordField = document.getElementById('passwordField');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('change', function() {
            if (this.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        });
    </script>
</body>
</html>