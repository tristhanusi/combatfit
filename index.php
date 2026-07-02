<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CombatFit - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { 
            box-sizing: border-box; 
            transition: background-color 0.3s ease, border-color 0.3s ease, transform 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        }

        body {
            background-color: var(--bg-color);
            color: #fff;
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            will-change: transform, opacity;
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

        .home-page-container {
            padding: 60px 4%;
            background-color: var(--bg-color);
        }
        
        .section-inner-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        .services-hero { 
            padding: 60px 4%; 
            text-align: center; 
        }
        .services-hero h1 { 
            font-size: 2.5rem; 
            font-weight: 900; 
            text-transform: uppercase; 
            margin: 0;
        }
        .services-hero p { 
            color: var(--text-gray); 
            margin-top: 10px; 
            font-size: 1rem;
        }

        .banner-area {
            width: 100%;
            height: 500px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            position: relative;
            margin-bottom: 40px;
        }
        .carousel {
            width: 100%;
            height: 100%;
            position: relative;
        }
        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 0 8%;
            transition: opacity 0.6s ease-in-out, visibility 0.6s ease-in-out;
            z-index: 1;
        }
        .carousel-slide.active {
            opacity: 1;
            visibility: visible;
            z-index: 2;
        }
        .slide-content {
            max-width: 600px;
            transform: translateY(20px);
            transition: transform 0.6s ease-out 0.2s;
        }
        .carousel-slide.active .slide-content {
            transform: translateY(0);
        }
        .slide-content h1 {
            font-size: 3rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
            margin: 15px 0;
            line-height: 1.1;
        }
        .slide-content p {
            color: #e0e0e0;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .badge {
            background: var(--accent-red);
            color: #fff;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 3px;
            display: inline-block;
        }

        .section-title {
            text-transform: uppercase;
            font-weight: 900;
            font-size: 2rem;
            margin-top: 0;
            margin-bottom: 30px;
        }
        .section-desc {
            color: var(--text-gray);
            margin-top: -20px;
            margin-bottom: 35px;
            font-size: 0.95rem;
            max-width: 700px;
            line-height: 1.6;
        }

        .announcement-panel {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 25px;
            margin-bottom: 50px;
        }
        .announcement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .announcement-item {
            background: rgba(0,0,0,0.2);
            border: 1px solid #222;
            padding: 20px;
            border-radius: 6px;
        }
        .announcement-date {
            color: var(--accent-red);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 8px;
        }
        .announcement-item h4 {
            margin: 0 0 10px 0;
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
        }
        .announcement-item p {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        .service-card {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .service-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-red);
        }
        .service-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .service-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .service-content h3 {
            text-transform: uppercase;
            font-weight: 800;
            font-size: 1.2rem;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .service-content p {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        .feature-row-box {
            background: #141414;
            border-left: 4px solid var(--accent-red);
            padding: 25px;
            border-radius: 0 6px 6px 0;
            border-top: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }
        .feature-row-box h4 {
            margin: 0 0 8px 0;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 1.1rem;
        }
        .feature-row-box p {
            margin: 0;
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .about-preview-block {
            background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.95)), url('https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?q=80&w=1200');
            background-size: cover;
            background-position: center;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 50px 40px;
            text-align: center;
            margin-top: 20px;
        }
        .about-preview-block h2 {
            font-size: 2.2rem; 
            font-weight: 900; 
            text-transform: uppercase; 
            margin-top: 0; 
            margin-bottom: 20px;
        }
        .about-preview-content {
            max-width: 800px;
            margin: 0 auto;
        }
        .about-preview-content p {
            color: #ddd;
            font-size: 1.05rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
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
                flex-wrap: nowrap !important; /* Strictly blocks item multi-line wrapping */
                justify-content: center !important;
                align-items: center !important;
                gap: 12px !important; 
                width: 100% !important;
            }
            
            .nav-links a {
                font-size: 0.85rem !important; 
                white-space: nowrap !important; /* Prevents text splitting mid-link */
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
            
            .home-page-container {
                padding: 30px 4% !important;
            }
            
            .services-hero { 
                padding: 30px 15px !important; 
            }
            
            .services-hero h1 { 
                font-size: 1.8rem !important; 
            }

            .banner-area {
                height: auto !important;
                min-height: auto !important;
                padding: 0 !important;
                margin-bottom: 25px;
                overflow: hidden;
            }
            
            .carousel {
                height: auto !important;
            }

            .carousel-slide {
                position: relative !important;
                opacity: 0;
                display: none;
                padding: 45px 6% !important;
                height: auto !important;
            }
            
            .carousel-slide.active {
                opacity: 1;
                display: flex;
            }

            .slide-content h1 {
                font-size: 1.6rem !important;
                line-height: 1.2;
                margin: 10px 0;
            }
            
            .slide-content p {
                font-size: 0.85rem !important;
                margin-bottom: 20px;
                line-height: 1.5;
            }

            .btn-large {
                padding: 10px 20px !important;
                font-size: 0.85rem !important;
            }

            .section-title {
                font-size: 1.5rem !important;
                text-align: center;
            }
            
            .section-desc {
                text-align: center;
                margin-left: auto;
                margin-right: auto;
                font-size: 0.85rem;
            }

            .announcement-grid, .services-grid {
                grid-template-columns: 1fr !important;
                gap: 20px !important;
            }
            
            .about-preview-block {
                padding: 35px 20px;
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
            .logo-text { font-size: 0.9rem; }
            .navbar-logo { width: 28px; height: 28px; }
            .banner-area { min-height: auto !important; }
            .carousel-slide { padding: 35px 5% !important; }
            .slide-content h1 { font-size: 1.4rem !important; }
            .about-preview-block { text-align: left; }
            
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
            <a href="index.php" class="active">Home</a>
            <a href="services.php">Services</a>            
            <a href="whyjoin.php">Why Join?</a>
            <a href="about.php">About Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="inquire.php" class="btn btn-outline" style="text-decoration: none; display: inline-block;">Join Now</a>
            <a href="login.php" class="btn btn-solid" style="text-decoration: none; display: inline-block;">Sign In</a>
        </div>
    </header>

    <main class="services-page">
        
        <section class="services-hero scroll-reveal active">
            <h1>OUR PERFORMANCE <span class="red-text">FORGE HUB</span></h1>
            <p>Welcome to Malabon's premier target station for elite combat ring-craft and physical conditioning.</p>
        </section>

        <div class="home-page-container">
            <div class="section-inner-wrapper">

                <section class="banner-area scroll-reveal delay-100">
                    <div class="carousel">
                        <div class="carousel-slide active" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1517838277536-f5f99be501cd?q=80&w=1200');">
                            <div class="slide-content">
                                <span class="badge">MALABON'S PREMIUM COMBAT GYM</span>
                                <h1>UNLEASH YOUR <span class="red-text">INNER BEAST</span></h1>
                                <p>Experience premier boxing, MMA, strength training, and elite conditioning routines.</p>
                                <a href="inquire.php" class="btn btn-solid btn-large" style="text-decoration:none;">Book a Session</a>
                            </div>
                        </div>
                        <div class="carousel-slide" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?q=80&w=1200');">
                            <div class="slide-content">
                                <span class="badge">ELITE TRAINING</span>
                                <h1>CRUSH YOUR <span class="red-text">FITNESS GOALS</span></h1>
                                <p>Work 1-on-1 with expert certified coaches tailored to your skill tier.</p>
                                <a href="services.php" class="btn btn-solid btn-large" style="text-decoration:none;">Explore Programs</a>
                            </div>
                        </div>
                    </div>
                </section>

                <aside class="announcement-panel scroll-reveal">
                    <h3 style="font-size: 1.2rem; font-weight: 900; text-transform: uppercase; margin-top: 0; margin-bottom: 20px; border-left: 4px solid var(--accent-red); padding-left: 12px;"><i class="fa-solid fa-bullhorn text-red"></i> What's New</h3>
                    <div class="announcement-grid">
                        <div class="announcement-item scroll-reveal delay-100">
                            <span class="announcement-date">June 15, 2026</span>
                            <h4>Grand Opening Promo!</h4>
                            <p>Get 20% off on our annual premium combat sports membership this month!</p>
                        </div>
                        <div class="announcement-item scroll-reveal delay-200">
                            <span class="announcement-date">June 12, 2026</span>
                            <h4>Striking Class Schedule</h4>
                            <p>New MMA and Boxing schedules are now updated for evening sessions.</p>
                        </div>
                        <div class="announcement-item scroll-reveal delay-300">
                            <span class="announcement-date">June 08, 2026</span>
                            <h4>Pro Trainer Spotlight</h4>
                            <p>Book a slot with our certified Muay Thai coaches this weekend.</p>
                        </div>
                    </div>
                </aside>

                <section style="margin-top: 20px;">
                    <div class="scroll-reveal">
                        <h2 class="section-title">TRAINING <span class="red-text">PROGRAMS</span></h2>
                        <p class="section-desc">We engineer high-performance training regimens engineered to reconstruct raw power, explosive stamina, and premium combat defense.</p>
                    </div>
                    
                    <div class="services-grid">
                        <div class="service-card scroll-reveal delay-100">
                            <div class="service-image" style="background-image: url('Images/boxing2.jpg');"></div>
                            <div class="service-content">
                                <div>
                                    <h3>Boxing Mechanics</h3>
                                    <p>Master authentic boxing ring-craft, high-speed combinations, heavy bag execution, and professional heavy-duty pad drills.</p>
                                </div>
                                <a href="services.php" class="btn btn-outline" style="text-decoration:none; width:100%; text-align:center;">Learn More</a>
                            </div>
                        </div>
                        <div class="service-card scroll-reveal delay-200">
                            <div class="service-image" style="background-image: url('Images/mma.jpg');"></div>
                            <div class="service-content">
                                <div>
                                    <h3>Mixed Martial Arts</h3>
                                    <p>An explosive discipline fusing technical striking routines, wrestling setups, and structured combat submission grappling systems.</p>
                                </div>
                                <a href="services.php" class="btn btn-outline" style="text-decoration:none; width:100%; text-align:center;">Learn More</a>
                            </div>
                        </div>
                        <div class="service-card scroll-reveal delay-300">
                            <div class="service-image" style="background-image: url('Images/conditioning.jpg');"></div>
                            <div class="service-content">
                                <div>
                                    <h3>Conditioning Forge</h3>
                                    <p>Functional power output exercises using free weights, heavy kettlebells, and speed stations custom engineered for raw combat endurance.</p>
                                </div>
                                <a href="services.php" class="btn btn-outline" style="text-decoration:none; width:100%; text-align:center;">Learn More</a>
                            </div>
                        </div>
                    </div>
                </section>

                <section style="margin-top: 20px;">
                    <div class="scroll-reveal">
                        <h2 class="section-title">WHY TRAIN <span class="red-text">WITH US?</span></h2>
                        <p class="section-desc">Bad Boys Fit & Brawl runs an elite performance forge purpose-built to break performance records safely and cleanly.</p>
                    </div>
                    
                    <div class="services-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
                        <div class="feature-row-box scroll-reveal delay-100">
                            <h4><i class="fa-solid fa-star red-text" style="margin-right:8px;"></i> Certified Ring Instructors</h4>
                            <p>Train directly under battle-tested fighters and coaches focused heavily on your stance, defensive posture, and progression logs.</p>
                        </div>
                        <div class="feature-row-box scroll-reveal delay-200">
                            <h4><i class="fa-solid fa-circle-check red-text" style="margin-right:8px;"></i> Tactical Combat Spaces</h4>
                            <p>Gain access to an authentic full-sized sparring ring, specialized floor gear configurations, and premier heavy bags setup for extreme abuse.</p>
                        </div>
                        <div class="feature-row-box scroll-reveal delay-300">
                            <h4><i class="fa-solid fa-chart-simple red-text" style="margin-right:8px;"></i> Clean Portal Tracking</h4>
                            <p>Ditch paper slips. Authenticate via biometric face metrics, log attendance logs, and track customized athletic bar progress safely inside your console portals.</p>
                        </div>
                    </div>
                </section>

                <section style="margin-top: 20px;" class="scroll-reveal">
                    <div class="about-preview-block">
                        <div class="about-preview-content">
                            <h2>ABOUT <span class="red-text">OUR FORGE</span></h2>
                            <p>Located in the heart of Malabon City, Bad Boys Fit & Brawl stands as the premier hub for realistic combat ring-craft and elite physiological conditioning. We intentionally reject toxic commercial gym culture in order to provide a focused, high-performance training refuge for authentic athletes and determined trainees.</p>
                            <a href="about.php" class="btn btn-solid btn-large" style="text-decoration:none; display:inline-block;">Read Our Full Mission</a>
                        </div>
                    </div>
                </section>

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

    <script src="script.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const animatedElements = document.querySelectorAll('.scroll-reveal');

            const observerOptions = {
                root: null, 
                rootMargin: '0px 0px -60px 0px',
                threshold: 0.12 
            };

            const scrollObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            animatedElements.forEach(element => {
                scrollObserver.observe(element);
            });
        });
    </script>
</body>
</html>