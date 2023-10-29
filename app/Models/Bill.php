<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'branch_id',
        'sequence_id',
        'total',
        'Tax',
        'cash',
        'online',
        'isUpload',
        'CustomerName',
        'CustomerPhone',
        'Status',
        'created_at',
        'updated_at',
    ];

    public function staff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }

    public function Branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
    public function driver()
    {
        return $this->hasOne(Staff::class, 'id', 'driver_id');
    }
    
}
