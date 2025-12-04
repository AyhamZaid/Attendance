<div class="qr-container">
    <div id="qr-{{ $mode }}" class="text-center qr-code-container" data-token="{{ $token }}" data-mode="{{ $mode }}">
        <!-- QR code will be generated here -->
    </div>
    <p class="text-center mt-2">
        <small class="text-muted">Mode: {{ ucfirst($mode) }}</small>
    </p>
</div>

