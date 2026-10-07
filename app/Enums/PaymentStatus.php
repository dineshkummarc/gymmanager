<?php
namespace App\Enums;
enum PaymentStatus: string { case Pending='pending'; case Paid='paid'; case Overdue='overdue'; case Cancelled='cancelled'; case Refunded='refunded'; case Partial='partial';
    public function label(): string { return match($this){ self::Pending=>'Pendente', self::Paid=>'Pago', self::Overdue=>'Vencido', self::Cancelled=>'Cancelado', self::Refunded=>'Estornado', self::Partial=>'Parcial' }; } }
