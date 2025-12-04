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

                <button id="useLinkToken" class="btn btn-info btn-checkin" style="display: none;">
                    Use Link Token
                </button>

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
                    <input type="text" id="keywordInput" class="form-control keyword-input" maxlength="6"
                        placeholder="Enter 6-character keyword">
                    <button id="submitKeyword" class="btn btn-primary mt-3 w-100">Submit Keyword</button>
                </div>

                <div id="statusMessage" class="mt-3"></div>

                <div class="alert-note">
                    <strong>Note:</strong> Please keep this tab open during the session for presence beacons.
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Library - Using local jsQR -->
    <script src="{{ asset('js/jsQR.min.js') }}"></script>
    <script>
        // Verify jsQR is loaded
        window.addEventListener('load', function () {
            if (typeof jsQR === 'undefined') {
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

                <button id="useLinkToken" class="btn btn-info btn-checkin" style="display: none;">
                    Use Link Token
                </button>

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
                    <input type="text" id="keywordInput" class="form-control keyword-input" maxlength="6"
                        placeholder="Enter 6-character keyword">
                    <button id="submitKeyword" class="btn btn-primary mt-3 w-100">Submit Keyword</button>
                </div>

                <div id="statusMessage" class="mt-3"></div>

                <div class="alert-note">
                    <strong>Note:</strong> Please keep this tab open during the session for presence beacons.
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Library - Using local jsQR -->
    <script src="{{ asset('js/jsQR.min.js') }}"></script>
    <script>
        // Verify jsQR is loaded
        window.addEventListener('load', function () {
            if (typeof jsQR === 'undefined') {
                console.error('jsQR library failed to load from CDN');
            } else {
                console.log('jsQR library loaded successfully');
            }
        });
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        /**
         * Attendance Module
         * Handles QR scanning, geolocation, check-in, beacons, and keyword challenges
         */
        const AttendanceModule = (function () {
            let beaconInterval = null;
            let currentSessionId = null;
            let scanner = null;

            function init() {
                setupEventListeners();
                checkUrlForManualCheckIn();
            }

            function checkUrlForManualCheckIn() {
                const urlParams = new URLSearchParams(window.location.search);
                const token = urlParams.get('token');
                const mode = urlParams.get('mode');

                if (token && mode) {
                    console.log('Manual check-in token detected');
                    const useLinkTokenBtn = document.getElementById('useLinkToken');
                    if (useLinkTokenBtn) {
                        useLinkTokenBtn.style.display = 'block';
                        useLinkTokenBtn.addEventListener('click', function () {
                            handleQRResult(token, mode, null);
                        });
                    }
                }
            }

            function setupEventListeners() {
                const scanQRBtn = document.getElementById('scanQR');
                const joinRemoteBtn = document.getElementById('joinRemote');
                const stopScannerBtn = document.getElementById('stopScanner');
                const submitKeywordBtn = document.getElementById('submitKeyword');

                if (scanQRBtn) {
                    scanQRBtn.addEventListener('click', handleScanQR);
                }

                if (joinRemoteBtn) {
                    joinRemoteBtn.addEventListener('click', handleJoinRemote);
                }

                if (stopScannerBtn) {
                    stopScannerBtn.addEventListener('click', stopScanner);
                }

                if (submitKeywordBtn) {
                    submitKeywordBtn.addEventListener('click', handleSubmitKeyword);
                }
            }

            async function handleScanQR() {
                try {
                    const position = await getCurrentPosition();
                    const video = document.getElementById('video');
                    const canvas = document.getElementById('canvas');
                    const qrScannerDiv = document.getElementById('qrScanner');

                    if (!video || !canvas) {
                        showStatus('Error: Video or canvas element not found', 'danger');
                        return;
                    }

                    qrScannerDiv.style.display = 'block';
                    video.style.display = 'block';
                    canvas.style.display = 'block';

                    if (typeof QrScanner !== 'undefined') {
                        scanner = new QrScanner(video, result => {
                            handleQRResult(result, 'onsite', position);
                        });
                        await scanner.start();
                    } else if (typeof jsQR !== 'undefined') {
                        console.log('Using jsQR library for QR scanning');
                        startJsQRScanner(video, canvas, position);
                    } else {
                        console.error('Neither QrScanner nor jsQR is available');
                        showStatus('QR Scanner library not loaded. Please refresh the page.', 'warning');
                    }
                } catch (error) {
                    console.error('Error starting QR scanner:', error);
                    showStatus('Error accessing camera: ' + error.message, 'danger');
                }
            }

            async function handleJoinRemote() {
                try {
                    const position = await getCurrentPosition();
                    const sessionId = prompt('Enter session ID:');
                    if (!sessionId) return;
                    showStatus('Remote join functionality requires session token', 'info');
                } catch (error) {
                    console.error('Error joining remote:', error);
                    showStatus('Error: ' + error.message, 'danger');
                }
            }

            async function handleQRResult(token, mode, position) {
                stopScanner();
                try {
                    const checkInData = { token: token, mode: mode };
                    if (position) {
                        checkInData.lat = position.coords.latitude;
                        checkInData.lng = position.coords.longitude;
                        checkInData.geo_confidence = 0.9;
                    }
                    const response = await axios.post('/attendance/check-in', checkInData);
                    if (response.data.attendance) {
                        currentSessionId = response.data.attendance.training_session_id;
                        showStatus('Checked in successfully!', 'success');
                        startBeacons(currentSessionId);
                        showKeywordSection();
                    }
                } catch (error) {
                    console.error('Check-in error:', error);
                    const message = error.response?.data?.error || 'Check-in failed';
                    showStatus(message, 'danger');
                }
            }

            function getCurrentPosition() {
                return new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        reject(new Error('Geolocation is not supported by this browser'));
                        return;
                    }
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    });
                });
            }

            function startBeacons(sessionId) {
                if (beaconInterval) clearInterval(beaconInterval);
                sendBeacon(sessionId);
                beaconInterval = setInterval(() => { sendBeacon(sessionId); }, 120000);
            }

            async function sendBeacon(sessionId) {
                try {
                    const beaconData = {};
                    try {
                        const position = await getCurrentPosition();
                        beaconData.lat = position.coords.latitude;
                        beaconData.lng = position.coords.longitude;
                    } catch (error) {
                        console.warn('Could not get position for beacon:', error);
                    }
                    await axios.post(`/sessions/${sessionId}/beacon`, beaconData);
                    console.log('Beacon sent successfully');
                } catch (error) {
                    console.error('Beacon error:', error);
                }
            }

            function startJsQRScanner(video, canvas, position) {
                if (typeof jsQR === 'undefined') {
                    showStatus('jsQR library not loaded. Please refresh the page.', 'danger');
                    return;
                }
                const context = canvas.getContext('2d');
                let scanning = true;

                function scan() {
                    if (!scanning) return;
                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        canvas.height = video.videoHeight;
                        canvas.width = video.videoWidth;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                        try {
                            const code = jsQR(imageData.data, imageData.width, imageData.height);
                            if (code) {
                                console.log('QR Code detected:', code.data);
                                handleQRResult(code.data, 'onsite', position);
                                return;
                            }
                        } catch (error) {
                            console.error('Error decoding QR code:', error);
                        }
                    }
                    requestAnimationFrame(scan);
                }

                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } } })
                    .then(stream => {
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true);
                        video.play();
                        showStatus('Camera started. Point at QR code...', 'info');
                        scan();
                    })
                    .catch(error => {
                        console.error('Error accessing camera:', error);
                        showStatus('Error accessing camera: ' + error.message, 'danger');
                    });

                scanner = {
                    stop: function () {
                        scanning = false;
                        if (video.srcObject) {
                            video.srcObject.getTracks().forEach(track => track.stop());
                        }
                        video.srcObject = null;
                    }
                };
            }

            function stopScanner() {
                if (scanner) {
                    scanner.stop();
                    scanner = null;
                }
                const video = document.getElementById('video');
                const qrScannerDiv = document.getElementById('qrScanner');
                if (video) {
                    video.style.display = 'none';
                    const stream = video.srcObject;
                    if (stream) stream.getTracks().forEach(track => track.stop());
                }
                if (qrScannerDiv) qrScannerDiv.style.display = 'none';
            }

            async function handleSubmitKeyword() {
                const keywordInput = document.getElementById('keywordInput');
                const keyword = keywordInput.value.trim().toUpperCase();
                if (keyword.length !== 6) {
                    showStatus('Keyword must be 6 characters', 'danger');
                    return;
                }
                const sessionId = getSessionIdFromContext();
                if (!sessionId) {
                    showStatus('Session ID not found', 'danger');
                    return;
                }
                try {
                    await axios.post(`/sessions/${sessionId}/challenge`, { keyword: keyword });
                    showStatus('Challenge passed successfully!', 'success');
                    keywordInput.value = '';
                } catch (error) {
                    console.error('Challenge error:', error);
                    const message = error.response?.data?.error || 'Invalid keyword';
                    showStatus(message, 'danger');
                }
            }

            function getSessionIdFromContext() {
                const urlParams = new URLSearchParams(window.location.search);
                const sessionId = urlParams.get('session_id');
                if (sessionId) return sessionId;
                return currentSessionId;
            }

            function showStatus(message, type) {
                const statusDiv = document.getElementById('statusMessage');
                if (!statusDiv) return;
                const alertClass = `alert alert-${type}`;
                statusDiv.innerHTML = `<div class="${alertClass}">${message}</div>`;
                setTimeout(() => { statusDiv.innerHTML = ''; }, 5000);
            }

            function showKeywordSection() {
                const keywordSection = document.getElementById('keywordSection');
                if (keywordSection) keywordSection.style.display = 'block';
            }

            return { init: init, showKeywordSection: showKeywordSection };
        })();

        // Auto-initialize if DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', AttendanceModule.init);
        } else {
            AttendanceModule.init();
        }
    </script>
</body>

</html>