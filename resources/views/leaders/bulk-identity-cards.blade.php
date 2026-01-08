<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leadership Identity Cards - {{ $churchName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f0f0f0;
            padding: 20px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .page-header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: #666;
            font-size: 14px;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(3.375in, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .identity-card {
            width: 3.375in;
            height: 2.125in;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            color: white;
            page-break-inside: avoid;
            margin: 0 auto 20px;
        }
        
        .card-header {
            background: rgba(255,255,255,0.1);
            padding: 15px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .church-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .church-subtitle {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .card-body {
            padding: 20px;
            display: flex;
            height: calc(100% - 80px);
        }
        
        .photo-section {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: rgba(255,255,255,0.7);
        }
        
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .leader-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .position {
            font-size: 16px;
            margin-bottom: 10px;
            color: #ffd700;
            font-weight: 600;
        }
        
        .member-id {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 10px;
        }
        
        .appointment-date {
            font-size: 11px;
            opacity: 0.7;
        }
        
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.2);
            padding: 10px 20px;
            font-size: 10px;
            text-align: center;
            opacity: 0.8;
        }
        
        .decorative-elements {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        
        .decorative-elements::before {
            content: '';
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
            text-decoration: none;
        }
        
        .back-button:hover {
            background: #545b62;
            color: white;
        }
        
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-button, .back-button {
                display: none !important;
            }
            .page-header {
                box-shadow: none !important;
                margin-bottom: 20px !important;
            }
            .identity-card {
                box-shadow: none !important;
                margin: 0 auto 20px !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%) !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .position {
                color: #ffd700 !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .cards-container {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
            }
        }
        
        @page {
            size: A4;
            margin: 0.5in;
        }
    </style>
</head>
<body>
    <a href="{{ route('leaders.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Leaders
    </a>
    
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print All Cards
    </button>
    
    <div class="page-header">
        <h1>{{ $churchName }} - Leadership Identity Cards</h1>
        <p>Generated on {{ date('F d, Y \a\t g:i A') }} | Total Cards: {{ $leaders->count() }}</p>
    </div>
    
    <div class="cards-container">
        @foreach($leaders as $leader)
            <div class="identity-card">
                <div class="decorative-elements"></div>
                
                <div class="card-header">
                    <div class="church-name">{{ $churchName }}</div>
                    <div class="church-subtitle">Leadership Identity Card</div>
                </div>
                
                <div class="card-body">
                    <div class="photo-section">
                        <div class="photo">
                            @if($leader->member->profile_picture)
                                <img src="{{ asset($leader->member->profile_picture) }}" 
                                     alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <div>
                            <div class="leader-name">{{ $leader->member->full_name }}</div>
                            <div class="position">{{ $leader->position_display }}</div>
                            @if($leader->position_title)
                                <div class="position" style="font-size: 14px; color: rgba(255,255,255,0.9);">
                                    {{ $leader->position_title }}
                                </div>
                            @endif
                            <div class="member-id">ID: {{ $leader->member->member_id }}</div>
                        </div>
                        
                        <div class="appointment-date">
                            Appointed: {{ $leader->appointment_date->format('M d, Y') }}
                            @if($leader->end_date)
                                <br>Term Ends: {{ $leader->end_date->format('M d, Y') }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    {{ $churchAddress }} | {{ $churchPhone }} | {{ $churchEmail }}
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     setTimeout(() => {
        //         window.print();
        //     }, 2000);
        // }
    </script>
</body>
</html>
