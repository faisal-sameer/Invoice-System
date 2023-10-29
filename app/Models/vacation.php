<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vacation extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->hasOne(typeVacation::class, 'id', 'type_id');
    }
    public function Staff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }
}
