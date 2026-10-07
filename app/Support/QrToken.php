<?php
namespace App\Support;
class QrToken {
    public static function make(string $code): string { return hash_hmac('sha256', $code, config('app.key')); }
    public static function check(string $code, string $token): bool { return hash_equals(self::make($code), $token); }
}
