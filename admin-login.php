<?php
session_start(); 
include('admin-config.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['user_role'] = 'Admin';
        
        $_SESSION['user_id'] = 9999; 
        
        header("Location: dashboard-admin.php");
        exit();
    } else {
        $message = "<p style='color:#ff5252; background:rgba(211,47,47,0.1); padding:10px; border-radius:4px; font-size:0.85rem; text-align:center; margin-bottom: 20px; font-weight: 600; border-left: 3px solid #ff5252;'>Invalid Administrator Signature.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HQ Gate - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        .gate-wrapper { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background: #111; 
            color: #fff; 
            font-family: var(--font-family, sans-serif); 
            padding: 20px; 
        }
        .gate-box { 
            background: #1a1a1a; 
            padding: 40px; 
            border-radius: 8px; 
            border: 1px solid #333; 
            width: 100%; 
            max-width: 380px; 
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.8rem; text-transform: uppercase; color: #aaa; margin-bottom: 7px; font-weight: 600; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 11px; background: #222; border: 1px solid #333; color: #fff; border-radius: 4px; font-size: 0.95rem; }
        .form-control:focus { border-color: #ff5252; outline: none; }
        
        .show-password-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            cursor: pointer;
            user-select: none;
        }
        .show-password-container input[type="checkbox"] {
            accent-color: #ff5252; 
            cursor: pointer;
            width: 14px;
            height: 14px;
        }
        .show-password-container label {
            display: inline;
            font-size: 0.75rem;
            color: #aaa;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            margin-bottom: 0;
        }
        .show-password-container:hover label {
            color: #fff;
        }

        .red-text { color: #ff5252; }
        .btn-solid { background: #ff5252; color: #fff; border: none; font-weight: bold; cursor: pointer; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px; transition: background 0.2s; }
        .btn-solid:hover { background: #b71c1c; }

        @media (max-width: 480px) {
            .gate-box {
                padding: 30px 20px; 
            }
            .gate-box h2 {
                font-size: 1.5rem; 
                margin-bottom: 20px !important;
            }
            .form-control {
                padding: 12px; 
                font-size: 16px; 
            }
        }
    </style>
</head>
<body>
    <div class="gate-wrapper">
        <div class="gate-box">
            <h2 style="text-transform:uppercase; font-weight:900; letter-spacing:-0.5px; text-align:center; margin-bottom:25px;">HQ <span class="red-text">GATEWAY</span></h2>
            
            <?php echo $message; ?>
            
            <form action="admin-login.php" method="POST">
                <div class="form-group">
                    <label>Admin Username</label>
                    <input type="text" name="username" class="form-control" required autocomplete="off">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Master Password</label>
                    <input type="password" name="password" id="adminPassword" class="form-control" required>
                    
                    <div class="show-password-container">
                        <input type="checkbox" id="togglePassword">
                        <label for="togglePassword">Show Password</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-solid" style="width:100%; padding:13px; margin-top: 10px;">AUTHORIZE ACCESS</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('adminPassword');
            const toggleCheckbox = document.getElementById('togglePassword');

            toggleCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            });
        });
    </script>
</body>
</html>