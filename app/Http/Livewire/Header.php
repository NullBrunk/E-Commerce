<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Notifications;
use Livewire\Component;

class Header extends Component
{
    public $notifs;
    public $notifs_number;

    protected $listeners = ['NotificationReceived' => 'get_notifs'];

    public function get_notifs(){
        $data = Notifications::get_array_notifications();

        $this -> notifs = $data[0];
        $this -> notifs_number = $data[1];
    }

    
    public function mount() {
        self::get_notifs();
    }


    public function render()
    {
        return view('livewire.header');
    }
}
