<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    public function staff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }

    public function expense()
    {
        return $this->hasOne(expense::class, 'id', 'expense_id');
    }

    public function PurchaseDetail()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

}
