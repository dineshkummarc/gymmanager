<?php
namespace App\Enums;
enum ReservationStatus: string { case Available='available'; case Reserved='reserved'; case Waitlist='waitlist'; case Cancelled='cancelled'; case Attended='attended'; case NoShow='no_show'; }
