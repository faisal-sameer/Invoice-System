<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    public function Branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }

    public function User()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
