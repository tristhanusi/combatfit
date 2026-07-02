<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Trainer') {
    header("Location: login.php");
    exit();
}

$coach_id = $_SESSION['user_id'];

$coach_sql = "SELECT * FROM users WHERE id = $coach_id AND role = 'Trainer'";
$coach_result = $conn->query($coach_sql);

if ($coach_result && $coach_result->num_rows > 0) {
    $coach_data = $coach_result->fetch_assoc();
    $coach_name = $coach_data['full_name']; 
    $coach_discipline = $coach_data['fitness_goal'] ?? 'Combat Sports / Fitness';
} else {
    $coach_name = $_SESSION['user_name'] ?? 'Coach';
    $coach_discipline = 'Combat Sports / Fitness';
}

$trainees_query = "SELECT id, full_name, fitness_goal, membership_tier, status 
                   FROM users 
                   WHERE assigned_coach_id = $coach_id AND role = 'Trainee' 
                   ORDER BY full_name ASC";
$trainees_result = $conn->query($trainees_query);
$active_trainee_count = $trainees_result ? $trainees_result->num_rows : 0;

$attendance_by_trainee = [];
$attendance_sql = "SELECT a.* FROM gym_attendance a
                   JOIN users u ON a.user_id = u.id
                   WHERE u.assigned_coach_id = $coach_id
                   ORDER BY a.session_date DESC, a.id DESC";
$attendance_result = $conn->query($attendance_sql);

