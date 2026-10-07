<?php
namespace App\Support;
class Money {
    public static function format(float|int $v): string { return 'R$ '.number_format((float)$v, 2, ',', '.'); }
    public static function fine(float $v, int $days): float { return round($v * 0.02 + $v * 0.00033 * $days, 2); }
}
