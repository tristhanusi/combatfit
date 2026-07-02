<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Bad Boys Fit & Brawl</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Global structure normalization */
        * { box-sizing: border-box; }

        /* Pricing Cards Layout styling to match your dark industrial theme */
        .pricing-section { padding: 60px 4%; background-color: var(--bg-color); }
        .pricing-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; max-width: 1200px; margin: 40px auto 0 auto; }
        .price-card { background: var(--panel-bg); border: 2px solid var(--border-color); border-radius: 8px; padding: 35px 25px; text-align: center; transition: all 0.3s ease; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
        .price-card.featured { border-color: #f1c40f; }
        .price-card:hover { border-color: var(--accent-red); transform: translateY(-5px); }
        .price-card.featured:hover { border-color: #f1c40f; }
        .plan-name { font-size: 1.6rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .plan-type { font-size: 0.8rem; color: var(--text-gray); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 15px; }
        .plan-cost { font-size: 2rem; font-weight: 900; margin-bottom: 25px; }
        .plan-features { list-style: none; padding: 0; margin: 0 0 30px 0; text-align: left; font-size: 0.9rem; line-height: 1.6; }
        .plan-features li { margin-bottom: 12px; color: #ddd; display: flex; align-items: flex-start; gap: 10px; }
        .plan-features li i { color: #4caf50; margin-top: 4px; font-size: 0.9rem; flex-shrink: 0; }
        .plan-features li i.excl-icon { color: #ff5252; }

        /* Rate Sheet Matrix Tables CSS */
        .rates-container { max-width: 1200px; margin: 50px auto 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; }
        .rate-table-box { background-color: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; padding: 25px; }
        .rate-table-title { font-size: 1.1rem; font-weight: 900; text-transform: uppercase; margin-bottom: 20px; color: #fff; border-left: 4px solid var(--accent-red); padding-left: 12px; }
        .rate-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #222; font-size: 0.95rem; gap: 15px; }
        .rate-row :first-child { text-align: left; }
        .rate-row :last-child { text-align: right; flex-shrink: 0; }
        .rate-row:last-child { border-bottom: none; }
        .rate-row span strong { color: var(--accent-red); }
        .badge-save { display: inline-block; background: #4caf50; color: #fff; font-size: 0.75rem; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 5px; text-transform: uppercase; white-space: nowrap; }

        /* --- SINGLE ROW ROW WRAPPER STRATEGY --- */
        .row-scroller-container {
            max-width: 1200px;
            margin: 0 auto;
            overflow-x: auto;
            padding-bottom: 15px;
            scrollbar-width: thin;
            scrollbar-color: var(--accent-red) #1e1e1e;
        }
        .row-scroller-container::-webkit-scrollbar {
            height: 6px;
        }
        .row-scroller-container::-webkit-scrollbar-track {
            background: #1e1e1e;
        }
        .row-scroller-container::-webkit-scrollbar-thumb {
            background-color: var(--accent-red);
            border-radius: 3px;
        }
        .single-row-grid {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            gap: 24px;
        }
        .single-row-grid .service-card {
            flex: 0 0 300px; /* Forces exact row sizing constraints */
            width: 300px;
        }

        /* --- SCROLL ANIMATION CSS UTILITIES --- */
        .reveal {
            opacity: 0;
            will-change: transform, opacity;
            transition: all 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.fade-up { transform: translateY(30px); }
        .reveal.fade-left { transform: translateX(-40px); }
        .reveal.fade-right { transform: translateX(40px); }
        .reveal.scale-in { transform: scale(0.95); }
        .reveal.active { opacity: 1; transform: translate(0) scale(1); }

        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }

        /* --- MOBILE RESPONSIVE MEDIA BREAKPOINTS --- */
        @media (max-width: 768px) {
            .reveal.fade-left, .reveal.fade-right { transform: translateY(20px); }

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

            .services-hero { padding: 40px 20px !important; text-align: center; }
            .services-hero h1 { font-size: 1.8rem !important; }
            .services-section { padding: 40px 5% !important; }
            .section-title { font-size: 1.6rem !important; text-align: center; }
            
            .pricing-section { padding: 40px 5%; }
            .price-card { padding: 30px 20px; }
            .rates-container { gap: 25px; margin-top: 30px; }
            .rate-table-box { padding: 20px; }
            
            .services-grid:not(.single-row-grid) {
                grid-template-columns: 1fr !important;
                gap: 20px !important;
            }

            footer { padding: 40px 5% 20px 5% !important; }
            footer > div:first-child { grid-template-columns: 1fr !important; gap: 30px !important; }

            .delay-100, .delay-200, .delay-300 { transition-delay: 0ms !important; }
        }

        @media (max-width: 480px) {
            .plan-name { font-size: 1.4rem; }
            .plan-cost { font-size: 1.75rem; }
            .rate-row { font-size: 0.88rem; padding: 10px 0; }
            .badge-save { display: block; margin-left: 0; margin-top: 4px; text-align: right; width: max-content; float: right; }
            .logo-text { font-size: 0.9rem; }
            .navbar-logo { width: 28px; height: 28px; }
            .nav-links { gap: 8px !important; }
            .nav-links a { font-size: 0.8rem !important; }
            .single-row-grid .service-card { flex: 0 0 260px; width: 260px; }
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
            <a href="services.php" class="active">Services</a>
            <a href="whyjoin.php">Why Join?</a>
            <a href="about.php">About Us</a>
        </nav>
        <div class="auth-buttons">
            <a href="inquire.php" class="btn btn-outline" style="text-decoration: none; display: inline-block;">Join Now</a>
            <a href="login.php" class="btn btn-solid" style="text-decoration: none; display: inline-block;">Sign In</a>
        </div>
    </header>

    <main class="services-page">
        
        <section class="services-hero reveal scale-in active" style="padding: 60px 4%; text-align: center;">
            <h1 style="font-size: 2.5rem; font-weight: 900; text-transform: uppercase;">OUR <span class="red-text">PROGRAMS & AMENITIES</span></h1>
            <p style="color: var(--text-gray); margin-top: 10px;">From foundational combat basics to elite high-performance strength routines.</p>
        </section>

        <!-- TRAINING PROGRAMS SECTION -->
        <section class="services-section" style="padding: 40px 4%;">
            <h2 class="section-title reveal fade-up" style="text-transform: uppercase; font-weight: 900; font-size: 2rem; margin-bottom: 30px;">TRAINING <span class="red-text">PROGRAMS</span></h2>
            <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                
                <div class="service-card reveal fade-up delay-100" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                    <div class="service-image" style="background-image: url('Images/boxing.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                    <div class="service-content" style="padding: 20px;">
                        <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;"><i class="fa-solid fa-glove-boxing text-red"></i> BOXING</h3>
                        <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">Master authentic boxing fundamentals, footwork, hand speed, and heavy bag striking drills. Suitable for all skill levels from beginners to advanced competitors.</p>
                    </div>
                </div>

                <div class="service-card reveal fade-up delay-200" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                    <div class="service-image" style="background-image: url('Images/mma.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                    <div class="service-content" style="padding: 20px;">
                        <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;"><i class="fa-solid fa-user-ninja text-red"></i> MIXED MARTIAL ARTS (MMA)</h3>
                        <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">A high-intensity blend of striking, wrestling, and submission grappling designed to turn you into a well-rounded martial artist.</p>
                    </div>
                </div>

                <div class="service-card reveal fade-up delay-300" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                    <div class="service-image" style="background-image: url('Images/strength.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                    <div class="service-content" style="padding: 20px;">
                        <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;"><i class="fa-solid fa-dumbbell text-red"></i> STRENGTH & CONDITIONING</h3>
                        <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">High-performance routines focusing on functional power, muscular endurance, agility, and explosive speed built specifically for athletes.</p>
                    </div>
                </div>

            </div>
        </section>

        <!-- GYM AMENITIES SECTION - UPDATED TO UNIFIED SAME ROW ROW WRAPPER -->
        <section class="services-section alternate-bg" style="padding: 40px 4%; background: #141414;">
            <h2 class="section-title reveal fade-up" style="text-transform: uppercase; font-weight: 900; font-size: 2rem; margin-bottom: 30px;">GYM <span class="red-text">AMENITIES</span></h2>
            
            <div class="row-scroller-container">
                <div class="services-grid single-row-grid">
                    
                    <!-- Amenity 1 -->
                    <div class="service-card reveal fade-up delay-100" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                        <div class="service-image" style="background-image: url('Images/brawl.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                        <div class="service-content" style="padding: 20px;">
                            <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;">PREMIUM BRAWL RING</h3>
                            <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">Train inside our full-sized, standard boxing ring optimized for controlled sparring and realistic ring-craft drills.</p>
                        </div>
                    </div>

                    <!-- Amenity 2 -->
                    <div class="service-card reveal fade-up delay-200" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                        <div class="service-image" style="background-image: url('Images/equipment.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                        <div class="service-content" style="padding: 20px;">
                            <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;">ELITE HEAVY EQUIPMENT ZONE</h3>
                            <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">Equipped with high-grade free weights, power racks, kettlebells, and heavy combat bags designed to handle punishing routines.</p>
                        </div>
                    </div>

                    <!-- Amenity 3 -->
                    <div class="service-card reveal fade-up delay-300" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                        <div class="service-image" style="background-image: url('Images/heavybag.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                        <div class="service-content" style="padding: 20px;">
                            <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;">HEAVY BAG MATRIX</h3>
                            <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">A continuous alignment of heavy-duty hanging bags designed for multi-angle kickboxing combinations and target density endurance.</p>
                        </div>
                    </div>

                    <!-- Amenity 4 -->
                    <div class="service-card reveal fade-up delay-100" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                        <div class="service-image" style="background-image: url('Images/grappling.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                        <div class="service-content" style="padding: 20px;">
                            <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;">TACTICAL GRAPPLING MATS</h3>
                            <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">High-density foam combat flooring engineered safely for wrestling setups, judo throws, and complex submission systems.</p>
                        </div>
                    </div>

                    <!-- Amenity 5 -->
                    <div class="service-card reveal fade-up delay-200" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                        <div class="service-image" style="background-image: url('Images/speed.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                        <div class="service-content" style="padding: 20px;">
                            <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;">SPEED SPEEDSTATIONS</h3>
                            <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">Equipped with high-rebound speedballs and double-end anchor bags to enhance visual tracking, timing mechanics, and shoulder retention.</p>
                        </div>
                    </div>

                    <!-- Amenity 6 -->
                    <div class="service-card reveal fade-up delay-300" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                        <div class="service-image" style="background-image: url('Images/cardio.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                        <div class="service-content" style="padding: 20px;">
                            <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;">CARDIO FORGE BAY</h3>
                            <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">High-output rowing systems, curved athletic treadmills, and air resistance bikes tuned for peak explosive threshold metrics.</p>
                        </div>
                    </div>

                    <!-- Amenity 7 -->
                    <div class="service-card reveal fade-up delay-100" style="background: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden;">
                        <div class="service-image" style="background-image: url('Images/shower.jpg'); height: 200px; background-size: cover; background-position: center;"></div>
                        <div class="service-content" style="padding: 20px;">
                            <h3 style="text-transform: uppercase; font-weight: 800; font-size: 1.2rem; margin-bottom: 10px;">HYGIENE LOCKER SUITE</h3>
                            <p style="color: var(--text-gray); font-size: 0.9rem; line-height: 1.6;">Secure structural lockers alongside clean high-pressure shower stalls and recovery supplies provided entirely free for members.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- MEMBERSHIP JOURNEYS SECTION -->
        <section class="pricing-section">
            <h2 class="section-title reveal fade-up" style="text-transform: uppercase; font-weight: 900; font-size: 2rem; text-align: center; margin-bottom: 25px;">MEMBERSHIP <span class="red-text">JOURNEYS</span></h2>
            <p class="reveal fade-up delay-100" style="text-align: center; color: var(--text-gray); margin-top: -15px; margin-bottom: 20px;">Pick the plan tailored to your combat and fitness goals.</p>
            
            <div class="pricing-grid">
                
                <div class="price-card reveal fade-up delay-100">
                    <div>
                        <div class="plan-name" style="color: #00bcd4;">REGULAR</div>
                        <div class="plan-type">Fitness Membership</div>
                        <div class="plan-cost red-text">₱800 <span style="display:block; margin-top:4px; font-size: 1rem; color: var(--text-gray);">(1 Year Access)</span></div>
                        <ul class="plan-features">
                            <li><i class="fa-solid fa-check"></i> Member-rate gym access on every visit</li>
                            <li><i class="fa-solid fa-check"></i> Eligible to avail of unlimited monthly/yearly fitness plans</li>
                            <li><i class="fa-solid fa-check"></i> Access to Fitness Area (Weights, Cardio & Functional Training)</li>
                            <li style="color: var(--text-gray);"><i class="fa-solid fa-circle-xmark excl-icon"></i> Excluding the Combat Sports Area</li>
                        </ul>
                    </div>
                    <a href="inquire.php?plan=Regular" class="btn btn-outline" style="text-decoration:none;">Select Plan</a>
                </div>

                <div class="price-card reveal fade-up delay-200">
                    <div>
                        <div class="plan-name" style="color: #fff;">PRIME</div>
                        <div class="plan-type">Combat Sports Membership</div>
                        <div class="plan-cost red-text">₱1,500 <span style="display:block; margin-top:4px; font-size: 1rem; color: var(--text-gray);">(1 Year Access)</span></div>
                        <ul class="plan-features">
                            <li><i class="fa-solid fa-check"></i> Access to Boxing, Muay Thai, and MMA training sessions</li>
                            <li><i class="fa-solid fa-check"></i> Exclusive use of the combat sports training area</li>
                            <li><i class="fa-solid fa-check"></i> Access to exclusive combat training packages</li>
                            <li><i class="fa-solid fa-check"></i> Train with professional & international coaches and fighters</li>
                            <li style="color: var(--text-gray); font-size:0.8rem;"><i class="fa-solid fa-circle-info" style="color:var(--accent-red)"></i> Fitness/weightlifting area use charged at non-member rates</li>
                        </ul>
                    </div>
                    <a href="inquire.php?plan=Prime" class="btn btn-outline" style="text-decoration:none;">Select Plan</a>
                </div>

                <div class="price-card featured reveal fade-up delay-300">
                    <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #f1c40f; color: #111; padding: 2px 12px; font-size: 0.7rem; font-weight: 900; border-radius: 20px; text-transform: uppercase; z-index: 2;">Best Value</div>
                    <div>
                        <div class="plan-name" style="color: #f1c40f;">PREMIUM</div>
                        <div class="plan-type">All-Access Membership</div>
                        <div class="plan-cost red-text">₱1,999 <span style="display:block; margin-top:4px; font-size: 1rem; color: var(--text-gray);">(1 Year Access)</span></div>
                        <ul class="plan-features">
                            <li><i class="fa-solid fa-check"></i> <strong>Full Member Privileges Across All Areas (Combat Sports & Fitness)</strong></li>
                            <li><i class="fa-solid fa-check"></i> Enjoy exclusive member rates on combat sports packages</li>
                            <li><i class="fa-solid fa-check"></i> Eligible for monthly or yearly fitness plans</li>
                            <li><i class="fa-solid fa-check"></i> Train with professional & international coaches</li>
                        </ul>
                    </div>
                    <a href="inquire.php?plan=Premium" class="btn btn-solid" style="text-decoration:none; background:#f1c40f; color:#111; border-color:#f1c40f;">Select Plan</a>
                </div>
            </div>

            <!-- RATE SHEETS MATRIX -->
            <div class="rates-container">
                
                <div class="rate-table-box reveal fade-left">
                    <div class="rate-table-title">Boxing & MMA Area Use (Per Visit)</div>
                    <div class="rate-row"><span style="color:var(--text-gray);">Gym Member Rate</span> <span><strong>₱200</strong></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">Non-Member Rate</span> <span><strong>₱250</strong></span></div>

                    <div class="rate-table-title" style="margin-top: 30px;">Professional Boxing Training (1-on-1)</div>
                    <div class="rate-row"><span style="color:var(--text-gray);">Member Per-Session</span> <span><strong>₱350</strong></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">Non-Member Per-Session</span> <span><strong>₱500</strong></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">12+2 Sessions Package <small style="color:var(--text-gray);">(Members Only)</small></span> <span><strong>₱4,200</strong> <span class="badge-save">Save ₱700</span></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">24+4 Sessions Package <small style="color:var(--text-gray);">(Members Only)</small></span> <span><strong>₱8,200</strong> <span class="badge-save">Save ₱1,600</span></span></div>
                </div>

                <div class="rate-table-box reveal fade-right">
                    <div class="rate-table-title">Professional Muay Thai Training (1-on-1)</div>
                    <div class="rate-row"><span style="color:var(--text-gray);">Member Per-Session</span> <span><strong>₱400</strong></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">Non-Member Per-Session</span> <span><strong>₱550</strong></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">12+2 Sessions Package <small style="color:var(--text-gray);">(Members Only)</small></span> <span><strong>₱4,800</strong> <span class="badge-save">Save ₱800</span></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">24+4 Sessions Package <small style="color:var(--text-gray);">(Members Only)</small></span> <span><strong>₱9,400</strong> <span class="badge-save">Save ₱1,800</span></span></div>

                    <div class="rate-table-title" style="margin-top: 30px;">Professional MMA Training (1-on-1)</div>
                    <div class="rate-row"><span style="color:var(--text-gray);">Member Per-Session</span> <span><strong>₱600</strong></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">Non-Member Per-Session</span> <span><strong>₱750</strong></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">12+2 Sessions Package <small style="color:var(--text-gray);">(Members Only)</small></span> <span><strong>₱7,200</strong> <span class="badge-save">Save ₱1,200</span></span></div>
                    <div class="rate-row"><span style="color:var(--text-gray);">24+4 Sessions Package <small style="color:var(--text-gray);">(Members Only)</small></span> <span><strong>₱14,200</strong> <span class="badge-save">Save ₱2,600</span></span></div>
                </div>

            </div>

            <!-- ADDIITONAL BOTTOM INFOTILE -->
            <div class="reveal fade-up" style="max-width:1200px; margin: 40px auto 0 auto; background: #111; border: 1px dashed var(--border-color); padding: 20px; border-radius: 6px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; text-align: center; font-size: 0.85rem; font-weight: 700;">
                <div><i class="fa-solid fa-face-smile red-text"></i> Face Recognition Access</div>
                <div><i class="fa-solid fa-key red-text"></i> No "KEY FOB" or "CARD" Needed</div>
                <div><i class="fa-solid fa-shower red-text"></i> Free Showers & Soap</div>
                <div><i class="fa-solid fa-lock red-text"></i> Free Lockers Provided</div>
            </div>
        </section>

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
        document.addEventListener("DOMContentLoaded", function() {
            const animatedElements = document.querySelectorAll('.reveal');

            const observerOptions = {
                root: null,
                rootMargin: "0px 0px -60px 0px",
                threshold: 0.12
            };

            const revealCallback = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            };

            const scrollObserver = new IntersectionObserver(revealCallback, observerOptions);
            
            animatedElements.forEach(element => {
                scrollObserver.observe(element);
            });
        });
    </script>

</body>
</html>