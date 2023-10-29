<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attend extends Model
{
    use HasFactory;

    public function Staff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }
}
