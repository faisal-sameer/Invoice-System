<?php

namespace App\Http\Controllers;

use App\Models\SequenceBill;
use App\Models\Shope;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //


    protected function FAStored(Request $request)
    {

        // return $request->all();
        $Shope =  Shope::get();
        if ($request->shope == 0) {
            if ($request->day == null) {
                $Box = SequenceBill::orderBy('created_at', 'DESC')->paginate(10,  ['*'], 'Box');
            } else {
                $day =  $request->day;
                $Box = SequenceBill::where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), $day)->orderBy('created_at', 'DESC')->paginate(10,  ['*'], 'Box');
            }
        } else {

            if ($request->day == null) {
                $Box = SequenceBill::whereHas('Branch', function ($q)  use ($request) {
                    $q->where('shope_id', $request->shope);
                })->orderBy('created_at', 'DESC')->paginate(10,  ['*'], 'Box');
            } else {
                $day =  $request->day;
                $Box = SequenceBill::whereHas('Branch', function ($q)  use ($request) {
                    $q->where('shope_id', $request->shope);
                })->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), $day)->orderBy('created_at', 'DESC')->paginate(10,  ['*'], 'Box');
            }
        }
        $all = ['Box' => $Box, 'Shope' => $Shope, 'day' => $request->day, 'shope' => $request->shope];
        $admin = User::where('id', auth()->user()->id)->first();
        alert()->success('يا هلا  ', $admin->name);

        return view('FA.FAStored')->with('all', $all);
    }
}
