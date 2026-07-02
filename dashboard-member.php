<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Trainee') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_attendance'])) {
    $session_date = $conn->real_escape_string($_POST['session_date']);
    $session_type = $conn->real_escape_string($_POST['session_type']);

    if (!empty($session_date) && !empty($session_type)) {
        $insert_sql = "INSERT INTO gym_attendance (user_id, session_date, session_type, status) 
                       VALUES ($user_id, '$session_date', '$session_type', 'Present')";
        $conn->query($insert_sql);
        
        header("Location: dashboard-member.php");
        exit();
    }
}

$sql = "SELECT 
            u.id,
            u.full_name,
            u.contact_number,
            u.membership_tier,
            u.status,
            u.fitness_goal AS user_goal,
            u.assigned_coach_id,
            c.full_name AS coach_name, 
            c.fitness_goal AS coach_specialty 
        FROM users u 
        LEFT JOIN users c ON u.assigned_coach_id = c.id AND c.role = 'Trainer'
        WHERE u.id = $user_id";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "Error retrieving user metrics.";
    exit();
}

$attendance_count_query = "SELECT COUNT(*) AS total FROM gym_attendance WHERE user_id = $user_id";
$attendance_res = $conn->query($attendance_count_query);
$attendance_row = $attendance_res->fetch_assoc();
$logged_sessions = $attendance_row['total'];

$target_sessions = 12; 
$attendance_rate = ($logged_sessions > 0) ? min(round(($logged_sessions / $target_sessions) * 100), 100) : 0;

$goal = $user_data['user_goal'] ?? 'General Fitness';
if (is_numeric(filter_var($goal, FILTER_SANITIZE_NUMBER_INT)) && strlen(preg_replace('/[^0-9]/', '', $goal)) > 6) {
    $goal = 'General Fitness'; // Hard fallback if data contains a phone string
}

$schedule_plans = [
    'Boxing & Striking Specialty' => [
        'Mon/Wed 6:00 PM' => 'Heavy Bag Power Drills & Pad Work',
        'Tue/Thu 5:30 PM' => 'Footwork Conditioning & Technical Sparring',
        'Fri 6:00 PM' => 'Endurance Speed Interval Runs'
    ],
    'Muay Thai & Conditioning' => [
        'Mon/Wed 7:00 PM' => 'Thai Pad Clinics & Clinch Mechanics',
        'Tue/Thu 6:00 PM' => 'Core Conditioning & Low Kick Target Drills',
        'Saturday 9:00 AM' => 'Open Mat Active Conditioning Circuit'
    ],
    'BJJ & Submission Grappling' => [
        'Mon/Wed 6:30 PM' => 'Positional Sparring & Guard Passing Systems',
        'Tue/Thu 7:00 PM' => 'Takedown Mechanics & Submission Chains',
        'Fri 6:30 PM' => 'Live Rolling / Open Drill Lab Sessions'
    ],
    'HIIT & Weight Management' => [
        'Mon/Wed/Fri 8:00 AM' => 'High-Intensity Metabolic Conditioning Burn',
        'Tue/Thu 7:00 AM' => 'Strength Circuits & Full Body Kettlebell Drills',
        'Saturday 8:00 AM' => 'Outdoor Group Assault Runs'
    ],
    'General Fitness' => [
        'Mon/Wed/Fri 5:00 PM' => 'Functional Strength Training Circuit Base',
        'Tue/Thu 6:00 PM' => 'Steady-State Cardio & Flexibility Mobility Routines'
    ]
];

