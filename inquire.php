<?php
include('db.php');
session_start();
$message = "";

$selected_plan = isset($_GET['plan']) ? htmlspecialchars($_GET['plan']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $fb_link = mysqli_real_escape_string($conn, $_POST['fb_link']);
    $inquired_plan = mysqli_real_escape_string($conn, $_POST['inquired_plan']);

    if (!$conn) {
        $message = "<p class='msg error-msg'>Database connection error failure.</p>";
    } else {
        $status = 'Pending';
        $temp_password = 'WAITING_FOR_ON_SITE_PAYMENT'; 
        $role = 'Trainee';

        $sql = "INSERT INTO users (full_name, email, password, address, membership_tier, fitness_goal, role, status) 
                VALUES ('$first_name $last_name', '$email', '$temp_password', '$fb_link', '$inquired_plan', '$contact_number', '$role', '$status')";
                
        if ($conn->query($sql) === TRUE) {
            $message = "<p class='msg success-msg'><i class='fa-solid fa-circle-check'></i> Inquiry submitted successfully! Our team will contact you on Facebook or mobile shortly to schedule your walk-in payment confirmation.</p>";
            // Update selected plan to match what they just submitted for display consistency
            $selected_plan = $inquired_plan;
        } else {
            $message = "<p class='msg error-msg'>Processing Error: " . $conn->error . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Inquiry - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            min-height: calc(100vh - 89px);
            background-color: var(--bg-color);
        }
        .auth-container { 
            width: 100%;
            max-width: 520px; 
            background: var(--panel-bg); 
            padding: 40px; 
            border-radius: 8px; 
            border: 1px solid var(--border-color); 
        }
        .auth-title { text-transform: uppercase; font-weight: 900; letter-spacing: -0.5px; text-align: center; margin-bottom: 5px; }
        .auth-subtitle { text-align: center; color: var(--text-gray); font-size: 0.85rem; margin-bottom: 25px; line-height: 1.4; }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.85rem; margin-bottom: 7px; color: var(--text-gray); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid var(--border-color); color: #fff; border-radius: 4px; font-family: var(--font-family); font-size: 0.95rem; box-sizing: border-box; }
        .form-control:focus { border-color: var(--accent-red); outline: none; background: #222; }
        
        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23888888' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }
        select.form-control option {
            background: #1a1a1a;
            color: #fff;
        }
        
        .msg { padding: 15px; border-radius: 4px; margin-bottom: 25px; font-size: 0.9rem; font-weight: 600; text-align: center; line-height: 1.4; }
        .error-msg { background: rgba(211, 47, 47, 0.1); border-left: 4px solid var(--accent-red); color: #ff5252; }
        .success-msg { background: rgba(76, 175, 80, 0.1); border-left: 4px solid #4caf50; color: #4caf50; }
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
            <a href="whyjoin.php">Why Join?</a>
            <a href="about.php">About Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="inquire.php" class="btn btn-solid" style="text-decoration: none; display: inline-block;">Join Now</a>
            <a href="login.php" class="btn btn-outline" style="text-decoration: none; display: inline-block;">Sign In</a>
        </div>
    </header>

    <div class="auth-wrapper">
        <div class="auth-container">
            <h2 class="auth-title">START YOUR <span class="red-text">JOURNEY</span></h2>
            <p class="auth-subtitle">Fill out the inquiry form below. Our fight management team will track your request and reach out to complete your setup options.</p>
            
            <?php echo $message; ?>
            
            <form action="inquire.php" method="POST">

                <div class="form-group">
                    <label>Select Membership / Package Plan</label>
                    <select name="inquired_plan" class="form-control" required>
                        <option value="General Membership" <?php echo ($selected_plan == '' || $selected_plan == 'General Membership') ? 'selected' : ''; ?>>General Membership / Not Sure Yet</option>
                        <option value="Regular" <?php echo ($selected_plan == 'Regular') ? 'selected' : ''; ?>>Regular - Fitness Plan (₱800/Yr Access)</option>
                        <option value="Prime" <?php echo ($selected_plan == 'Prime') ? 'selected' : ''; ?>>Prime - Combat Sports Plan (₱1,500/Yr Access)</option>
                        <option value="Premium" <?php echo ($selected_plan == 'Premium') ? 'selected' : ''; ?>>Premium - All-Access Plan (₱1,999/Yr Access)</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" placeholder="e.g. Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="e.g. Dela Cruz" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                </div>
                
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="tel" name="contact_number" class="form-control" placeholder="e.g. 0917XXXXXXX" required>
                </div>

                <div class="form-group">
                    <label>Facebook Profile Link</label>
                    <input type="url" name="fb_link" class="form-control" placeholder="https://facebook.com/your.username" required>
                </div>
                
                <button type="submit" class="btn btn-solid" style="width: 100%; padding: 14px; margin-top: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Submit Membership Inquiry</button>
            </form>
            
            <p style="margin-top: 30px; font-size: 0.85rem; color: var(--text-gray); text-align: center;">Want to speak directly to staff? <a href="about.php" class="red-text" style="text-decoration:none; font-weight:600;">View Gym Location</a></p>
        </div>
    </div>

</body>
</html>