<?php
namespace App\Enums;
enum ContractStatus: string { case Draft='draft'; case PendingSignature='pending_signature'; case Active='active'; case Expired='expired'; case Cancelled='cancelled'; }
