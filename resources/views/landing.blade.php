<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Waumini Link - Church Management System">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Waumini Link - Church Management System</title>

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
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader d-flex flex-column align-items-center justify-content-center" style="background-color: #121212;">
        <img src="{{ asset('assets/images/waumini_link_logo.png') }}" alt="Waumini Link Logo" style="height: 80px; width: auto; margin-bottom: 30px; animation: pulse 2s infinite ease-in-out;">
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
                    <img src="{{ asset('assets/images/waumini_link_logo.png') }}" alt="Waumini Link" style="height: 60px; width: auto;">
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
            <span class="ve-hero-badge">Empowering Faith Through Technology</span>
            <h1>Connecting <span class="ve-highlight">Believers</span><br>Empowering Churches</h1>
            <p>Waumini Link delivers intelligent, data-driven church management strategies and personalised guidance to help your congregation grow.</p>
            <div class="ve-hero-btns">
                <a href="{{ route('login') }}" class="ve-btn-primary" style="background-color: #940000; border-color: #940000; color: #ffffff;">View Demo</a>
                <a href="#about" class="ve-btn-ghost" style="color: #940000; border-color: #940000;">Learn More</a>
            </div>
            <!-- Quick Stats Row -->
            <div class="ve-hero-stats">
                <div class="ve-stat">
                    <strong>5,000+</strong>
                    <span>Members Tracked</span>
                </div>
                <div class="ve-stat-divider"></div>
                <div class="ve-stat">
                    <strong>100%</strong>
                    <span>Transparency</span>
                </div>
                <div class="ve-stat-divider"></div>
                <div class="ve-stat">
                    <strong>24/7</strong>
                    <span>Support</span>
                </div>
            </div>
        </div>
        <!-- Right Panel -->
        <div class="ve-hero-right">
            <div class="ve-hero-img-main bg-img" style="background-image:url({{ asset('assets/images/church1.jpg') }});"></div>
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
                <h2>Comprehensive Church <span>Solutions</span></h2>
                <p>From member management to financial tracking — we cover every aspect of your church administration.</p>
            </div>
            <div class="ve-services-grid">
                <div class="ve-service-card wow fadeInUp" data-wow-delay="100ms">
                    <div class="ve-service-icon"><i class="fa fa-users"></i></div>
                    <h4>Member Management</h4>
                    <p>Maintain a centralized database of all church members, including families and children.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="200ms">
                    <div class="ve-service-icon"><i class="fa fa-money"></i></div>
                    <h4>Financial Tracking</h4>
                    <p>Seamlessly track tithes, offerings, and donations with automated reporting.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="300ms">
                    <div class="ve-service-icon"><i class="fa fa-calendar"></i></div>
                    <h4>Attendance Control</h4>
                    <p>Monitor attendance for services and special events using digital tools.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="400ms">
                    <div class="ve-service-icon"><i class="fa fa-bullhorn"></i></div>
                    <h4>Announcement System</h4>
                    <p>Broadcast important information to your congregation via SMS and notifications.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="500ms">
                    <div class="ve-service-icon"><i class="fa fa-line-chart"></i></div>
                    <h4>Transparent Reports</h4>
                    <p>Get real-time insights into your church's growth and financial health.</p>
                </div>
                <div class="ve-service-card wow fadeInUp" data-wow-delay="600ms">
                    <div class="ve-service-icon"><i class="fa fa-mobile"></i></div>
                    <h4>Mobile Accessible</h4>
                    <p>Access your church data anytime, anywhere from any device.</p>
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
                        <div class="ve-whyus-img-main bg-img" style="background-image:url({{ asset('assets/images/church2.jpg') }});"></div>
                        <div class="ve-whyus-badge">
                            <strong>100%</strong>
                            <span>Dedicated to Church Growth</span>
                        </div>
                    </div>
                </div>
                <!-- Content Side -->
                <div class="col-12 col-lg-7 wow fadeInRight" data-wow-delay="200ms">
                    <div class="ve-whyus-content">
                        <span class="ve-section-tag">Why Waumini Link</span>
                        <h2>A Smarter Way to Manage <span>Your Church</span></h2>
                        <p>We combine church expertise with cutting-edge technology to deliver outcomes that streamline your administration — all while keeping your mission first.</p>
                        <div class="ve-checklist">
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div><strong>Personalised Support</strong><p>Our team is here to help you set up and manage your system.</p></div>
                            </div>
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div><strong>Transparent Finance</strong><p>Clear, automated tracking of all church contributions and expenses.</p></div>
                            </div>
                            <div class="ve-check-item">
                                <i class="fa fa-check-circle"></i>
                                <div><strong>Scalable Solution</strong><p>Whether you're a small chapel or a large cathedral, we grow with you.</p></div>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="ve-btn-primary mt-30" style="background-color: #940000; border-color: #940000; color: #ffffff;">View Demo</a>
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
                    <h2>Ready to Take Control of Your <span>Church Administration?</span></h2>
                    <p>Join hundreds of churches already using Waumini Link to empower their congregation.</p>
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
                    <h5 class="ve-footer-title">About Waumini Link</h5>
                    <p style="color: rgba(255,255,255,0.7); line-height: 1.8; margin-bottom: 25px;">
                        Your comprehensive church management system designed to streamline member administration, financial tracking, and community engagement.
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
                        <li><a href="#"><i class="fa fa-users"></i> Member Management</a></li>
                        <li><a href="#"><i class="fa fa-line-chart"></i> Financial Reports</a></li>
                        <li><a href="#"><i class="fa fa-check-square-o"></i> Attendance Tracking</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="ve-footer-bottom">
            <div class="container">
                <div class="ve-footer-bottom-inner">
                    <p>&copy; {{ date('Y') }} Waumini Link. All rights reserved.</p>
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