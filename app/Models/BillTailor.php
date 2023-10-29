<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillTailor extends Model
{
    use HasFactory;

   
    public function bill()
    {
        return $this->hasOne(Bill::class, 'id', 'Bill_id');
    }
    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function upPoket()
    {
        return $this->hasOne(itemsTailor::class, 'id', 'up_poket_id');
    }
    public function neckID()
    {
        return $this->hasOne(itemsTailor::class, 'id', 'neck_id');
    }
    public function handID()
    {
        return $this->hasOne(itemsTailor::class, 'id', 'hand_id');
    }
    public function Midstyle()
    {
        return $this->hasOne(itemsTailor::class, 'id', 'midstyle_id');
    }
}
