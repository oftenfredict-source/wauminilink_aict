<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="AIC Moshi Kilimanjaro - Church Management System">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>AIC Moshi Kilimanjaro - Church Management System</title>

    <link rel="icon" href="{{ asset('vaultedge/img/core-img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('vaultedge/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vaultedge/css/custom-override.css') }}">

    <style>
        /* Global Typography Override - Exclude Icons */
        body, h1, h2, h3, h4, h5, h6, p, a, li, input, button, select, textarea, span:not(.fa):not(.fas):not(.far):not(.fab), div:not(.fa):not(.fas):not(.far):not(.fab) {
            font-family: 'Century Gothic', 'CenturyGothic', 'AppleGothic', sans-serif !important;
        }
        /* Ensure Icons still show */
        .fa, .fas, .far, .fab, i {
            font-family: FontAwesome !important;
        }

        /* Header Overrides */
        .ve-header {
            background: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
        }
        .ve-nav ul li a {
            color: #000000 !important;
            font-weight: 700 !important;
        }
        .ve-nav ul li a:hover, .ve-nav ul li a.active {
            color: #940000 !important;
            background: rgba(148, 0, 0, 0.1) !important;
        }
        .ve-toggler span {
            background: #000000 !important;
        }
        /* Hero Overrides */
        .ve-hero {
            background: #fff0f0 !important;
        }
        .ve-hero-left h1, .ve-stat strong {
            color: #000000 !important;
        }
        .ve-hero-left p, .ve-stat span {
            color: #444444 !important;
        }
        .ve-hero-left h1 .ve-highlight {
            color: #940000 !important;
        }
        .ve-hero-img-main::after {
            background: linear-gradient(120deg, #fff0f0 0%, transparent 40%) !important;
        }
        /* Ensure logo is visible */
        .ve-logo img {
            max-height: 60px;
        }
        /* Trust Bar Override */
        .ve-trust-bar {
            background: #940000 !important;
        }
        .ve-trust-inner span {
            color: #ffffff !important;
        }
        .ve-trust-inner span i {
            color: #ffffff !important;
        }

        /* Generic Accent Color Override */
        .ve-service-icon {
            background: #940000 !important;
        }
        .ve-service-icon i {
            color: #ffffff !important;
        }
        .ve-check-item i, .ve-footer-links li a::before, .ve-footer-contact li i {
            color: #940000 !important;
        }
        .ve-service-card:hover::before, .ve-whyus-badge {
            background: #940000 !important;
        }
        .ve-whyus-badge strong, .ve-whyus-badge span {
            color: #ffffff !important;
        }
        .ve-section-tag {
            background: rgba(148, 0, 0, 0.1) !important;
            color: #940000 !important;
            border-color: rgba(148, 0, 0, 0.25) !important;
        }
        .ve-btn-white:hover {
            background: #940000 !important;
            color: #ffffff !important;
        }
        /* Heading Highlights */
        h2 span, h3 span, h4 span {
            color: #940000 !important;
        }
        /* Button Hovers */
        .ve-btn-primary:hover {
            background: #7a0000 !important;
            border-color: #7a0000 !important;
            transform: translateY(-2px);
        }
        .ve-btn-ghost:hover {
            background: #940000 !important;
            color: #ffffff !important;
            transform: translateY(-2px);
        }
        /* Header & Footer Hovers */
        .ve-cta-btn:hover {
            background: #7a0000 !important;
            color: #ffffff !important;
        }
        .ve-social a:hover {
            background: #940000 !important;
            border-color: #940000 !important;
            color: #ffffff !important;
        }
        /* Login Menu Item Override */
        .ve-nav ul li a[href*="login"] {
            color: #940000 !important;
        }
        /* Back to Top */
        #scrollUp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: #940000;
            color: #ffffff;
            text-align: center;
            line-height: 45px;
            border-radius: 50%;
            z-index: 1000;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            display: none;
        }
        #scrollUp:hover {
            background: #7a0000;
            transform: translateY(-5px);
        }
        /* Hero Floating Card Icon */
        .ve-float-card i {
            color: #940000 !important;
        }

        /* New Footer Styles */
        .ve-footer {
            border-top: 5px solid #940000 !important;
            background: #0d0d0d !important;
            padding-top: 80px !important;
        }
        .ve-footer-title {
            position: relative;
            padding-bottom: 15px;
            border-bottom: none !important;
            margin-bottom: 25px !important;
            text-transform: capitalize !important;
            font-size: 18px !important;
            font-weight: 700 !important;
        }
        .ve-footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 45px;
            height: 3px;
            background: #940000;
        }
        .ve-footer-links li a i, .ve-footer-contact li i {
            color: #940000 !important;
            margin-right: 12px;
            width: 16px;
            text-align: center;
        }
        .ve-footer-links li a {
            display: flex !important;
            align-items: center;
            padding-left: 0 !important;
            color: rgba(255,255,255,0.7) !important;
        }
        .ve-footer-links li a::before {
            display: none !important;
        }
        .ve-footer-bottom {
            background: #050505 !important;
            padding: 25px 0 !important;
            border-top: 1px solid rgba(255,255,255,0.05) !important;
        }
        .ve-footer-bottom-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .ve-footer-bottom-inner p {
            margin-bottom: 0 !important;
            color: rgba(255,255,255,0.5) !important;
        }
        .emca-red {
            color: #940000 !important;
            font-weight: 700;
        }

        /* Mobile Responsiveness Improvements */
        @media only screen and (max-width: 991px) {
            .ve-hero {
                flex-direction: column !important;
                height: auto !important;
                padding-top: 120px !important;
                padding-bottom: 60px !important;
            }
            .ve-hero-left, .ve-hero-right {
                width: 100% !important;
                flex: none !important;
            }
            .ve-hero-left {
                text-align: center !important;
                padding: 0 30px !important;
                order: 2 !important;
            }
            .ve-hero-right {
                height: 350px !important;
                order: 1 !important;
                margin-bottom: 40px !important;
            }
            .ve-hero-stats {
                justify-content: center !important;
            }
            .ve-stat-divider {
                height: 30px !important;
                margin: 0 15px !important;
            }
        }

        @media only screen and (max-width: 767px) {
            .ve-hero-left h1 {
                font-size: 2.2rem !important;
            }
            .ve-hero-left p {
                font-size: 1rem !important;
            }
            .ve-hero-stats {
                flex-wrap: wrap !important;
                gap: 15px !important;
            }
            .ve-stat-divider {
                display: none !important;
            }
            .ve-stat {
                width: 45% !important;
            }
            .ve-trust-bar {
                overflow: hidden !important;
            }
            .ve-trust-inner {
                display: flex !important;
                width: max-content !important;
                animation: marquee-scroll 20s linear infinite !important;
            }
            .ve-trust-inner span {
                padding: 0 20px !important;
                white-space: nowrap !important;
            }
            @keyframes marquee-scroll {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .ve-footer-bottom-inner {
                flex-direction: column !important;
                text-align: center !important;
                gap: 10px !important;
            }
            .ve-section-header h2 {
                font-size: 2rem !important;
            }
        }

        /* Centering Footer & Why Us Image */
        .ve-whyus-section .row {
            justify-content: center !important;
        }
        .ve-whyus-img-wrap {
            margin: 0 auto 30px !important;
            max-width: 500px;
            display: block !important;
        }
        .ve-whyus-img-main {
            margin: 0 auto !important;
        }
        .ve-footer .col-12 {
            text-align: center !important;
            margin-bottom: 40px;
        }
        .ve-footer-title::after {
            left: 50% !important;
            transform: translateX(-50%);
        }
        .ve-footer-links, .ve-footer-contact {
            display: inline-block;
            text-align: left;
            padding: 0 !important;
        }
        .ve-footer-links li a, .ve-footer-contact li {
            justify-content: center !important;
            text-align: center !important;
        }
        .ve-footer-links li a i, .ve-footer-contact li i {
            margin-right: 8px !important;
        }
        .ve-social {
            justify-content: center !important;
            display: flex;
        }

        /* Hero Button Centering & Widening */
        .ve-hero-btns {
            justify-content: center !important;
            display: flex !important;
            width: 100%;
            margin-top: 30px;
        }
        .ve-hero-btns .ve-btn-primary {
            min-width: 280px !important;
            padding: 18px 50px !important;
            font-size: 1.2rem !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        /* Section Spacing Overrides */
        .ve-section {
            padding: 70px 0 !important;
        }
        .ve-services-section {
            padding-bottom: 20px !important;
        }
        .ve-whyus-section {
            padding-top: 20px !important;
        }
        .ve-services-grid {
            margin-bottom: 0 !important;
        }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader d-flex flex-column align-items-center justify-content-center" style="background-color: #121212;">
        <img src="{{ asset('assets/images/aict.png') }}" alt="AIC Logo" style="height: 140px; width: auto; margin-bottom: 30px; animation: pulse 2s infinite ease-in-out;">
        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        <div style="margin-top: 40px; font-size: 13px; color: rgba(255, 255, 255, 0.6); letter-spacing: 0.2rem; text-transform: uppercase;">
            Powered by <span style="color: #940000; font-weight: 600;">EmCa Technologies</span>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 0.8; }
        }
        .lds-ellipsis div { background: #940000 !important; }
    </style>

    <!-- ===== NAVBAR ===== -->
    <header class="ve-header" id="ve-sticky">
        <div class="container-fluid ve-nav-wrap">
            <!-- Logo -->
            <div class="ve-logo">
                <a href="{{ route('landing_page') }}">
                    <img src="{{ asset('assets/images/aict.png') }}" alt="AIC Moshi Kilimanjaro" style="height: 100px; width: auto;">
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="ve-nav">
                <ul>
                    <li><a href="{{ route('landing_page') }}" class="active">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>

            <!-- CTA -->
            <div class="ve-nav-cta">
                <a href="{{ route('login') }}" class="ve-cta-btn" style="background-color: #940000; border-color: #940000; color: #ffffff;">Login <i class="fa fa-arrow-right"></i></a>
            </div>

            <!-- Mobile Toggle -->
            <button class="ve-toggler" id="ve-toggle">
                <span></span><span></span><span></span>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="ve-mobile-menu" id="ve-mobile-menu">
            <ul>
                <li><a href="{{ route('landing_page') }}">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="ve-hero">
        <!-- Left Panel -->
        <div class="ve-hero-left">
            <span class="ve-hero-badge">Walking in Faith, Growing in Christ</span>
            <h1>Welcome to <span class="ve-highlight">AICT</span><br>Moshi Kilimanjaro</h1>
            <p style="font-weight: 600; font-size: 1.25rem; color: #940000; margin-bottom: 10px;">A Place of Faith, Love, and Hope</p>
            <p>AICT Moshi is a church devoted to worshiping God, building a strong community of believers, and guiding each member in their spiritual journey through the Word, fellowship, and service.</p>
            <div class="ve-hero-btns">
                <a href="{{ route('login') }}" class="ve-btn-primary" style="background-color: #940000; border-color: #940000; color: #ffffff;">Login</a>
            </div>
            <!-- Quick Stats Row -->
            <div class="ve-hero-stats">
                <div class="ve-stat">
                    <strong>5,000+</strong>
                    <span>Believers</span>
                </div>
                <div class="ve-stat-divider"></div>
                <div class="ve-stat">
                    <strong>100%</strong>
                    <span>Trust & Integrity</span>
                </div>
                <div class="ve-stat-divider"></div>
                <div class="ve-stat">
                    <strong>24/7</strong>
                    <span>Spiritual Care</span>
                </div>
            </div>
        </div>
        <!-- Right Panel -->
        <div class="ve-hero-right">
            <div class="ve-hero-img-main bg-img" style="background-image:url({{ asset('assets/images/aict.jpeg') }});"></div>
            <div class="ve-hero-img-accent bg-img" style="background-image:url({{ asset('assets/images/church4.jpg') }});"></div>
            <!-- Floating card -->
            <div class="ve-float-card">
                <i class="fa fa-users"></i>
                <div>
                    <strong>Integrated</strong>
                    <span>Church System</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MARQUEE TRUST BAR ===== -->
    <div class="ve-trust-bar">
        <div class="ve-trust-inner">
            <span><i class="fa fa-shield"></i> Secure Data Storage</span>
            <span><i class="fa fa-check-circle"></i> Easy Member Tracking</span>
            <span><i class="fa fa-users"></i> Congregational Growth</span>
            <span><i class="fa fa-lock"></i> 256-bit Encryption</span>
            <span><i class="fa fa-trophy"></i> Best Church App</span>
            <span><i class="fa fa-globe"></i> Accessible Anywhere</span>
        </div>
    </div>

    <!-- ===== SERVICES GRID ===== -->
    <section id="services" class="ve-section ve-services-section">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">What We Offer</span>
                <h2>Ministries & Church <span>Life</span></h2>
                <p>At AICT Moshi Kilimanjaro, we provide a welcoming environment where everyone can grow in faith and serve God together.</p>
            </div>
            <div class="ve-services-grid">
                <div class="ve-service-card wow fadeInUp" data-wow-delay="100ms">
                    <div class="ve-service-icon"><i class="fa fa-book"></i></div>
                    <h4>Spiritual Growth & Discipleship</h4>
                    <p>We guide believers in their journey of faith through biblical teachings, discipleship programs, and spiritual mentorship.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="200ms">
                    <div class="ve-service-icon"><i class="fa fa-heart"></i></div>
                    <h4>Fellowship & Community Building</h4>
                    <p>We create a loving and united church family where members connect, support one another, and grow together in Christ.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="300ms">
                    <div class="ve-service-icon"><i class="fa fa-music"></i></div>
                    <h4>Worship Services & Events</h4>
                    <p>We organize inspiring worship services, prayer meetings, and special events that bring people closer to God.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="400ms">
                    <div class="ve-service-icon"><i class="fa fa-gift"></i></div>
                    <h4>Giving & Stewardship</h4>
                    <p>We encourage faithful giving and ensure responsible, transparent use of resources to support the mission of the church.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="500ms">
                    <div class="ve-service-icon"><i class="fa fa-comments"></i></div>
                    <h4>Church Communication</h4>
                    <p>We keep everyone informed and connected through timely announcements, updates, and shared information.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="600ms">
                    <div class="ve-service-icon"><i class="fa fa-life-ring"></i></div>
                    <h4>Care & Support for Members</h4>
                    <p>We provide spiritual care, guidance, and support to members in times of need, strengthening faith and hope.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== WHY US ===== -->
    <section id="about" class="ve-section ve-whyus-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Image Side -->
                <div class="col-12 col-lg-5">
                    <div class="ve-whyus-img-wrap wow fadeInLeft" data-wow-delay="100ms">
                        <div class="ve-whyus-img-main bg-img" style="background-image:url({{ asset('assets/images/aict.jpeg') }});"></div>
                        <div class="ve-whyus-badge">
                            <strong>100%</strong>
                            <span>Dedicated to Church Growth</span>
                        </div>
                    </div>
                </div>
                <!-- Content Side -->
                <div class="col-12 col-lg-7 wow fadeInRight" data-wow-delay="200ms">
                    <div class="ve-whyus-content">
                        <span class="ve-section-tag">Why AICT Moshi Kilimanjaro</span>
                        <h2>A Church That <span>Cares and Grows</span></h2>
                        <p>At AICT Moshi Kilimanjaro, we are committed to fostering a spiritual environment where love, faith, and community thrive, ensuring every member feels valued and supported in their journey with Christ.</p>
                        <div class="ve-checklist">
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div><strong>Dedicated Support</strong><p>Guiding and supporting members in every aspect of church life.</p></div>
                            </div>
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div><strong>Faithful Stewardship</strong><p>Ensuring transparency in all church resources.</p></div>
                            </div>
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div><strong>Growing Together</strong><p>Building a strong church at every stage of growth.</p></div>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="ve-btn-primary mt-30" style="background-color: #940000; border-color: #940000; color: #ffffff;">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA BANNER ===== -->
    <section class="ve-cta-banner bg-img" style="background-image:url({{ asset('assets/images/church3.jpg') }});">
        <div class="ve-cta-overlay"></div>
        <div class="container ve-cta-content">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <h2>Ready to Join Our Community and <span>Grow in Faith?</span></h2>
                    <p>Experience the warmth of our church family and the power of spiritual connection at AICT Moshi Kilimanjaro.</p>
                </div>
                <div class="col-12 col-lg-4 text-lg-right">
                    <a href="{{ route('login') }}" class="ve-btn-white">Go to Dashboard</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer id="contact" class="ve-footer">
        <div class="container">
            <div class="row">
                <!-- Col 1: About -->
                <div class="col-12 col-md-6 col-lg-3 mb-50">
                    <h5 class="ve-footer-title">About AICT Moshi</h5>
                    <p style="color: rgba(255,255,255,0.7); line-height: 1.8; margin-bottom: 25px;">
                        A church family devoted to worshiping God, building a strong community of believers, and guiding each member in their spiritual journey through faith, love, and service.
                    </p>
                    <div class="ve-social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-12 col-md-6 col-lg-3 mb-50">
                    <h5 class="ve-footer-title">Quick Links</h5>
                    <ul class="ve-footer-links">
                        <li><a href="{{ route('landing_page') }}"><i class="fa fa-home"></i> Home</a></li>
                        <li><a href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                        <li><a href="#about"><i class="fa fa-info-circle"></i> About Us</a></li>
                        <li><a href="#"><i class="fa fa-question-circle"></i> Help & Support</a></li>
                        <li><a href="#"><i class="fa fa-shield"></i> Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Col 3: Contact -->
                <div class="col-12 col-md-6 col-lg-3 mb-50">
                    <h5 class="ve-footer-title">Contact Us</h5>
                    <ul class="ve-footer-contact">
                        <li style="color: rgba(255,255,255,0.7);"><i class="fa fa-envelope"></i> emca@emca.tech</li>
                        <li style="color: rgba(255,255,255,0.7);"><i class="fa fa-phone"></i> +255 749 719 998</li>
                        <li style="color: rgba(255,255,255,0.7);"><i class="fa fa-map-marker"></i> Moshi, Kilimanjaro</li>
                    </ul>
                </div>

                <!-- Col 4: Services -->
                <div class="col-12 col-md-6 col-lg-3 mb-50">
                    <h5 class="ve-footer-title">Our Services</h5>
                    <ul class="ve-footer-links">
                        <li><a href="#"><i class="fa fa-book"></i> Spiritual Growth</a></li>
                        <li><a href="#"><i class="fa fa-music"></i> Worship Services</a></li>
                        <li><a href="#"><i class="fa fa-heart"></i> Community Outreach</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="ve-footer-bottom">
            <div class="container">
                <div class="ve-footer-bottom-inner">
                    <p>&copy; {{ date('Y') }} AIC Moshi Kilimanjaro. All rights reserved.</p>
                    <p>Powered by <span class="emca-red">EmCa Technologies</span></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('vaultedge/js/jquery/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('vaultedge/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('vaultedge/js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vaultedge/js/plugins/plugins.js') }}"></script>
    <script src="{{ asset('vaultedge/js/active.js') }}"></script>
    <script src="{{ asset('vaultedge/js/vaultedge.js') }}"></script>
    <script>
        // Back to Top functionality
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                if ($('#scrollUp').length === 0) {
                    $('body').append('<div id="scrollUp"><i class="fa fa-angle-up"></i></div>');
                    $('#scrollUp').click(function() {
                        $('html, body').animate({scrollTop: 0}, 800);
                        return false;
                    });
                }
                $('#scrollUp').fadeIn();
            } else {
                $('#scrollUp').fadeOut();
            }
        });
    </script>
</body>
</html>