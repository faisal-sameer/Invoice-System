<?php

namespace App\Http\Controllers;

use App\Mail\AFMail;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Branch;
use App\Models\Categorie;
use App\Models\expense;
use App\Models\Item;
use App\Models\SequenceBill;
use App\Models\Shope;
use App\Models\Staff;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Prgayman\Zatca\Facades\Zatca;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Mail;
use DB;
use PDF;
use App\Models\typeNotification;
new \GuzzleHttp\Client;
use App\Models\notification;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function LoginPage()
    {


        if (auth()->user() == null) {
            return view('auth.login');
        } else {
            return view('juiceAndResturant.Call');
        }
    }
    protected function Call()
    {
        /*$staff = Staff::where('user_id', auth()->user()->id)->first();

        $owner  = Shope::where('id', $staff->Branch->shope_id)->first();
        $Box =  SequenceBill::where(['Status' => 1])
            ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
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
        SequenceBill::where(['Status' => 1])
            ->where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d'))
            ->orderBy('created_at', 'DESC')->update([
                // 'Status' => $request->autoClose == 1 ?  3 : 2

            ]);
        $mailData   = ['Box' => $Box, 'Incoming' => $Incoming, 'owner' => $owner->Owner];
        //  $pdf = PDF::loadView('emails.afmail', $mailData)->setOptions(['defaultFont' => 'sans-serif']);

        \Mail::send('emails.afmail', $mailData, function ($message) use ($owner) {
            $message->to($owner->Owner->email, $owner->Owner->name)
                ->subject("AF");
            //  ->attachData($pdf->output(), "text.pdf");
        });

        return view('emails.afmail', $mailData);*/

        return view('juiceAndResturant.Call');
    }


    protected function Home()
    {
        if (auth()->user() == null ) {
            return view('auth.login');
        } 
    
           
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $notifications = notification::where(['to_staff_id'=> $staff->id, 'seen'=>1])->count();
        $all =['notifications'=>$notifications ];

        return view('juiceAndResturant.Home')->with('all',$all);
    }
    protected function Notification()
    {
        if (auth()->user() == null ) {
            return view('auth.login');
        } 
    
           
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $notifications = notification::where(['to_staff_id'=> $staff->id, 'seen'=>1])->get();
        notification::where(['to_staff_id'=> $staff->id, 'seen'=>1])->update([
            'seen'=>2
        ]);
        $all =['notifications'=>$notifications ];
        return view('juiceAndResturant.Notification')->with('all', $all);
    }
    protected function SendNewMessage()
    {
        if (auth()->user() == null ) {
            return view('auth.login');
        } 
            if (auth()->user()->permission_id == 2 || auth()->user()->permission_id == 4 ) {
                $typeNotification = typeNotification::get() ;
            } else {
                $typeNotification = typeNotification::where('permission_id', 3)->get() ;
            }
        $staff = Staff::where('user_id', auth()->user()->id)->first();
            
        $branch = Branch::where('id', $staff->branch_id)->first();
        $staffs = Staff::where('id','!=',$staff->id)->whereHas('Branch', function ($p)  use ($branch){
                $p->where('shope_id',$branch->shope_id);
            
        })->get();
        $all =['typeNotification'=>$typeNotification , 'staffs'=>$staffs];
        return view('juiceAndResturant.SendNewMessage')->with('all', $all);
    }
    protected function SendMessage(Request $request){

        $messages = [

            'staff.required' => 'لم يتم توجيه الرسالة لمن  ',
            'type.required' => 'لم يتم تحديد نوع الرسالة ',
            'notes.required' => 'يجب ذكر السبب ',

        ];
        $validator = Validator::make($request->all(), [
            'staff' => 'required',
            'type' => 'required',
            'notes' => 'required',
            

        ], $messages);
        if (auth()->user() == null ) {
            return view('auth.login');
        } 
        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $branch = Branch::where('id', $staff->branch_id)->first();

        if ($request->staff == 0) {
            $staffs = Staff::where('id','!=',$staff->id)->whereHas('Branch', function ($p)  use ($branch){
                $p->where('shope_id',$branch->shope_id);
        })->get();
        foreach ($staffs as $key => $OtherStaff) {
           
        $notification = new notification();
        $notification->staff_id = $staff->id;
        $notification->to_staff_id = $OtherStaff->id;
        $notification->type_id	 = $request->type;
        $notification->notes = $request->notes;
        $notification->save();
        }
        } else {
            $notification = new notification();
            $notification->staff_id = $staff->id;
            $notification->to_staff_id = $request->staff;
            $notification->type_id	 = $request->type;
            $notification->notes = $request->notes;
            $notification->save();
        }
        
        
        Alert::success('تم الارسال  ', );

        return  redirect()->route('SendNewMessage');
    }
    protected function PreviousMessages()
    {  if (auth()->user() == null ) {
        return view('auth.login');
    } 

       
    $staff = Staff::where('user_id', auth()->user()->id)->first();

    $notifications = notification::where('to_staff_id', $staff->id)->get();
    $all =['notifications'=>$notifications ];
        
        return view('juiceAndResturant.PreviousMessages')->with('all', $all);
    }
    protected function RespoenMes(Request $request){

        $messages = [

            'id.required' => 'يوجد خطاء  ',
            'notes.required' => 'يجب ذكر السبب ',

        ];
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'notes' => 'required',
            

        ], $messages);
        if (auth()->user() == null ) {
            return view('auth.login');
        } 
        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $oldNoti =   notification::where([ 'id'=>$request->id ])->first();
       $notification = new notification();
            $notification->staff_id = $staff->id;
            $notification->to_staff_id = $oldNoti->staff_id;
            $notification->resend_id =  $request->id;
            $notification->type_id	 = 17;
            $notification->notes = $request->notes;
            $notification->Status = 3;
            $notification->save();
            notification::where(['to_staff_id'=>$staff->id , 'resend_id'=>null , 'id'=>$request->id ])->update([
                'resend_id'=> $notification->id,
                'seen'=>2
            ]);
            Alert::success('تم ارسال الرد  ', );

            return back();

    }
    protected function SentMail()
    {
        if (auth()->user() == null ) {
            return view('auth.login');
        } 
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $notifications = notification::where(['staff_id'=>$staff->id])->get();

        return view('juiceAndResturant.SentMail')->with('notifications',$notifications);
    }
    public function mail()
    {

        $detailsforAdmin = [
            'title' => 'Boss Af Title',
            'url ' => 'Boss AF Body ',
            'body' => now() . 'صلاحية الكود تنتهي بعد '
        ];
        \Mail::to('nsh-fjambi@outlook.com')->send(new AFMail($detailsforAdmin));

        dd("Email is sent OK .");
    }


    protected function TestAPIFA()
    {

        // Start API
        $token = session('token');
        $data =
            Http::withHeaders([
                //  'Authorization' => ' Basic     NDg5Njk3Njc4NjA5MTcxOlA3TmlpT2RXY29HN2Zzcg==',
                // 'X-KEYALI-API' => ' ali_w6g64MVAkAgY2k9FX1rWJNawMEIodWfA4c6z',
                // 'X-COMALI-ID' => ' 2',
                //'User-Agent' => ' Dispodger/0.1',
                'Authorization' => ' bearer ' .  $token,
                'Content-Type' => ' application/json',
                'Accept' => ' application/json',
            ])->get('http://192.168.0.103:8000/api/items');

        $posts = json_decode($data->getBody());
        return dd($posts->all);
        //   return view('test')->with('posts', $posts->response->items);
        //  return ($posts->response->items);
        /*  $response = Http::post('http://192.168.0.103:8000/api/login', [
            'id' => '11',
            'password' => '123456789',
        ]);
        session(['token' => $response['access_token']]);
        $token = session('token');
        return $token;
        */
        /// end Api 

        /*
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $items = Item::where('Shope_id', $staff->Branch->shope_id)->get();
        $todayBills = Bill::where('branch_id', $staff->branch_id)->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])->get();
        $Categories = Categorie::where('Shope_id', $staff->Branch->shope_id)->get();
        $all = ['items' => $items, 'todayBill' => $todayBills, 'Categories' => $Categories];
        return view('juiceAndResturant.CasherBoard')->with('all', $all);*/
    }
    /*
    protected function cancelBill(Request $request)
    {

        Bill::where('id', $request->billNo)->update([
            'Status' => 2
        ]);
        return  redirect()->route('CacherBoard');
    }
    protected function CreateBill(Request $request)
    {
        $total = 0;
        for ($i = 0; $i < count($request->item); $i++) {
            $finalPrice = $request->count[$i] *  $request->price[$i];
            $total += $finalPrice;
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $Bill = new Bill();
        $Bill->staff_id = $staff->id;
        $Bill->branch_id = $staff->branch_id;
        $Bill->total = $total;
        $Bill->payType =  $request->pay;
        $Bill->Status = 1;
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
        for ($i = 0; $i < count($request->item); $i++) {
            $BillDetails = new BillDetail();
            $BillDetails->Bill_id = $Bill->id;
            $BillDetails->item_id = $request->item[$i];
            $BillDetails->size =  $request->size[$i];
            $BillDetails->count =  $request->count[$i];
            $BillDetails->price =  $request->price[$i];
            $BillDetails->Status = 1;
            $BillDetails->created_at = $Bill->created_at->format('Y-m-d');
            $BillDetails->save();
        }
        $Details = BillDetail::where('Bill_id', $BillDetails->Bill_id)->get();
        $qr = Zatca::sellerName('AF Subfix')
            ->vatRegistrationNumber("697878545487874")
            ->timestamp($Bill->created_at)
            ->totalWithVat($total)
            ->vatTotal($total * 0.15)
            ->toBase64();
        $all = ['Bill' => $Bill, 'BillDetails' => $Details, 'qr' => $qr];

        return view('juiceAndResturant.billPDF')->with('all', $all);
    }*/
}
