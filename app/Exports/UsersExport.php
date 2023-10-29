<?php

namespace App\Exports;

use App\Models\User;
use App\Models\expense;
use App\Models\Bill;;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {

        return [
            'التاريخ ',
            'المصروفات ',
            'المكسب ',
            'الكاش',
            'الشبكة ',
            'الصافي '
        ];
    }
    public function collection()
    {
        return collect($this->data);
    }
    /* public function collection()
    {
        // if ($this->id['id'] == null) {
        /* $exp =  DB::table('expenses')
            ->join('branches', 'expenses.branch_id', '=', 'branches.id')
            ->join('bills as cash', 'cash.branch_id', '=', 'expenses.branch_id')
            ->join('bills as online', 'online.branch_id', '=', 'expenses.branch_id')
            ->where([
                'branches.shope_id' => 1, /*'expenses.month' => '2022-03-01'
                'cash.payType' => '1',
                'online.payType' => '3' 
            ])
            ->select(
                'expenses.month',
                DB::raw('expenses.branchRent +
                expenses.electricBill+expenses.waterBill+ expenses.salaryBill+expenses.OtherBill'),
                DB::raw('SUM(cash.total)'),
                DB::raw('SUM(online.total)'),
                'branches.Name',

            )->orderBy('month')
            ->groupBy(
                'expenses.month',
                'expenses.branchRent',
                'expenses.electricBill',
                'expenses.waterBill',
                'expenses.salaryBill',
                'expenses.OtherBill',
                'branches.Name'
            )
            ->get();

        $users = [
            [
                'id' => 1,
                'name' => 'Hardik',
                'email' => 'hardik@gmail.com'
            ],
            [
                'id' => 2,
                'name' => 'Vimal',
                'email' => 'vimal@gmail.com'
            ],
            [
                'id' => 3,
                'name' => 'Harshad',
                'email' => 'harshad@gmail.com'
            ]
        ];

        return $users;
        /*  } else {
            return DB::table('expenses')
                ->join('branches', 'expenses.branch_id', '=', 'branches.id')
                ->where(['branches.shope_id' => 1,])
                ->whereYear('expenses.created_at', '=', '')
                ->select(
                    'expenses.*',
                    DB::raw('expenses.branchRent +
            expenses.electricBill+expenses.waterBill+ expenses.salaryBill+expenses.OtherBill'),
                    'branches.Name'
                )->orderBy('month')
                ->get();
        }
    }*/
}