$active_schedule = $schedule_plans[$goal] ?? $schedule_plans['General Fitness'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container { 
            padding: 40px 4%; 
            background-color: var(--bg-color); 
            min-height: calc(100vh - 84px); 
        }
        .welcome-banner { 
            margin-bottom: 30px; 
            border-left: 5px solid var(--accent-red); 
            padding-left: 15px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .welcome-banner h1 { 
            font-size: 2rem; 
            font-weight: 900; 
            text-transform: uppercase; 
            line-height: 1.2;
        }
        
        .dashboard-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); 
            gap: 25px; 
        }
        .dashboard-card { 
            background-color: var(--panel-bg); 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            padding: 25px; 
            transition: border-color 0.3s; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
        }
        .dashboard-card:hover { border-color: var(--accent-red); }
        .card-header { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            border-bottom: 2px solid var(--border-color); 
            padding-bottom: 12px; 
            margin-bottom: 15px; 
        }
        .card-header i { font-size: 1.4rem; color: var(--accent-red); }
        .card-header h3 { font-size: 1.15rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; margin: 0; }
        
        .info-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 12px; 
            font-size: 0.95rem; 
            align-items: center; 
            gap: 15px;
        }
        .info-label { color: var(--text-gray); font-weight: 600; flex-shrink: 0; }
        .info-value { text-align: right; word-break: break-word; }

        .progress-bar-container { background: #222; border-radius: 4px; height: 12px; width: 100%; margin-top: 8px; overflow: hidden; border: 1px solid var(--border-color); }
        .progress-bar { background: var(--accent-red); height: 100%; transition: width 0.4s ease; }
        .attendance-badge { display: inline-block; padding: 4px 10px; background: rgba(76,175,80,0.1); border: 1px solid #4caf50; color: #4caf50; border-radius: 4px; font-size: 0.8rem; font-weight: bold; flex-shrink: 0; }
        
        .log-form { margin-top: 15px; padding-top: 15px; border-top: 1px dashed var(--border-color); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
        .log-input, .log-select { background: #111; border: 1px solid var(--border-color); color: #fff; padding: 8px; border-radius: 4px; font-size: 0.85rem; width: 100%; box-sizing: border-box; }
        .btn-log { background: var(--accent-red); color: #fff; border: none; padding: 10px 12px; font-weight: bold; text-transform: uppercase; border-radius: 4px; cursor: pointer; width: 100%; font-size: 0.8rem; letter-spacing: 0.5px; transition: background 0.2s; }
        .btn-log:hover { background: #b32424; }
        
        .scrollable-logs { max-height: 150px; overflow-y: auto; margin-bottom: 10px; padding-right: 5px; }
        .schedule-time { color: var(--accent-red); font-weight: 700; font-size: 0.85rem; min-width: 120px; display: inline-flex; align-items: center; gap: 6px; flex-shrink: 0; }

        /* --- RESPONSIVE MEDIA BREAKPOINTS --- */
        @media (max-width: 768px) {
            .dashboard-container { padding: 25px 4%; }
            
            /* Responsive Navbar Stack Configuration */
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px 5%;
                text-align: center;
                height: auto !important;
            }
            .auth-buttons {
                width: 100%;
                flex-direction: column;
                gap: 10px;
            }
            .auth-buttons span { margin-right: 0 !important; }
            .auth-buttons .btn { width: 100%; max-width: 200px; text-align: center; }

            .welcome-banner { flex-direction: column; align-items: flex-start; gap: 10px; }
            .welcome-banner h1 { font-size: 1.6rem; }

            .dashboard-grid { grid-template-columns: 1fr; gap: 20px; }
            
            .info-row.schedule-row { flex-direction: column; align-items: flex-start; gap: 4px; border-bottom: 1px solid var(--border-color); padding: 12px 0 !important; }
            .info-row.schedule-row .info-value { text-align: left; }
        }

        @media (max-width: 480px) {
            .form-grid { grid-template-columns: 1fr; gap: 8px; }
            .dashboard-card { padding: 18px; }
            .info-row:not(.schedule-row) { font-size: 0.9rem; }
        }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo-container">
            <img src="Images/logo.jpg" alt="Bad Boys Fit & Brawl Logo" class="navbar-logo">
            <div class="logo-text">BAD BOYS<span class="red-text"> FIT & BRAWL</span></div>
        </div>
        <div class="auth-buttons">
            <span>
                <i class="fa-solid fa-circle" style="color: #4caf50; font-size: 0.6rem; margin-right: 5px; vertical-align: middle;"></i> Trainee Portal
            </span>
            <a href="logout.php" class="btn btn-outline" style="text-decoration: none;">Sign Out</a>
        </div>
    </header>

    <main class="dashboard-container">
        <div class="welcome-banner">
            <div>
                <h1>Welcome Back, <span class="red-text"><?php echo htmlspecialchars($user_data['full_name']); ?></span></h1>
                <p style="color: var(--text-gray); margin-top: 5px; font-size: 0.95rem;">Track your fighter performance and gym status metrics below.</p>
            </div>
        </div>

        <div class="dashboard-grid">
            
            <div class="dashboard-card">
                <div>
                    <div class="card-header">
                        <i class="fa-solid fa-address-card"></i>
                        <h3>Customer Profile</h3>
                    </div>
                    <div class="info-row"><span class="info-label">Full Name:</span> <span class="info-value"><?php echo htmlspecialchars($user_data['full_name']); ?></span></div>
                    <div class="info-row"><span class="info-label">Membership Tier:</span> <span class="red-text info-value" style="font-weight:700; font-size: 0.9rem;"><?php echo htmlspecialchars($user_data['membership_tier'] ?? 'Unassigned'); ?></span></div>
                    <div class="info-row"><span class="info-label">Status:</span> <span class="info-value" style="color:#4caf50; font-weight:600;"><?php echo htmlspecialchars($user_data['status'] ?? 'Active'); ?></span></div>
                </div>
            </div> 

            <div class="dashboard-card">
                <div>
                    <div class="card-header">
                        <i class="fa-solid fa-chart-line"></i>
                        <h3>Performance Progress</h3>
                    </div>
                    <div class="info-row"><span class="info-label">Current Goal:</span> <span class="info-value" style="font-size:0.9rem; max-width: 280px;"><?php echo htmlspecialchars($goal); ?></span></div>
                    <div class="info-row" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                        <span class="info-label">Program Attendance Rate:</span> 
                        <span style="font-size: 0.9rem;"><?php echo $attendance_rate; ?>% Completion (<?php echo $logged_sessions; ?>/<?php echo $target_sessions; ?> Sessions)</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo $attendance_rate; ?>%;"></div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <div>
                    <div class="card-header">
                        <i class="fa-solid fa-user-ninja"></i>
                        <h3>My Assigned Coach</h3>
                    </div>
                    <?php if (!empty($user_data['assigned_coach_id'])): ?>
                        <div class="info-row"><span class="info-label">Trainer Name:</span> <span class="info-value"><?php echo htmlspecialchars($user_data['coach_name'] ?? 'Assigned Coach'); ?></span></div>
                        <div class="info-row"><span class="info-label">Specialization:</span> <span class="red-text info-value"><?php echo htmlspecialchars($user_data['coach_specialty'] ?? 'Combat Sports / Fitness'); ?></span></div>
                    <?php else: ?>
                        <div class="info-row"><span class="info-label">Trainer Name:</span> <span class="info-value" style="color: var(--text-gray); font-style: italic;">No Coach Assigned</span></div>
                        <div class="info-row"><span class="info-label">Specialization:</span> <span class="info-value">Independent Training</span></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="dashboard-card">
                <div>
                    <div class="card-header">
                        <i class="fa-solid fa-calendar-check"></i>
                        <h3>Gym Attendance Log</h3>
                    </div>
                    
                    <div class="scrollable-logs">
                        <?php
                        $log_query = "SELECT * FROM gym_attendance WHERE user_id = $user_id ORDER BY session_date DESC, id DESC";
                        $log_result = $conn->query($log_query);
                        
                        if ($log_result && $log_result->num_rows > 0):
                            while($log = $log_result->fetch_assoc()):
                                $formatted_date = date("M d, Y", strtotime($log['session_date']));
                        ?>
                            <div class="info-row" style="border-bottom: 1px solid #111; padding-bottom: 6px; gap: 10px;">
                                <span style="font-size: 0.85rem; word-break: break-word;"><?php echo $formatted_date . " - " . htmlspecialchars($log['session_type']); ?></span> 
                                <span class="attendance-badge"><?php echo htmlspecialchars($log['status']); ?></span>
                            </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <p style="color: var(--text-gray); font-size: 0.85rem; font-style: italic; text-align: center; margin-top: 20px;">No workout sessions logged yet.</p>
                        <?php endif; ?>
                    </div>

                    <form class="log-form" action="" method="POST">
                        <div class="form-grid">
                            <input type="date" name="session_date" required class="log-input" value="<?php echo date('Y-m-d'); ?>">
                            <select name="session_type" required class="log-select">
                                <option value="Boxing Sparring Session">Boxing Sparring</option>
                                <option value="Muay Thai Pads Lab">Muay Thai Pads</option>
                                <option value="MMA Ground Drills">MMA Ground Drills</option>
                                <option value="Strength Run Drill">Strength Run Drill</option>
                                <option value="Weight & HIIT Circuit">Weight & HIIT Circuit</option>
                            </select>
                        </div>
                        <button type="submit" name="log_attendance" class="btn-log">Log Today's Attendance</button>
                    </form>
                </div>
            </div>

            <div class="dashboard-card" style="grid-column: 1 / -1;">
                <div>
                    <div class="card-header">
                        <i class="fa-solid fa-calendar-days"></i>
                        <h3>My Weekly Program Schedule Plan</h3>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-gray); margin-bottom: 15px;">
                        This schedule matches your current tracking program priority: <strong><?php echo htmlspecialchars($goal); ?></strong>.
                    </p>
                    
                    <?php foreach($active_schedule as $time_slot => $routine_desc): ?>
                        <div class="info-row schedule-row" style="border-bottom: 1px solid var(--border-color); padding: 10px 0;">
                            <span class="schedule-time"><i class="fa-regular fa-clock"></i><?php echo $time_slot; ?></span>
                            <span class="info-value" style="font-weight: 500;"><?php echo htmlspecialchars($routine_desc); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </main>

</body>
</html>