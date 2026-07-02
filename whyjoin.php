<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Why Join? - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }

        .why-section {
            padding: 60px 4%;
            background-color: var(--bg-color);
            color: #fff;
        }
        .why-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 70px;
        }
        .feature-box {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            padding: 30px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        .feature-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-red);
        }
        .feature-box h3 {
            font-size: 1.3rem;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 12px;
            color: #fff;
        }
        .feature-box p {
            color: var(--text-gray);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .reviews-heading {
            text-align: center;
            text-transform: uppercase;
            font-weight: 900;
            font-size: 2rem;
            margin-bottom: 40px;
        }
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 25px;
        }
        .review-card {
            background: #161616;
            border: 1px solid var(--border-color);
            padding: 20px;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .review-proof-container {
            width: 100%;
            aspect-ratio: 4 / 3; 
            overflow: hidden;
            border-radius: 4px;
            border: 1px solid #252525;
            background: #111;
            margin-bottom: 18px;
        }
        .review-proof-img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }

        .stars {
            color: #ffc107;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }
        .review-text {
            color: #e0e0e0;
            font-style: italic;
            line-height: 1.6;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }
        .client-info {
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid #222;
            padding-top: 15px;
        }
        .client-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: var(--accent-red);
            border: 2px solid var(--border-color);
            flex-shrink: 0;
        }
        .client-details h4 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .client-details span {
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }

        @media (max-width: 768px) {
            .why-section {
                padding: 40px 5%;
            }

            .navbar {
                flex-direction: column;
                gap: 12px;
                padding: 15px 4%;
                text-align: center;
                height: auto !important;
            }
            
            .nav-links {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                justify-content: center !important;
                align-items: center !important;
                gap: 12px !important;
                width: 100% !important;
            }

            .nav-links a {
                font-size: 0.85rem !important;
                white-space: nowrap !important;
                padding: 4px 2px !important;
            }

            .auth-buttons {
                width: 100%;
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 5px;
            }
            .auth-buttons .btn {
                flex: 1;
                max-width: 140px;
                text-align: center;
                padding: 8px 12px;
                font-size: 0.85rem;
            }

            .page-header { margin-bottom: 35px; }
            .page-header h1 { font-size: 1.85rem; }
            .page-header p { font-size: 0.95rem !important; }
            .reviews-heading { font-size: 1.6rem; margin-bottom: 30px; }

            .features-grid { 
                grid-template-columns: 1fr; 
                gap: 20px; 
                margin-bottom: 50px; 
            }
            .feature-box { padding: 25px 20px; }
            .feature-box h3 { font-size: 1.15rem; }

            .reviews-grid { 
                grid-template-columns: 1fr; 
                gap: 20px; 
            }

            footer {
                padding: 40px 5% 20px 5% !important;
            }
            footer > div:first-child {
                grid-template-columns: 1fr !important;
                gap: 30px !important;
            }
            
            .delay-100, .delay-200, .delay-300 { transition-delay: 0ms !important; }
        }

        @media (max-width: 480px) {
            .page-header h1 { font-size: 1.6rem; }
            .logo-text { font-size: 0.9rem; }
            .navbar-logo { width: 28px; height: 28px; }
            .nav-links { gap: 8px !important; }
            .nav-links a { font-size: 0.8rem !important; }
        }
    </style>
