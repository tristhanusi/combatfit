# 🏋️‍♂️ Bad Boys Fit & Brawl - HQ Management System

An AJAX-driven, lightweight Monolithic MVC management portal built to streamline gym operations, track member leads, assign on-duty coaches, and handle administrative security.

## 🛠️ Tech Stack & Architecture
* **Backend Framework:** Native PHP (Procedural/Hybrid Session Control)
* **Database Layer:** MySQLi (Object-Oriented)
* **Frontend UI/UX:** Vanilla JavaScript (Fetch API Engine), Semantic HTML5, Native CSS3 Grid/Flexbox
* **Icons:** Font Awesome v6.4.0
* **Hosting Environment:** Apache Server (InfinityFree Web Stack)

## ✨ Core Features
* **Live System Analytics:** Real-time polling monitoring active memberships, pending walk-in leads, and coach registries.
* **Inline Dynamic User Activation:** Secure administrative credential generation and instant status updates via background AJAX transactions.
* **Dynamic Coach Deployment:** CRUD system for active staff rosters preventing system collisions if members are actively assigned.
* **Granular Profile Control:** On-the-fly updates to account states, membership tiers (Regular, Prime, Premium), and personal trainer allocations.

## ⚙️ Installation & Local Setup
1. Clone this repository or download the ZIP file.
2. Import the database schema (`.sql` export) into your local MySQL server via phpMyAdmin.
3. Update your database configuration credentials inside `db.php`.
4. Drop the project folder into your local server root directory (e.g., `xampp/htdocs/`).
5. Open your browser and navigate to `http://localhost/your-project-folder/admin-login.php`.
