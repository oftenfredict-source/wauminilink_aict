<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identity Card - {{ $leader->member->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .identity-card {
            width: 3.375in; /* Standard ID card size */
            height: 2.125in;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
            color: white;
            border: 2px solid #fff;
        }
        
        .card-header {
            background: rgba(255,255,255,0.15);
            padding: 8px 12px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }
        
        .church-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        
        .church-subtitle {
            font-size: 9px;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .card-body {
            padding: 12px;
            display: flex;
            height: calc(100% - 50px);
        }
        
        .photo-section {
            width: 60px;
            height: 60px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: rgba(255,255,255,0.8);
            overflow: hidden;
        }
        
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .leader-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            line-height: 1.2;
        }
        
        .position {
            font-size: 12px;
            margin-bottom: 6px;
            color: #ffd700;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .member-id {
            font-size: 10px;
            opacity: 0.9;
            margin-bottom: 6px;
            font-family: 'Courier New', monospace;
        }
        
        .appointment-date {
            font-size: 9px;
            opacity: 0.8;
            line-height: 1.2;
        }
        
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.3);
            padding: 6px 12px;
            font-size: 8px;
            text-align: center;
            opacity: 0.9;
        }
        
        .decorative-elements {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        
        .decorative-elements::before {
            content: '';
            position: absolute;
            top: 15px;
            right: 15px;
            width: 30px;
            height: 30px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
            transition: all 0.3s ease;
        }
        
        .print-button:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,123,255,0.4);
        }
        
        /* Print optimizations */
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .print-button {
                display: none !important;
            }
            
            .identity-card {
                box-shadow: none !important;
                margin: 0 !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            
            /* Ensure consistent colors in print */
            .identity-card {
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%) !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            .position {
                color: #ffd700 !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
        
        @page {
            size: A4;
            margin: 0.5in;
        }
        
        /* High DPI print support */
        @media print and (min-resolution: 300dpi) {
            .identity-card {
                width: 3.375in;
                height: 2.125in;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print ID Card
    </button>
    
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

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     setTimeout(() => {
        //         window.print();
        //     }, 1000);
        // }
    </script>
</body>
</html>
