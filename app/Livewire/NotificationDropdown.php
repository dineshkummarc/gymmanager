<?php
namespace App\Livewire;
use Livewire\Component;
class NotificationDropdown extends Component {
    public function readAll(){ auth()->user()->unreadNotifications->markAsRead(); }
    public function render(){ return view('livewire.notification-dropdown',['items'=>auth()->user()->notifications()->limit(10)->get(),'unread'=>auth()->user()->unreadNotifications()->count()]); }
}
