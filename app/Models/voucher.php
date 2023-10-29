<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class voucher extends Model
{
    use HasFactory;

    public function Shope()
    {
        return $this->hasOne(Shope::class, 'id', 'Shope_id');
    }
}