if ($attendance_result && $attendance_result->num_rows > 0) {
    while ($log = $attendance_result->fetch_assoc()) {
        $attendance_by_trainee[$log['user_id']][] = $log;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Dashboard - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container { padding: 40px 4%; background-color: var(--bg-color); min-height: calc(100vh - 84px); }
        .welcome-banner { margin-bottom: 30px; border-left: 5px solid var(--accent-red); padding-left: 15px; display: flex; justify-content: space-between; align-items: center; }
        .welcome-banner h1 { font-size: 2rem; font-weight: 900; text-transform: uppercase; line-height: 1.2; }
        
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 25px; }
        .dashboard-card { background-color: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 25px; transition: border-color 0.3s; display: flex; flex-direction: column; }
        .dashboard-card:hover { border-color: var(--accent-red); }
        .card-header { display: flex; align-items: center; gap: 12px; border-bottom: 2px solid var(--border-color); padding-bottom: 12px; margin-bottom: 15px; }
        .card-header i { font-size: 1.4rem; color: var(--accent-red); }
        .card-header h3 { font-size: 1.15rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; margin: 0; }
        
        .dashboard-card.full-width { grid-column: span 2; }
        
        .info-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem; align-items: center; gap: 15px; }
        .info-label { color: var(--text-gray); font-weight: 600; flex-shrink: 0; }
        .info-value { text-align: right; word-break: break-word; }
        .status-pill { padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; background: #d32f2f; color:#fff; flex-shrink: 0; }
        .scrollable-list { max-height: 550px; overflow-y: auto; padding-right: 5px; }
        
        .search-wrapper { position: relative; margin-bottom: 20px; }
        .search-wrapper i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-gray); font-size: 0.9rem; }
        .search-control { width: 100%; padding: 11px 11px 11px 38px; background: #1a1a1a; border: 1px solid var(--border-color); color: #fff; border-radius: 6px; font-size: 0.9rem; box-sizing: border-box; }
        .search-control:focus { border-color: var(--accent-red); outline: none; }

        .trainee-accordion { background: #1a1a1a; border: 1px solid var(--border-color); border-radius: 6px; margin-bottom: 12px; overflow: hidden; transition: border-color 0.2s; }
        .trainee-accordion[open] { border-color: rgba(211, 47, 47, 0.4); }
        
        .trainee-summary-header { display: flex; justify-content: space-between; align-items: center; padding: 15px; cursor: pointer; user-select: none; list-style: none; gap: 15px; }
        .trainee-summary-header::-webkit-details-marker { display: none; } 
        
        .summary-left-block { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .summary-left-block strong { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .summary-left-block .chevron-icon { color: var(--text-gray); transition: transform 0.2s; font-size: 0.8rem; flex-shrink: 0; }
        .trainee-accordion[open] .chevron-icon { transform: rotate(180deg); color: var(--accent-red); }

        .session-count-badge { background-color: rgba(211, 47, 47, 0.1); border: 1px solid var(--accent-red); color: var(--text-white); font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; font-weight: 700; text-transform: uppercase; white-space: nowrap; flex-shrink: 0; }
        
        .trainee-dropdown-content { padding: 0 15px 15px 15px; border-top: 1px dashed #252525; background: #161616; }
        .meta-info-strip { display: flex; justify-content: space-between; align-items: center; gap: 15px; padding: 10px 0; border-bottom: 1px solid #252525; margin-bottom: 10px; font-size: 0.8rem; color: var(--text-gray); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .meta-goal-block { min-width: 0; text-overflow: ellipsis; overflow: hidden; }
        
        .mini-log-row { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 8px 0; border-bottom: 1px solid #222; gap: 15px; align-items: center; }
        .mini-log-row:last-child { border-bottom: none; }
        .session-type-text { color: var(--accent-red); font-weight: 600; text-align: right; }

        @media (max-width: 900px) {
            .dashboard-card.full-width { grid-column: span 1; }
        }

        @media (max-width: 768px) {
            .dashboard-container { padding: 25px 4%; }
            
            .navbar { flex-direction: column; gap: 15px; padding: 15px 5%; text-align: center; height: auto !important; }
            .auth-buttons { width: 100%; flex-direction: column; gap: 10px; }
            .auth-buttons span { margin-right: 0 !important; }
            .auth-buttons .btn { width: 100%; max-width: 200px; text-align: center; }

            .welcome-banner { flex-direction: column; align-items: flex-start; gap: 10px; }
            .welcome-banner h1 { font-size: 1.6rem; }
            
            .dashboard-grid { grid-template-columns: 1fr; gap: 20px; }
            
            .meta-info-strip { flex-direction: column; align-items: flex-start; gap: 6px; }
            .meta-info-strip div:last-child { margin-left: 0 !important; }
        }

        @media (max-width: 480px) {
            .dashboard-card { padding: 18px; }
            .trainee-summary-header { flex-direction: column; align-items: flex-start; gap: 8px; padding: 12px; }
            .session-count-badge { align-self: flex-start; margin-left: 24px; }
            .mini-log-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .session-type-text { text-align: left; }
            .info-row { font-size: 0.9rem; }
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
                <i class="fa-solid fa-user-shield" style="margin-right: 5px;"></i> Coach Portal
            </span>
            <a href="logout.php" class="btn btn-outline" style="text-decoration: none;">Sign Out</a>
        </div>
    </header>

    <main class="dashboard-container">
        <div class="welcome-banner">
            <div>
                <h1>Coach: <span class="red-text"><?php echo htmlspecialchars($coach_name); ?></span></h1>
                <p style="color: var(--text-gray); margin-top: 5px; font-size: 0.95rem;">Manage schedules, monitor rostered trainees, and view verified facility sessions.</p>
            </div>
        </div>

        <div class="dashboard-grid">
            
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fa-solid fa-id-card-clip"></i>
                    <h3>Trainer Profile</h3>
                </div>
                <div class="info-row"><span class="info-label">Staff Name:</span> <span class="info-value"><?php echo htmlspecialchars($coach_name); ?></span></div>
                <div class="info-row"><span class="info-label">Assigned Core Discipline:</span> <span class="red-text info-value" style="font-weight: 700;"><?php echo htmlspecialchars($coach_discipline); ?></span></div>
                <div class="info-row"><span class="info-label">Active Roster Size:</span> <span class="info-value"><?php echo $active_trainee_count; ?> Managed Trainees</span></div>
                <div class="info-row"><span class="info-label">Duty Status:</span> <span class="info-value" style="color:#4caf50; font-weight:600;">On Duty</span></div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <h3>Coach Attendance Duty Verification</h3>
                </div>
                <div class="info-row"><span class="info-value" style="text-align: left;">June 20, 2026 - Morning Gate Access Check-In</span> <span class="status-pill" style="background:#4caf50;">Verified</span></div>
                <div class="info-row"><span class="info-value" style="text-align: left;">June 19, 2026 - Evening Shift Gate Access Log</span> <span class="status-pill" style="background:#4caf50;">Verified</span></div>
                <div class="info-row"><span class="info-value" style="text-align: left;">June 16, 2026 - Afternoon Gate Access Check-In</span> <span class="status-pill" style="background:#4caf50;">Verified</span></div>
            </div>

            <div class="dashboard-card full-width">
                <div class="card-header">
                    <i class="fa-solid fa-chart-line-up"></i>
                    <h3>Trainee Performance & Progress Rosters</h3>
                </div>

                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="traineeSearch" class="search-control" placeholder="Search assigned trainees by name...">
                </div>

                <div class="scrollable-list" id="traineeListContainer">
                    <?php 
                    if ($trainees_result && $trainees_result->num_rows > 0):
                        $trainees_result->data_seek(0); 
                        
                        while($trainee = $trainees_result->fetch_assoc()): 
                            $trainee_id = $trainee['id'];
                            $trainee_name = $trainee['full_name'];
                            
                            $display_goal = trim($trainee['fitness_goal'] ?? 'General Fitness');
                            if (preg_match('/[0-9]{7,}/', preg_replace('/[^0-9]/', '', $display_goal))) {
                                $display_goal = 'General Fitness'; 
                            }

                            $my_logs = $attendance_by_trainee[$trainee_id] ?? [];
                            $total_sessions = count($my_logs);
                    ?>
                            <details class="trainee-accordion" data-name="<?php echo strtolower(htmlspecialchars($trainee_name)); ?>">
                                <summary class="trainee-summary-header">
                                    <div class="summary-left-block">
                                        <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                        <strong style="color: #fff; font-size: 1rem;"><?php echo htmlspecialchars($trainee_name); ?></strong>
                                    </div>
                                    <span class="session-count-badge"><?php echo $total_sessions; ?> Sessions Logged</span>
                                </summary>

                                <div class="trainee-dropdown-content">
                                    <div class="meta-info-strip">
                                        <div class="meta-goal-block"><span style="color:#666;">Goal:</span> <span style="color:var(--text-white);"><?php echo htmlspecialchars($display_goal); ?></span></div>
                                        <div style="margin-left: auto; flex-shrink: 0;"><span style="color:#666;">Tier Variant:</span> <span class="red-text"><?php echo htmlspecialchars($trainee['membership_tier'] ?? 'General'); ?></span></div>
                                    </div>

                                    <div class="trainee-session-history">
                                        <?php if ($total_sessions > 0): ?>
                                            <?php foreach ($my_logs as $log): 
                                                $formatted_date = date("F d, Y", strtotime($log['session_date']));
                                            ?>
                                                <div class="mini-log-row">
                                                    <span><i class="fa-regular fa-calendar-check" style="margin-right: 8px; color: var(--text-gray);"></i><?php echo $formatted_date; ?></span>
                                                    <span class="session-type-text"><?php echo htmlspecialchars($log['session_type']); ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p style="color: var(--text-gray); font-size: 0.85rem; font-style: italic; padding: 10px 5px 0 5px;">No active workouts logged for this trainee profile.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </details>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p id="emptyRosterMsg" style="color: var(--text-gray); font-size: 0.85rem; font-style: italic; text-align: center; margin-top: 30px;">
                            No assigned trainees to track performance records for.
                        </p>
                    <?php endif; ?>
                    
                    <p id="noResultsMsg" style="color: var(--text-gray); font-size: 0.85rem; font-style: italic; text-align: center; margin-top: 30px; display: none;">
                        No trainees found matching that search criteria.
                    </p>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.getElementById('traineeSearch').addEventListener('input', function(e) {
            const searchValue = e.target.value.toLowerCase().trim();
            const accordions = document.querySelectorAll('.trainee-accordion');
            const noResultsMsg = document.getElementById('noResultsMsg');
            let visibleCount = 0;

            accordions.forEach(function(accordion) {
                const nameAttr = accordion.getAttribute('data-name');
                
                if (nameAttr.includes(searchValue)) {
                    accordion.style.display = "block";
                    visibleCount++;
                } else {
                    accordion.style.display = "none";
                }
            });

            if (visibleCount === 0 && accordions.length > 0) {
                noResultsMsg.style.display = "block";
            } else {
                noResultsMsg.style.display = "none";
            }
        });
    </script>
</body>
</html>