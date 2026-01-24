<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Waumini Link</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/fontawesome.min.js') }}" crossorigin="anonymous"></script>
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #224abe;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
            --waumini: #17082d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Century Gothic', 'CenturyGothic', 'AppleGothic', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }
        
        .error-container {
            text-align: center;
            color: white;
            max-width: 600px;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .error-animation {
            position: relative;
            margin-bottom: 30px;
        }
        
        .error-number {
            font-size: 150px;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 40px rgba(255, 255, 255, 0.3);
            animation: float 3s ease-in-out infinite;
            margin-bottom: 20px;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.9;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.9;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }
        
        .error-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .error-message {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.95;
            line-height: 1.6;
        }
        
        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        
        .btn-custom {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary-custom {
            background: white;
            color: var(--gradient-start);
        }
        
        .btn-primary-custom:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            color: var(--gradient-start);
        }
        
        .btn-outline-custom {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
        }
        
        .btn-outline-custom:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: white;
            transform: translateY(-2px);
            color: white;
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float-shape 20s infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 40%;
            right: 30%;
            animation-delay: 6s;
        }
        
        @keyframes float-shape {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }
            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }
        
        .search-box {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 15px 25px;
            margin: 30px auto;
            max-width: 400px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .search-box i {
            font-size: 20px;
            opacity: 0.8;
        }
        
        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            color: white;
            font-size: 16px;
            flex: 1;
        }
        
        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        @media (max-width: 768px) {
            .error-number {
                font-size: 100px;
            }
            
            .error-title {
                font-size: 28px;
            }
            
            .error-message {
                font-size: 16px;
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .btn-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="error-container">
        <div class="error-animation">
            <div class="error-number">404</div>
            <div class="error-icon">
                <i class="fas fa-search"></i>
            </div>
        </div>
        
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            Oops! The page you're looking for doesn't exist or has been moved. 
            Don't worry, let's get you back on track.
        </p>
        
        <div class="error-actions">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-custom btn-primary-custom">
                        <i class="fas fa-home"></i>
                        Go to Dashboard
                    </a>
                @elseif(auth()->user()->member_id)
                    <a href="{{ route('member.dashboard') }}" class="btn-custom btn-primary-custom">
                        <i class="fas fa-home"></i>
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ url('/') }}" class="btn-custom btn-primary-custom">
                        <i class="fas fa-home"></i>
                        Go to Home
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-custom btn-primary-custom">
                    <i class="fas fa-sign-in-alt"></i>
                    Go to Login
                </a>
            @endauth
            <a href="javascript:history.back()" class="btn-custom btn-outline-custom">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </a>
        </div>
        
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search for what you need..." id="searchInput">
        </div>
    </div>
    
    <script>
        // Get dashboard URL based on user type
        @php
            $dashboardUrl = url('/');
            if (auth()->check()) {
                if (auth()->user()->isAdmin()) {
                    $dashboardUrl = route('admin.dashboard');
                } elseif (auth()->user()->member_id) {
                    $dashboardUrl = route('member.dashboard');
                }
            } else {
                $dashboardUrl = route('login');
            }
        @endphp
        const dashboardUrl = '{{ $dashboardUrl }}';
        
        // Simple search functionality
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    window.location.href = dashboardUrl + '?search=' + encodeURIComponent(searchTerm);
                }
            }
        });
        
        // Add some interactive effects
        document.querySelectorAll('.btn-custom').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.05)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>

