<?php
namespace App\Notifications;
use Illuminate\Notifications\Notification;
class GenericNotification extends Notification {
    public function __construct(public string $title, public string $body, public string $url='/dashboard') {}
    public function via($n): array { return ['database']; }
    public function toArray($n): array { return ['title'=>$this->title,'body'=>$this->body,'url'=>$this->url]; }
}
