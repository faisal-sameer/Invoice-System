<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCompound extends Model
{
    use HasFactory;

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
