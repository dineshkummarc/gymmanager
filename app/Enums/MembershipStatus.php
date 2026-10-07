<?php
namespace App\Enums;
enum MembershipStatus: string { case Pending='pending'; case Active='active'; case Paused='paused'; case Expired='expired'; case Cancelled='cancelled'; case Completed='completed';
    public function label(): string { return match($this){ self::Pending=>'Pendente', self::Active=>'Ativa', self::Paused=>'Pausada', self::Expired=>'Expirada', self::Cancelled=>'Cancelada', self::Completed=>'Concluída' }; } }
