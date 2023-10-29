<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    use HasFactory;


    public function type()
    {
        return $this->hasOne(typeNotification::class, 'id', 'type_id');
    }
    public function resend()
    {
        return $this->hasOne(notification::class, 'id', 'resend_id');
    }
    public function staff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }
    public function Tostaff()
    {
        return $this->hasOne(Staff::class, 'id', 'to_staff_id');
    }
}
