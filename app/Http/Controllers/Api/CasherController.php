<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Bill_Extra_Topping;
use App\Models\BillDetail;
use App\Models\Branch;
use App\Models\Categorie;
use App\Models\expense;
use App\Models\extra_topping;
use App\Models\Item;
use App\Models\ItemCompound;
use App\Models\SequenceBill;
use App\Models\Shope;
use App\Models\Staff;
use App\Models\StaffScheduling;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Prgayman\Zatca\Facades\Zatca;
use Illuminate\Support\Facades\Http;
use Session;
use DateTime;
use Mpdf\Mpdf;
use App\Models\otherExpense;


class CasherController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    protected function testApi()
    {

        $response = null;

        if (system("ping -n 2 fa-tech-bills.com >nul && echo 1 || echo 0")) {
            $token = session('token');
            $busket = [['24', '5', 'small', 'small', '24', 'ssss', 'null']];

            $response = Http::withHeaders([
                'Authorization' => ' bearer ' .   $token,
                'Content-Type' => ' application/json',
                'Accept' => ' application/json',
            ])->post('http://192.168.0.103:8000/api/testbasket', [
                'basket' => $busket,
                'price' => 150.0,
            ]);
            $posts = json_decode($response->getBody());
            /*  return response()->json([
                'success' => true,
                'token' => $token,
                'all' =>      $posts,
                'Status' =>  $response->status(),
            ], 200);*/

            $total = 150.0  / 1.15;

            $totalWithTax = ($total * 0.15) + $total;
            $staff =   Staff::where('user_id', 11)->first();
            $Bill = new Bill();
            $Bill->staff_id =  $staff->id;
            $Bill->branch_id =   $staff->branch_id;
            $Bill->total = $total;
            $Bill->cash = 1; // $request->pay;
            $Bill->online = 1; // $request->pay;
            // $Bill->CustomerName =  $request->name;
            // $Bill->CustomerPhone =  $request->phone;
            $Bill->CustomerType = 1;  // $request->Ctype;
            $Bill->Status = 1;
            $Bill->save();
            $lastExp = expense::whereRelation('Branch', 'shope_id', '=', $staff->Branch->shope_id)
                ->whereBetween('month', [$Bill->created_at->format('Y-m') . '-01', date('Y-m-d', strtotime('+1 Months', strtotime($Bill->created_at->format('Y-m'))))])
                ->where('branch_id',  $staff->branch_id)->first();
            if ($lastExp == null) {
                $Expense =  new expense();
                $Expense->branch_id = $staff->branch_id;
                $Expense->month = $Bill->created_at->format('Y-m') . '-01';
                $Expense->Status = 1;
                $Expense->save();
            }
            for ($i = 0; $i < count($busket); $i++) {
                switch ($busket[$i][2]) {
                    case 'small':
                        $size = 1;
                        break;
                    case 'mid':
                        $size = 2;
                        break;
                    case 'big':
                        $size = 3;
                        break;

                    default:
                        $size = 1;
                        break;
                }
                $BillDetails = new BillDetail();
                $BillDetails->Bill_id = $Bill->id;
                $BillDetails->item_id = $busket[$i][0];
                $BillDetails->size =  $size;
                $BillDetails->count =  $busket[$i][1];
                $BillDetails->price =  $busket[$i][4];
                $BillDetails->Status = 1;
                $BillDetails->created_at = $Bill->created_at->format('Y-m-d H:i:s');
                $BillDetails->save();
            }
            $Details = BillDetail::where('Bill_id', $BillDetails->Bill_id)->get();
            $qr = Zatca::sellerName('AF Subfix')
                ->vatRegistrationNumber("697878545487874")
                ->timestamp($Bill->created_at)
                ->totalWithVat(($total * 0.15) + $total)
                ->vatTotal($total * 0.15)
                ->toBase64();
            return "Conncet ";
        } else {

            $busket = [['24', '5', 'small', 'small', '24', 'ssss', 'null']];


            $total = 120.0  / 1.15;



            $totalWithTax = ($total * 0.15) + $total;
            $staff = Staff::where('user_id', 11)->first();
            $Bill = new Bill();
            $Bill->staff_id =  $staff->id;
            $Bill->branch_id =   $staff->branch_id;
            $Bill->total = $total;
            $Bill->cash = 1; // $request->pay;
            $Bill->online = 1; // $request->pay;
            // $Bill->CustomerName =  $request->name;
            // $Bill->CustomerPhone =  $request->phone;
            $Bill->CustomerType = 1;  // $request->Ctype;
            $Bill->Status = 7;
            $Bill->save();
            $lastExp = expense::whereRelation('Branch', 'shope_id', '=', $staff->Branch->shope_id)
                ->whereBetween('month', [$Bill->created_at->format('Y-m') . '-01', date('Y-m-d', strtotime('+1 Months', strtotime($Bill->created_at->format('Y-m'))))])
                ->where('branch_id',  $staff->branch_id)->first();
            if ($lastExp == null) {
                $Expense =  new expense();
                $Expense->branch_id = $staff->branch_id;
                $Expense->month = $Bill->created_at->format('Y-m') . '-01';
                $Expense->Status = 1;
                $Expense->save();
            }
            for ($i = 0; $i < count($busket); $i++) {
                switch ($busket[$i][2]) {
                    case 'small':
                        $size = 1;
                        break;
                    case 'mid':
                        $size = 2;
                        break;
                    case 'big':
                        $size = 3;
                        break;

                    default:
                        $size = 1;
                        break;
                }
                $BillDetails = new BillDetail();
                $BillDetails->Bill_id = $Bill->id;
                $BillDetails->item_id = $busket[$i][0];
                $BillDetails->size =  $size;
                $BillDetails->count =  $busket[$i][1];
                $BillDetails->price =  $busket[$i][4];
                $BillDetails->Status = 7;
                $BillDetails->created_at = $Bill->created_at->format('Y-m-d H:i:s');
                $BillDetails->save();
            }
            $Details = BillDetail::where('Bill_id', $BillDetails->Bill_id)->get();
            $qr = Zatca::sellerName('AF Subfix')
                ->vatRegistrationNumber("697878545487874")
                ->timestamp($Bill->created_at)
                ->totalWithVat(($total * 0.15) + $total)
                ->vatTotal($total * 0.15)
                ->toBase64();

            return "Not Conncet ";
        }


        /* 
        $data =
            Http::withHeaders([
                'Authorization' => ' bearer ' .   $token,
                'Content-Type' => ' application/json',
                'Accept' => ' application/json',
            ])->get('http://192.168.0.103:8000/api/items');

        $posts = json_decode($data->getBody());
        return response()->json([
            'success' => true,
            'all' =>     $posts,
            'Status' =>  $data->status(),
        ], 200);
        return $posts->all->items;
        //   return view('test')->with('posts', $posts->response->items);
        //  return ($posts->response->items);
        // return Session::all();

        $response = Http::post('http://192.168.0.103:8000/api/login', [
            'id' => '11',
            'password' => '123456789',
        ]);
        session(['token' => $response['access_token']]);
        $token = session('token');

        return response()->json([
            'success' => true,
            'all' =>  $token,
            'Status' => $response->status(),
        ], 200);*/
    }

    protected function CheckSchedule()
    {
        $staff = Staff::where('user_id', auth('api')->user()->id)->first();
        $Schedules = StaffScheduling::where(['branch_id' => $staff->branch_id])->get();
        return response()->json([
            'success' => true,
            'schedules' => $Schedules
        ], 200);
    }
    protected function CheckBoxs(Request $request)
    {
        $staff = Staff::where('user_id', auth('api')->user()->id)->first();
        $Boxs = $request->Boxs;
        $Bills = $request->Bills;
        $Details = $request->Details;
        $BillExtraTopping = $request->BillExtraTopping;



        foreach ($Boxs  as $keyBox => $Box) {
            if ($Box['connection_id'] == null) {
                switch ($Box['Status']) {
                    case 4:
                        $status = 1;
                        break;
                    case 5:
                        $status = 2;
                        break;
                    case 6:
                        $status = 3;
                        break;
                    default:
                        $status = 1;
                        break;
                }
                $SequenceBill = new SequenceBill();
                $SequenceBill->connection_id =   $Box['id'];
                $SequenceBill->staff_id =   $Box['staff_id'];
                $SequenceBill->branch_id =   $Box['branch_id'];
                $SequenceBill->schedule_id = $Box['schedule_id'];
                $SequenceBill->Start_Date = $Box['Start_Date'];
                $SequenceBill->End_Date = $Box['End_Date'];
                $SequenceBill->Start_Custody =  $Box['Start_Custody'];
                $SequenceBill->End_Custody = $Box['End_Custody'];
                $SequenceBill->Status = $status;
                $SequenceBill->created_at = date("Y-m-d H:i:s", strtotime($Box['created_at']));
                $SequenceBill->save();
            } else {
                switch ($Box['Status']) {
                    case 4:
                        /* $SequenceBill = new SequenceBill();
                        $SequenceBill->connection_id =   $Box['id'];
                        $SequenceBill->staff_id =   $Box['staff_id'];
                        $SequenceBill->branch_id =   $Box['branch_id'];
                        $SequenceBill->schedule_id = $Box['schedule_id'];
                        $SequenceBill->Start_Date = $Box['Start_Date'];
                        $SequenceBill->End_Date = $Box['End_Date'];
                        $SequenceBill->Start_Custody =  $Box['Start_Custody'];
                        $SequenceBill->End_Custody = $Box['End_Custody'];
                        $SequenceBill->created_at = date("Y-m-d H:i:s", strtotime($Box['created_at']));
                        $SequenceBill->Status = 1;
                        $SequenceBill->save();*/
                        $SequenceBill =  SequenceBill::where('id', $Box['connection_id'])->first();

                        break;
                    case 5:
                        SequenceBill::where('id', $Box['connection_id'])->update([
                            'End_Date' => $Box['End_Date'],
                            'End_Custody' =>  $Box['End_Custody'],
                            'Status' => 2,
                        ]);
                        $SequenceBill =  SequenceBill::where('id', $Box['connection_id'])->first();
                        break;
                    case 6:
                        SequenceBill::where('id', $Box['connection_id'])->update([
                            'Status' => 3,
                        ]);
                        $SequenceBill =  SequenceBill::where('id', $Box['connection_id'])->first();
                        break;
                    default:
                        //  $status = 1;
                        break;
                }
            }

            foreach ($Bills as $keyBill => $Bill) {
               $foundBill = Bill::where(['connection_id' => $Bill['id'], 'branch_id' => $staff->branch_id])->count();
                if ($foundBill == 0) {

                    if ($Bill['sequence_id']  ==  $Box['id']) {

                        switch ($Bill['Status']) {
                            case 6:
                                $StatusBill = 4;
                                break;
                            case 7:
                                $StatusBill = 3;
                                break;
                            case 8:
                                $StatusBill = 2;
                                break;
                            default:
                                break;
                        }

                        $BillNew = new Bill();
                        $BillNew->connection_id =   $Bill['id'];
                        $BillNew->staff_id = $Bill['staff_id'];
                        $BillNew->sequence_id =  $SequenceBill->id;
                        $BillNew->branch_id = $Bill['branch_id'];
                        $BillNew->total = $Bill['total'];
                        $BillNew->cash =  $Bill['cash'];
                        $BillNew->online =  $Bill['online'];
                        $BillNew->CustomerName =  $Bill['CustomerName'];
                        $BillNew->CustomerPhone =  $Bill['CustomerPhone'];
                        $BillNew->CustomerType = $Bill['CustomerType'];
                        $BillNew->Status = $StatusBill;
                        $BillNew->created_at = date("Y-m-d H:i:s", strtotime($Bill['created_at']));
                        $BillNew->save();




                        foreach ($Details  as $keyDetail => $Detail) {
                            if ($Detail['Bill_id']  ==  $Bill['id']) {
                                $BillDetails = new BillDetail();
                                $BillDetails->Bill_id = $BillNew['id'];
                                $BillDetails->item_id = $Detail['item_id'];
                                $BillDetails->size = $Detail['size'];
                                $BillDetails->count =  $Detail['count'];
                                $BillDetails->price = $Detail['price'];
                                $BillDetails->Status = 1;
                                $BillDetails->created_at = $BillNew['created_at']->format('Y-m-d H:i:s');
                                $BillDetails->save();
                                if (count($BillExtraTopping) > 0) {
                                    foreach ($BillExtraTopping as $key => $BillExtraToppingD) {
                                        if ($Detail['id'] == $BillExtraToppingD['Bill_details_id']) {
                                            $bill_extra_toppingNew = new Bill_Extra_Topping();
                                            $bill_extra_toppingNew->Bill_details_id = $BillDetails->id;
                                            $bill_extra_toppingNew->extra_topping_id = $BillExtraToppingD['extra_topping_id'];
                                            $bill_extra_toppingNew->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($Bills as $key => $Bill) {
                                  $foundBill = Bill::where(['connection_id' => $Bill['id'], 'sequence_id' => $Bill['sequence_id'], 'branch_id' => $staff->branch_id])->count();


            if ($foundBill == 0) {

                $SequenceBill = SequenceBill::where(['connection_id' => $Bill['sequence_id'], 'branch_id' => $staff->branch_id])->first();

                if ($Bill['connection_id'] == null) {

                    switch ($Bill['Status']) {
                        case 6:
                            $StatusBill = 4;
                            break;
                        case 7:
                            $StatusBill = 3;
                            break;
                        case 8:
                            $StatusBill = 2;
                            break;
                        default:

                            break;
                    }
                    $BillNew = new Bill();
                    $BillNew->connection_id =   $Bill['id'];
                    $BillNew->staff_id = $Bill['staff_id'];
                    $BillNew->sequence_id =  $SequenceBill->id;
                    $BillNew->branch_id = $Bill['branch_id'];
                    $BillNew->total = $Bill['total'];
                    $BillNew->cash =  $Bill['cash'];
                    $BillNew->online =  $Bill['online'];
                    $BillNew->CustomerName =  $Bill['CustomerName'];
                    $BillNew->CustomerPhone =  $Bill['CustomerPhone'];
                    $BillNew->CustomerType = $Bill['CustomerType'];
                    $BillNew->Status = $StatusBill;
                    $BillNew->created_at = date("Y-m-d H:i:s", strtotime($Bill['created_at']));
                    $BillNew->save();

                    foreach ($Details  as $keyDetail => $Detail) {
                        if ($Detail['Bill_id']  ==  $Bill['id']) {
                            $BillDetails = new BillDetail();
                            $BillDetails->Bill_id = $BillNew['id'];
                            $BillDetails->item_id = $Detail['item_id'];
                            $BillDetails->size = $Detail['size'];
                            $BillDetails->count =  $Detail['count'];
                            $BillDetails->price = $Detail['price'];
                            $BillDetails->Status = 1;
                            $BillDetails->created_at = date("Y-m-d H:i:s", strtotime($BillNew['created_at']));
                            $BillDetails->save();
                        }
                        if (count($BillExtraTopping) > 0) {

                            foreach ($BillExtraTopping as $key => $BillExtraToppingD) {
                                if ($Detail['id'] == $BillExtraToppingD['Bill_details_id']) {
                                    $bill_extra_toppingNew = new Bill_Extra_Topping();
                                    $bill_extra_toppingNew->Bill_details_id = $BillDetails->id;
                                    $bill_extra_toppingNew->extra_topping_id = $BillExtraToppingD['extra_topping_id'];
                                    $bill_extra_toppingNew->save();
                                }
                            }
                        }
                    }
                } else {

                    switch ($Bill['Status']) {

                        case 6:
                            Bill::where('id', $Bill['connection_id'])->update([
                                'total' => $Bill['total'],
                                'cash' => $Bill['cash'],
                                'online' => $Bill['online'],
                                'Status' => 4
                            ]);
                            break;
                        case 7:
                            Bill::where('connection_id', $Bill['id'])->update([
                                'total' => $Bill['total'],
                                'cash' => $Bill['cash'],
                                'online' => $Bill['online'],
                                'Status' => 3
                            ]);
                            break;
                        case 8:
                            Bill::where('connection_id', $Bill['id'])->update([
                                'total' => $Bill['total'],
                                'cash' => $Bill['cash'],
                                'online' => $Bill['online'],
                                'Status' => 2
                            ]);
                            break;
                        default:
                            break;
                    }
                }
            }
        }


        $date = new DateTime('+1 day');
        $OneDay = $date->format('Y-m-d');
        $date = new DateTime('-20 day');
        $Day = $date->format('Y-m-d');

        $Schedules = StaffScheduling::where(['branch_id' => $staff->branch_id])->get();
        $items = Item::where(['Shope_id' => $staff->Branch->shope_id])->get();
        $Categories = Categorie::where('Shope_id', $staff->Branch->shope_id)->whereHas('Item', function ($q) {
            $q->where('Status', 1);
        })->orderBy('created_at', 'DESC')->get();
        $extraToppings  = extra_topping::where(['Shope_id' => $staff->Branch->shope_id])->get();

        $this->decrementStore();
        $BoxsNew = SequenceBill::where('branch_id', $staff->branch_id)->whereBetween(
            'Start_Date',
            [
                $Day, $OneDay
            ]
        )->get(); // local open  
        $BillsNew = Bill::where('branch_id', $staff->branch_id)->where('connection_id', '!=', null)->whereBetween(
            'created_at',
            [
                $Day   . " 00:00:00",  $OneDay . " 23:59:59"
            ]
        )->get(); // local open  


        return response()->json([
            'success' => true,
            'all' => $Details,
            'newBoxs' => $BoxsNew,
            'newBills' => $BillsNew,
            'items' => $items,
            'Categories' => $Categories,
            'extraToppings' => $extraToppings,
            'schedules' => $Schedules
        ], 200);
    }
    protected function decrementStore()
    {

        $staff = Staff::where('user_id', auth('api')->user()->id)->first();

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
                                $staff = Staff::where('user_id', auth('api')->user()->id)->first();
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
                                if ($restValueExt <= 0) {
                                    $restValueExt = 0;
                                }
                                Store::where('Name', 'Like', '%' . $storeName->Name . '%')->where(function ($q) {
                                    $staff = Staff::where('user_id', auth('api')->user()->id)->first();
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
                            $staff = Staff::where('user_id', auth('api')->user()->id)->first();
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
                            if ($restValue <= 0) {
                                $restValue = 0;
                            }
                            Store::where('Name', 'Like', '%' . $storeName->Name . '%')->where(function ($q) {
                                $staff = Staff::where('user_id', auth('api')->user()->id)->first();
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
    protected function items()
    {

       $staff = Staff::with('Branch')->where('user_id',  auth('api')->user()->id)->first();
        $Sequence =  SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null])->whereIn('Status', [1, 4])
        ->orderBy('created_at', 'DESC')->count();
        $isOpen = true ; 
        $seq =  SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null])->whereIn('Status', [1, 4])
        ->orderBy('created_at', 'DESC')->first(); 
        if($Sequence == 0 ){
            $isOpen = false ;
            $seq =null ; 
        }
        $items = Item::where(['Shope_id' => $staff->Branch->shope_id, 'Status' => 1])->get();
        $extraToppings  = extra_topping::where(['Shope_id' => $staff->Branch->shope_id, 'Status' => 1])->get();
        $todayBills = Bill::where('branch_id', $staff->branch_id)->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])->get();
        $Categories = Categorie::where('Shope_id', $staff->Branch->shope_id)->get();
        $info  = Bill::where('CustomerPhone','!=',null )->where(['branch_id' => $staff->branch_id,])->groupBy('CustomerPhone','CustomerName')->select('CustomerPhone','CustomerName')->get();
        
        $all = [ 'vte'=> $staff->Branch->Shope->VTENumber , 'pedding'=> $staff->Branch->Shope->pedding, 'isOpen'=>$isOpen,'items' => $items, 'todayBill' => $todayBills, 'Categories' => $Categories , 'seq' =>$seq , 
                'extraToppings'=>$extraToppings , 'info'=> $info];

        return response()->json([
            'success' => true,
            'all' => $all,

        ], 200);
    }
    protected function OpenBoxForDay(Request $request)
    {

      
        $staff = Staff::where('user_id',  auth('api')->user()->id)->first();

        $schedule = StaffScheduling::where(['branch_id' => $staff->branch_id])
        ->where([
            ['Start_Date', '<=', date('H:i')],
           // ['End_Date', '>=', date('H:i')],
        ])
        ->first();
        $Sequence = new  SequenceBill();
        $Sequence->staff_id = $staff->id;
        $Sequence->branch_id = $staff->branch_id;
        $Sequence->schedule_id =   $schedule->id;
        $Sequence->Start_Date = date('Y-m-d H:i:s');
        $Sequence->Start_Custody = $request->price; // Open Day 
        $Sequence->Status = 1; // Open Day 
        $Sequence->save();

        return response()->json([
            'success' => true,

        ], 200);
    }
    protected function closeBoxForDay(Request $request){
        $staff = Staff::where('user_id',  auth('api')->user()->id)->first();

        SequenceBill::where(['branch_id' => $staff->branch_id, 'End_Date' => null, 'staff_id' => $staff->id, 'End_Custody' => null, 'Status' => 1])
        ->orderBy('created_at', 'DESC')->update([
            'End_Date' => date('Y-m-d H:i:s'),
            'End_Custody' => $request->price,
            'Status' => 2

        ]);

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
        return response()->json([
            'success' => true,

        ], 200);
    }
    protected function BillConfirm(Request $request){
        $staff = Staff::where('user_id',  auth('api')->user()->id)->first();
        $bill  =   Bill::where(['branch_id'=> $staff->branch_id , 'id'=>$request->id])->first();
        Bill::where(['branch_id'=> $staff->branch_id , 'id'=>$request->id])->update([
            'Status'=>4 , 
            'cash'=> $request->payWay == 1 ? $bill->total : 0 , 
            'online'=> $request->payWay == 2 ? $bill->total : 0
        ]);
        $this->decrementStore();
        return response()->json([
            'success' => true
        ], 200);
    }
    protected function BillDelete(Request $request){
        $staff = Staff::where('user_id',  auth('api')->user()->id)->first();
        Bill::where(['branch_id'=> $staff->branch_id , 'id'=>$request->id])->update([
            'Status'=>2
        ]);
       $details = BillDetail::where('Bill_id', $request->id)->get();
        foreach ($details as $key => $items) {

            $compounds = ItemCompound::where(['item_id' => $items->item_id, 'size' => $items->size])->get();

            $extraToppings = Bill_Extra_Topping::where('Bill_details_id', $items->id)->get();

            if (count($extraToppings) != 0) {
                foreach ($extraToppings as $key => $extra) {
                    $extraTopping = extra_topping::where('id', $extra->extra_topping_id)->first();
                    $storeName = Store::where('id', $extraTopping->store_id)->first();
                    $oldstore = Store::where('Name', 'Like',  $storeName->Name)->where(function ($q) {
                        $staff = Staff::where('user_id', auth('api')->user()->id)->first();
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
                                $staff = Staff::where('user_id', auth('api')->user()->id)->first();
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
                        $staff = Staff::where('user_id', auth('api')->user()->id)->first();
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
                                $staff = Staff::where('user_id', auth('api')->user()->id)->first();
                                $q->where('branch_id', $staff->branch_id);
                            })->update([
                                'restValue' => $restValue,
                                'count' => round($newCount)
                            ]);
                        }
                    }
                }
            } 
        return response()->json([
            'success' => true,

        ], 200);
    
    }
}
    
    protected function createBill(Request $request)
    {
      
      
        foreach ($request->basket as $item) {
            $all[] = $item;
        }

        $total = $request->price  / 1.15;



        $totalWithTax = ($total * 0.15) + $total;
        $staff =  Staff::with('Branch')->where('user_id', auth('api')->user()->id)->first();

        $Bill = new Bill();
        $Bill->staff_id =  $staff->id;
        $Bill->branch_id =   $staff->branch_id;
        $Bill->sequence_id =   $request->seq;
        $Bill->total = $request->price;
        $Bill->cash = $request->PayWay == 1 ? $request->cash : 0 ;
        $Bill->online = $request->PayWay == 2 ? $request->price : 0 ;
        $Bill->CustomerName =  $request->name;
        $Bill->CustomerPhone =  $request->phone;
        $Bill->CustomerType = $request->customerType;
        $Bill->Status = $request->isPadding ? 3 : 4 ;
        $Bill->save();
        $lastExp = expense::whereRelation('Branch', 'shope_id', '=', $staff->Branch->shope_id)
            ->whereBetween('month', [$Bill->created_at->format('Y-m') . '-01', date('Y-m-d', strtotime('+1 Months', strtotime($Bill->created_at->format('Y-m'))))])
            ->where('branch_id',  $staff->branch_id)->first();
        if ($lastExp == null) {
            $Expense =  new expense();
            $Expense->branch_id = $staff->branch_id;
            $Expense->month = $Bill->created_at->format('Y-m') . '-01';
            $Expense->Status = 1;
            $Expense->save();
        }
        for ($i = 0; $i < count($all); $i++) {
            $item = Item::where('id',$all[$i][0] )->first();

            switch ($all[$i][3]) {
                case 'small':
                    $size = 1;
                    $letter = 's';
                    $itemPrice =   $item->Small_Price ; 
                    break;
                case 'mid':
                    $size = 2;
                    $letter = 'm';
                    $itemPrice =   $item->Mid_Price ; 

                    break;
                case 'big':
                    $size = 3;
                    $letter = 'b';
                    $itemPrice =   $item->Big_Price ; 

                    break;

                default:
                    $size = 1;
                    $letter = 's';
                    $itemPrice =   $item->Small_Price ; 

                    break;
            }
            $BillDetails = new BillDetail();
            $BillDetails->Bill_id = $Bill->id;
            $BillDetails->item_id = $all[$i][0];
            $BillDetails->size =  $size;
            $BillDetails->count =  $all[$i][1];
            $BillDetails->price =  $all[$i][8];
            $BillDetails->Status = 1;
            $BillDetails->created_at = $Bill->created_at->format('Y-m-d H:i:s');
            $BillDetails->save();

            if ($all[$i][7] != "") {
            foreach ($request->extraTopping as $extra) {
                if ($extra[0] == $letter && $extra[2] == $all[$i][0]  &&  $extra[3] ==   $all[$i][1] ) {
                  
                    $bill_extra_topping = new Bill_Extra_Topping();
                    $bill_extra_topping->Bill_details_id = $BillDetails->id;
                    $bill_extra_topping->extra_topping_id = $extra[1];
                    $bill_extra_topping->save();
                }
            }
        }

        }
        $Details = BillDetail::where('Bill_id', $Bill->id)->get();

      
          $bill = Bill::where('id', $Bill->id)->first();
       $extraToppings =  Bill_Extra_Topping::with('ExtraTopping')->whereHas('billDetails', function ($q) use ($bill)  {
            $q->where(['Bill_id'=> "{$bill->id}" , ]);
    })->get();

        
        $seq =  SequenceBill::where(['staff_id' => $staff->id,  'branch_id' => $staff->branch_id, 'End_Date' => null])->whereIn('Status', [1, 4])
         // ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
         ->orderBy('created_at', 'DESC')->first();
        $seqCount = Bill::where('sequence_id',  $seq->id)->count();

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
                ->totalWithVat(($total * 0.15) + $total)
                ->vatTotal($total * 0.15)
                ->toBase64();
                 $all = ['Bill' => $Bill, 'BillDetails' => $Details,
         'BillNo' => $seqCount, 'extraToppings' => $extraToppings, 'qr' => $qr ,];
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
        return response()->json([
            'success' => true,
            'all' => $all,
            'fa' => $FA

        ], 200);
    } 
    protected function BillsAll(){
        $staff = Staff::where('user_id', auth('api')->user()->id)->first();
        $Sequence =  SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null, 'Status' => 1])
        /*->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))*/->orderBy('created_at', 'DESC')->first();
   
            $bills  = Bill::where(['branch_id' => $staff->branch_id,  'Status' => 1 , 'sequence_id'=>$Sequence->id])->get();
            $details = BillDetail::with('Item')->whereHas('bill', function ($q) use ($Sequence) {
                $staff = Staff::where('user_id', auth('api')->user()->id)->first();
                $q->where(['status'=> 1 , 'branch_id' => $staff->branch_id, 'sequence_id'=>$Sequence->id]);
            })->get();
            $extra = Bill_Extra_Topping::with('ExtraTopping')->whereHas('billDetails', function ($q) use ($Sequence) {
                $q->whereHas('bill', function ($q) use ($Sequence)  {
                    $staff = Staff::where('user_id', auth('api')->user()->id)->first();
                    $q->where(['status'=> 1 , 'branch_id' => $staff->branch_id, 'sequence_id'=>$Sequence->id]);
                });
            })->get();
    
        $all = ['Bills' => $bills , 'BillDetails'=>$details , 'extra'=>$extra];
        return response()->json([
            'success' => true,
            'all' => $all
        ], 200);
    }
        protected function infoCustomer(){
        $staff = Staff::where('user_id', auth('api')->user()->id)->first();

        $info  = Bill::where('CustomerPhone','!=',null )->where(['branch_id' => $staff->branch_id,])->groupBy('CustomerPhone','CustomerName')->select('CustomerPhone','CustomerName')->get();
        
    $Sequence =  SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null, 'Status' => 1])
       ->orderBy('created_at', 'DESC')->first();
        
       
        $bills = Bill::where(['sequence_id' => $Sequence->id])->where('Status', '<>', 2)->get();
        $total = 0;
        $cash = 0;
        $online = 0;
        $all = [];
        $i = 1;
        foreach ($bills as $key => $bill) {
            $total += $bill->total;
            $cash += $bill->cash;
            $online += $bill->online;

        }

        $all = [ 'name'=>$staff->User->name , 'phone'=>$staff->Branch->phone , 'address'=>$staff->Branch->address,
                 'info'=>$info , 'total'=>$total , 'cash'=>$cash , 'online'=>$online ,
                 'shope' => $staff->Branch->Shope->Name]; 
        return response()->json([
            'success' => true,
            'all' => $all
        ], 200);
      
    }
    protected function PendingBill(){
        $staff = Staff::where('user_id', auth('api')->user()->id)->first();
            $bills  = Bill::where(['branch_id' => $staff->branch_id,  'Status' => 5])->get();
            $details = BillDetail::with('Item')->whereHas('bill', function ($q) {
                $staff = Staff::where('user_id', auth('api')->user()->id)->first();
                $q->where(['status'=> 5 , 'branch_id' => $staff->branch_id,]);
            })->get();
            $extra = Bill_Extra_Topping::with('ExtraTopping')->whereHas('billDetails', function ($q) {
                $q->whereHas('bill', function ($q) {
                    $staff = Staff::where('user_id', auth('api')->user()->id)->first();
                    $q->where(['status'=> 5 , 'branch_id' => $staff->branch_id,]);
                });
            })->get();
    
        $all = ['Bills' => $bills , 'BillDetails'=>$details , 'extra'=>$extra];
        return response()->json([
            'success' => true,
            'all' => $all
        ], 200);
    }
    protected function expenses(){
        $staff = Staff::where('user_id', auth('api')->user()->id)->first();
        $today = Carbon::today();

        $TodayExp = otherExpense::where(['staff_id'=>$staff->id ])->whereDate('created_at', $today)->orderBy('id','desc')->get();
        $all = ['TodayExp'=>$TodayExp];
        return response()->json([
            'success' => true,
            'all' =>  $all
        ], 200);
    }
    protected function NewExp(Request $request){
      
        $staff = Staff::where('user_id', auth('api')->user()->id)->first();
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

         $today = Carbon::today();

         $expense = otherExpense::where(['staff_id'=>$staff->id ])->whereDate('created_at', $today)->get();


        return response()->json([
            'success' => true,
            'all' => "FA"
        ], 200);
    }
}
