<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otherExpense extends Model
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

}
