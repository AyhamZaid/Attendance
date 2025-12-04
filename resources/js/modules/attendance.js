/**
 * Attendance Module
 * Handles QR scanning, geolocation, check-in, beacons, and keyword challenges
 */

// QrScanner will be available globally from CDN or npm
// If using npm: const QrScanner = require('qr-scanner');
// If using CDN: QrScanner is available globally

const AttendanceModule = (function () {
    let beaconInterval = null;
    let currentSessionId = null;
    let scanner = null;

    /**
     * Initialize the attendance module
     */
    function init() {
        setupEventListeners();
        checkUrlForManualCheckIn();
    }

    /**
     * Check URL for manual check-in parameters (token & mode)
     */
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

    /**
     * Setup event listeners for check-in buttons
     */
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

    /**
     * Handle QR code scanning
     */
    async function handleScanQR() {
        try {
            // Request geolocation
            const position = await getCurrentPosition();

            // Initialize QR scanner
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

            // Use QR scanner library if available
            if (typeof QrScanner !== 'undefined') {
                // Using qr-scanner library (if installed via npm)
                scanner = new QrScanner(video, result => {
                    handleQRResult(result, 'onsite', position);
                });
                await scanner.start();
            } else if (typeof jsQR !== 'undefined') {
                // Using jsQR library (CDN fallback)
                console.log('Using jsQR library for QR scanning');
                startJsQRScanner(video, canvas, position);
            } else {
                console.error('Neither QrScanner nor jsQR is available');
                showStatus('QR Scanner library not loaded. Please refresh the page or check your internet connection.', 'warning');
            }
        } catch (error) {
            console.error('Error starting QR scanner:', error);
            showStatus('Error accessing camera: ' + error.message, 'danger');
        }
    }

    /**
     * Handle remote join
     */
    async function handleJoinRemote() {
        try {
            const position = await getCurrentPosition();

            // For remote, we need to get the session ID from URL or prompt
            const sessionId = prompt('Enter session ID:');
            if (!sessionId) {
                return;
            }

            // In a real implementation, you'd get the remote token from the server
            // For now, we'll simulate it
            showStatus('Remote join functionality requires session token', 'info');
        } catch (error) {
            console.error('Error joining remote:', error);
            showStatus('Error: ' + error.message, 'danger');
        }
    }

    /**
     * Handle QR scan result
     */
    async function handleQRResult(token, mode, position) {
        stopScanner();

        try {
            const checkInData = {
                token: token,
                mode: mode,
            };

            if (position) {
                checkInData.lat = position.coords.latitude;
                checkInData.lng = position.coords.longitude;
                checkInData.geo_confidence = 0.9; // High confidence for GPS
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

    /**
     * Get current geolocation
     */
    function getCurrentPosition() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation is not supported by this browser'));
                return;
            }

            navigator.geolocation.getCurrentPosition(
                resolve,
                reject,
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    }

    /**
     * Start sending beacon heartbeats every 2 minutes
     */
    function startBeacons(sessionId) {
        if (beaconInterval) {
            clearInterval(beaconInterval);
        }

        // Send initial beacon
        sendBeacon(sessionId);

        // Then send every 2 minutes (120000 ms)
        beaconInterval = setInterval(() => {
            sendBeacon(sessionId);
        }, 120000);
    }

    /**
     * Send a beacon heartbeat
     */
    async function sendBeacon(sessionId) {
        try {
            const beaconData = {};

            // Try to get current position for beacon
            try {
                const position = await getCurrentPosition();
                beaconData.lat = position.coords.latitude;
                beaconData.lng = position.coords.longitude;
            } catch (error) {
                // Geolocation failed, send without coordinates
                console.warn('Could not get position for beacon:', error);
            }

            await axios.post(`/sessions/${sessionId}/beacon`, beaconData);
            console.log('Beacon sent successfully');
        } catch (error) {
            console.error('Beacon error:', error);
            // Don't show error to user for beacons, just log
        }
    }

    /**
     * Start jsQR scanner (fallback when qr-scanner is not available)
     */
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

        // Start video stream
        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        })
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

        // Store scanning state for stop function
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

    /**
     * Stop QR scanner
     */
    function stopScanner() {
        if (scanner) {
            scanner.stop();
            scanner = null;
        }

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const qrScannerDiv = document.getElementById('qrScanner');

        if (video) {
            video.style.display = 'none';
            const stream = video.srcObject;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        }

        if (qrScannerDiv) {
            qrScannerDiv.style.display = 'none';
        }
    }

    /**
     * Handle keyword challenge submission
     */
    async function handleSubmitKeyword() {
        const keywordInput = document.getElementById('keywordInput');
        const keyword = keywordInput.value.trim().toUpperCase();

        if (keyword.length !== 6) {
            showStatus('Keyword must be 6 characters', 'danger');
            return;
        }

        // Get session ID from URL or context
        const sessionId = getSessionIdFromContext();
        if (!sessionId) {
            showStatus('Session ID not found', 'danger');
            return;
        }

        try {
            const response = await axios.post(`/sessions/${sessionId}/challenge`, {
                keyword: keyword
            });

            showStatus('Challenge passed successfully!', 'success');
            keywordInput.value = '';
        } catch (error) {
            console.error('Challenge error:', error);
            const message = error.response?.data?.error || 'Invalid keyword';
            showStatus(message, 'danger');
        }
    }

    /**
     * Get session ID from URL or context
     */
    function getSessionIdFromContext() {
        // Try to get from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const sessionId = urlParams.get('session_id');
        if (sessionId) {
            return sessionId;
        }

        // Try to get from current session ID if set
        return currentSessionId;
    }

    /**
     * Show status message
     */
    function showStatus(message, type) {
        const statusDiv = document.getElementById('statusMessage');
        if (!statusDiv) {
            return;
        }

        const alertClass = `alert alert-${type}`;
        statusDiv.innerHTML = `<div class="${alertClass}">${message}</div>`;

        // Auto-hide after 5 seconds
        setTimeout(() => {
            statusDiv.innerHTML = '';
        }, 5000);
    }

    /**
     * Show keyword input section
     */
    function showKeywordSection() {
        const keywordSection = document.getElementById('keywordSection');
        if (keywordSection) {
            keywordSection.style.display = 'block';
        }
    }

    // Public API
    return {
        init: init,
        showKeywordSection: showKeywordSection,
    };
})();

// Auto-initialize if DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', AttendanceModule.init);
} else {
    AttendanceModule.init();
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AttendanceModule;
}

