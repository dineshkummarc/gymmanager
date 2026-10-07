<?php
namespace App\Enums;
enum ClassStatus: string { case Scheduled='scheduled'; case Open='open'; case Full='full'; case Completed='completed'; case Cancelled='cancelled'; }
