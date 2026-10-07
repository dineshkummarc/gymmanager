<?php
namespace App\Enums;
enum StudentStatus: string { case Active='active'; case Inactive='inactive'; case Suspended='suspended'; case Pending='pending'; case Cancelled='cancelled';
    public function label(): string { return match($this){ self::Active=>'Ativo', self::Inactive=>'Inativo', self::Suspended=>'Suspenso', self::Pending=>'Pendente', self::Cancelled=>'Cancelado' }; }
    public function color(): string { return match($this){ self::Active=>'green', self::Inactive=>'zinc', self::Suspended=>'amber', self::Pending=>'blue', self::Cancelled=>'red' }; } }
