<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SequenceBill extends Model
{
    use HasFactory;
    public function Branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }

    public function Scheduling()
    {
        return $this->hasOne(StaffScheduling::class, 'id', 'schedule_id');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }
}
