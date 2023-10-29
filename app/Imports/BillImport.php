<?php

namespace App\Imports;

use App\Models\bill;
use Maatwebsite\Excel\Concerns\ToModel;

class BillImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {


        return new bill([
            'staff_id' => $row[1],
            'branch_id' => $row[2],
            'total' => $row[3],
            'Tax' => $row[4],
            'payType' => $row[5],
            'paidUp' => $row[6],
            'isUpload' =>  1,
            'Status' => $row[8],
            'created_at' => $row[9],
            'updated_at' => $row[10],

        ]);
    }
}
