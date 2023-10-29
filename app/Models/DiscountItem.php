<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountItem extends Model
{
    use HasFactory;

    public function Discount()
    {
        return $this->hasOne(Discount::class, 'id', 'Discount_id');
    }

    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function Cat()
    {
        return $this->hasOne(Categorie::class, 'id', 'categorie_id');
    }
}
