<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shope extends Model
{
    use HasFactory;

    public function Owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }
}