</head>
<body>

    <header class="navbar">
        <a href="index.php" class="logo-container" style="text-decoration: none;">
            <img src="Images/logo.jpg" alt="Bad Boys Fit & Brawl Logo" class="navbar-logo">
            <div class="logo-text">BAD BOYS<span class="red-text"> FIT & BRAWL</span></div>
        </a>
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="whyjoin.php" class="active">Why Join?</a>
            <a href="about.php">About Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="inquire.php" class="btn btn-outline" style="text-decoration: none; display: inline-block;">Join Now</a>
            <a href="login.php" class="btn btn-solid" style="text-decoration: none; display: inline-block;">Sign In</a>
        </div>
    </header>

    <main class="why-section">
        <div class="why-wrapper">
            
            <div class="page-header scroll-reveal">
                <h1>WHY TRAIN WITH <span class="red-text">BAD BOYS?</span></h1>
                <p style="color: var(--text-gray); margin-top: 10px; font-size: 1.05rem;">We don't run a commercial health club. We run a performance forge.</p>
            </div>

            <div class="features-grid">
                <div class="feature-box scroll-reveal">
                    <h3>Elite Coaching Standards</h3>
                    <p>Learn from certified, experienced instructors who specialize in raw ring-craft, defensive fight mechanics, and comprehensive sparring drills. No guesswork—just elite progression blueprints.</p>
                </div>
                <div class="feature-box scroll-reveal delay-100">
                    <h3>Premium Combat Layout</h3>
                    <p>Train inside an authentic full-sized boxing ring, elite heavy bag stations, and tactical equipment configurations purpose-built to survive heavy-duty high-performance output.</p>
                </div>
                <div class="feature-box scroll-reveal delay-200">
                    <h3>Structured Portals</h3>
                    <p>Track your attendance, view custom athletic progress bars, and connect directly to your assigned coach via structured, private Trainee and Trainer data consoles.</p>
                </div>
            </div>

            <h2 class="reviews-heading scroll-reveal"> WHAT OUR <span class="red-text">FIGHTERS SAY</span></h2>
            
            <div class="reviews-grid">
                
                <div class="review-card scroll-reveal">
                    <div>
                        <div class="review-proof-container">
                            <img src="Images/mark.jpg" alt="Mark Anthony Proof" class="review-proof-img" onerror="this.src='https://via.placeholder.com/400x300/252525/ffffff?text=Mark+Training+Proof';">
                        </div>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="review-text">"Best combat gym in Malabon, hands down. The boxing trainers don't just give you casual pad work; they focus heavily on your stance and tactical movement. Worth every peso."</p>
                    </div>
                    <div class="client-info">
                        <div class="client-avatar">Mark</div>
                        <div class="client-details">
                            <h4>Mark Anthony</h4>
                            <span>Premium Member (Boxing)</span>
                        </div>
                    </div>
                </div>

                <div class="review-card scroll-reveal delay-100">
                    <div>
                        <div class="review-proof-container">
                            <img src="Images/sarah.jpg" alt="Sarah G. Proof" class="review-proof-img" onerror="this.src='https://via.placeholder.com/400x300/252525/ffffff?text=Sarah+Training+Proof';">
                        </div>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="review-text">"The strength and conditioning layouts here completely transformed my endurance levels. The portal dashboard also makes tracking my session attendance logs extremely clean."</p>
                    </div>
                    <div class="client-info">
                        <div class="client-avatar">Sarah</div>
                        <div class="client-details">
                            <h4>Sarah P.</h4>
                            <span>MMA Trainee</span>
                        </div>
                    </div>
                </div>

                <div class="review-card scroll-reveal delay-200">
                    <div>
                        <div class="review-proof-container">
                            <img src="Images/john.jpg" alt="John Doe Proof" class="review-proof-img" onerror="this.src='https://via.placeholder.com/400x300/252525/ffffff?text=John+Training+Proof';">
                        </div>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                        </div>
                        <p class="review-text">"Intense atmosphere but zero toxic ego. Everyone from the coaches to the senior members push you to break your limits safely. Strongly recommended!"</p>
                    </div>
                    <div class="client-info">
                        <div class="client-avatar">John</div>
                        <div class="client-details">
                            <h4>John Michael</h4>
                            <span>Conditioning Member</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer style="background-color: #111; border-top: 1px solid var(--border-color); padding: 40px 4% 20px 4%; margin-top: 60px; color: #fff;">
        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
            
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                    <img src="Images/logo.jpg" alt="Bad Boys Logo" style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px;">
                    <span style="font-weight: 900; font-size: 1.1rem; letter-spacing: -0.5px; text-transform: uppercase;">BAD BOYS<span class="red-text"> FIT & BRAWL</span></span>
                </div>
                <p style="color: var(--text-gray); font-size: 0.85rem; line-height: 1.6;">Malabon's premier performance forge for realistic combat ring-craft and raw athletic engineering.</p>
            </div>

            <div>
                <h4 style="text-transform: uppercase; font-size: 0.9rem; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 15px; border-left: 3px solid var(--accent-red); padding-left: 10px;">Quick Links</h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem;">
                    <li style="margin-bottom: 10px;"><a href="index.php" style="color: var(--text-gray); text-decoration: none; transition: color 0.2s;">Home</a></li>
                    <li style="margin-bottom: 10px;"><a href="services.php" style="color: var(--text-gray); text-decoration: none; transition: color 0.2s;">Services</a></li>
                    <li style="margin-bottom: 10px;"><a href="whyjoin.php" style="color: var(--text-gray); text-decoration: none; transition: color 0.2s;">Why Join?</a></li>
                    <li style="margin-bottom: 10px;"><a href="about.php" style="color: var(--text-gray); text-decoration: none; transition: color 0.2s;">About Us</a></li>
                </ul>
            </div>

            <div>
                <h4 style="text-transform: uppercase; font-size: 0.9rem; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 15px; border-left: 3px solid var(--accent-red); padding-left: 10px;">Connect With Us</h4>
                <p style="color: var(--text-gray); font-size: 0.85rem; margin-bottom: 12px;"><i class="fa-solid fa-location-dot red-text" style="margin-right: 8px;"></i> Malabon City, Philippines</p>
                <div style="display: flex; gap: 15px; font-size: 1.2rem; margin-top: 15px;">
                    <a href="https://www.facebook.com/profile.php?id=61580471945698" target="_blank" rel="noopener noreferrer" style="color: var(--text-gray); transition: color 0.2s;">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/badboysfitandbrwl?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw%3D%3D" target="_blank" rel="noopener noreferrer" style="color: var(--text-gray); transition: color 0.2s;">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>
            </div>

        </div>

        <div style="max-width: 1200px; margin: 40px auto 0 auto; padding-top: 20px; border-top: 1px solid #222; text-align: center; color: var(--text-gray); font-size: 0.8rem;">
            &copy; 2026 Bad Boys Fit & Brawl. All Rights Reserved. Engineered for Performance.
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const animatedElements = document.querySelectorAll('.scroll-reveal');

            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -80px 0px',
                threshold: 0.15
            };

            const revealObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            animatedElements.forEach(element => {
                revealObserver.observe(element);
            });
        });
    </script>

</body>
</html>