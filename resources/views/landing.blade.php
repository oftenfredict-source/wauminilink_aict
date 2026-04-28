<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>Waumini Link</title>
    <meta name="description" content="Waumini Link - Church Management System">
    <meta name="author" content="Waumini Link">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="{{ asset('hesed-master/hesed-master/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('hesed-master/hesed-master/css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Global Font Override */
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        a,
        li,
        span,
        button {
            font-family: 'Century Gothic', 'CenturyGothic', 'AppleGothic', sans-serif !important;
        }

        /* Hero Adjustments */
        .hero-content {
            align-items: center !important;
            padding-top: 5vh !important;
        }

        .hero-content__text {
            width: 100%;
        }

        .hero-content h1 {
            margin-right: 0 !important;
            font-size: 8.4rem !important;
        }

        .hero-content h1::before {
            display: none !important;
        }

        .hero-content__buttons {
            position: relative !important;
            bottom: auto !important;
            right: auto !important;
            margin-top: 5rem;
            display: flex;
            gap: 2rem;
        }

        .hero-content__buttons .btn {
            width: auto !important;
            min-width: 20rem;
            margin-bottom: 0;
        }

        /* Redesigned About Section */
        .s-about {
            padding-top: 15rem;
            padding-bottom: 15rem;
            background-color: #ffffff;
        }

        .about-header {
            text-align: center;
            max-width: 900px;
            margin: 0 auto 10rem;
        }

        .about-header .subhead {
            margin-bottom: 2rem;
        }

        .about-header h2 {
            font-size: 4.8rem;
            margin-top: 0;
            margin-bottom: 3rem;
        }

        .about-header .lead {
            font-size: 2.2rem;
            color: #555;
            line-height: 1.8;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4rem;
        }

        .feature-item {
            background: #fff;
            padding: 5rem 3.5rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid #f0f0f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        .feature-item:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
            border-color: #fb8b23;
        }

        .feature-icon {
            font-size: 5rem;
            color: #fb8b23;
            margin-bottom: 3rem;
            display: inline-block;
        }

        .feature-item h4 {
            font-size: 2.4rem;
            margin-top: 0;
            margin-bottom: 2rem;
            color: #111;
        }

        .feature-item p {
            font-size: 1.7rem;
            line-height: 1.7;
            color: #666;
            margin-bottom: 0;
        }

        .btn--login-about {
            margin-top: 6rem;
        }

        @media screen and (max-width: 1000px) {
            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 800px) {
            .hero-content h1 {
                font-size: 6rem !important;
            }

            .hero-content__buttons {
                flex-direction: column;
                gap: 1.5rem;
            }

            .hero-content__buttons .btn {
                width: 100% !important;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Preloader Customization */
        #preloader {
            background-color: #121212;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #loader-wrapper {
            text-align: center;
            width: 100%;
            max-width: 300px;
            animation: fadeIn 1s ease-in-out;
        }

        #preloader-logo {
            height: 80px;
            width: auto;
            margin-bottom: 40px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            filter: drop-shadow(0 0 10px rgba(251, 139, 35, 0.3));
            animation: preloaderPulse 2s infinite ease-in-out;
        }

        #loader {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            margin: 0 auto !important;
            transform: none !important;
            display: inline-block !important;
            height: 20px !important;
        }

        #loader>div {
            background-color: #fb8b23 !important;
            /* Matches theme color */
        }

        #preloader-powered {
            margin-top: 40px;
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
            letter-spacing: 0.2rem;
            text-transform: uppercase;
        }

        #preloader-powered span {
            color: #fb8b23;
            font-weight: 600;
        }

        @keyframes preloaderPulse {
            0% {
                transform: scale(1);
                opacity: 0.9;
            }

            50% {
                transform: scale(1.05);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 0.9;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- script
    ================================================== -->
    <script src="{{ asset('hesed-master/hesed-master/js/modernizr.js') }}"></script>

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('hesed-master/hesed-master/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('hesed-master/hesed-master/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('hesed-master/hesed-master/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('hesed-master/hesed-master/site.webmanifest') }}">

</head>

<body id="top">

    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader-wrapper">
            <img src="{{ asset('assets/images/waumini_link_logo.png') }}" alt="Waumini Link Logo" id="preloader-logo">
            <div id="loader" class="dots-jump">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div id="preloader-powered">
                Powered by <span>EmCa Technologies</span>
            </div>
        </div>
    </div>


    <!-- header
    ================================================== -->
    <header class="s-header">

        <div class="header-logo">
            <a class="site-logo" href="{{ route('landing_page') }}">
                <img src="{{ asset('assets/images/waumini_link_logo.png') }}" alt="Waumini Link"
                    style="height: 50px; width: auto;">
            </a>
        </div>

        <nav class="header-nav-wrap">
            <ul class="header-nav">
                <li class="current"><a href="{{ route('landing_page') }}" title="Home">Home</a></li>
                <li><a href="{{ route('login') }}" title="Login">Login</a></li>
                <li><a href="#about" class="smoothscroll" title="About">About</a></li>
                <li><a href="#events" class="smoothscroll" title="Events">Events</a></li>
                <li><a href="#contact" class="smoothscroll" title="Contact us">Contact</a></li>
            </ul>
        </nav>

        <a class="header-menu-toggle" href="#0"><span>Menu</span></a>

    </header> <!-- end s-header -->


    <!-- hero
    ================================================== -->
    <section class="s-hero" data-parallax="scroll"
        data-image-src="{{ asset('hesed-master/hesed-master/images/hero-bg-3000.jpg') }}" data-natural-width=3000
        data-natural-height=2000 data-position-y=center>

        <div class="hero-left-bar"></div>

        <div class="row hero-content">

            <div class="column large-full hero-content__text">
                <h1>
                    Connecting <br>
                    Believers, <br>
                    Empowering Churches
                </h1>

                <div class="hero-content__buttons">
                    <a href="{{ route('login') }}" class="btn btn--stroke">View Demo</a>
                    <a href="#about" class="smoothscroll btn btn--stroke">About Us</a>
                </div>
            </div> <!-- end hero-content__text -->

        </div> <!-- end hero-content -->

        <ul class="hero-social">
            <li class="hero-social__title">Follow Us</li>
            <li>
                <a href="#0" title="">Facebook</a>
            </li>
            <li>
                <a href="#0" title="">YouTube</a>
            </li>
            <li>
                <a href="#0" title="">Instagram</a>
            </li>
        </ul> <!-- end hero-social -->

        <div class="hero-scroll">
            <a href="#about" class="scroll-link smoothscroll">
                Scroll For More
            </a>
        </div> <!-- end hero-scroll -->

    </section> <!-- end s-hero -->


    <!-- about
    ================================================== -->
    <section id="about" class="s-about">

        <div class="row about-header">
            <div class="column large-full">
                <h3 class="subhead">Welcome to Waumini Link</h3>
                <h2>Elevating Church Administration</h2>
                <p class="lead">
                    Waumini Link is a comprehensive church management system designed to streamline your administration.
                    From member tracking to financial management, we provide the tools you need to focus on what matters
                    most: your mission.
                </p>
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn--primary btn--login-about">View Demo</a>
                </div>
            </div>
        </div>

        <div class="row feature-grid">
            <div class="column feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <h4>Efficient Management</h4>
                <p>
                    Manage your congregation with ease. Track attendance, contributions, and special events effectively.
                </p>
            </div>

            <div class="column feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4>Secure & Reliable</h4>
                <p>
                    Your data is safe with us. We use state-of-the-art security to ensure your church records are
                    protected.
                </p>
            </div>

            <div class="column feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h4>Transparent Reports</h4>
                <p>
                    Get real-time insights into your church's growth and financial health with detailed reporting.
                </p>
            </div>
        </div> <!-- end feature-grid -->

    </section> <!-- end s-about -->


    <!-- connect
    ================================================== -->
    <section class="s-connect">

        <div class="row connect-content">
            <div class="column large-half tab-full">
                <h3 class="display-1">Manage Your Church Better.</h3>
                <p>
                    Our platform offers integrated tools for database management, contribution tracking,
                    and communication, all tailored for the modern church environment.
                </p>

                <a href="{{ route('login') }}" class="btn btn--primary h-full-width">Join Us Today</a>
            </div>
            <div class="column large-half tab-full">
                <h3 class="display-1">Communicate with Ease.</h3>
                <p>
                    Keep your community engaged with built-in notification systems.
                    Send updates and announcements directly to your members.
                </p>

                <a href="{{ route('login') }}" class="btn btn--primary h-full-width">Learn More</a>
            </div>
        </div> <!-- end connect-content  -->

    </section> <!-- end s-connect -->


    <!-- events
    ================================================== -->
    <section id="events" class="s-events">

        <div class="row events-header">
            <div class="column">
                <h2 class="subhead">Core Features.</h2>
            </div>
        </div> <!-- end event-header -->

        <div class="row block-large-1-2 block-900-full events-list">

            <div class="column events-list__item">
                <h3 class="display-1 events-list__item-title">
                    <a href="#0" title="">Member Management</a>
                </h3>
                <p>
                    Maintain a centralized database of all church members, including families, youth, and children.
                </p>
            </div> <!-- end events-list__item -->
            <div class="column events-list__item">
                <h3 class="display-1 events-list__item-title">
                    <a href="#0" title="">Financial Tracking</a>
                </h3>
                <p>
                    Seamlessly track tithes, offerings, and donations with automated receipting and reporting.
                </p>
            </div> <!-- end events-list__item -->
            <div class="column events-list__item">
                <h3 class="display-1 events-list__item-title">
                    <a href="#0" title="">Attendance Control</a>
                </h3>
                <p>
                    Monitor attendance for services and special events using digital tools and biometric integration.
                </p>
            </div> <!-- end events-list__item -->
            <div class="column events-list__item">
                <h3 class="display-1 events-list__item-title">
                    <a href="#0" title="">Announcement System</a>
                </h3>
                <p>
                    Broadcast important information to your congregation via SMS and in-app notifications.
                </p>
            </div> <!-- end events-list__item -->

        </div> <!-- end events-list -->

    </section> <!-- end s-events -->


    <!-- Social
    ================================================== -->
    <section class="s-social">

        <div class="row social-content">
            <div class="column">
                <ul class="social-list">
                    <li class="social-list__item">
                        <a href="#0" title="">
                            <span class="social-list__icon social-list__icon--facebook"></span>
                            <span class="social-list__text">Facebook</span>
                        </a>
                    </li>
                    <li class="social-list__item">
                        <a href="#0" title="">
                            <span class="social-list__icon social-list__icon--twitter"></span>
                            <span class="social-list__text">Twitter</span>
                        </a>
                    </li>
                    <li class="social-list__item">
                        <a href="#0" title="">
                            <span class="social-list__icon social-list__icon--instagram"></span>
                            <span class="social-list__text">Instagram</span>
                        </a>
                    </li>
                    <li class="social-list__item">
                        <a href="#0" title="">
                            <span class="social-list__icon social-list__icon--email"></span>
                            <span class="social-list__text">Email</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div> <!-- end social-content -->

    </section> <!-- end s-social -->


    <!-- footer
    ================================================== -->
    <footer id="contact" class="s-footer">

        <div class="row footer-top">
            <div class="column large-4 medium-5 tab-full">
                <div class="footer-logo">
                    <a class="site-footer-logo" href="{{ route('landing_page') }}">
                        <img src="{{ asset('assets/images/waumini_link_logo.png') }}" alt="Waumini Link Logo">
                    </a>
                </div> <!-- footer-logo -->
                <p>
                    Waumini Link is dedicated to providing technological solutions for faith-based organizations,
                    helping them manage their communities more effectively and transparently.
                </p>
            </div>
            <div class="column large-half tab-full">
                <div class="row">
                    <div class="column large-7 medium-full">
                        <h4 class="h6">Our Office</h4>
                        <p>
                            Moshi, Kilimanjaro <br>
                            Tanzania
                        </p>

                        <p>
                            <a href="mailto:emca@emca.tech" class="btn btn--footer">Contact Support</a>
                        </p>
                    </div>
                    <div class="column large-5 medium-full">
                        <h4 class="h6">Quick Links</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('landing_page') }}">Home</a></li>
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="#about" class="smoothscroll">About</a></li>
                            <li><a href="#events" class="smoothscroll">Features</a></li>
                            <li><a href="#contact" class="smoothscroll">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> <!-- end footer-top -->

        <div class="row footer-bottom">
            <div class="column ss-copyright">
                <span>© Copyright Waumini Link {{ date('Y') }}</span>
                <span>Powered by <a href="https://emca.tech/">EmCa Technologies</a></span>
            </div>
        </div> <!-- footer-bottom -->

        <div class="ss-go-top">
            <a class="smoothscroll" title="Back to Top" href="#top">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M12 0l8 9h-6v15h-4v-15h-6z" />
                </svg>
            </a>
        </div> <!-- ss-go-top -->

    </footer> <!-- end s-footer -->


    <!-- Java Script
    ================================================== -->
    <script src="{{ asset('hesed-master/hesed-master/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('hesed-master/hesed-master/js/plugins.js') }}"></script>
    <script src="{{ asset('hesed-master/hesed-master/js/main.js') }}"></script>

</body>

</html>