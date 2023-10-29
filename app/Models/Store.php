<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
    public function Branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function followUp()
    {
        return $this->hasMany(storeFollowup::class, 'store_id', 'id');
    }
}
