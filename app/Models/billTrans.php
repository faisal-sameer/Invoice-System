<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class billTrans extends Model
{
    use HasFactory;

    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function city()
    {
        return $this->hasOne(Item::class, 'id', 'city_id');
    }
    public function Tocity()
    {
        return $this->hasOne(Item::class, 'id', 'to_city_id');
    }
    public function bill()
    {
        return $this->hasOne(Bill::class, 'id', 'Bill_id');
    }

}
