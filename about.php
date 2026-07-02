<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Global Reset for layout calculations */
        * { box-sizing: border-box; }

        .about-section {
            padding: 60px 4%;
            background-color: var(--bg-color);
            color: #fff;
        }
        .about-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about-hero {
            text-align: center;
            margin-bottom: 60px;
            padding: 40px 20px;
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('Images/logo.jpg');
            background-position: center;
            background-size: contain;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }
        .about-hero h1 {
            font-size: 2.8rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
            margin-bottom: 15px;
        }
        .about-hero p {
            color: var(--text-gray);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
            margin-bottom: 60px;
        }
        .about-text h2 {
            font-size: 2rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-red);
            padding-left: 15px;
        }
        .about-text p {
            color: var(--text-gray);
            line-height: 1.7;
            margin-bottom: 15px;
            font-size: 1rem;
        }
        .about-img-box {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            background: var(--panel-bg);
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .about-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .pillars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 60px;
        }
        .pillar-card {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            padding: 30px;
            border-radius: 8px;
            transition: border-color 0.3s, opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .pillar-card:hover {
            border-color: var(--accent-red);
        }
        .pillar-card i {
            font-size: 2rem;
            color: var(--accent-red);
            margin-bottom: 15px;
        }
        .pillar-card h3 {
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 12px;
            font-size: 1.2rem;
        }
        .pillar-card p {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .location-section {
            margin-top: 60px;
            border-top: 1px dashed var(--border-color);
            padding-top: 60px;
        }
        .location-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }
        .map-wrapper {
            width: 100%;
            height: 450px;
            background: #111;
        }
        .map-wrapper iframe {
            width: 100%;
            height: 100%;
            border: 0;
            filter: grayscale(0.8) invert(0.9) contrast(1.2); /* Dark Map adaptation */
        }
        .location-info-panel {
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .info-row {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }
        .info-row:last-child { margin-bottom: 0; }
        .info-row i {
            font-size: 1.4rem;
            color: var(--accent-red);
            margin-top: 3px;
        }
        .info-row h4 {
            font-size: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 5px;
            color: #fff;
        }
        .info-row p {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .reveal-right {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-reveal.active,
        .reveal-left.active,
        .reveal-right.active {
            opacity: 1;
            transform: translate(0, 0);
        }

        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }

        @media (max-width: 992px) {
            .location-grid { grid-template-columns: 1fr; }
            .map-wrapper { height: 350px; }
            .location-info-panel { padding: 35px 25px; }
        }
        
        @media (max-width: 768px) {
            .about-section { padding: 40px 5%; }
            
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
                flex-wrap: nowrap !important; /* Blocks secondary layout line breaks */
                justify-content: center !important;
                align-items: center !important;
                gap: 12px !important;
                width: 100% !important;
            }
            
            .nav-links a {
                font-size: 0.85rem !important; /* Uniform structure sizing */
                white-space: nowrap !important; /* Stops individual links from breaking into multi-line words */
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

            .about-grid { grid-template-columns: 1fr; gap: 30px; }
            .about-hero { padding: 30px 15px; margin-bottom: 40px; }
            .about-hero h1 { font-size: 2rem; }
            .about-hero p { font-size: 0.95rem; }
            
            .about-text h2 { font-size: 1.6rem; }
            .about-img-box { height: 260px; }
            
            .pillars-grid { grid-template-columns: 1fr; gap: 20px; }
            .pillar-card { padding: 22px; }
            
            .location-section { margin-top: 40px; padding-top: 40px; }

            .reveal-left { transform: translateY(20px); }
            .reveal-right { transform: translateY(20px); }
            .delay-100, .delay-200 { transition-delay: 0ms !important; }
        }

        @media (max-width: 480px) {
            .about-hero h1 { font-size: 1.7rem; }
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
            <a href="whyjoin.php">Why Join?</a>
            <a href="about.php" class="active">About Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="inquire.php" class="btn btn-outline" style="text-decoration: none; display: inline-block;">Join Now</a>
            <a href="login.php" class="btn btn-solid" style="text-decoration: none; display: inline-block;">Sign In</a>
        </div>
    </header>

    <main class="about-section">
        <div class="about-wrapper">
            
            <div class="about-hero scroll-reveal">
                <h1>WE BREED <span class="red-text">CHAMPIONS</span></h1>
                <p>The premium destination for authentic combat sports engineering, martial arts refinement, and explosive functional fitness conditioning in Malabon.</p>
            </div>

            <div class="about-grid">
                <div class="about-text reveal-left">
                    <h2>Our Story</h2>
                    <p>Founded on raw grit and the pursuit of elite physical mastery, Bad Boys Fit & Brawl was built to bridge the gap between high-performance athletic programming and realistic combat ring craftsmanship.</p>
                    <p>Whether you are stepping into a regulation boxing ring for the very first time, refining your tactical ground execution, or developing explosive raw conditioning, our environment is designed to break limitations and engineer results.</p>
                </div>
                <div class="about-img-box reveal-right">
                    <img src="Images/about.jpg" alt="Bad Boys Fit & Brawl Training Area">
                </div>
            </div>

            <h2 class="scroll-reveal" style="text-transform: uppercase; font-weight: 800; text-align: center; margin-bottom: 35px; font-size: 1.75rem;">The <span class="red-text">Brawl Code</span></h2>
            
            <div class="pillars-grid">
                <div class="pillar-card scroll-reveal">
                    <i class="fa-solid fa-shield-halved"></i>
                    <h3>Authentic Discipline</h3>
                    <p>No shortcuts. We focus heavily on fundamental fight geometry, authentic footwork alignment, and real defensive safety habits under certified instruction.</p>
                </div>
                <div class="pillar-card scroll-reveal delay-100">
                    <i class="fa-solid fa-heart-pulse"></i>
                    <h3>Elite Conditioning</h3>
                    <p>Our program schedules optimize functional metabolic output, explosive power delivery, and aerobic endurance built directly for heavy canvas recovery.</p>
                </div>
                <div class="pillar-card scroll-reveal delay-200">
                    <i class="fa-solid fa-gavel"></i>
                    <h3>Forged Community</h3>
                    <p>From amateur trainees to elite active coaches, we maintain an inclusive, ego-free compound where respect, hard work, and mutual elevation are strictly required.</p>
                </div>
            </div>

            <div class="location-section">
                <h2 class="scroll-reveal" style="text-transform: uppercase; font-weight: 800; text-align: center; margin-bottom: 10px; font-size: 1.75rem;">FIND OUR <span class="red-text">HEADQUARTERS</span></h2>
                <p class="scroll-reveal" style="text-align: center; color: var(--text-gray); font-size: 0.95rem; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">Locate our tactical gym compound in Malabon. Stop by for field validation, equipment scoping, or to confirm setup parameters directly with coaches.</p>
                
                <div class="location-grid scroll-reveal">
                    <div class="map-wrapper">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.4005856417757!2d120.95517457589578!3d14.660085375591322!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b502aa2f7c3b%3A0xbded475eab703c2a!2sBad%20boys%20fit%20and%20brwl!5e0!3m2!1sen!2sph!4v1718844800000!5m2!1sen!2sph" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    
                    <div class="location-info-panel">
                        <div class="info-row">
                            <i class="fa-solid fa-location-dot"></i>
                            <div>
                                <h4>Gym Location</h4>
                                <p>108 P. Aquino Ave, Brgy. Longos,<br>Malabon City, Metro Manila</p>
                            </div>
                        </div>

                        <div class="info-row">
                            <i class="fa-solid fa-clock"></i>
                            <div>
                                <h4>Brawl Hours</h4>
                                <p>Monday – Saturday: 7:00 AM – 12:00 AM<br>Sunday: 7:00 AM – 12:00 AM</p>
                            </div>
                        </div>

                        <div class="info-row">
                            <i class="fa-solid fa-phone"></i>
                            <div>
                                <h4>Contact Line</h4>
                                <p>Facebook: Bad Boys Fit & Brawl<br>Instagram: @badboysfitandbrwl<br>Email: badboysfitandbrwl@gmail.com</p>
                            </div>
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
                    <li style="margin-bottom: 10px;"><a href="index.php" style="color: var(--text-gray); text-decoration: none;">Home</a></li>
                    <li style="margin-bottom: 10px;"><a href="services.php" style="color: var(--text-gray); text-decoration: none;">Services</a></li>
                    <li style="margin-bottom: 10px;"><a href="whyjoin.php" style="color: var(--text-gray); text-decoration: none;">Why Join?</a></li>
                    <li style="margin-bottom: 10px;"><a href="about.php" style="color: var(--text-gray); text-decoration: none;">About Us</a></li>
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
            // Find all elements targeted for scroll entry effects
            const animTargets = document.querySelectorAll('.scroll-reveal, .reveal-left, .reveal-right');

            const observerOptions = {
                root: null, // Scrapes metrics based on viewports
                rootMargin: '0px 0px -60px 0px', // Triggers slightly before crossing into actual base view lines
                threshold: 0.12 // Requires 12% panel cross validation
            };

            const revealObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            animTargets.forEach(target => {
                revealObserver.observe(target);
            });
        });
    </script>

</body>
</html>