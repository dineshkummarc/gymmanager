<?php
namespace App\Enums;
enum UserRole: string {
    case SuperAdmin='super_admin'; case Owner='owner'; case Manager='manager';
    case Receptionist='receptionist'; case Instructor='instructor'; case Personal='personal';
    case Financial='financial'; case Sales='sales'; case Nutritionist='nutritionist';
    case Viewer='viewer'; case Student='student';
    public function label(): string {
        return match($this){
            self::SuperAdmin=>'Super Admin', self::Owner=>'Gym Owner', self::Manager=>'Gerente',
            self::Receptionist=>'Recepção', self::Instructor=>'Instrutor', self::Personal=>'Personal Trainer',
            self::Financial=>'Financeiro', self::Sales=>'Vendas', self::Nutritionist=>'Nutricionista',
            self::Viewer=>'Visualizador', self::Student=>'Aluno',
        };
    }
    public static function permissions(self $r): array {
        return match($r){
            self::SuperAdmin, self::Owner => ['*'],
            self::Manager => ['students','teachers','plans','workouts','classes','reports','leads','sales'],
            self::Receptionist => ['students','memberships','payments','checkin','schedule','leads'],
            self::Instructor => ['students','workouts','assessments','classes'],
            self::Personal => ['students','workouts','schedule','commissions'],
            self::Financial => ['payments','invoices','cash','financial','reports','commissions'],
            self::Sales => ['leads','sales','students','plans'],
            self::Nutritionist => ['students','assessments'],
            self::Viewer => ['reports'],
            self::Student => ['portal'],
        };
    }
}
