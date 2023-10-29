<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffScheduling extends Model
{
    use HasFactory;

    public function Branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
}
