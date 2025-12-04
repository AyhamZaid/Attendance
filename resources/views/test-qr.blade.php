<!DOCTYPE html>
<html>

<head>
    <title>QR Code Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Local QR Code Library Test</h1>
        <div class="row mt-4">
            <div class="col-md-6">
                <div id="qr-test" class="border p-3 text-center"></div>
            </div>
        </div>
        <div id="status" class="mt-3 alert alert-secondary">Initializing...</div>
    </div>

    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var statusDiv = document.getElementById('status');

            if (typeof QRCode === 'undefined') {
                statusDiv.className = 'mt-3 alert alert-danger';
                statusDiv.textContent = 'Error: QRCode library NOT loaded.';
                return;
            }

            statusDiv.className = 'mt-3 alert alert-success';
            statusDiv.textContent = 'Success: QRCode library loaded. Generating QR code...';

            try {
                var canvas = document.createElement('canvas');
                document.getElementById('qr-test').appendChild(canvas);

                QRCode.toCanvas(canvas, 'Test QR Code Content', {
                    width: 200
                }, function (error) {
                    if (error) {
                        statusDiv.className = 'mt-3 alert alert-warning';
                        statusDiv.textContent = 'Library loaded but generation failed: ' + error;
                    } else {
                        statusDiv.textContent = 'Success: QR Code generated successfully!';
                    }
                });
            } catch (e) {
                statusDiv.className = 'mt-3 alert alert-danger';
                statusDiv.textContent = 'Exception: ' + e.message;
            }
        });
    </script>
</body>

</html>