<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    public function Shope()
    {
        return $this->hasOne(Shope::class, 'id', 'shope_id');
    }


    public function expense()
    {
        return $this->hasMany(expense::class, 'branch_id', 'id');
    }
}
