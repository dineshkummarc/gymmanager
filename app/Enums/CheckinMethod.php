<?php
namespace App\Enums;
enum CheckinMethod: string { case Qr='qr_code'; case Code='member_code'; case Search='search'; case Card='card'; }
