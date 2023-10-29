<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'Bill_id',
        'item_id',
        'size',
        'count',
        'price',
        'isUpload',
        'Status',
        'created_at',
        'updated_at',
    ];
    public function Item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
    public function bill()
    {
        return $this->hasOne(Bill::class, 'id', 'Bill_id');
    }
    public function Discount()
    {
        return $this->hasOne(Discount::class, 'id', 'Discount_id');
    }
}
