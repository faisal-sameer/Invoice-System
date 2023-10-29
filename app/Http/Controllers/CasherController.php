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
use App\Models\itemsTailor;
use App\Models\BillTailor;
use App\Models\User;
use App\Models\attend;
use App\Models\vacation;
use App\Models\typeVacation;
use App\Models\notification;
use App\Models\otherExpense;

use App\Models\Discount;
use App\Models\DiscountItem;
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
        if (auth()->user()->type_id ==  2) {
          return  $this->CasherBoardTailors();
        }else {
        $staff = Staff::where('user_id', auth()->user()->id)->first();


        $items = Item::where(['Shope_id' => $staff->Branch->shope_id, 'Status' => 1])->get();
        $Categories = Categorie::where('Shope_id', $staff->Branch->shope_id)->whereHas('Item', function ($q) {
            $q->where('Status', 1);
        })->orderBy('created_at', 'DESC')->get();
        $Sequence =  SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null, 'Status' => 1])
            /*->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))*/->orderBy('created_at', 'DESC')->first();
        $todayBills = Bill::where(['branch_id' => $staff->branch_id, 'sequence_id' => $Sequence == null ? null : $Sequence->id])->whereNotIn('Status', [3, 5])->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])->get();
        $extraToppings  = extra_topping::where(['Shope_id' => $staff->Branch->shope_id, 'Status' => 1])->get();
        $UsedItems = [];
        $inventoryStaff = StaffScheduling::where(['branch_id' => $staff->branch_id, 'inventory_Officer_id' => $staff->id, 'id' => $Sequence == null ? null : $Sequence->schedule_id])
            ->select('Start_Date', 'End_Date')->first();
        $stores = Store::where('branch_id', $staff->branch_id)->get();
        $Discounts = Discount::where('Status',1)->whereHas('Branch', function ($q) use ($staff)  {
            $q->where(['shope_id' => $staff->Branch->shope_id, 'Status' => 1 ]);
        })->get();
        $DiscountItems = DiscountItem::whereHas('Discount', function ($q)  use ($staff) {
            $q->where('Status', 1);
            $q->whereHas('Branch', function ($q) use ($staff)  {
                $q->where(['shope_id' => $staff->Branch->shope_id, 'Status' => 1 ]);
            });
        })->get();
        $driver = Staff::where('Status',2)->whereHas('Branch', function ($q) use ($staff)  {
            $q->where(['id' => $staff->Branch->id, 'Status' => 1 ]);
        })->get();
        $OpenDay = $Sequence == null ? false : true;
        $Custody = $Sequence == null ? 0 : $Sequence->Start_Custody;
        $staffInventory = $inventoryStaff == null ? false : true;
        $startDay = $Sequence == null ? null : $Sequence->Scheduling->Start_Date;
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
            'incoming' => $Incoming, 'extraToppings' => $extraToppings, 'close' => $close,
            'Discounts'=>$Discounts, 'DiscountItems'=>$DiscountItems ,'driver'=> $driver
        ];
        $this->decrementStore();
        return view('juiceAndResturant.CasherBoard')->with('all', $all);
    }
}
    
    protected function CasherBoardTailors()
    {
        $staff = Staff::where('user_id', auth()->user()->id)->first();


        $items = Item::where(['Shope_id' => $staff->Branch->shope_id, 'Status' => 1])->get();
        $tailoritems = itemsTailor::where(['Status' => 1])->get();
        $Categories = Categorie::where('Shope_id', $staff->Branch->shope_id)->whereHas('Item', function ($q) {
            $q->where('Status', 1);
        })->orderBy('created_at', 'DESC')->get();
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
            'incoming' => $Incoming, 'extraToppings' => $extraToppings, 'close' => $close,
            'tailoritems' => $tailoritems
        ];
        $this->decrementStore();
        return view('juiceAndResturant.CasherBoardTailors')->with('all', $all);
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
    public function search(Request $request)
    {
        $search = $request->input('search');
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $details = BillTailor::whereHas('bill', function ($q) use ($search) {
            $q->where('id', 'LIKE', '%' . $search . '%');
            $q->orWhere('CustomerPhone', 'LIKE', '%' . $search . '%');
        })->get();
        $bill = Bill::where('branch_id',$staff->branch_id )->where('id', 'LIKE', '%' . $search . '%')->orWhere('CustomerPhone', 'LIKE', '%' . $search . '%')->first();
        $all = ['details' => $details, 'bill' => $bill];
        return response()->json($all);
    }
    protected function PendingBills(Request $request)
    {

        $messages = [

            'Phone.nullable' => 'يجب تحديد  رقم فاتورة',
            'Phone.digits' => 'رقم الهاتف يجب ان يتكون من ارقام فقط ',

        ];
        $validator = Validator::make($request->all(), [
            'Phone' => 'nullable|digits:10',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $phoneNo = $request->Phone;
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $staffs = Staff::where('status',3)->whereRelation('Branch', 'shope_id', '=', $staff->Branch->Shope->id)->get();

        if ($phoneNo == null) {
            $bills  = Bill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id])
            ->whereBetween('Status', [5, 6])->paginate(20);
        } else {
            $bills  = Bill::where(['CustomerPhone' => $phoneNo, 'branch_id' => $staff->branch_id, 'staff_id' => $staff->id,
           ])->whereBetween('Status', [5, 6])->paginate(20);
        }
        $all = ['Bills' => $bills , 'staffs'=>$staffs];
        return view('juiceAndResturant.PendingBills')->with('all', $all);
    }
    protected function ClosePendingBill(Request $request)
    {
        $messages = [

            'ID_Bill.required' => 'يجب تحديد  رقم فاتورة',
            'payway.required' => 'يجب تحديد  طريقة الدفع',
            'payway.not_in' => 'يجب اختيار نوع الدفع ',

        ];
        $validator = Validator::make($request->all(), [
            'ID_Bill' => 'required',
            'payway' => 'required|not_in:0',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }

        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $bill = Bill::where(['id' => $request->ID_Bill, 'branch_id' => $staff->branch_id, 'staff_id' => $staff->id, 'Status' => 5])->first();
        $rest =  $bill->total - $bill->cash + $bill->online;
        $cash = $request->payway == 1 ? $bill->cash + $rest  : $bill->cash;
        $online = $request->payway == 2 ? $bill->online + $rest  : $bill->online;
        Bill::where(['id' => $request->ID_Bill, 'branch_id' => $staff->branch_id, 'staff_id' => $staff->id, 'Status' => 5])->update([
            'cash' => $cash,
            'online' => $online,
            'Status' => 1
        ]);
        alert()->success('تم تصدير الفاتورة ', '');

        return  redirect()->route('PendingBills');
    }

    protected function sittailor(Request $request){
        $messages = [

            'IDBill.required' => 'يجب تحديد  رقم فاتورة',
            'tailorId.required' => 'يجب تحديد  الخياط',
            'tailorId.not_in' => 'حدث خطاء  ',

        ];
        $validator = Validator::make($request->all(), [
            'IDBill' => 'required',
            'tailorId' => 'required|not_in:0',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        Bill::where(['id' => $request->IDBill, 'branch_id' => $staff->branch_id, 'staff_id' => $staff->id, 'Status' => 5])->update([
            'tailor_id' => $request->tailorId,
        
        ]);
        alert()->success('تم تحديد الخياط ', '');

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
        $today = Carbon::today();

        $Sequence =  SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null, 'Status' => 1])
            /*->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))*/->orderBy('created_at', 'DESC')->first();
        SequenceBill::where(['branch_id' => $staff->branch_id, 'End_Date' => null, 'staff_id' => $staff->id, 'End_Custody' => null, 'Status' => 1])
            ->orderBy('created_at', 'DESC')->update([
                'End_Date' => date('Y-m-d H:i:s'),
                'End_Custody' => $request->EndCustody,
                'Status' => $request->autoClose == 1 ?  3 : 2

            ]);

            $TodayExp = otherExpense::where(['staff_id'=>$staff->id ])->whereDate('created_at', $today)->orderBy('id','desc')->get();
           
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
        $alls = ['seq' => $seq, 'total' => $total, 'cash' => $cash, 'online' => $online,  'items' => $all , 'TodayExp'=>$TodayExp];

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
       
     //  return $request->all();
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
        for ($i = 0; $i < count($request->item); $i++) {
            if (substr($request->item[$i], 0, 1)  != "e") {
                $arrItems[] = $request->item[$i];
            }
        }
        for ($i = 0; $i < count($arrItems); $i++) {
            $finalPrice = $request->count[$i] *  $request->price[$i];
            $total += $finalPrice;
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();

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

        /*  $diffShift = $this->getTimeDiff($startDay, $endDay);
        $diffShiftAfterOnehour = $this->getTimeDiff($startDay, $afterOneHoure);
        $diffCurrent = $this->getTimeDiff($startDay, $curr);
        if ($seq !=  null) {
            if ($diffCurrent >= $diffShift && $diffCurrent <= $diffShiftAfterOnehour) {
                Alert::info('اقترب موعد اغلاق الصندوق');
                $close = 1;
            } else if ($diffCurrent >= $diffShift && $diffCurrent > $diffShiftAfterOnehour) {
                SequenceBill::where(['staff_id' => $staff->id,  'branch_id' => $staff->branch_id, 'End_Date' => null, 'Status' => 1])
                    ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
                    ->orderBy('created_at', 'DESC')->update([
                        'Status' => 3
                    ]);
                Alert::warning('يتم اغلاق الصندوق من قبل النظام ');
                $close = 2;
            } else {
                $close = 0;
            }
        } else {
        }*/


        //end

        $driveSerive =  0 ; 
        if ( $request->customer == 4) {
            $driverPrice = Item::where(['Shope_id'=>$staff->Branch->shope_id , 'Name'=> "خدمة" ])->first();
            $driveSerive =  $driverPrice->Small_Price ;
        }
        
        $totalWithTax = ($total * 0.15) + $total;
        $totalWithTax =  $total +$driveSerive ;
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
        $Bill->driver_id  =  $request->customer == 4 ? $request->driverID : null  ;
        $Bill->Status = !$request->Status  ? 3 :  4;
        $Bill->days = $request->days  ;
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
        $detailTailor = collect($request->details)->sortBy(0)->values()->all();
        usort($detailTailor, function ($a, $b) {
            return $a[0] - $b[0];
        });
        for ($i = 0; $i < count($request->item); $i++) {
                $HasDiscount = null ; 
            if (substr($request->item[$i], 0, 1)  == "e") {
                $bill_extra_topping = new Bill_Extra_Topping();
                $bill_extra_topping->Bill_details_id = $oldItem[array_key_last($oldItem)];
                $bill_extra_topping->extra_topping_id = substr($request->item[$i], 1);
                $bill_extra_topping->save();
            } else {
                if ($request->discountID != null  && $request->discountID  != 0 ) {
                    $Discount = Discount::where('id',$request->discountID )->first();
                    $DiscountItems = DiscountItem::where('Discount_id', $Discount->id)->get();
                    $item = Item::where('id',$request->item[$i] )->first();
                    switch ( $Discount->Discount_type  ) {
                        case 1:
                           foreach ($DiscountItems as $key => $DiscountItem) {
                               if ($DiscountItem->categorie_id == $item->categories_id) {
                                  $HasDiscount= $request->discountID ;
                               }
                           }
                            break;
                            case 2:
                                foreach ($DiscountItems as $key => $DiscountItem) {
                                    if ($DiscountItem->item_id == $item->id) {
                                       $HasDiscount= $request->discountID ;
                                    }
                                }
                                break;
                        default:
                        $HasDiscount = null ; 
                            break;
                    }
                }
                
                $BillDetails = new BillDetail();
                $BillDetails->Bill_id = $Bill->id;
                $BillDetails->item_id = $request->item[$i];
                $BillDetails->size =  $request->size[$ii];
                $BillDetails->count =  $request->count[$ii];
                $BillDetails->price =  $request->price[$ii];
                $BillDetails->Status = 1;
                $BillDetails->Discount_id = $HasDiscount ;
                $BillDetails->created_at = $Bill->created_at->format('Y-m-d H:i:s');
                $BillDetails->save();
                $oldItem[]  = $BillDetails->id;
               
                 $arrdetailTailor = explode(',', $detailTailor[$ii]);

                $notes = preg_replace('/\s+/', '  ', $request->notes[$ii]);

                $tailorDetails = new BillTailor();
                $tailorDetails->Bill_id =  $Bill->id; // رقم الفاتورة
                $tailorDetails->count_no =  $request->count[$ii]; // عدد الثياب 
                $tailorDetails->item_id =  $request->item[$i]; // نوع الثوب 
                $tailorDetails->length = $arrdetailTailor[3]; // الطول 
                $tailorDetails->shoulder = $arrdetailTailor[4]; // طول الكتف 
                $tailorDetails->sleeves = $arrdetailTailor[5]; // طول الكم  
                $tailorDetails->neck = $arrdetailTailor[6]; // وسع الرقبة 
                $tailorDetails->chest = $arrdetailTailor[7]; // وسع الصدر 
                $tailorDetails->expand_hand = $arrdetailTailor[8]; // وسع اليد 
                $tailorDetails->under_poket = $arrdetailTailor[9]; // جيب اسفل 
                $tailorDetails->zipper = $arrdetailTailor[10] == "true" ? 1 : 0; // سحاب 
                $tailorDetails->double_line = $arrdetailTailor[11] == "true" ? 1 : 0; // خياطة دبل 
                $tailorDetails->under = $arrdetailTailor[12] == "true" ? 1 : 0; // اسفل 
                $tailorDetails->cuff = $arrdetailTailor[13] == "true" ? 1 : 0; // كفة
                $tailorDetails->under_poket_check = $arrdetailTailor[14] == "true" ? 1 : 0; // تحت الجيب 
                
                $tailorDetails->under_details = $arrdetailTailor[26]; // اسفل المقاس
                $tailorDetails->cuff_details = $arrdetailTailor[27]; // كفة المقاس
                $tailorDetails->under_poket_details = $arrdetailTailor[28]; // تحت الجيب المقاس 

                $tailorDetails->price =  $request->price[$ii]; // سعر الثوب 
                $tailorDetails->name = $arrdetailTailor[1]; // اسم صاحب  الثوب 
                $tailorDetails->model_name = "empty"; // اسم الموديل 
                $tailorDetails->up_poket_id = $arrdetailTailor[15]; //  شكل الجيب 
                $tailorDetails->up_poket_details = $arrdetailTailor[19]; //  شكل الجيب 
                $tailorDetails->neck_id = $arrdetailTailor[16]; // شكل الرقبة 
                $tailorDetails->neck_details = $arrdetailTailor[20]; // شكل الرقبة تفاصيل
                $tailorDetails->hand_id = $arrdetailTailor[17]; // شكل اليد 
                $tailorDetails->hand_details = $arrdetailTailor[21]; // شكل اليد تفاصيل
                $tailorDetails->midstyle_id = $arrdetailTailor[18]; // شكل السحاب 
                $tailorDetails->midstyle_details = $arrdetailTailor[22]; // شكل السحاب تفاصيل
                $tailorDetails->downhand_up_details  = $arrdetailTailor[23]; // شكل السحاب تفاصيل Up
                $tailorDetails->downhand_right_details  = $arrdetailTailor[24]; // شكل السحاب تفاصيل Rigth
                $tailorDetails->downhand_down_details  = $arrdetailTailor[25]; // شكل السحاب تفاصيل Down
                $tailorDetails->notes =$notes;// ملاحظات 
                $tailorDetails->save();



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
        $extraToppings =  Bill_Extra_Topping::with('ExtraTopping')->whereHas('billDetails', function ($q) use ($Bill) {
            $q->where(['Bill_id' => "{$Bill->id}",]);
        })->get();
        $nameShope =  $staff->Branch->Shope->Name;
        $VTEnum =  $staff->Branch->Shope->VTENumber;
        $vte = $total - ($total  *0.15);
        $vte = round($vte, 2);

        // Send Whats Bill 
        $seq =  SequenceBill::where(['staff_id' => $staff->id,  'branch_id' => $staff->branch_id, 'End_Date' => null])->whereIn('Status', [1, 4])
           
            ->orderBy('created_at', 'DESC')->first();
        $seqCount = Bill::where('sequence_id',  $seq->id)->count();
        $Details = BillDetail::where('Bill_id', $Bill->id)->get();

    
        if ($VTEnum == "0") {
            $qr = [];
            $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'extraToppings' => $extraToppings,  'qr' => $qr];

            return view('juiceAndResturant.billPDFWithoutTax')->with('all', $all);
        } else {
            $qr = Zatca::sellerName($nameShope)
                ->vatRegistrationNumber($VTEnum)
                ->timestamp($Bill->created_at)
                ->totalWithVat($total)
                ->vatTotal($vte)
                ->toBase64();
            $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'extraToppings' => $extraToppings,  'qr' => $qr];
            return view('juiceAndResturant.billPDFBig')->with('all', $all);
        }
    }
    protected function CreateBillTailor(Request $request)
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

                $arrdetailTailor = explode(',', $detailTailor[$ii]);

                $notes = preg_replace('/\s+/', ' , ', $request->notes[$ii]);

                $tailorDetails = new BillTailor();
                $tailorDetails->Bill_id =  $Bill->id; // رقم الفاتورة
                $tailorDetails->count_no =  $request->count[$ii]; // عدد الثياب 
                $tailorDetails->item_id =  $request->item[$i]; // نوع الثوب 
                $tailorDetails->length = $arrdetailTailor[3]; // الطول 
                $tailorDetails->shoulder = $arrdetailTailor[4]; // طول الكتف 
                $tailorDetails->sleeves = $arrdetailTailor[5]; // طول الكم  
                $tailorDetails->neck = $arrdetailTailor[6]; // وسع الرقبة 
                $tailorDetails->chest = $arrdetailTailor[7]; // وسع الصدر 
                $tailorDetails->expand_hand = $arrdetailTailor[8]; // وسع اليد 
                $tailorDetails->under_poket = $arrdetailTailor[9]; // جيب اسفل 
                $tailorDetails->zipper = $arrdetailTailor[10] == "true" ? 1 : 0; // سحاب 
                $tailorDetails->double_line = $arrdetailTailor[11] == "true" ? 1 : 0; // خياطة دبل 
                $tailorDetails->under = $arrdetailTailor[12] == "true" ? 1 : 0; // اسفل 
                $tailorDetails->cuff = $arrdetailTailor[13] == "true" ? 1 : 0; // كفة
                $tailorDetails->under_poket_check = $arrdetailTailor[14] == "true" ? 1 : 0; // تحت الجيب 
                
                $tailorDetails->under_details = $arrdetailTailor[26]; // اسفل المقاس
                $tailorDetails->cuff_details = $arrdetailTailor[27]; // كفة المقاس
                $tailorDetails->under_poket_details = $arrdetailTailor[28]; // تحت الجيب المقاس 

                $tailorDetails->price =  $request->price[$ii]; // سعر الثوب 
                $tailorDetails->name = $arrdetailTailor[1]; // اسم صاحب  الثوب 
                $tailorDetails->model_name = ""; // اسم الموديل 
                $tailorDetails->up_poket_id = $arrdetailTailor[15]; //  شكل الجيب 
                $tailorDetails->up_poket_details = $arrdetailTailor[19]; //  شكل الجيب 
                $tailorDetails->neck_id = $arrdetailTailor[16]; // شكل الرقبة 
                $tailorDetails->neck_details = $arrdetailTailor[20]; // شكل الرقبة تفاصيل
                $tailorDetails->hand_id = $arrdetailTailor[17]; // شكل اليد 
                $tailorDetails->hand_details = $arrdetailTailor[21]; // شكل اليد تفاصيل
                $tailorDetails->midstyle_id = $arrdetailTailor[18]; // شكل السحاب 
                $tailorDetails->midstyle_details = $arrdetailTailor[22]; // شكل السحاب تفاصيل
                $tailorDetails->downhand_up_details  = $arrdetailTailor[23]; // شكل السحاب تفاصيل Up
                $tailorDetails->downhand_right_details  = $arrdetailTailor[24]; // شكل السحاب تفاصيل Rigth
                $tailorDetails->downhand_down_details  = $arrdetailTailor[25]; // شكل السحاب تفاصيل Down
                $tailorDetails->notes =$notes;// ملاحظات 
                $tailorDetails->save();
                $oldItem[]  = $tailorDetails->id;
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
    protected function SendToCustomer(Request $request){

        //return $request->all();

        $Bill = Bill::where('id',$request->BillId)->first();
        $name = $Bill->CustomerName ;
        
        $phone = $Bill->CustomerPhone ;
        $meg = 'السلام عليكم  ' .  $name . ' ثوبك جاهز للاستلام ، رقم الفاتورة  ' . $request->BillId . '  خياط الديثار . ' ; 
        $params=array(
            'token' => 'duicoaqqiim1rdvw',
            'to' => $phone,
            'body' =>  $meg
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.ultramsg.com/instance31210/messages/chat",
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
            $Bill = Bill::where('id',$request->BillId)->update([
                'Status' => 6
            ]);
            alert()->success('تم ارسال رسالة الاستلام  ', '');

            return  redirect()->route('PendingBills');      }
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

        $extraToppings =  Bill_Extra_Topping::with('ExtraTopping')->whereHas('billDetails', function ($q) use ($Bill) {
            $q->where(['Bill_id' => "{$Bill->id}",]);
        })->get();
        //return $extraToppings[0];
        $nameShope =  $staff->Branch->Shope->Name;
        $VTEnum =  $staff->Branch->Shope->VTENumber;
        $vte = $Bill->total - ($Bill->total  / 1.15);
        $vte = round($vte, 2);

        if ($VTEnum == "0") {
            $qr = [];
            $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'extraToppings' => $extraToppings,  'qr' => $qr];

            return view('juiceAndResturant.billPDFWithoutTax')->with('all', $all);
        } else {
            $qr = Zatca::sellerName($nameShope)
                ->vatRegistrationNumber($VTEnum)
                ->timestamp($Bill->created_at)
                ->totalWithVat($total)
                ->vatTotal($vte)
                ->toBase64();
            $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'extraToppings' => $extraToppings,  'qr' => $qr];

            return view('juiceAndResturant.billPDF')->with('all', $all);
        }
    }
    protected function TailorBill(Request $request)
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
        $tailorDetails = BillTailor::where("Bill_id", $Bill->id)->get();
        $nameShope =  $staff->Branch->Shope->Name;
        $VTEnum =  $staff->Branch->Shope->VTENumber;
        $vte = $Bill->total - ($Bill->total  / 1.15);
        $vte = round($vte, 2);

        $qr = Zatca::sellerName($nameShope)
            ->vatRegistrationNumber($VTEnum)
            ->timestamp($Bill->created_at)
            ->totalWithVat($Bill->total)
            ->vatTotal($vte)
            ->toBase64();
        $all = ['tailorDetails' => $tailorDetails, 'Bill' => $Bill, 'qr' => $qr];

        return view('juiceAndResturant.billPDFTailor')->with('all', $all);
    }
    protected function billPDFBig()
    {
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $Bill = Bill::where(['id' => 9602,])->first();
        if ($Bill == []) {
            return back();
        }
        $Details = BillDetail::where('Bill_id', $Bill->id)->get();

        foreach ($Details as $key => $Detail) {
            $extraToppings[] = Bill_Extra_Topping::where('Bill_details_id', $Detail->id)->get();
        }
        $nameShope =  $staff->Branch->Shope->Name;
        $VTEnum =  $staff->Branch->Shope->VTENumber;
        $vte = $Bill->total - ($Bill->total  / 1.15);
        $vte = round($vte, 2);

        $qr = Zatca::sellerName($nameShope)
            ->vatRegistrationNumber($VTEnum)
            ->timestamp($Bill->created_at)
            ->totalWithVat($Bill->total)
            ->vatTotal($vte)
            ->toBase64();
        $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'BillNo' => -1, 'extraToppings' => $extraToppings[0],  'qr' => $qr];

        return view('juiceAndResturant.billPDFBig')->with('all', $all);
    }
    protected function billPDFTrans(Request $request)
    {
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $Bill = Bill::where(['id' => 9602,])->first();
        if ($Bill == []) {
            return back();
        }
        $Details = BillDetail::where('Bill_id', $Bill->id)->get();



        $nameShope =  $staff->Branch->Shope->Name;
        $VTEnum =  $staff->Branch->Shope->VTENumber;
        $vte = $Bill->total - ($Bill->total  / 1.15);
        $vte = round($vte, 2);

        $qr = Zatca::sellerName($nameShope)
            ->vatRegistrationNumber($VTEnum)
            ->timestamp($Bill->created_at)
            ->totalWithVat($Bill->total)
            ->vatTotal($vte)
            ->toBase64();
        $all = ['Bill' => $Bill, 'BillDetails' => $Details,  'qr' => $qr];

        return view('juiceAndResturant.billPDFTrans')->with('all', $all);
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
         //
        //      return $all[0];

        return view('juiceAndResturant.Customers')->with('all', $all);
    }
    protected function DashboardHrForEmp()
    {
        $now = Carbon::now();

        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $attend = attend::where('staff_id', $staff->id)->whereDate('created_at', $now)->get();
        $vacation = vacation::where('staff_id', $staff->id)->get();

        $all = ['staff' => $staff, 'attend' => $attend, 'vacation' => $vacation];

        return view('juiceAndResturant.DashboardHrForEmp')->with('all', $all);
    }
    protected function StaffAttend(Request $request)
    {

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
        $attend = attend::where(['staff_id' => $staff->id, 'End_date' => null])->whereDate('created_at', $now)->first();
        if ($attend == null) {

            $attend = new attend();
            $attend->staff_id = $staff->id;
            $attend->Start_Date = $formatted;
            $attend->End_Date = null;
            $attend->save();

            Alert::success('تم التحضير ', $formatted);
        } else {
            Alert::info('لقد تم التحضير مسبقا ',);
        }
        return back();
    }

    protected function Staffleaving(Request $request)
    {

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


        attend::where(['staff_id' => $staff->id, 'End_date' => null])->whereDate('created_at', $now)->update([
            'End_Date' => $formatted
        ]);

        Alert::success('تم الانصراف ', $formatted);

        return back();
    }
    protected function VacationRequestEmp()
    {
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $typeVacation = typeVacation::where('Status', 1)->get();

        $all = ['staff' => $staff, 'typeVacation' => $typeVacation];


        return view('juiceAndResturant.VacationRequestEmp')->with('all', $all);
    }
    protected function RequestVaction(Request $request)
    {

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
        $vacation->staff_id = $staff->id;
        $vacation->type_id = $request->type;
        $vacation->Start_Date = $request->from;
        $vacation->End_Date = $request->to;
        $vacation->notes = $request->notes;
        $vacation->save();
        if (auth()->user()->permission_id == 4) {
            $owner = staff::where('user_id', $staff->Branch->Shope->owner_id)->first();
        } else {
            $findManger =  Staff::where(['branch_id'=> $staff->branch_id ])->whereHas('User',  function ($q) {
                $q->where('permission_id', 4);
            })->first();
            if ($findManger == []) {
                $owner = Staff::whereHas('Branch',function ($q) use($staff) {
                    $q->where('shope_id',  $staff->Branch->shope_id  );
                })->whereHas('User',  function ($q) {
                    $q->where('permission_id', 2);
                })->first();
    
            }else {
                $owner = Staff::where(['branch_id'=> $staff->branch_id ])->whereHas('User',  function ($q) {
                    $q->where('permission_id', 4);
                })->first();
    
            }
        
        }
        
        $notification = new notification();
        $notification->staff_id = $staff->id;
        $notification->to_staff_id = $owner->id;
        $notification->vacation_id = $vacation->id;
        $notification->type_id     = 15;
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
        $attend = attend::where(['staff_id' => $staff->id])
            ->whereBetween('Start_Date', [$daySelect . ' 00:00:00', $daySelect . ' 23:59:50'])->get();

        return view('juiceAndResturant.AttendanceFollowupForEmp')->with('attend', $attend);
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
    /*
    protected function billExport(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'fromB.required' => 'لم تحدد تاريخ البد  ',   // Required
            'fromB.date_format' => 'صغية التاريخ غير صحيحة   ',   // Required
            'toB.date_format' => 'صيغة التاريخ  غير صحيحة ',   // Required


        ];
        $validator = Validator::make($request->all(), [
            'fromB' => 'required | date_format:m-d-Y',
            'toB' => 'nullable| date_format:m-d-Y',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }

        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $from = $request->fromB == null ? null : Carbon::createFromFormat('Y-m-d', $request->fromB);
        $to = $request->toB == null ? null :  Carbon::createFromFormat('Y-m-d', $request->toB);
        if ($to == null) {
            $bills = Bill::where('staff_id', $staff->id)->whereDate('created_at', $from)->get();
            if ($bills->count() == 0) {
                Alert::warning('لا توجد بيانات بالتاريخ المدخل ');

                return back();
            }
        } elseif ($to != null && $from != null) {
            $bills = Bill::where('staff_id', $staff->id)->whereBetween('created_at', [$from, $to])->get();
            if ($bills->count() == 0) {
                Alert::warning('لا توجد بيانات بالتاريخ المدخل ');

                return back();
            }
        } else {
            return back();
        }



        return   Excel::download(new UsersExport($bills),  $request->fromB  . '-' . $request->toB . ' تاريخ ' . '.xlsx');  // Done
    }
    protected function billDetailsExport(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'fromD.required' => 'لم تحدد تاريخ البد  ',   // Required
            'fromD.date_format' => 'صغية التاريخ غير صحيحة   ',   // Required
            'toD.date_format' => 'صيغة التاريخ  غير صحيحة ',   // Required


        ];
        $validator = Validator::make($request->all(), [
            'fromD' => 'required |date_format:m-d-Y',
            'toD' => 'nullable| date_format:m-d-Y',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $from = $request->fromD == null ? null : Carbon::createFromFormat('Y-m-d', $request->fromD);
        $to = $request->toD == null ? null :  Carbon::createFromFormat('Y-m-d', $request->toD);
        if ($to == null) {
            $bills = Bill::where('staff_id', $staff->id)->whereDate('created_at', $from)->get();
            if ($bills->count() != 0) {
                foreach ($bills as $key => $bill) {
                    $details[] = BillDetail::where('Bill_id', $bill->id)->whereDate('created_at', $from)->get();
                }
            } else {
                Alert::warning('لا توجد بيانات بالتاريخ المدخل ');

                return back();
            }
        } elseif ($to != null && $from != null) {
            $bills = Bill::where('staff_id', $staff->id)->whereBetween('created_at', [$from, $to])->get();
            if ($bills->count() != 0) {
                foreach ($bills as $key => $bill) {
                    $details[] = BillDetail::where('Bill_id', $bill->id)->whereDate('created_at', $from)->get();
                }
            } else {
                Alert::warning('لا توجد بيانات بالتاريخ المدخل ');

                return back();
            }
        } else {
            return back();
        }



        return    Excel::download(new UsersExport($details),  $request->fromD  . '-' . $request->toD . ' تاريخ التفصيل ' . '.xlsx'); // Done
    }
    protected function billImport(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'BillFile.required' => 'ملف الفواتير غير موجود ',   // Required
            'DetailsFile.required' => 'ملف الفواتير التفصيل غير موجود ',   // Required


        ];
        $validator = Validator::make($request->all(), [
            'BillFile' => 'required ',
            'DetailsFile' => 'required ',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $fileBills = $request->file('BillFile')->store('temp');
        $filepath = storage_path('app') . '/' . $fileBills;
        $fileDetails = $request->file('DetailsFile')->store('temp');
        $filepath2 = storage_path('app') . '/' . $fileDetails;
        $staff = Staff::where('user_id', auth()->user()->id)->first();


        $billFile =   Excel::toCollection(new billImport, $filepath);
        $billsDetailsFile =   Excel::toCollection(new billImport, $filepath2);
        foreach ($billFile[0] as $key => $bill) {
            if ($bill[2] != $staff->branch_id) {
                Alert::warning('خطأ في بيانات الملف ', 'يرجى التأكد من بيانات الملف و عدم التعديل عليها ، او التواصل مع الدعم الفني ');
                return back();
            }
            $created_at =  Carbon::parse($bill[9])->format('Y-m-d H:i:s');

            $oldBill = Bill::where(['staff_id' => $staff->id, 'isUpload' => 1,  'created_at' =>   $created_at])->first();

            if ($oldBill != null) {
                Alert::warning('خطأ  ', 'يوجد فواتير قد سبق رفعها داخل الملف ');
                return back();
            }
        }
        foreach ($billsDetailsFile[0] as $key => $details) {

            $created_at =  Carbon::parse($details[8])->format('Y-m-d H:i:s');

            $billDetailsCheck = BillDetail::where(['created_at' =>   $created_at])->first();
            $billCheck = Bill::where(['created_at' => $created_at, 'branch_id' => $staff->branch_id, 'isUpload' => 1])->first();
            //  return [$billCheck, $billDetailsCheck];
            if ($billCheck != null) {
                if ($billCheck->branch_id != $staff->branch_id) {
                    //      return  $billCheck->branch_id;
                    Alert::warning('خطأ في بيانات الملف التفصيل ', 'يرجى التأكد من بيانات الملف و عدم التعديل عليها ، او التواصل مع الدعم الفني ');
                    return back();
                }
                if ($billCheck->isUpload == 1) {
                    Alert::warning('خطأ في الملف التفصيل ', 'يوجد فواتير قد سبق رفعها داخل الملف ');
                    return back();
                }
            }
        }
        Excel::import(new BillImport, $filepath);
        foreach ($billsDetailsFile[0] as $key => $details) {

            $created_at =  Carbon::parse($details[8])->format('Y-m-d H:i:s');


            $billCheck = Bill::where(['created_at' => $created_at, 'branch_id' => $staff->branch_id, 'isUpload' => 1])->first();
            return $billCheck;
            $allDetails[] = [
                1, $billCheck->id, $details[2], $details[3], $details[4], $details[5], $details[7], $details[8], $details[9]
            ];
            $BillDetails = new BillDetail();
            $BillDetails->Bill_id = $billCheck->id;
            $BillDetails->item_id = $details[2];
            $BillDetails->size =  $details[3];
            $BillDetails->count =  $details[4];
            $BillDetails->price =  $details[5];
            $BillDetails->isUpload = 1;
            $BillDetails->Status = 1;
            $BillDetails->created_at = $details[8];
            $BillDetails->save();
        }
        // Excel::import(new BillDetailImport, $allDetails);
        Alert::success('تم التحميل بنجاح ', 'تم رفع جميع الفواتير ');

        return back();
    }

    protected function ExportAndImport()
    {
        return view('juiceAndResturant.ExportAndImport');
    }*/
}
