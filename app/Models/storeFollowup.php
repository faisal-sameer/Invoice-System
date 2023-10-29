<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storeFollowup extends Model
{
    use HasFactory;

    public function staff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }
    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

}
