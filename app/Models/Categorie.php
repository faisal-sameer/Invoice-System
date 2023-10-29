<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    public function Item()
    {
        return $this->hasOne(Item::class, 'categories_id', 'id');
    }
}
