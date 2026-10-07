<?php
namespace App\Enums;
enum PaymentMethod: string { case Pix='pix'; case Cash='cash'; case Credit='credit_card'; case Debit='debit_card'; case Boleto='boleto'; case Transfer='transfer';
    public function label(): string { return match($this){ self::Pix=>'PIX', self::Cash=>'Dinheiro', self::Credit=>'Crédito', self::Debit=>'Débito', self::Boleto=>'Boleto', self::Transfer=>'Transferência' }; } }
