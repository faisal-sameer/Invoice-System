<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    public function Purchase()
    {
        return $this->hasOne(Purchase::class, 'id', 'purchase_id');
    }

}
