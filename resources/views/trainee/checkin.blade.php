<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check In</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .checkin-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .btn-checkin {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
        }
        .keyword-input {
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 0.3rem;
            text-transform: uppercase;
        }
        .alert-note {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container checkin-container">
        <h1 class="text-center mb-4">Training Session Check-In</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Choose Check-In Method</h5>
                
                <button id="scanQR" class="btn btn-primary btn-checkin">
                    Scan QR Code
                </button>

                <button id="joinRemote" class="btn btn-success btn-checkin">
                    Join Remote Session
                </button>

                <div id="qrScanner" style="display: none;" class="mt-3">
                    <video id="video" width="100%" autoplay playsinline style="display: none;"></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <button id="stopScanner" class="btn btn-secondary btn-sm mt-2">Stop Scanner</button>
                </div>

                <div id="keywordSection" style="display: none;" class="mt-4">
                    <h5>Enter Keyword Challenge</h5>
                    <input type="text" id="keywordInput" class="form-control keyword-input" 
                           maxlength="6" placeholder="Enter 6-character keyword">
                    <button id="submitKeyword" class="btn btn-primary mt-3 w-100">Submit Keyword</button>
                </div>

                <div id="statusMessage" class="mt-3"></div>

                <div class="alert-note">
                    <strong>Note:</strong> Please keep this tab open during the session for presence beacons.
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Library - Using jsQR as alternative with better CDN support -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        // Verify jsQR is loaded
        window.addEventListener('load', function() {
            if (typeof jsQR === 'undefined') {
                console.error('jsQR library failed to load from CDN');
            } else {
                console.log('jsQR library loaded successfully');
            }
        });
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ mix('js/modules/attendance.js') }}"></script>
    <script>
        // Initialize attendance module after libraries are loaded
        window.addEventListener('load', function() {
            if (typeof AttendanceModule !== 'undefined') {
                AttendanceModule.init();
            } else {
                console.error('AttendanceModule not found');
            }
        });
    </script>
</body>
</html>

