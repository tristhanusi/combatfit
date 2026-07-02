<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Trainee') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $age = intval($_POST['age']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $membership_tier = mysqli_real_escape_string($conn, $_POST['membership_tier']);
    $fitness_goal = mysqli_real_escape_string($conn, $_POST['fitness_goal']);
    
    $avail_coach = $_POST['avail_coach'];
    $assigned_coach_id = "NULL";
    if ($avail_coach === 'yes' && isset($_POST['selected_coach'])) {
        $assigned_coach_id = intval($_POST['selected_coach']);
    }

    $sql = "UPDATE users SET 
            age = $age, 
            sex = '$sex', 
            address = '$address', 
            membership_tier = '$membership_tier', 
            fitness_goal = '$fitness_goal', 
            assigned_coach_id = $assigned_coach_id 
            WHERE id = $user_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard-member.php");
        exit();
    } else {
        $message = "<div class='msg error-msg'>Error saving profiles setup: " . $conn->error . "</div>";
    }
}

$coaches_result = $conn->query("SELECT * FROM coaches");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Profile - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .setup-wrapper { display: flex; justify-content: center; align-items: center; padding: 50px 20px; background-color: var(--bg-color); min-height: 100vh; }
        .setup-container { width: 100%; max-width: 700px; background: var(--panel-bg); padding: 40px; border-radius: 8px; border: 1px solid var(--border-color); }
        .setup-title { text-transform: uppercase; font-weight: 900; text-align: center; margin-bottom: 30px; letter-spacing: -0.5px; }
        .section-subtitle { text-transform: uppercase; font-size: 1rem; font-weight: 800; color: var(--accent-red); margin: 25px 0 15px 0; border-bottom: 1px solid var(--border-color); padding-bottom: 5px; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .full-width { grid-column: span 2; }
        .form-group label { display: block; font-size: 0.8rem; margin-bottom: 7px; color: var(--text-gray); text-transform: uppercase; font-weight: 700; }
        .form-control { width: 100%; padding: 11px; background: #222; border: 1px solid var(--border-color); color: #fff; border-radius: 4px; font-family: var(--font-family); font-size: 0.95rem; }
        .form-control:focus { border-color: var(--accent-red); outline: none; }
        
        #coach_selection_area { display: none; margin-top: 20px; }
        .coach-roster { display: flex; flex-direction: column; gap: 15px; }
        .coach-card { background: #1a1a1a; border: 1px solid var(--border-color); padding: 15px; border-radius: 6px; display: flex; align-items: center; gap: 15px; cursor: pointer; transition: all 0.2s; }
        .coach-card:hover { border-color: var(--text-gray); }
        .coach-card input[type="radio"] { width: 20px; height: 20px; accent-color: var(--accent-red); }
        .coach-info { flex-grow: 1; }
        .coach-name { font-weight: 800; text-transform: uppercase; font-size: 1rem; color: #fff; }
        .coach-spec { font-size: 0.85rem; color: var(--accent-red); font-weight: 600; margin: 2px 0; }
        .coach-skills { font-size: 0.8rem; color: var(--text-gray); }
        .coach-rating { font-size: 0.85rem; color: #ffc107; font-weight: bold; display: flex; align-items: center; gap: 4px; }
        
        .coach-card.selected-card { border-color: var(--accent-red); background: rgba(211,47,47,0.05); }
    </style>
</head>
<body>

    <div class="setup-wrapper">
        <div class="setup-container">
            <h2 class="setup-title">Complete Your <span class="red-text">Fighter Profile</span></h2>
            
            <?php echo $message; ?>
            
            <form action="onboarding.php" method="POST">
                
                <div class="section-subtitle">1. Customer Profile Personal Info</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" required min="12" max="100">
                    </div>
                    <div class="form-group">
                        <label>Sex / Gender</label>
                        <select name="sex" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Prefer Not To Say">Prefer Not To Say</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label>Home Address</label>
                        <input type="text" name="address" class="form-control" placeholder="House No, Street, Barangay, City" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Desired Membership Tier</label>
                        <select name="membership_tier" class="form-control" required>
                            <option value="Standard Brawler (Gym Access Only)">Standard Brawler (Gym Access Only)</option>
                            <option value="Premium Brawl Member (Classes Included)">Premium Brawl Member (Classes Included)</option>
                            <option value="Elite VIP Competitor Complex">Elite VIP Competitor Complex</option>
                        </select>
                    </div>
                </div>

                <div class="section-subtitle">2. Performance Progress Goal</div>
                <div class="form-group">
                    <label>What specific combat fitness goal are you availing?</label>
                    <select name="fitness_goal" class="form-control" required>
                        <option value="Boxing Basics & Pure Ring Footwork">Boxing Basics & Pure Ring Footwork</option>
                        <option value="Mixed Martial Arts (MMA) Ground Tactical & Striking">Mixed Martial Arts (MMA) Ground Tactical & Striking</option>
                        <option value="Explosive Functional Strength & Power Conditioning">Explosive Functional Strength & Power Conditioning</option>
                        <option value="Weight Weight Mitigation & Active Cardiovascular Sparring">Weight Mitigation & Active Cardiovascular Sparring</option>
                    </select>
                </div>

                <div class="section-subtitle">3. Personal Coach Assignment</div>
                <div class="form-group">
                    <label>Do you want to avail of a certified personal Coach?</label>
                    <select name="avail_coach" id="avail_coach" class="form-control" onchange="toggleCoachSection()" required>
                        <option value="no">No, I prefer training independently for now</option>
                        <option value="yes">Yes, I want to assign a professional trainer</option>
                    </select>
                </div>

                <div id="coach_selection_area">
                    <label style="display:block; font-size:0.8rem; margin-bottom:10px; color:var(--text-gray); text-transform:uppercase; font-weight:700;">Select Your Coach:</label>
                    <div class="coach-roster">
                        <?php if($coaches_result->num_rows > 0): ?>
                            <?php while($coach = $coaches_result->fetch_assoc()): ?>
                                <label class="coach-card" onclick="highlightCoachCard(this)">
                                    <input type="radio" name="selected_coach" value="<?php echo $coach['id']; ?>">
                                    <div class="coach-info">
                                        <div class="coach-name"><?php echo htmlspecialchars($coach['name']); ?></div>
                                        <div class="coach-spec"><?php echo htmlspecialchars($coach['specialty']); ?></div>
                                        <div class="coach-skills">Skills: <?php echo htmlspecialchars($coach['skills']); ?></div>
                                    </div>
                                    <div class="coach-rating">
                                        <i class="fa-solid fa-star"></i> <?php echo $coach['rating']; ?>
                                    </div>
                                </label>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-solid" style="width: 100%; padding: 14px; margin-top: 35px; font-weight:800; text-transform:uppercase;">Save Setup & Open Dashboard</button>
            </form>
        </div>
    </div>

    <script>
        function toggleCoachSection() {
            var selection = document.getElementById("avail_coach").value;
            var coachArea = document.getElementById("coach_selection_area");
            var radios = document.querySelectorAll('input[name="selected_coach"]');
            
            if (selection === "yes") {
                coachArea.style.display = "block";
                if (radios.length > 0) {
                    radios[0].checked = true; 
                    highlightCoachCard(radios[0].closest('.coach-card'));
                }
            } else {
                coachArea.style.display = "none"; 
                radios.forEach(radio => radio.checked = false);
            }
        }

        function highlightCoachCard(element) {
            if (!element) return;
            document.querySelectorAll('.coach-card').forEach(card => card.classList.remove('selected-card'));
            element.classList.add('selected-card');
            
            var radio = element.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        }
    </script>
</body>
</html>