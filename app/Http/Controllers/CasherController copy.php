<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Branch;
use App\Models\Categorie;
use App\Models\expense;
use App\Models\Item;
use App\Models\Shope;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder;
use Prgayman\Zatca\Facades\Zatca;
use Carbon\Carbon;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Exports\UsersExport;
use App\Imports\BillImport;
use App\Imports\BillDetailImport;
use App\Mail\AFMail;
use App\Models\Bill_Extra_Topping;
use App\Models\extra_topping;
use App\Models\ItemCompound;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SequenceBill;
use App\Models\Inventory;
use App\Models\StaffScheduling;
use App\Models\Store;
use DB;
use PDF;
use Illuminate\Database\Eloquent\Factories\Sequence;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\CheckBox as JobsCheckBox;
use Illuminate\Support\Arr;
use Mpdf\Mpdf;
use App\Models\billTrans;

use App\Models\attend;
use App\Models\vacation;
use App\Models\typeVacation;
use App\Models\notification;
use App\Models\otherExpense;

class CasherController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    protected function CasherBoard()
    {
        $staff = Staff::where('user_id', auth()->user()->id)->first();
    if (auth()->user()->type_id ==  3) {
          return  $this->CasherBoardTransfer();
    }else if (auth()->user()->type_id ==  4){
        return  $this->CasherBoardTailors();
    }else {

        $items = Item::where(['Shope_id' => $staff->Branch->shope_id, 'Status' => 1])->get();
        $Categories = Categorie::where('Shope_id', $staff->Branch->shope_id)->whereHas('Item', function ($q) {
            $q->where('Status', 1);
        })->get();
        $Sequence =  SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null, 'Status' => 1])
            /*->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))*/->orderBy('created_at', 'DESC')->first();
        $todayBills = Bill::where(['branch_id' => $staff->branch_id, 'sequence_id' => $Sequence == null ? null : $Sequence->id])->whereNotIn('Status', [3, 5])->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])->get();
        $extraToppings  = extra_topping::where(['Shope_id' => $staff->Branch->shope_id, 'Status' => 1])->get();
        $UsedItems = [];
        $inventoryStaff = StaffScheduling::where(['branch_id' => $staff->branch_id, 'inventory_Officer_id' => $staff->id, 'id' => $Sequence == null ? null : $Sequence->schedule_id])
            ->select('Start_Date', 'End_Date')->first();
        $stores = Store::where('branch_id', $staff->branch_id)->get();
        $OpenDay = $Sequence == null ? false : true;
        $Custody = $Sequence == null ? 0 : $Sequence->Start_Custody;
        $staffInventory = $inventoryStaff == null ? false : true;
        $startDay = $Sequence == null ? null : $Sequence->Scheduling->Start_Date;
         $driver = Staff::where('Status',2)->whereHas('Branch', function ($q) use ($staff)  {
            $q->where(['id' => $staff->Branch->id, 'Status' => 1 ]);
        })->get();
        $endDay = $Sequence == null ? null : $Sequence->Scheduling->End_Date;
        $afterOneHoure = date('H:i', strtotime($endDay . ' + 1 hours'));
        $endDay  = date('H:i', strtotime($endDay . ''));
        $startDay =  date('H:i', strtotime($startDay . ''));
        $curr =  \Carbon\Carbon::now()->format('H:i');
        $Incoming = 0;
        $close = 0;

        $diffShift = $this->getTimeDiff($startDay, $endDay);
        $diffShiftAfterOnehour = $this->getTimeDiff($startDay, $afterOneHoure);
        $diffCurrent = $this->getTimeDiff($startDay, $curr);
        /*  if ($Sequence !=  null) {
            if ($diffCurrent >= $diffShift && $diffCurrent <= $diffShiftAfterOnehour) {
               // Alert::info('اقترب موعد اغلاق الصندوق');
               // $close = 1;
            } else if ($diffCurrent >= $diffShift && $diffCurrent > $diffShiftAfterOnehour) {
                Alert::warning('يتم اغلاق الصندوق من قبل النظام ');
                $close = 2;
            } else {
                $close = 0;
            }
        }*/
        //return $diffShift . ' ' . $diffShiftAfterOnehour . ' ' . $diffCurrent;
        if ($OpenDay) {
            $Bills  = Bill::where(['branch_id' => $staff->branch_id, 'sequence_id' => $Sequence->id,])->whereIn('Status', [1, 3, 4, 5, 6])->get();
            foreach ($Bills as $key => $bill) {
                $details = BillDetail::where(['bill_id' => $bill->id])->get();
                $Incoming += $bill->total;
                if ($details->count() < 0) {

                    foreach ($details as $key => $itemCom) {
                        $compound = ItemCompound::where(['item_id' => $itemCom->item_id, 'size' => $itemCom->size])->first();
                        if (array_key_exists($compound->id, $UsedItems)) {
                            $UsedItems[$compound->id] +=  ($compound->count * $itemCom->count);
                        } else {
                            $UsedItems[$compound->id] =  ($compound->count * $itemCom->count);
                        }
                    }
                }
            }
        }
        $all = [
            'staff' => $staff,
            'items' => $items, 'todayBill' => $todayBills, 'Categories' => $Categories,
            'Sequence' => $OpenDay, 'Custody' => $Custody, "endDay" => $Sequence,
            'stores' => $stores, 'staffInventory' => $staffInventory, 'UsedItems' => $UsedItems,
            'incoming' => $Incoming, 'extraToppings' => $extraToppings, 'close' => $close , 'driver'=> $driver
        ];
        $this->decrementStore();
        return view('juiceAndResturant.CasherBoard')->with('all', $all);
    }
    }

    function getTimeDiff($dtime, $atime)
    {
        $nextDay = $dtime > $atime ? 1 : 0;
        $dep = explode(':', $dtime);
        $arr = explode(':', $atime);
        $diff = abs(mktime($dep[0], $dep[1], 0, date('n'), date('j'), date('y')) - mktime($arr[0], $arr[1], 0, date('n'), date('j') + $nextDay, date('y')));
        $hours = floor($diff / (60 * 60));
        $mins = floor(($diff - ($hours * 60 * 60)) / (60));
        $secs = floor(($diff - (($hours * 60 * 60) + ($mins * 60))));
        if (strlen($hours) < 2) {
            $hours = "0" . $hours;
        }
        if (strlen($mins) < 2) {
            $mins = "0" . $mins;
        }
        if (strlen($secs) < 2) {
            $secs = "0" . $secs;
        }
        return $hours;
    }
    protected function decrementStore()
    {

        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $bills  = Bill::where(['branch_id' => $staff->branch_id])->whereIn('Status', [3, 4])->get();

        foreach ($bills as $key => $bill) {
            $details = BillDetail::where('Bill_id', $bill->id)->get();
            foreach ($details as $key => $item) {
                $compounds = ItemCompound::where(['item_id' => $item->item_id, 'size' => $item->size])->get();

                $extraToppings = Bill_Extra_Topping::where('Bill_details_id', $item->id)->get();

                if (count($extraToppings) != 0) {
                    foreach ($extraToppings as $key => $extra) {
                        if ($extra->store_id != null) {

                            $extraTopping = extra_topping::where('id', $extra->extra_topping_id)->first();
                            $storeName = Store::where('id', $extraTopping->store_id)->first();
                            $oldstore = Store::where('Name', 'Like',  $storeName->Name)->where(function ($q) {
                                $staff = Staff::where('user_id', auth()->user()->id)->first();
                                $q->where('branch_id', $staff->branch_id);
                            })
                                ->first();
                            if ($oldstore != null) {
                                $restValueExt = $oldstore->restValue - ($extraTopping->count * $item->count); // 260 
                                $total = $oldstore->count * $oldstore->value;  // 300
                                $subCount = $oldstore->count;

                                for ($i = $oldstore->count; $i >= 0; $i--) {
                                    $total = $i * $oldstore->value;  // 300

                                    if (($total - $oldstore->value) > $restValueExt && $subCount  >= 0) {
                                        $subCount--;
                                    }
                                    break;
                                }
                                if ($restValueExt <= 1) {
                                    $restValueExt = 0;
                                }
                                Store::where('Name', 'Like', '%' . $storeName->Name . '%')->where(function ($q) {
                                    $staff = Staff::where('user_id', auth()->user()->id)->first();
                                    $q->where('branch_id', $staff->branch_id);
                                })->update([
                                    'restValue' => $restValueExt,
                                    'count' => $subCount
                                ]);
                            }
                        }
                    }
                }
                if (count($compounds) != 0) {
                    foreach ($compounds as $key => $compound) {
                        $storeName = Store::where('id', $compound->store_id)->first();
                        $oldstore = Store::where('Name', 'Like', '%' . $storeName->Name . '%')->where(function ($q) {
                            $staff = Staff::where('user_id', auth()->user()->id)->first();
                            $q->where('branch_id', $staff->branch_id);
                        })
                            ->first();

                        if ($oldstore != null) {
                            $restValue = $oldstore->restValue - ($compound->count * $item->count); // 260 
                            $total = $oldstore->count * $oldstore->value;  // 300
                            $subCount = $oldstore->count;

                            for ($i = $oldstore->count; $i >= 0; $i--) {
                                $total = $i * $oldstore->value;  // 300

                                if (($total - $oldstore->value) > $restValue && $subCount  >= 0) {
                                    $subCount--;
                                }
                                break;
                            }
                            if ($restValue <= 1) {
                                $restValue = 0;
                            }
                            Store::where('Name', 'Like', '%' . $storeName->Name . '%')->where(function ($q) {
                                $staff = Staff::where('user_id', auth()->user()->id)->first();
                                $q->where('branch_id', $staff->branch_id);
                            })->update([
                                'restValue' => $restValue,
                                'count' => $subCount
                            ]);
                        }
                    }
                }
            }
            if ($bill->Status == 4) {
                Bill::where('id', $bill->id)->update([
                    'Status' => 1
                ]);
            } else {
                Bill::where('id', $bill->id)->update([
                    'Status' => 5
                ]);
            }
        }

        return 1;
    }
    protected function PendingBills(Request $request)
    {

        $messages = [

            'Phone.nullable' => 'يجب تحديد  رقم فاتورة',
            'Phone.integer' => 'رقم الهاتف يجب ان يتكون من ارقام فقط ',

        ];
        $validator = Validator::make($request->all(), [
            'Phone' => 'nullable|integer',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $phoneNo = $request->Phone;
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        if ($phoneNo == null) {
            $bills  = Bill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id, 'Status' => 5])->paginate(8);
        } else {
            $bills  = Bill::where(['CustomerPhone' => $phoneNo, 'branch_id' => $staff->branch_id, 'staff_id' => $staff->id, 'Status' => 5])->paginate(8);
        }
        $all = ['Bills' => $bills];
        return view('juiceAndResturant.PendingBills')->with('all', $all);
    }
    protected function ClosePendingBill(Request $request)
    {
        $messages = [

            'BillId.required' => 'يجب تحديد  رقم فاتورة',
            'payway.required' => 'يجب تحديد  طريقة الدفع',
            'payway.not_in' => 'يجب اختيار نوع الدفع ',

        ];
        $validator = Validator::make($request->all(), [
            'BillId' => 'required',
            'payway' => 'required|not_in:0',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }

        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $bill = Bill::where(['id' => $request->BillId, 'branch_id' => $staff->branch_id, 'staff_id' => $staff->id, 'Status' => 5])->first();
        $rest =  $bill->total - $bill->cash + $bill->online;
        $cash = $request->payway == 1 ? $bill->cash + $rest  : $bill->cash;
        $online = $request->payway == 2 ? $bill->online + $rest  : $bill->online;
        Bill::where(['id' => $request->BillId, 'branch_id' => $staff->branch_id, 'staff_id' => $staff->id, 'Status' => 5])->update([
            'cash' => $cash,
            'online' => $online,
            'Status' => 1
        ]);
        alert()->success('تم تصدير الفاتورة ', '');

        return  redirect()->route('PendingBills');
    }
    protected function openDay(Request $request)
    {
        $messages = [

            'Custody.required' => 'يجب تحديد قيمة العهدة ',
        ];
        $validator = Validator::make($request->all(), [
            'Custody' => 'required',
            'IdStore' => 'nullable',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $schedule = StaffScheduling::where(['branch_id' => $staff->branch_id])
            ->where([
                ['Start_Date', '<=', date('H:i')],
                //   ['End_Date', '>=', date('H:i')],
            ])
            ->first();
        if (!$schedule == null) {

            $Sequence = new  SequenceBill();
            $Sequence->staff_id = $staff->id;
            $Sequence->branch_id = $staff->branch_id;
            $Sequence->schedule_id =   $schedule->id;
            $Sequence->Start_Date = date('Y-m-d H:i:s');
            $Sequence->Start_Custody = $request->Custody; // Open Day 
            $Sequence->Status = 1; // Open Day 
            $Sequence->save();


            if ($request->Inventory == "1") {

                foreach ($request->IdStore as $key => $Id) {
                    $store =  Store::where(['id' => $request->IdStore[$key], 'branch_id' => $staff->branch_id])->first();
                    if ($store->count <> $request->newValue[$key]) {
                        $Count = $request->newValue[$key];
                        $newCount = $Count;
                        $newRestValue = $Count * $store->value;
                        if (($store->restValue - $newRestValue) < 0) {
                            $newRestValue = 0;
                        }
                        $inventori = new Inventory();
                        $inventori->sequence_id  = $Sequence->id;
                        $inventori->old_value  = $store->restValue;
                        $inventori->new_value   = $newRestValue;
                        $inventori->Status     = 1;
                        $inventori->save();

                        Store::where(['branch_id' => $staff->branch_id, 'id' => $request->IdStore[$key]])->update([
                            'count' => $newCount,
                            'restValue' =>   $newRestValue,
                        ]);
                    }
                }
            }
        } else {
            alert()->warning('لا يمكن فتح الصندوق  ', '');

            return  redirect()->route('CasherBoard');
        }


        return  redirect()->route('CasherBoard');
    }
    protected function EndDay(Request $request)
    {
        $messages = [

            'IdStore.*.required' => 'يجب أن يكون هناك id',
            'EndCustody.nullable' => 'حدد قيمة العهدة ',

        ];
        $validator = Validator::make($request->all(), [
            'IdStore.*' => 'required',
            'EndCustody' => 'nullable',
            'newValue' => 'nullable',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $Sequence =  SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null, 'Status' => 1])
            /*->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))*/->orderBy('created_at', 'DESC')->first();
        SequenceBill::where(['branch_id' => $staff->branch_id, 'End_Date' => null, 'staff_id' => $staff->id, 'End_Custody' => null, 'Status' => 1])
            ->orderBy('created_at', 'DESC')->update([
                'End_Date' => date('Y-m-d H:i:s'),
                'End_Custody' => $request->EndCustody,
                'Status' => $request->autoClose == 1 ?  3 : 2

            ]);
        $lastShift = StaffScheduling::where(['branch_id' => $staff->branch_id])->where('inventory_Officer_id', '!=', null)->orderBy('shift', 'desc')->first();
        $date = new DateTime('+1 day');
        $OneDay = $date->format('Y-m-d');
        $date = new DateTime('-1 day');
        $Day = $date->format('Y-m-d');
        if ($lastShift != null) {
            if ($lastShift->id == $Sequence->schedule_id) {
                $Box =   SequenceBill::where(['branch_id' => $staff->branch_id,])
                    ->whereBetween(
                        'Start_Date',
                        [
                            $Day, $OneDay
                        ]
                    )
                    // ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
                    ->orderBy('created_at', 'DESC')->get();

                $Incoming = [];
                foreach ($Box as $key => $seq) {
                    $bills = Bill::where(['sequence_id' => $seq->id])->where('Status', '<>', 2)->get();
                    foreach ($bills as $key => $bill) {
                        if (array_key_exists($seq->id, $Incoming)) {
                            $Incoming[$seq->id] +=  $bill->total;
                        } else {
                            $Incoming[$seq->id] =  $bill->total;
                        }
                    }
                }

                $mailData   = ['Box' => $Box, 'Incoming' => $Incoming];
                $owner  = Shope::where('id', $staff->Branch->shope_id)->first();

                $mailData   = ['Box' => $Box, 'Incoming' => $Incoming, 'owner' => $owner->Owner];
              //  $pdf = PDF::loadView('emails.afmail', $mailData)->setOptions(['defaultFont' => 'sans-serif']);

                //    \Mail::send('emails.afmail', $mailData, function ($message) use ($owner, $pdf) {
                //        $message->to($owner->Owner->email, $owner->Owner->name)
                //            ->subject("ملخص دوام يوم " . now())
                //            ->attachData($pdf->output(), 'ملخص دوام يوم ' . now() . ".pdf");
                //    });
            }
        }

        if ($request->Inventory == "1") {
            if ($request->IdStore  != null) {
                # code...

                foreach ($request->IdStore as $key => $Id) {
                    $store =  Store::where(['id' => $request->IdStore[$key], 'branch_id' => $staff->branch_id])->first();
                    if ($store->count <> $request->newValue[$key]) {
                        $Count = $request->newValue[$key];
                        $newCount = $Count;
                        $newRestValue = $Count * $store->value;
                        if (($store->restValue - $newRestValue) < 0) {
                            $newRestValue = 0;
                        }
                        $inventori = new Inventory();
                        $inventori->sequence_id  = $seq->id;
                        $inventori->old_value  = $store->restValue;
                        $inventori->new_value   = $newRestValue;
                        $inventori->Status     = 1;
                        $inventori->save();

                        Store::where(['branch_id' => $staff->branch_id, 'id' => $request->IdStore[$key]])->update([
                            'count' => $newCount,
                            'restValue' =>   $newRestValue,
                        ]);
                    }
                }
            }
        }
        SequenceBill::where(['branch_id' => $staff->branch_id, 'End_Date' => null,  'End_Custody' => null, 'Status' => 1])
            ->update([
                'Status' => 3
            ]);

        $seq = SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,])
            /*->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))*/->orderBy('created_at', 'DESC')->first();

        $bills = Bill::where(['sequence_id' => $seq->id])->where('Status', '<>', 2)->get();
        $total = 0;
        $cash = 0;
        $online = 0;
        $all = [];
        $i = 1;
        foreach ($bills as $key => $bill) {
            $total += $bill->total;
            $cash += $bill->cash;
            $online += $bill->online;
            $Details = BillDetail::where('Bill_id', $bill->id)->get();


            foreach ($Details as $key => $Detail) {
                $item = item::where(['id' => $Detail->item_id])->first();
                switch ($Detail->size) {
                    case '1':
                        $price = $item->Small_Price * $Detail->count;
                        $found =   isset($all[$item->Name . ' ' . $item->Small_Name]);
                        if ($found) {
                            $all[$item->Name . ' ' . $item->Small_Name]['price'] +=  $price;
                            $all[$item->Name . ' ' . $item->Small_Name]['count'] += $Detail->count;
                        } else {
                            $all[$item->Name . ' ' . $item->Small_Name] =  [
                                'price' => $price,
                                'id' => $i++,
                                'Name' => $item->Name . ' ' . $item->Small_Name,
                                'OrignelPrice' => $item->Small_Price, 'count' => $Detail->count
                            ];
                        }

                        break;
                    case '2':
                        $price = $item->Mid_Price * $Detail->count;

                        $found =   isset($all[$item->Name . ' ' . $item->Mid_Name]);
                        if ($found) {
                            $all[$item->Name . ' ' . $item->Mid_Name]['price'] += $price;
                            $all[$item->Name . ' ' . $item->Mid_Name]['count'] += $Detail->count;
                        } else {
                            $all[$item->Name . ' ' . $item->Mid_Name] = [
                                'price' => $price,
                                'id' => $i++,
                                'Name' => $item->Name . ' ' . $item->Mid_Name,
                                'OrignelPrice' => $item->Mid_Price, 'count' => $Detail->count
                            ];
                        }

                        break;
                    case '3':
                        $price = $item->Big_Price * $Detail->count;

                        $found =   isset($all[$item->Name . ' ' . $item->Big_Name]);
                        if ($found) {
                            $all[$item->Name . ' ' . $item->Big_Name]['price'] += $price;
                            $all[$item->Name . ' ' . $item->Big_Name]['count'] += $Detail->count;
                        } else {
                            $all[$item->Name . ' ' . $item->Big_Name] = [
                                'price' => $price,
                                'id' => $i++,
                                'Name' => $item->Name . ' ' . $item->Big_Name, 'OrignelPrice' => $item->Big_Price, 'count' => $Detail->count
                            ];
                        }

                        break;
                    default:
                        # code...
                        break;
                }
            }
        }
                  /* report for Owner */
    $seq = SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,])
    /*->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))*/->orderBy('created_at', 'DESC')->first();

    $bills = Bill::where(['sequence_id' => $seq->id])->where('Status', '<>', 2)->get();
    $total = 0;
    $cash = 0;
    $online = 0;
    $all = [];
    $i = 1;
    foreach ($bills as $key => $bill) {
        $total += $bill->total;
        $cash += $bill->cash;
        $online += $bill->online;
        $Details = BillDetail::where('Bill_id', $bill->id)->get();


        foreach ($Details as $key => $Detail) {
            $item = item::where(['id' => $Detail->item_id])->first();
            switch ($Detail->size) {
                case '1':
                    $price = $item->Small_Price * $Detail->count;
                    $found =   isset($all[$item->Name . ' ' . $item->Small_Name]);
                    if ($found) {
                        $all[$item->Name . ' ' . $item->Small_Name]['price'] +=  $price;
                        $all[$item->Name . ' ' . $item->Small_Name]['count'] += $Detail->count;
                    } else {
                        $all[$item->Name . ' ' . $item->Small_Name] =  [
                            'price' => $price,
                            'id' => $i++,
                            'Name' => $item->Name . ' ' . $item->Small_Name,
                            'OrignelPrice' => $item->Small_Price, 'count' => $Detail->count
                        ];
                    }

                    break;
                case '2':
                    $price = $item->Mid_Price * $Detail->count;

                    $found =   isset($all[$item->Name . ' ' . $item->Mid_Name]);
                    if ($found) {
                        $all[$item->Name . ' ' . $item->Mid_Name]['price'] += $price;
                        $all[$item->Name . ' ' . $item->Mid_Name]['count'] += $Detail->count;
                    } else {
                        $all[$item->Name . ' ' . $item->Mid_Name] = [
                            'price' => $price,
                            'id' => $i++,
                            'Name' => $item->Name . ' ' . $item->Mid_Name,
                            'OrignelPrice' => $item->Mid_Price, 'count' => $Detail->count
                        ];
                    }

                    break;
                case '3':
                    $price = $item->Big_Price * $Detail->count;

                    $found =   isset($all[$item->Name . ' ' . $item->Big_Name]);
                    if ($found) {
                        $all[$item->Name . ' ' . $item->Big_Name]['price'] += $price;
                        $all[$item->Name . ' ' . $item->Big_Name]['count'] += $Detail->count;
                    } else {
                        $all[$item->Name . ' ' . $item->Big_Name] = [
                            'price' => $price,
                            'id' => $i++,
                            'Name' => $item->Name . ' ' . $item->Big_Name, 'OrignelPrice' => $item->Big_Price, 'count' => $Detail->count
                        ];
                    }

                    break;
                default:
                    # code...
                    break;
            }
        }
    }
    $alls = ['seq' => $seq, 'total' => $total, 'cash' => $cash, 'online' => $online,  'items' => $all];

    $html = view('juiceAndResturant.SeqShowNet')->with('all', $alls)->render();

    $mpdf = new Mpdf([
        //'fontDir' => [base_path('resources/fonts')],
         'default_font' => 'arial'
     ]);
     $mpdf->WriteHTML($html);
     $mpdf->Output(public_path('Bills/Report'. $seq->id  .'.pdf'), 'F');

     $FA = 'https://fa-tech-bills.com/public/Bills/Report' .$seq->id  . '.pdf';
     $phone = '966'.   substr($staff->Branch->Shope->phone, 1); 
     $fimeName = 'فاتورة '; 
     $cap = 'السلام عليكم ' ; 
    $params=array(
        'token' => 'duicoaqqiim1rdvw',
        'to' =>  $phone ,
        'filename' => 'فاتورة ',
        'document' =>  $FA,
        'caption' => ' السلام عليكم \n الاستاذ   '  .  $staff->Branch->Shope->Owner->name .'  \n'.
        'المحل : ' .  $staff->Branch->Shope->Name . ' . \n'  .
        'الفرع : ' . $staff->Branch->address . ' . \n' .
          'تقرير عهدة  :  '  .$staff->User->name. 
        '\n لمزيد من التفاصيل عبر رابط الموقع  ' .'https://fa-tech-bills.com'.'\n' . ' شكرا لكم '  ,
       );
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.ultramsg.com/instance31210/messages/document",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => http_build_query($params),
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
          ),
        ));
       
   $response = curl_exec($curl);
   $err = curl_error($curl);
   
   curl_close($curl);

    /* end Report */
        $alls = ['seq' => $seq, 'total' => $total, 'cash' => $cash, 'online' => $online,  'items' => $all];

        return view('juiceAndResturant.SeqShow')->with('all', $alls);
    }
    protected function cancelBill(Request $request)
    {
        $messages = [

            'billNo.*.required' => 'يجب أن يكون هناك رقم فاتورة',

        ];
        $validator = Validator::make($request->all(), [
            'billNo.*' => 'required',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $details = BillDetail::where('bill_id', $request->billNo)->get();
        foreach ($details as $key => $items) {

            $compounds = ItemCompound::where(['item_id' => $items->item_id, 'size' => $items->size])->get();

            $extraToppings = Bill_Extra_Topping::where('Bill_details_id', $items->id)->get();

            if (count($extraToppings) != 0) {
                foreach ($extraToppings as $key => $extra) {
                    $extraTopping = extra_topping::where('id', $extra->extra_topping_id)->first();
                    $storeName = Store::where('id', $extraTopping->store_id)->first();
                    $oldstore = Store::where('Name', 'Like',  $storeName->Name)->where(function ($q) {
                        $staff = Staff::where('user_id', auth()->user()->id)->first();
                        $q->where('branch_id', $staff->branch_id);
                    })
                        ->first();
                    if ($oldstore != null) {
                        if ($oldstore->restValue > 0) {


                            $plus = $items->count * $extra->count;
                            $restValue = $oldstore->restValue +  $plus;
                            if ($restValue < $oldstore->value) {
                                $newCount = 1;
                            } else {
                                $newCount =  $restValue /  $oldstore->value;
                            }

                            Store::where('id', $oldstore->id)->where(function ($q) {
                                $staff = Staff::where('user_id', auth()->user()->id)->first();
                                $q->where('branch_id', $staff->branch_id);
                            })->update([
                                'restValue' => $restValue,
                                'count' => round($newCount)
                            ]);
                        }
                    }
                }
            }
            if (count($compounds) != 0) {
                foreach ($compounds as $key => $compound) {
                    $storeName = Store::where('id', $compound->store_id)->first();
                    $oldstore = Store::where('Name', 'Like', '%' . $storeName->Name . '%')->where(function ($q) {
                        $staff = Staff::where('user_id', auth()->user()->id)->first();
                        $q->where('branch_id', $staff->branch_id);
                    })
                        ->first();

                    if ($oldstore != null) {
                        if ($oldstore->restValue > 0) {
                            $plus = ($items->count * $compound->count);
                            $restValue = $oldstore->restValue +  $plus;
                            if ($restValue < $oldstore->value) {
                                $newCount = 1;
                            } else {
                                $newCount =  $restValue /  $oldstore->value;
                            }


                            Store::where('Name', 'Like', '%' . $storeName->Name . '%')->where(function ($q) {
                                $staff = Staff::where('user_id', auth()->user()->id)->first();
                                $q->where('branch_id', $staff->branch_id);
                            })->update([
                                'restValue' => $restValue,
                                'count' => round($newCount)
                            ]);
                        }
                    }
                }
            }            /*  if ($compound != null) {
                $plus = $items->count * $compound->count;

                $storeName = Store::where('id', $compound->store_id)->first();
                $oldstore = Store::where('Name', 'Like', '%' . $storeName->Name . '%')->where(function ($q) {
                    $staff = Staff::where('user_id', auth()->user()->id)->first();
                    $q->where('branch_id', $staff->branch_id);
                })
                    ->first();
                if ($oldstore != null) {
                    $restValue = $oldstore->restValue +  $plus;
                    if ($restValue < $oldstore->value) {
                        $newCount = 1;
                    } else {
                        $newCount =  $restValue /  $oldstore->value;
                    }

                    Store::where('id', $oldstore->id)->update([
                        'restValue' => $restValue,
                        'count' => round($newCount)
                    ]);
                }
            }*/
        }

        Bill::where('id', $request->billNo)->update([
            'Status' => 2
        ]);
        return  redirect()->route('CasherBoard');
    }
    protected function CreateBill(Request $request)
    {

        //return $request->all();

        // Messages for valid Input 
        $messages = [
            'count.required' => 'لا يوجد كمية  ',   // Required
            'price.required' => 'لا يوجد سعر  ',   // Required
            /// 'Ctype.required' => 'حدد نوع العميل  ',   // Required


        ];
        $validator = Validator::make($request->all(), [
            'count' => 'required ',
            'price' => 'required ',
            ///   'Ctype' => 'required'


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if ($request->cash == null  &&  $request->online == null && $request->Status && $request->payType == null) {
            Alert::error('خطأ ', 'حدد قيمة الدفع ');

            return back();
        }
        $total = 0;
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        for ($i = 0; $i < count($request->item); $i++) {
            if (substr($request->item[$i], 0, 1)  != "e") {
                $arrItems[] = $request->item[$i];
            }
        }
        for ($i = 0; $i < count($arrItems); $i++) {
            $finalPrice = $request->count[$i] *  $request->price[$i];
            $total += $finalPrice;
        }
        $driveSerive =  0 ; 
        if ( $request->customer == 4) {
            $driverPrice = Item::where(['Shope_id'=>$staff->Branch->shope_id , 'Name'=> "خدمة" ])->first();
            $driveSerive =  $driverPrice->Small_Price ;

            $total+= $driveSerive ;
        }

        $seq =    SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null, 'Status' => 1])
            /*->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))*/->orderBy('created_at', 'DESC')->first();
        // Start 
        $startDay = $seq == null ? null : $seq->Scheduling->Start_Date;
        $endDay = $seq == null ? null : $seq->Scheduling->End_Date;
        $afterOneHoure = date('H:i', strtotime($endDay . ' + 1 hours'));
        $endDay  = date('H:i', strtotime($endDay . ''));
        $startDay =  date('H:i', strtotime($startDay . ''));
        $curr =  \Carbon\Carbon::now()->format('H:i');

        $seqCount =  SequenceBill::where(['staff_id' => $staff->id,  'branch_id' => $staff->branch_id, 'End_Date' => null])->whereIn('Status', [1, 4])
            ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
            ->orderBy('created_at', 'DESC')->count();

     


        //end
       
        
        $totalWithTax = ($total * 0.15) + $total;
        $totalWithTax =  $total  ;
        if ($request->Status) {
            if ($request->payType != null) {

                $cash = $request->payType  == 1 ? $totalWithTax :  0;
                $online =  $request->payType  == 2 ? $totalWithTax :   0;
            } else {
                $cash = $request->online  == null ? $totalWithTax :   $request->cash;
                $online =  $request->cash  == null ? $totalWithTax :   $request->online;
            }
        } else {

            $cash = $request->cash;
            $online =  $request->onlline;
        }
        $Bill = new Bill();
        $Bill->staff_id = $staff->id;
        $Bill->sequence_id =  $seq->id;
        $Bill->branch_id = $staff->branch_id;
        $Bill->total = $totalWithTax;
        $Bill->cash =  $cash;
        $Bill->online =  $online;
        $Bill->CustomerName =  $request->name;
        $Bill->CustomerPhone =  $request->phone;
        $Bill->CustomerType =  $request->customer == null ? 1 :  $request->customer;
        $Bill->driver_id  =  $request->customer == 4 ? $request->driverID : null;
         $Bill->CT =  $request->ct; // $request->Ctype;
        
        $Bill->Status = !$request->Status  ? 3 :  4;
        $Bill->save();
        $lastExp = expense::whereRelation('Branch', 'shope_id', '=', $staff->Branch->shope_id)
            ->whereBetween('month', [$Bill->created_at->format('Y-m') . '-01', date('Y-m-d', strtotime('+1 Months', strtotime($Bill->created_at->format('Y-m'))))])
            ->where('branch_id', $staff->branch_id)->first();
        if ($lastExp == null) {
            $Expense =  new expense();
            $Expense->branch_id = $staff->branch_id;
            $Expense->month = $Bill->created_at->format('Y-m') . '-01';
            $Expense->Status = 1;
            $Expense->save();
        }
        $oldItem = [];
        $ii = 0;
        for ($i = 0; $i < count($request->item); $i++) {

            if (substr($request->item[$i], 0, 1)  == "e") {
                $bill_extra_topping = new Bill_Extra_Topping();
                $bill_extra_topping->Bill_details_id = $oldItem[array_key_last($oldItem)];
                $bill_extra_topping->extra_topping_id = substr($request->item[$i], 1);
                $bill_extra_topping->save();
            } else {

                $BillDetails = new BillDetail();
                $BillDetails->Bill_id = $Bill->id;
                $BillDetails->item_id = $request->item[$i];
                $BillDetails->size =  $request->size[$ii];
                $BillDetails->count =  $request->count[$ii];
                $BillDetails->price =  $request->price[$ii];
                $BillDetails->Status = 1;
                $BillDetails->created_at = $Bill->created_at->format('Y-m-d H:i:s');
                $BillDetails->save();
                $oldItem[]  = $BillDetails->id;
                $ii++;
            }
        }
          if ( $request->customer == 4) {
            $driverPrice = Item::where(['Shope_id'=>$staff->Branch->shope_id , 'Name'=> "خدمة" ])->first();
            $BillDetails = new BillDetail();
            $BillDetails->Bill_id = $Bill->id;
            $BillDetails->item_id = $driverPrice->id;
            $BillDetails->size =  1;
            $BillDetails->count =  1;
            $BillDetails->price =  $driverPrice->Small_Price;
            $BillDetails->Status = 1;
            $BillDetails->created_at = $Bill->created_at->format('Y-m-d H:i:s');
            $BillDetails->save();
        }
         $extraToppings =  Bill_Extra_Topping::with('ExtraTopping')->whereHas('billDetails', function ($q) use ($Bill)  {
            $q->where(['Bill_id'=> "{$Bill->id}" , ]);
    })->get();
        $nameShope =  $staff->Branch->Shope->Name;
        $VTEnum =  $staff->Branch->Shope->VTENumber;
        $vte = $total  *0.15;
        $vte = round($vte, 2);

        // Send Whats Bill 
         $seq =  SequenceBill::where(['staff_id' => $staff->id,  'branch_id' => $staff->branch_id, 'End_Date' => null])->whereIn('Status', [1, 4])
         // ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
         ->orderBy('created_at', 'DESC')->first();
        $seqCount = Bill::where('sequence_id',  $seq->id)->count();
        $Details = BillDetail::where('Bill_id', $Bill->id)->get();

        if ($request->phone != "") {
                $qr = 0 ;
                $all = ['Bill' => $Bill, 'BillDetails' => $Details,
                 'BillNo' => $seqCount, 'extraToppings' => $extraToppings, 'qr' => $qr ,];
        
                 if ($staff->Branch->Shope->VTENumber == "0") {
                    $html = view('juiceAndResturant.billPDFNet')->with('all', $all)->render();
        
                    
                 }else {
                    $nameShope =  $staff->Branch->Shope->Name;
                    $VTEnum =  $staff->Branch->Shope->VTENumber;
                
            
                    $qr = Zatca::sellerName($nameShope)
                        ->vatRegistrationNumber($VTEnum)
                        ->timestamp($Bill->created_at)
                        ->totalWithVat($total)
                        ->vatTotal($total * 0.15)
                        ->toBase64();
                         $all = ['Bill' => $Bill, 'BillDetails' => $Details,
                 'BillNo' => $seqCount, 'extraToppings' => $extraToppings, 'qr' => $qr ,];
                 
                // return $all['BillDetails']->Item->Name ;
                    $html = view('juiceAndResturant.billPDFNetTAX')->with('all', $all)->render();
                 }
        
                $mpdf = new Mpdf([
                   //'fontDir' => [base_path('resources/fonts')],
                    'default_font' => 'arial'
                ]);
                $mpdf->WriteHTML($html);
                $mpdf->Output(public_path('Bills/'. $Bill->id .'.pdf'), 'F');
        
                $FA = 'https://fa-tech-bills.com/public/Bills/' . $Bill->id . '.pdf';
                $phone = '966'. substr($request->phone, 1);
                $fimeName = 'فاتورة '; 
                                   $cap = 'السلام عليكم ' ; 
                $num = $seqCount ; 
                 if ($request->phone != "") {
             $params=array(
                 'token' => 'duicoaqqiim1rdvw',
                 'to' =>  $phone ,
                 'filename' => 'فاتورة ',
                 'document' =>  $FA,
                 'caption' => ' السلام عليكم \nتم اصدار فاتورة من :  '  .  $staff->Branch->Shope->Name .' . \n'. 'رقم الفاتورة  :  ' 
                 . $num .'\n' . ' شكرا لكم '  ,
                );
                 $curl = curl_init();
                 curl_setopt_array($curl, array(
                   CURLOPT_URL => "https://api.ultramsg.com/instance31210/messages/document",
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => "",
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 30,
                   CURLOPT_SSL_VERIFYHOST => 0,
                   CURLOPT_SSL_VERIFYPEER => 0,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => "POST",
                   CURLOPT_POSTFIELDS => http_build_query($params),
                   CURLOPT_HTTPHEADER => array(
                     "content-type: application/x-www-form-urlencoded"
                   ),
                 ));
                
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);

            }
            }
        
        
        // End Send Whats Bill

       if ($VTEnum == "0") {
           $qr = [] ;
            $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'extraToppings' => $extraToppings,  'BillNo' => $seqCount, 'qr' => $qr];
             if (auth()->user()->type_id ==  4) {
               
        return view('juiceAndResturant.billPDFCasher')->with('all', $all);

            }
        
        return view('juiceAndResturant.billPDFWithoutTax')->with('all', $all);
        }else{
        $qr = Zatca::sellerName($nameShope)
            ->vatRegistrationNumber($VTEnum)
            ->timestamp($Bill->created_at)
            ->totalWithVat($total)
            ->vatTotal($vte)
            ->toBase64();
             $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'extraToppings' => $extraToppings,  'qr' => $qr];
          if ($staff->Branch->Shope->id == 104 || $staff->Branch->Shope->id == 2 ) {
            return view('juiceAndResturant.billPDFBig')->with('all', $all);

        }
        return view('juiceAndResturant.billPDF')->with('all', $all);
        }
    }
    protected function ShowBill(Request $request)
    {

        $messages = [

            'id.*.required' => 'يجب أن يكون هناك id',

        ];
        $validator = Validator::make($request->all(), [
            'id.*' => 'required',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $Bill = Bill::where(['id' => $request->id, 'staff_id' => $staff->id])->first();
        
        if ($Bill == []) {
            return back();
        }
                $total = $Bill->total;

        $Details = BillDetail::where('Bill_id', $Bill->id)->get();
   $extraToppings =  Bill_Extra_Topping::with('ExtraTopping')->whereHas('billDetails', function ($q) use ($Bill)  {
            $q->where(['Bill_id'=> "{$Bill->id}" , ]);
    })->get();
        //return $extraToppings[0];
        $nameShope =  $staff->Branch->Shope->Name;
        $VTEnum =  $staff->Branch->Shope->VTENumber;
        $vte = $Bill->total  * 0.15;
        $vte = round($vte, 2);

      if ($VTEnum == "0") {
           $qr = [] ;
            $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'extraToppings' => $extraToppings,  'qr' => $qr];
        
        return view('juiceAndResturant.billPDFWithoutTax')->with('all', $all);
        }else{
        $qr = Zatca::sellerName($nameShope)
            ->vatRegistrationNumber($VTEnum)
            ->timestamp($Bill->created_at)
            ->totalWithVat($total)
            ->vatTotal($vte)
            ->toBase64();
             $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'extraToppings' => $extraToppings,  'qr' => $qr];
          if ($staff->Branch->Shope->id == 104 ) {
            return view('juiceAndResturant.billPDFBig')->with('all', $all);

        }
        return view('juiceAndResturant.billPDF')->with('all', $all);
        }
       
    }
     protected function Customers(Request $request)
    {
        $messages = [

            'Phone.nullable' => 'يجب تحديد  رقم فاتورة',
            'Phone.numeric' => 'رقم الهاتف يجب ان يتكون من ارقام فقط ',

        ];
        $validator = Validator::make($request->all(), [
            'Phone' => 'nullable|numeric',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $phoneNo = $request->Phone;
        $bills = [];
        $restInfo =[];
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        if ($phoneNo == null) {
            $bills  =  Bill::where(['branch_id' => $staff->branch_id,])->where('CustomerPhone', '!=', null)
                ->groupBy('CustomerPhone')->select('CustomerPhone', DB::raw('count(*) as total'))->get();
            foreach ($bills as $key => $bill) {
                if ($bill->CustomerName == null) {
                    $info = Bill::where('CustomerPhone', $bill->CustomerPhone)->orderBy('created_at', 'desc')->first();
                } else {
                    $info = Bill::where('CustomerName', $bill->CustomerName)->orderBy('created_at', 'desc')->first();
                }
                $restInfo['Info'][] = ['phone' => $bill->CustomerPhone, 'name' => $info->CustomerName, 'count' => $bill->total, 'created_at' => $info->created_at];
            }
        } else {
            $bills  =  Bill::where(['branch_id' => $staff->branch_id, 'CustomerPhone' => $phoneNo,])->where('CustomerPhone', '!=', null)
                ->groupBy('CustomerPhone')->select('CustomerPhone', DB::raw('count(*) as total'))->get();


            foreach ($bills as $key => $bill) {
                if ($bill->CustomerName == null) {
                    $info = Bill::where('CustomerPhone', $bill->CustomerPhone)->orderBy('created_at', 'desc')->first();
                } else {
                    $info = Bill::where('CustomerName', $bill->CustomerName)->orderBy('created_at', 'desc')->first();
                }
                $restInfo['Info'][] = ['phone' => $bill->CustomerPhone, 'name' => $info->CustomerName, 'count' => $bill->total, 'created_at' => $info->created_at];
            }
        }
        $all = [$restInfo];
         // return $all;

        return view('juiceAndResturant.Customers')->with('all', $all);
    }
       protected function CasherBoardTransfer()
     {
        
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $city = Item::where(['Shope_id' => $staff->Branch->shope_id, 'categories_id'=>17, 'Status' => 1])->get();
        $citys = [];
        foreach($city as $item ){
            $citys[] = $item->Name;
        }
        $owner = Shope::where('owner_id', auth()->user()->id)->first();

        $TransItem = billTrans::whereHas('bill', function ($q) use ($owner)  {
            $q->whereHas('Branch', function ($p) use ($owner)  {
                $p->where('shope_id',$owner->id);
            });
        })->get();
        $TransItems = [];
        foreach ($TransItem as $key => $item) {
            $TransItems[] = $item->item;
        }
        $TransItems = array_values(array_unique($TransItems));
        $all= [ 'city'=>$citys , 'TransItems'=>$TransItems];
        return view('juiceAndResturant.CasherBoardTransfer')->with('all', $all);
    }
   
     protected function DashboardHrForEmp()
    {
        $now = Carbon::now();

        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $attend = attend::where('staff_id', $staff->id)->whereDate('created_at', $now)->get();
        $vacation = vacation::where('staff_id', $staff->id)->get();

        $all = ['staff'=>$staff , 'attend'=>$attend , 'vacation'=>$vacation];

        return view('juiceAndResturant.DashboardHrForEmp')->with('all', $all);
    }
      protected function StaffAttend(Request $request){

          // Messages for valid Input 
          $messages = [
            'id.required' => 'يوجد خطاء الرجاء اعادة المحاولة  ',   // Required
           

        ];
        $validator = Validator::make($request->all(), [
            'id' => 'required',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $now = Carbon::now();

        // Format the current date and time
        $formatted = $now->format('Y-m-d H:i:s');

        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $attend = attend::where(['staff_id'=> $staff->id , 'End_date'=>null ])->whereDate('created_at', $now)->first();
        if ($attend == null ) {
       
        $attend = new attend();
        $attend->staff_id = $staff->id; 
        $attend->Start_Date = $formatted; 
        $attend->End_Date = null; 
        $attend->save();

        Alert::success('تم التحضير ',$formatted);
    }else {
        Alert::info('لقد تم التحضير مسبقا ',);

    }
        return back();
    }
      protected function Staffleaving(Request $request){

        // Messages for valid Input 
        $messages = [
            'id.required' => 'يوجد خطاء الرجاء اعادة المحاولة  ',   // Required
        

        ];
        $validator = Validator::make($request->all(), [
            'id' => 'required',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $now = Carbon::now();

        // Format the current date and time
        $formatted = $now->format('Y-m-d H:i:s');

        $staff = Staff::where('user_id', auth()->user()->id)->first();
      
        
        attend::where(['staff_id'=> $staff->id , 'End_date'=>null ])->whereDate('created_at', $now)->update([
            'End_Date'=> $formatted
        ]);

        Alert::success('تم الانصراف ',$formatted);
    
        return back();   
     }
       protected function VacationRequestEmp()
    {
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $typeVacation = typeVacation::where('Status', 1)->get();

        $all = ['staff'=>$staff , 'typeVacation'=>$typeVacation];

        
        return view('juiceAndResturant.VacationRequestEmp')->with('all', $all);
    }
     protected function RequestVaction(Request $request){

          // Messages for valid Input 
          $messages = [
            'from.required' => 'لم تحدد وقت بد الاجازة   ',   // Required
            'to.required' => 'لم يحدد وقت نهاية الاجازة   ',   // Required
            'type.required' => 'لم تحدد نوع الاجازة   ',   // Required
            'notes.required' => 'لم تذكر سبب الاجازة  ',   // Required
        
        
        ];
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
            'type' => 'required',
            'notes' => 'required',
        
        
        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());
        
            return back();
        }  
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $vacation = new vacation();
        $vacation->staff_id = $staff->id ; 
        $vacation->type_id = $request->type;
        $vacation->Start_Date = $request->from;
        $vacation->End_Date = $request->to;
        $vacation->notes = $request->notes;
        $vacation->save();
        $owner = staff::where('user_id',$staff->Branch->Shope->owner_id )->first();
        $notification = new notification();
        $notification->staff_id = $staff->id;
        $notification->to_staff_id = $owner->id;
        $notification->vacation_id = $vacation->id;
        $notification->type_id	 = 15;
        $notification->notes = $request->notes;
        $notification->Status = 1;
        $notification->save();

        Alert::success('تم رفع طلبك بنجاح  ');
    
        return  redirect()->route('DashboardHrForEmp');



  }
   protected function AttendanceFollowupForEmp(Request $request)
    {
          // Messages for valid Input 
          $messages = [
            'id.nullable' => 'يوجد خطاء الرجاء اعادة المحاولة  ',   // Required
           

        ];
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|date',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $now = Carbon::now();

        // Format the current date and time
        $formatted = $now->format('Y-m-d ');

        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $daySelect = $request->date == null ? $formatted : $request->date;
        $attend = attend::where(['staff_id'=> $staff->id  ])
        ->whereBetween('Start_Date', [$daySelect . ' 00:00:00', $daySelect . ' 23:59:50'])->get();
       
        return view('juiceAndResturant.AttendanceFollowupForEmp')->with('attend',$attend);
    }
     protected function OtherExpenses()
    {
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $now = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $id = 0 ;
        $expense = expense::where(['branch_id'=>$staff->branch_id ,'month'=>$now ])->first();
        $id = $expense->id ; 
        if ($expense == null) {
            $expense =  new expense();
            $expense->branch_id = $staff->branch_id;
            $expense->month =  $now;
            $expense->Status = 1;
            $expense->save();
            $id = $expense->id ; 

        }   
        $otherExpense = otherExpense::where(['staff_id'=>$staff->id , 'expense_id'=>$expense->id])->get();
        $all =['otherExpense'=>$otherExpense]; 
        return view('juiceAndResturant.OtherExpenses')->with('all', $all);
    }
    protected function OtherExpensesNew(Request $request){

  // Messages for valid Input 
        $messages = [
            'title.required' => 'يجب كتابة العنوان',   // Required
            'price.required' => 'يجب ادخال القيمة  ',   // Required


        ];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $now = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $id = 0 ;
        $expense = expense::where(['branch_id'=>$staff->branch_id ,'month'=>$now ])->first();
        $id = $expense->id ; 
        if ($expense == null) {
            $Expense =  new expense();
            $Expense->branch_id = $staff->branch_id;
            $Expense->month =  $now;
            $Expense->Status = 1;
            $Expense->save();
            $id = $Expense->id ; 

        }   
        $otherExpense = new otherExpense();
        $otherExpense->staff_id =  $staff->id ;
        $otherExpense->expense_id = $id ;
        $otherExpense->title = $request->title ;
        $otherExpense->price = $request->price ;
        $otherExpense->save();
         expense::where(['branch_id'=>$staff->branch_id ,'month'=>$now ])->update([
             'OtherBill' =>   $expense-> OtherBill+ $request->price  ,
         ]);
        
        Alert::success('تم تسجيل المصروف  ');

        return  redirect()->route('OtherExpenses'); 
    
    }
}
