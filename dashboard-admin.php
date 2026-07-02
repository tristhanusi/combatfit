<?php
include('db.php');
include('admin-config.php'); 
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_coach'])) {
    $coach_name = mysqli_real_escape_string($conn, trim($_POST['coach_name']));
    $coach_specialty = mysqli_real_escape_string($conn, trim($_POST['coach_specialty']));
    $coach_email = mysqli_real_escape_string($conn, trim($_POST['coach_email']));
    $raw_password = $_POST['coach_password'];

    if (empty($coach_name) || empty($coach_specialty) || empty($coach_email) || empty($raw_password)) {
        $message = "<p class='msg error-msg'><i class='fa-solid fa-triangle-exclamation'></i> Creation Failed: All input fields are required.</p>";
    } else {
        $check_email = "SELECT id FROM users WHERE email = '$coach_email'";
        $check_result = $conn->query($check_email);

        if ($check_result && $check_result->num_rows > 0) {
            $message = "<p class='msg error-msg'><i class='fa-solid fa-triangle-exclamation'></i> Integration Crash Prevented: A user or coach with email '$coach_email' already exists.</p>";
        } else {
            $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

            $insert_coach_sql = "INSERT INTO users (full_name, email, password, role, status, fitness_goal, membership_tier) 
                                 VALUES ('$coach_name', '$coach_email', '$hashed_password', 'Trainer', 'Active', '$coach_specialty', 'Staff')";

            if ($conn->query($insert_coach_sql) === TRUE) {
                $message = "<p class='msg success-msg'><i class='fa-solid fa-circle-check'></i> Coach Account deployed successfully! They can now access their profile using their email and password.</p>";
            } else {
                $message = "<p class='msg error-msg'>System Registry Failure: " . $conn->error . "</p>";
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['activate_user'])) {
    $user_id = intval($_POST['user_id']);
    $raw_password = $_POST['assigned_password'];
    
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
    
    $update_sql = "UPDATE users SET password = '$hashed_password', status = 'Active' WHERE id = $user_id";
    if ($conn->query($update_sql) === TRUE) {
        $message = "<p class='msg success-msg'><i class='fa-solid fa-circle-check'></i> Account activated successfully! Hand over the credentials to the member.</p>";
    } else {
        $message = "<p class='msg error-msg'>Activation Error: " . $conn->error . "</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_member'])) {
    $member_id = intval($_POST['member_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $membership_tier = mysqli_real_escape_string($conn, $_POST['membership_tier']);
    $wants_coach = $_POST['wants_coach']; // 'Yes' or 'No'
    
    if ($wants_coach === 'Yes' && !empty($_POST['assigned_coach_id'])) {
        $assigned_coach_id = intval($_POST['assigned_coach_id']);
        $sql_update = "UPDATE users SET status='$status', membership_tier='$membership_tier', assigned_coach_id=$assigned_coach_id WHERE id=$member_id";
    } else {
        $sql_update = "UPDATE users SET status='$status', membership_tier='$membership_tier', assigned_coach_id=NULL WHERE id=$member_id";
    }

    if ($conn->query($sql_update) === TRUE) {
        $message = "<p class='msg success-msg'><i class='fa-solid fa-circle-check'></i> Member profile updated successfully!</p>";
    } else {
        $message = "<p class='msg error-msg'>Update Error: " . $conn->error . "</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_member'])) {
    $member_id = intval($_POST['member_id']);
    
    $verify_sql = "SELECT status FROM users WHERE id = $member_id AND role = 'Trainee'";
    $verify_result = $conn->query($verify_sql);
    
    if ($verify_result && $verify_result->num_rows > 0) {
        $member_data = $verify_result->fetch_assoc();
        if ($member_data['status'] === 'Active') {
            $message = "<p class='msg error-msg'><i class='fa-solid fa-triangle-exclamation'></i> Safeguard Alert: Cannot delete an Active account. Demote their status first.</p>";
        } else {
            $delete_sql = "DELETE FROM users WHERE id = $member_id";
            if ($conn->query($delete_sql) === TRUE) {
                $message = "<p class='msg success-msg'><i class='fa-solid fa-trash-can'></i> Member account cleanly purged from system registry.</p>";
            } else {
                $message = "<p class='msg error-msg'>Deletion Failure: " . $conn->error . "</p>";
            }
        }
    } else {
        $message = "<p class='msg error-msg'>Target member record not found.</p>";
    }
}

$coaches_result = $conn->query("SELECT id, full_name, fitness_goal, email FROM users WHERE role='Trainer' ORDER BY full_name ASC");
$coaches_list = [];
if ($coaches_result && $coaches_result->num_rows > 0) {
    while ($row = $coaches_result->fetch_assoc()) {
        $coaches_list[] = $row;
    }
}

$members_sql = "SELECT m.*, c.full_name AS coach_name 
                FROM users m 
                LEFT JOIN users c ON m.assigned_coach_id = c.id AND c.role='Trainer'
                WHERE m.role='Trainee'
                ORDER BY CASE WHEN m.assigned_coach_id IS NULL THEN 0 ELSE 1 END, m.full_name ASC";
$members_result = $conn->query($members_sql);

$total_pending = $conn->query("SELECT COUNT(*) as count FROM users WHERE status='Pending'")->fetch_assoc()['count'];
$total_members = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='Trainee' AND status='Active'")->fetch_assoc()['count'];
$total_coaches = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='Trainer'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin HQ Dashboard - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container { 
            padding: 20px 4%; 
            background-color: var(--bg-color); 
            min-height: calc(100vh - 84px); 
            box-sizing: border-box;
        }
        .banner { margin-bottom: 25px; border-left: 5px solid var(--accent-red); padding-left: 15px; }
        .banner h1 { font-size: 1.6rem; font-weight: 900; text-transform: uppercase; line-height: 1.2; }
        .banner p { font-size: 0.9rem; color: var(--text-gray); margin-top: 5px; }
        
        .navbar {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 15px 4%;
            background: #111;
            border-bottom: 1px solid var(--border-color);
        }
        .logo-container { display: flex; align-items: center; gap: 10px; }
        .navbar-logo { height: 40px; width: auto; border-radius: 4px; }
        .logo-text { font-size: 1.1rem; font-weight: 900; letter-spacing: 0.5px; }
        .auth-buttons { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            width: 100%; 
            flex-wrap: wrap; 
            gap: 10px; 
        }

        .metrics-grid { 
            display: grid; 
            grid-template-columns: 1fr; 
            gap: 15px; 
            margin-bottom: 30px; 
        }
        .metric-card { 
            background: var(--panel-bg); 
            border: 1px solid var(--border-color); 
            border-radius: 6px; 
            padding: 15px 20px; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
        }
        .metric-card i { font-size: 1.8rem; color: var(--accent-red); }
        .metric-card h3 { font-size: 0.8rem; color: var(--text-gray); text-transform: uppercase; margin-bottom: 3px; }
        .metric-card p { font-size: 1.5rem; font-weight: 900; color: #fff; }

        .workspace-grid { 
            display: flex;
            flex-direction: column;
            gap: 25px; 
        }

        .panel { 
            background: var(--panel-bg); 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            padding: 20px; 
            margin-bottom: 5px; 
            box-sizing: border-box;
        }
        .panel-title { 
            font-size: 1.05rem; 
            font-weight: 800; 
            text-transform: uppercase; 
            margin-bottom: 20px; 
            padding-bottom: 10px; 
            border-bottom: 2px solid var(--border-color); 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            line-height: 1.3;
        }
        .panel-title i { color: var(--accent-red); flex-shrink: 0; }

        .table-responsive { 
            overflow-x: auto; 
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; min-width: 600px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
        th { background: #1a1a1a; text-transform: uppercase; font-size: 0.75rem; color: var(--text-gray); font-weight: 700; letter-spacing: 0.5px; }
        tr:hover td { background: rgba(255,255,255,0.02); }
        
        .badge { display: inline-block; padding: 4px 8px; font-size: 0.7rem; font-weight: 700; border-radius: 4px; text-transform: uppercase; white-space: nowrap; }
        .badge-none { background: rgba(211,47,47,0.1); border: 1px solid var(--accent-red); color: #ff5252; }
        .badge-assigned { background: rgba(76,175,80,0.1); border: 1px solid #4caf50; color: #4caf50; }
        .badge-pending { background: rgba(255,152,0,0.1); border: 1px solid #ff9800; color: #ff9800; }

        .activation-form-wrap {
            display: flex; 
            gap: 6px; 
            align-items: center; 
            flex-wrap: nowrap;
            min-width: 210px;
        }
        .pwd-input { 
            padding: 8px; 
            background: #1a1a1a; 
            border: 1px solid var(--border-color); 
            color: #fff; 
            border-radius: 4px; 
            width: 110px; 
            font-size: 0.8rem; 
            box-sizing: border-box;
        }
        .pwd-input:focus { border-color: var(--accent-red); outline: none; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.8rem; color: var(--text-gray); text-transform: uppercase; font-weight: 600; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px; background: #1a1a1a; border: 1px solid var(--border-color); color: #fff; border-radius: 4px; font-size: 0.9rem; box-sizing: border-box; }
        .form-control:focus { border-color: var(--accent-red); outline: none; }
        select.form-control { cursor: pointer; }

        .msg { padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600; text-align: center; word-wrap: break-word; }
        .error-msg { background: rgba(211, 47, 47, 0.1); border-left: 4px solid var(--accent-red); color: #ff5252; }
        .success-msg { background: rgba(76, 175, 80, 0.1); border-left: 4px solid #4caf50; color: #4caf50; }
        
        @media (min-width: 600px) {
            .navbar { flex-direction: row; justify-content: space-between; align-items: center; padding: 15px 4%; }
            .auth-buttons { width: auto; justify-content: flex-end; gap: 20px; }
            .metrics-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
            .banner h1 { font-size: 2rem; }
        }

        @media (min-width: 1150px) {
            .admin-container { padding: 40px 4%; }
            .workspace-grid { 
                display: grid; 
                grid-template-columns: 2fr 1fr; 
                gap: 30px; 
                align-items: start;
            }
            .panel { padding: 25px; }
            table { min-width: auto; } 
            th, td { padding: 12px 15px; }
        }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo-container">
            <img src="Images/logo.jpg" alt="Bad Boys Fit & Brawl Logo" class="navbar-logo">
            <div class="logo-text">BAD BOYS<span class="red-text"> HQ MANAGEMENT</span></div>
        </div>
        <div class="auth-buttons">
            <span style="font-weight: 700; font-size: 0.85rem; color: var(--accent-red); text-transform: uppercase;"><i class="fa-solid fa-user-gear"></i> Administrator Portal</span>
            <a href="admin-login.php" class="btn btn-outline" style="text-decoration: none;">Sign Out</a>
        </div>
    </header>

    <main class="admin-container">
        <div class="banner">
            <h1>Gym Operations Command</h1>
            <p>Review inquiries, approve walk-in payments, assign on-site coaches, and activate system access.</p>
        </div>

        <div class="metrics-grid">
            <div class="metric-card">
                <i class="fa-solid fa-clock"></i>
                <div>
                    <h3>Pending Inquiries</h3>
                    <p><?php echo $total_pending; ?></p>
                </div>
            </div>
            <div class="metric-card">
                <i class="fa-solid fa-users"></i>
                <div>
                    <h3>Active Members</h3>
                    <p><?php echo $total_members; ?></p>
                </div>
            </div>
            <div class="metric-card">
                <i class="fa-solid fa-user-ninja"></i>
                <div>
                    <h3>On-Duty Coaches</h3>
                    <p><?php echo $total_coaches; ?></p>
                </div>
            </div>
        </div>

        <?php echo $message; ?>

        <div class="workspace-grid">
            
            <div class="panel">
                <div class="panel-title">
                    <i class="fa-solid fa-rectangle-list"></i> Member Lead & Assignment Directory
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Contact / Info</th>
                                <th>Plan / Tier</th>
                                <th>Coach Allocation</th>
                                <th>Status / Activation Form</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($members_result && $members_result->num_rows > 0): ?>
                                <?php while($member = $members_result->fetch_assoc()): ?>
                                    <tr>
                                        <td style="font-weight: 700;">
                                            <?php echo htmlspecialchars($member['full_name']); ?><br>
                                            <a href="<?php echo htmlspecialchars($member['address'] ?? '#'); ?>" target="_blank" style="color: #52a3ff; text-decoration: none; font-size: 0.8rem; font-weight: normal;"><i class="fa-brands fa-facebook"></i> View Profile</a>
                                        </td>
                                        
                                        <td style="font-size: 0.85rem; color: var(--text-gray);"><?php echo htmlspecialchars($member['fitness_goal'] ?? 'N/A'); ?></td>
                                        
                                        <td><span style="color: #f1c40f; font-weight: bold; font-size: 0.85rem;"><?php echo htmlspecialchars($member['membership_tier'] ?? 'Unassigned'); ?></span></td>
                                        
                                        <td>
                                            <?php if(empty($member['assigned_coach_id'])): ?>
                                                <span class="badge badge-none"><i class="fa-solid fa-circle-xmark"></i> No Coach</span>
                                            <?php else: ?>
                                                <span class="badge badge-assigned"><i class="fa-solid fa-circle-check"></i> Coach: <?php echo htmlspecialchars($member['coach_name']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <?php if($member['status'] === 'Pending'): ?>
                                                <form action="dashboard-admin.php" method="POST" class="activation-form-wrap">
                                                    <input type="hidden" name="user_id" value="<?php echo $member['id']; ?>">
                                                    <input type="text" name="assigned_password" class="pwd-input" placeholder="Set Password" required>
                                                    <button type="submit" name="activate_user" class="btn btn-solid" style="font-size:0.7rem; padding:6px 10px; border-radius:4px; text-transform:uppercase; font-weight:bold;">Activate</button>
                                                </form>
                                            <?php elseif($member['status'] === 'Active'): ?>
                                                <span class="badge badge-assigned" style="background:rgba(33,150,243,0.1); border-color:#2196f3; color:#2196f3;">
                                                    <i class="fa-solid fa-user-check"></i> Active Account
                                                </span>
                                            <?php elseif($member['status'] === 'Inactive'): ?>
                                                <span class="badge badge-none" style="background:rgba(244,67,54,0.1); border-color:#f44336; color:#f44336;">
                                                    <i class="fa-solid fa-user-slash"></i> Inactive Account
                                                </span>
                                            <?php else: ?>
                                                <span class="badge" style="background:rgba(158,158,158,0.1); border: 1px solid #9e9e9e; color:#9e9e9e;">
                                                    <i class="fa-solid fa-circle-question"></i> <?php echo htmlspecialchars($member['status']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <button class="btn btn-outline" style="font-size:0.75rem; padding:5px 10px; border-radius:4px;" 
                                                    onclick="loadMemberToForm(<?php echo htmlspecialchars(json_encode($member)); ?>)">
                                                Manage
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center; color:var(--text-gray); padding: 20px;">No gym members or lead records populated in data logs.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="panel">
                    <div class="panel-title"><i class="fa-solid fa-user-pen"></i> Assignment & Tier Modifier</div>
                    <form action="dashboard-admin.php" method="POST" id="managementForm">
                        <input type="hidden" name="member_id" id="form_member_id">
                        
                        <div class="form-group">
                            <label>Selected Target Member</label>
                            <input type="text" id="form_member_name" class="form-control" readonly placeholder="Click 'Manage' to load details">
                        </div>

                        <div class="form-group">
                            <label>Account Status</label>
                            <select name="status" id="form_status" class="form-control" required>
                                <option value="Pending">Pending Walk-In Confirmation</option>
                                <option value="Active">Active (Approve System Access)</option>
                                <option value="Inactive">Inactive/Expired Membership</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Membership Tier Allocation</label>
                            <select name="membership_tier" id="form_tier" class="form-control" required>
                                <option value="General Membership">General Membership</option>
                                <option value="Regular">Regular - Fitness Plan</option>
                                <option value="Prime">Prime - Combat Sports Plan</option>
                                <option value="Premium">Premium - All-Access Plan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Allocate Personal Coach?</label>
                            <select name="wants_coach" id="form_wants_coach" class="form-control" onchange="toggleCoachDropdown()" required>
                                <option value="No">No / Independent Training</option>
                                <option value="Yes">Yes / Allocating Coach</option>
                            </select>
                        </div>

                        <div class="form-group" id="coach_selection_block" style="display: none;">
                            <label>Select From Active Staff</label>
                            <select name="assigned_coach_id" id="form_coach_id" class="form-control">
                                <option value="">-- Choose Coach --</option>
                                <?php foreach($coaches_list as $coach): ?>
                                    <option value="<?php echo $coach['id']; ?>">
                                        Coach <?php echo htmlspecialchars($coach['full_name']); ?> (<?php echo htmlspecialchars($coach['fitness_goal'] ?? 'Combat Instructor'); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" name="update_member" class="btn btn-solid" style="width:100%; margin-top:15px; padding:12px; text-transform:uppercase; font-weight:bold;">Save Profile Metrics</button>
                        
                        <button type="submit" name="delete_member" id="form_delete_btn" class="btn" style="width:100%; margin-top:10px; padding:12px; text-transform:uppercase; font-weight:bold; background: rgba(211, 47, 47, 0.1); border: 1px solid #ff5252; color: #ff5252; display: none; cursor: pointer;" onclick="return confirm('CRITICAL ACCOUNT ACTION:\n\nAre you sure you want to permanently delete this member record? This will completely drop them from your gym registry. This action cannot be reversed.');">
                            <i class="fa-solid fa-trash-can"></i> Delete Member Account
                        </button>
                    </form>
                </div>

                <div class="panel">
                    <div class="panel-title"><i class="fa-solid fa-user-plus"></i> Setup Coach Account</div>
                    <form action="dashboard-admin.php" method="POST">
                        <div class="form-group">
                            <label>Coach Full Name</label>
                            <input type="text" name="coach_name" class="form-control" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="form-group">
                            <label>Focus Specialty Description</label>
                            <input type="text" name="coach_specialty" class="form-control" placeholder="e.g. Muay Thai Instructor / Striking" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address (Dashboard Login ID)</label>
                            <input type="email" name="coach_email" class="form-control" placeholder="coachname@example.com" required>
                        </div>
                        <div class="form-group">
                            <label>Assign System Password</label>
                            <input type="password" name="coach_password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" name="create_coach" class="btn btn-solid" style="width:100%; margin-top:10px; padding:12px; text-transform:uppercase; font-weight:bold;">Create Staff Profile</button>
                    </form>
                </div>

                <div class="panel">
                    <div class="panel-title"><i class="fa-solid fa-id-card"></i> Active Gym Staff Roster</div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr><th>Coach Name</th><th>Focus Specialty</th></tr>
                            </thead>
                            <tbody>
                                <?php if(count($coaches_list) > 0): ?>
                                    <?php foreach($coaches_list as $coach): ?>
                                        <tr>
                                            <td style="font-weight: 600;">
                                                Coach <?php echo htmlspecialchars($coach['full_name']); ?><br>
                                                <span style="font-size:0.75rem; color:var(--text-gray); font-weight:normal;"><?php echo htmlspecialchars($coach['email'] ?? 'No Email Logged'); ?></span>
                                            </td>
                                            <td class="red-text" style="font-size:0.85rem; font-weight:600;"><?php echo htmlspecialchars($coach['fitness_goal'] ?? 'General Martial Arts'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" style="text-align:center; color:var(--text-gray);">No coaches added yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        function loadMemberToForm(member) {
            document.getElementById('form_member_id').value = member.id;
            document.getElementById('form_member_name').value = member.full_name;
            document.getElementById('form_status').value = member.status;
            document.getElementById('form_tier').value = member.membership_tier || 'General Membership';
            
            if (member.assigned_coach_id) {
                document.getElementById('form_wants_coach').value = 'Yes';
                document.getElementById('form_coach_id').value = member.assigned_coach_id;
            } else {
                document.getElementById('form_wants_coach').value = 'No';
                document.getElementById('form_coach_id').value = '';
            }
            
            const deleteBtn = document.getElementById('form_delete_btn');
            if (member.status !== 'Active') {
                deleteBtn.style.display = 'block';
            } else {
                deleteBtn.style.display = 'none';
            }

            toggleCoachDropdown();
        }

        function toggleCoachDropdown() {
            var wantsCoach = document.getElementById('form_wants_coach').value;
            var block = document.getElementById('coach_selection_block');
            if (wantsCoach === 'Yes') {
                block.style.display = 'block';
                document.getElementById('form_coach_id').setAttribute('required', 'required');
            } else {
                block.style.display = 'none';
                document.getElementById('form_coach_id').removeAttribute('required');
            }
        }
    </script>
</body>
</html>