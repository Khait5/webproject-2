<?php
namespace Web\Core;

class Security {
    /**
     * Hashes a password according to L2J Mobius standard:
     * base64_encode(pack('H*', sha1($raw_password)))
     */
    public static function hashPassword($password) {
        return base64_encode(pack('H*', sha1($password)));
    }

    /**
     * Verifies if a raw password matches the given hash
     */
    public static function verifyPassword($password, $hash) {
        return hash_equals(self::hashPassword($password), $hash);
    }

    /**
     * Simple Brute Force Protection (Delay)
     */
    public static function bruteForceDelay($failedAttempts) {
        if ($failedAttempts > 3) {
            $delay = pow(2, $failedAttempts - 3); // 2s, 4s, 8s...
            // Max delay of 30 seconds
            $delay = min($delay, 30);
            sleep($delay);
        }
    }
}
