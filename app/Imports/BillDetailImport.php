<?php

namespace App\Imports;

use App\Models\BillDetail;
use Maatwebsite\Excel\Concerns\ToModel;

class BillDetailImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {


        return new BillDetail([
            'Bill_id' => $row[1],
            'item_id' => $row[2],
            'size' => $row[3],
            'count' => $row[4],
            'price' => $row[5],
            'isUpload' =>  1,
            'Status' => $row[7],
            'created_at' => $row[8],
            'updated_at' => $row[9],

        ]);
    }
}
