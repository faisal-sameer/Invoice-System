<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill_Extra_Topping extends Model
{
    use HasFactory;


    public function ExtraTopping()
    {
        return $this->hasOne(extra_topping::class, 'id', 'extra_topping_id');
    }
    public function billDetails()
    {
        return $this->hasOne(BillDetail::class, 'id', 'Bill_details_id');
    }

    
}
