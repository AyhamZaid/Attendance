<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session: {{ $session->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .qr-container {
            padding: 20px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .keyword-display {
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 0.2rem;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1>{{ $session->title }}</h1>
                <p class="text-muted mb-0">Mode: {{ ucfirst($session->mode) }}</p>
                <p class="text-muted mb-0">Starts: {{ $session->starts_at->format('Y-m-d H:i') }}</p>
                <p class="text-muted mb-0">Ends: {{ $session->ends_at->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            @if($onsiteQrToken)
                <div class="col-md-6">
                    <h3>Onsite QR Code</h3>
                    @component('components.qr-panel', ['token' => $onsiteQrToken, 'mode' => 'onsite'])
                    @endcomponent
                </div>
            @endif

            @if($remoteQrToken)
                <div class="col-md-6">
                    <h3>Remote QR Code</h3>
                    @component('components.qr-panel', ['token' => $remoteQrToken, 'mode' => 'remote'])
                    @endcomponent
                </div>
            @endif
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Keyword Challenge</h3>
                <button id="generateKeyword" class="btn btn-primary">Generate Keyword</button>
                <div id="keywordDisplay" class="keyword-display mt-3" style="display: none;"></div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Livewire Placeholder</h3>
                <div id="livewire-placeholder">
                    <!-- Livewire component will be rendered here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Load QRCode library - using jsDelivr which is more reliable -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js" onerror="loadQRCodeFallback()"></script>
    <script>
        // Fallback loader if primary CDN fails
        function loadQRCodeFallback() {
            console.warn('Primary CDN failed, trying fallback...');
            var script = document.createElement('script');
            script.src = 'https://unpkg.com/qrcode@1.5.3/build/qrcode.min.js';
            script.onerror = function() {
                console.error('Fallback CDN also failed, trying alternative...');
                var script2 = document.createElement('script');
                script2.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.4/build/qrcode.min.js';
                script2.onload = function() {
                    console.log('QRCode loaded from alternative version');
                    initQRCodes();
                };
                script2.onerror = function() {
                    console.error('All CDN sources failed');
                    document.querySelectorAll('.qr-code-container').forEach(function(container) {
                        container.innerHTML = '<p class="text-danger">Failed to load QR Code library. Please check your internet connection.</p>';
                    });
                };
                document.head.appendChild(script2);
            };
            script.onload = function() {
                console.log('QRCode loaded from fallback CDN');
                initQRCodes();
            };
            document.head.appendChild(script);
        }
        
        // Generate QR codes for all containers
        function generateQRCodes() {
            if (typeof QRCode === 'undefined') {
                console.error('QRCode library not available');
                document.querySelectorAll('.qr-code-container').forEach(function(container) {
                    container.innerHTML = '<p class="text-danger">QR Code library not available</p>';
                });
                return;
            }
            
            document.querySelectorAll('.qr-code-container').forEach(function(container) {
                var token = container.getAttribute('data-token');
                var mode = container.getAttribute('data-mode');
                
                if (!token) {
                    console.error('Token is missing for mode:', mode);
                    container.innerHTML = '<p class="text-danger">Token is missing</p>';
                    return;
                }
                
                console.log('Generating QR code for mode:', mode);
                
                // Create a canvas element
                var canvas = document.createElement('canvas');
                container.innerHTML = ''; // Clear any existing content
                container.appendChild(canvas);
                
                try {
                    QRCode.toCanvas(canvas, token, {
                        width: 300,
                        margin: 2,
                        color: {
                            dark: '#000000',
                            light: '#FFFFFF'
                        }
                    }, function (error) {
                        if (error) {
                            console.error('QR Code generation error for mode ' + mode + ':', error);
                            container.innerHTML = '<p class="text-danger">Error: ' + error.message + '</p>';
                        } else {
                            console.log('QR Code generated successfully for mode:', mode);
                        }
                    });
                } catch (e) {
                    console.error('Exception generating QR code:', e);
                    container.innerHTML = '<p class="text-danger">Exception: ' + e.message + '</p>';
                }
            });
        }
        
        // Initialize QR codes - check if library is already loaded
        function initQRCodes() {
            if (typeof QRCode !== 'undefined') {
                generateQRCodes();
            } else {
                // Wait a bit for library to load
                setTimeout(function() {
                    if (typeof QRCode !== 'undefined') {
                        generateQRCodes();
                    } else {
                        console.error('QRCode library not available after waiting');
                        document.querySelectorAll('.qr-code-container').forEach(function(container) {
                            container.innerHTML = '<p class="text-danger">QR Code library not loaded. Please refresh the page.</p>';
                        });
                    }
                }, 500);
            }
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initQRCodes, 100);
            });
        } else {
            setTimeout(initQRCodes, 100);
        }
        
        // Keyword challenge button
        document.getElementById('generateKeyword').addEventListener('click', function() {
            fetch('{{ route("trainer.sessions.challenge", $session->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('keywordDisplay').textContent = data.keyword;
                document.getElementById('keywordDisplay').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>

