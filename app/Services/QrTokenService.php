<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class QrTokenService
{
    /**
     * Generate an encrypted QR token with 45 second expiry.
     *
     * @param array $payload
     * @return string
     */
    public function generate(array $payload): string
    {
        $payload['expires_at'] = Carbon::now()->addSeconds(45)->timestamp;
        
        return Crypt::encryptString(json_encode($payload));
    }

    /**
     * Decrypt and validate QR token.
     *
     * @param string $token
     * @return array|null
     */
    public function validate(string $token): ?array
    {
        try {
            $decrypted = Crypt::decryptString($token);
            $payload = json_decode($decrypted, true);

            if (!isset($payload['expires_at'])) {
                return null;
            }

            if (Carbon::now()->timestamp > $payload['expires_at']) {
                return null;
            }

            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }
}


