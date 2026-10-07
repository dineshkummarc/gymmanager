<?php
use Illuminate\Support\Facades\Schedule;
Schedule::job(new \App\Jobs\GenerateInvoicesJob)->monthlyOn(1,'02:00');
Schedule::job(new \App\Jobs\MarkOverdueInvoicesJob)->dailyAt('01:00');
Schedule::job(new \App\Jobs\SendDueRemindersJob)->dailyAt('08:00');
