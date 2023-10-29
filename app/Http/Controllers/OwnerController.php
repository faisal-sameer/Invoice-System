<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PDF;

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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Exports\UsersExport;
use App\Models\Bill_Extra_Topping;
use App\Models\extra_topping;
use App\Models\ItemCompound;
use App\Models\Itemseq;
use App\Models\SequenceBill;
use App\Models\StaffScheduling;
use App\Models\Store;
use App\Models\Unit;
use App\Models\User;
use App\Models\billTrans;
use Illuminate\Support\Str;
use DB;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\DB as FacadesDB;
use phpDocumentor\Reflection\Types\Nullable;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use DateTime;
use App\Models\voucher;
use App\Models\attend;
use App\Models\vacation;
use App\Models\notification;
use App\Models\Discount;
use App\Models\DiscountItem;
use App\Models\otherExpense;
use App\Models\storeFollowup;
use App\Models\Purchase;
use App\Models\PurchaseDetail;

class OwnerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'isOwners']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function AddToMenu()
    {

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $cat = Categorie::where('Shope_id', $owner->id)->get();
        $item = Item::where(['Shope_id' => $owner->id,  'Status' => 1])->paginate(5);
        $AllItem = Item::where(['Shope_id' => $owner->id,  'Status' => 1])->get();
        $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();
        $extraToppings  = extra_topping::where(['Shope_id' => $owner->id, 'Status' => 1])
            ->get();
        $compounds = [];
        $store = [];
        $storeAll =  Store::where(['Status' => 1])->whereHas('Branch', function ($q) {
            $owner = Shope::where('owner_id', auth()->user()->id)->first();
            $q->where('shope_id', $owner->id);
        })->select('id', 'Name')->get()->unique('Name');
        foreach ($storeAll as $key => $storeItem) {
            $stores =  Store::where('Name', 'LIKE', '%' . $storeItem->Name . '%')->whereHas('Branch', function ($q) {
                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->first();
            if ($stores->count() > 0) {
                $store[] = [
                    'id' => $stores->id,
                    'Name' => $stores->Name, 'unit' => $stores->unit->Name, 'count' => $stores->count, 'value' => $stores->value,
                    'restValue' => $stores->restValue, 'branch' => $stores->branch_id
                ];
            }
        }
        foreach ($AllItem as $key => $itemes) {
            $multCompund = ItemCompound::where(['item_id' => $itemes->id, 'Status' => 1])->get();
            foreach ($multCompund as $key => $singleCompund) {
                $compounds[] = [
                    'id' => $singleCompund->id,
                    'item_id' => $singleCompund->item_id, 'store_id' => $singleCompund->store_id,
                    'count' => $singleCompund->count, 'size' => $singleCompund->size

                ];
            }
        }
        $all = [
            'categories' => $cat, 'items' => $item, 'allItem' => $AllItem, 'store' => $store,
            'compounds' => $compounds, 'extraToppings' => $extraToppings
        ];
        return view('juiceAndResturant.AddToMenu')->with('all', $all);
    }
    protected function Discount()
    {
        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $cat = Categorie::where('Shope_id', $owner->id)->whereHas('Item', function ($q) {
            $q->where('Status', 1);
        })->orderBy('created_at', 'DESC')->get();

        $AllItem = Item::where(['Shope_id' => $owner->id,  'Status' => 1])->get();
        $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();
        $Discounts = Discount::where('Status', 1)->whereHas('Branch', function ($q) use ($owner) {
            $q->where(['shope_id' => $owner->id, 'Status' => 1]);
        })->get();
        $DiscountItems = DiscountItem::whereHas('Discount', function ($q)  use ($owner) {
            $q->whereHas('Branch', function ($q) use ($owner) {
                $q->where(['shope_id' => $owner->id, 'Status' => 1]);
            });
        })->get();
        $all = [
            'categories' => $cat, 'items' => $AllItem, 'branchs' => $branchs,
            'Discounts' => $Discounts, 'DiscountItems' => $DiscountItems
        ];
        return view('juiceAndResturant.Discount')->with('all', $all);
    }
    protected function DiscountCreate(Request $request)
    {

        $messages = [

            'title.required' => 'لابد من وجود اسم  ',   // Required
            'Description.required' => 'لابد من  وجود وصف ',   // Required
            'from.required' => 'لابد من  تحديد وقت البدا',   // Required
            'to.required' => 'لابد من  من تحديد وقت الانتهاء  ',   // Required
            'disFor.required' => 'لابد من تحديد  كيفية الخصم  ',   // Required
            'amount.required' => 'لابد من تحديد المبلغ/النسبة  ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'title' => 'required ',
            'Description' => 'required ',
            'from' => 'required ',
            'to' => 'required ',
            'disFor' => 'required ',
            'discouttype' => 'required ',
            'amount' => 'required ',
            'branch' => 'required ',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }


        if ($request->branch == 'all') {
            $owner = Shope::where('owner_id', auth()->user()->id)->first();

            $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();
            foreach ($branchs as $key => $branch) {

                $Discount = new Discount();
                $Discount->branch_id = $branch->id;
                $Discount->title = $request->title;
                $Discount->Description = $request->Description;
                $Discount->from = $request->from;
                $Discount->to = $request->to;
                $Discount->auto = $request->auto == 'on' ? 1 : 0;
                $Discount->Discount_type = $request->discouttype == 'cata' ? 1 : 2;
                $Discount->Discount_for = $request->disFor == 'Pr' ? 2 : 1;
                $Discount->DiscountP = $request->amount;
                $Discount->save();

                if ($request->discouttype == 'cata') { // Cat

                    foreach ($request->cat as $key => $cat) {
                        $DiscountItem = new DiscountItem();
                        $DiscountItem->Discount_id =  $Discount->id;
                        $DiscountItem->categorie_id = $cat;
                        $DiscountItem->save();
                    }
                } else if ($request->discouttype == 'elemnt') { // Item

                    foreach ($request->item as $key => $item) {
                        $DiscountItem = new DiscountItem();
                        $DiscountItem->Discount_id =  $Discount->id;
                        $DiscountItem->item_id = $item;
                        $DiscountItem->save();
                    }
                } else { // Bill

                }
            }
        } else {
            $Discount = new Discount();
            $Discount->branch_id = $request->branch;
            $Discount->title = $request->title;
            $Discount->Description = $request->Description;
            $Discount->from = $request->from;
            $Discount->to = $request->to;
            $Discount->auto = $request->auto == 'on' ? 1 : 0;
            $Discount->Discount_type = $request->discouttype == 'cata' ? 1 : 2;
            $Discount->Discount_for = $request->disFor == 'Pr' ? 2 : 1;
            $Discount->DiscountP = $request->amount;
            $Discount->save();

            if ($request->discouttype == 'cata') { // Cat

                foreach ($request->cat as $key => $cat) {
                    $DiscountItem = new DiscountItem();
                    $DiscountItem->Discount_id =  $Discount->id;
                    $DiscountItem->categorie_id = $cat;
                    $DiscountItem->save();
                }
            } else if ($request->discouttype == 'elemnt') { // Item

                foreach ($request->item as $key => $item) {
                    $DiscountItem = new DiscountItem();
                    $DiscountItem->Discount_id =  $Discount->id;
                    $DiscountItem->item_id = $item;
                    $DiscountItem->save();
                }
            } else { // Bill

            }
        }
        alert()->success('تم اضافة العرض   ', '');
        return back();
    }
    protected function DeleteDiscount(Request $request)
    {

        $messages = [
            'id.required' => 'يجب تحديد العرض  ',
        ];
        $validator = Validator::make($request->all(), [
            'id' => 'required ',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        Discount::where('id', $request->id)->whereHas('Branch', function ($q) use ($owner) {
            $q->where(['shope_id' => $owner->id, 'Status' => 1]);
        })->update([
            'Status' => 2
        ]);
        alert()->success('تم حذف المنتج   ', '');
        return back();
    }
    protected function extraTopping(Request $request)
    {
        $messages = [

            'Name.required' => 'لابد من وجود اسم الاضافة ',   // Required
            'store.required' => 'يجب تحديد العنصر ',   // Required
            //'ToppingPrice.required' => 'لا يوجد رقم المنتج   ',   // Required
            //'ToppingCount.required' => 'يجب تحديد القيمة المستخدمة من العنصر ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'Name' => 'required ',
            'store' => 'required ',
            'ToppingPrice' => 'nullable',
            //'ToppingCount' => 'required ',
        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $owner = Shope::where('owner_id', auth()->user()->id)->first();

        $extraTopping = new extra_topping();
        $extraTopping->Name = $request->Name;
        $extraTopping->Shope_id = $owner->id;

        $extraTopping->store_id = $request->store == '0' ? null :   $request->store;
        $extraTopping->price = $request->ToppingPrice;
        $extraTopping->count = $request->ToppingCount;
        $extraTopping->save();

        alert()->success('تمت الاضافة ', '');

        return  redirect()->route('AddToMenu');
    }
    protected function ExtraToppingChange(Request $request)
    {

        $messages = [

            'NameExtra.required' => 'لابد من وجود اسم الاضافة ',   // Required
            'storeExtra.required' => 'يجب تحديد العنصر ',   // Required
            //'ToppingPrice.required' => 'لا يوجد رقم المنتج   ',   // Required
            'ToppingCount.required' => 'يجب تحديد القيمة المستخدمة من العنصر ',   // Required

            'NameExtra.*.required' => 'لابد من وجود اسم الاضافة ',   // Required
            'storeExtra.*.required' => 'يجب تحديد العنصر ',   // Required
            //'ToppingPrice.*.required' => 'لا يوجد رقم المنتج   ',   // Required
            // 'ToppingCount.*.required' => 'يجب تحديد القيمة المستخدمة من العنصر ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'NameExtra' => 'required|array',
            'storeExtra' => 'required|array',
            // 'ToppingPrice' => 'nullabll',
            //'ToppingCount' => 'required|array',

            'NameExtra.*' => 'required',
            'storeExtra.*' => 'required',
            // 'ToppingPrice.*' => 'nullabll',
            // 'ToppingCount.*' => 'required',

        ], $messages);
        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();

        foreach ($request->idExtra as $key => $id) {
            extra_topping::where(['id' => $id, 'Shope_id' => $owner->id])->update([
                'Name' => $request->NameExtra[$key],
                'store_id' => $request->storeExtra[$key],
                'price' => $request->ToppingPrice[$key] == null ? 0 : $request->ToppingPrice[$key],
                'count' => $request->ToppingCount[$key]
            ]);
        }


        alert()->success('تم التعديل  ', '');

        return  redirect()->route('AddToMenu');
    }
    protected function Stored()
    {
        if (auth()->user()->permission_id == 2) {

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();
        }else if (auth()->user()->permission_id == 4){
            $staff = Staff::where('user_id', auth()->user()->id)->first();
            $branchs  = Branch::where(['id' => $staff->branch_id, 'Status' => 1])->select('id', 'address')->get();
        }else {
            return redirect()->route('Stored');
        }
        $store = [];
        $comItems = [];
        foreach ($branchs as $key => $branch) {
            $items =  Store::where(['branch_id' => $branch->id, 'Status' => 1])->get();
            if ($items->count() > 0) {

                foreach ($items as $key => $item) {
                    $foundItem = ItemCompound::whereRelation('store', 'Name', 'like', $item->Name)->get();
                    if ($foundItem->count() > 0) {
                        $found  = true;
                        foreach ($foundItem as $key => $storeId) {
                            $itemCom = ItemCompound::whereRelation('store', 'Name', 'like',  $storeId->store->Name )->get();
                            if ($itemCom != null) {
                                $comItems[$item->id] = $itemCom;
                            }
                        }   
                    } else {
                        $found  = false;
                    }
                    $store[] = [
                        'id' => $item->id,
                        'Name' => $item->Name, 'unit' => $item->unit->Name, 'count' => $item->count, 'value' => $item->value,
                        'restValue' => $item->restValue, 'branch' => $item->branch_id, 'found' => $found, 'BranchName' => $item->Branch->address
                    ];
                }
            }
        }

        $units = Unit::select('id', 'Name')->get();
        $all = ['branchs' => $branchs, 'store' => $store, 'units' => $units, 'comItems' => $comItems,];
        return view('juiceAndResturant.Stored')->with('all', $all);
    }
    protected function EditItemStoreName(Request $request)
    {
        $messages = [

            'itemId.required' => 'لا يوجد رقم المنتج   ',   // Required
            'Name.*.required' => 'لا يمكن حذف اسم المنتج    ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'itemId' => 'required ',
            'Name.*' => 'required'
        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if (auth()->user()->permission_id == 2) {

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        foreach ($request->Name as $key => $name) {
            Store::where(['id' => $request->itemId[$key],])->whereHas('Branch', function ($q) {
                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->update([
                'Name' => $request->Name[$key],
            ]);
        }
        }else    if (auth()->user()->permission_id == 4) {
            $staff = Staff::where('user_id', auth()->user()->id)->first();
            foreach ($request->Name as $key => $name) {
                Store::where(['id' => $request->itemId[$key],])->whereHas('Branch', function ($q) use($staff) {
                    $q->where('id', $staff->branch_id);
                })->update([
                    'Name' => $request->Name[$key],
                ]);
            }
        }else {
            return redirect()->route('Stored');

        }
        alert()->success('تم تعديل الاسم   ', '');

        return  redirect()->route('Stored');
    }

    protected function ItemEdit(Request $request)
    {
        $messages = [

            'quantity.required' => 'يجب تحديد العدد  ',   // Required
            'id.required' => 'لا يوجد رقم المنتج   ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'quantity' => 'required ',
            'id' => 'required ',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $itemDetail =  Store::where(['id' => $request->id])->first();

        if (auth()->user()->permission_id == 2) {

            $owner = Shope::where('owner_id', auth()->user()->id)->first();
            $branch = Branch::where(['id' => $itemDetail->branch_id, 'shope_id' => $owner->id, 'Status' => 1])->first();
        }else  if (auth()->user()->permission_id == 4) {
            $staff = Staff::where('user_id', auth()->user()->id)->first();
            $branch = Branch::where(['id' => $itemDetail->branch_id, 'id' => $staff->branch_id, 'Status' => 1])->first();
        }else {
            return redirect()->route('Stored');

        }

        $count = $itemDetail->count + $request->quantity;

        $oldStore =  Store::where(['id' => $itemDetail->id, 'branch_id' => $branch->id])->first(); 
        $restValue = $itemDetail->restValue +  ($request->quantity * $itemDetail->value)  + $request->ValuesCount;
        Store::where(['id' => $itemDetail->id, 'branch_id' => $branch->id])->update([
            'count' => $count,
            'restValue' => $restValue
        ]);
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $storeFollowup = new storeFollowup();
        $storeFollowup->store_id = $oldStore->id; 
        $storeFollowup->staff_id =  $staff->id; 
        $storeFollowup->value =  $request->ValuesCount + ($request->quantity * $itemDetail->value)  ; 
        $storeFollowup->old_value =  $oldStore->restValue; 
        $storeFollowup->save(); 
        alert()->success('تمت الاضافة في المخزون  ', '');

        return  redirect()->route('Stored');
    }
    protected function ItemChangeDelete(Request $request)
    {
        $messages = [
            'secondaryID.required' => 'يجب تحديد المنتج البديل ',
            'idstore.required' => 'يجب تحديد المنتج   ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'idstore' => 'required ',
            'secondaryID' => 'required ',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $idstore = $request->idstore;
        $secondaryID = $request->secondaryID;
        if (auth()->user()->permission_id == 2) {

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        ItemCompound::where('store_id', $idstore)
            ->whereHas('store', function ($q) {
                $q->whereHas('Branch', function ($q) {
                    $owner = Shope::where('owner_id', auth()->user()->id)->first();
                    $q->where('shope_id', $owner->id);
                });
            })

            ->update([
                'store_id' => $secondaryID
            ]);
        Store::where(['id' => $idstore,])->whereHas('Branch', function ($q) {
            $owner = Shope::where('owner_id', auth()->user()->id)->first();
            $q->where('shope_id', $owner->id);
        })->delete();
        }else  if (auth()->user()->permission_id == 4) {
        
                alert()->warning('لا يمكنك القيام بهذا الامر  ', 'الرجاء التواصل مع المالك للحذف ');
        
                return  redirect()->route('Stored');
        }else {
            return redirect()->route('Stored');

        }
        alert()->success('تم حذف المنتج   ', '');
        return  redirect()->route('Stored');
    }
    protected function deleteFromStore(Request $request)
    {
        $messages = [

            'ID.required' => 'يجب تحديد المنتج   ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'ID' => 'required ',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if (auth()->user()->permission_id == 2) {

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $itemDetail =  Store::where(['id' => $request->ID])->first();
        $branch = Branch::where(['id' => $itemDetail->branch_id, 'shope_id' => $owner->id, 'Status' => 1])->first();
        Store::where(['id' => $itemDetail->id, 'branch_id' => $branch->id])->whereHas('Branch', function ($q) {
            $owner = Shope::where('owner_id', auth()->user()->id)->first();
            $q->where('shope_id', $owner->id);
        })->delete();
        }else  if (auth()->user()->permission_id == 4) {
            $staff = Staff::where('user_id', auth()->user()->id)->first();
            $itemDetail =  Store::where(['id' => $request->ID])->first();
            $branch = Branch::where(['id' => $itemDetail->branch_id, 'id' => $staff->branch_id, 'Status' => 1])->first();
            Store::where(['id' => $itemDetail->id, 'branch_id' => $branch->id])->whereHas('Branch', function ($q) use($staff) {
                $q->where('id', $staff->branch_id);
            })->delete();
        }else {
            return redirect()->route('Stored');

        }
        alert()->success('تم حذف المنتج   ', '');
        return  redirect()->route('Stored');
    }
    protected function StoreSave(Request $request)
    {
        $messages = [

            'branch.required' => 'يجب تحديد الفرع  ',   // Required
            'Name.required' => 'يجب ادخال اسم المنتج  ',   // Required
            'count.required' => 'يجب تحديد عدد المنتج   ',   // Required
            'unit.required' => 'يجب تحديد وحدة المنتج   ',   // Required
            'size.required' => 'يجب تحديد قيمة الوحدة الواحدة للمنتج   ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'branch' => 'required ',
            'Name' => 'required ',
            'count' => 'required ',
            'unit' => 'required ',
            'size' => 'required ',
        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if ($request->branch == 'none' || $request->unit == 'none') {
            $request->branch == 'none' ?  Alert::error('خطأ ',  'يجب تحديد الفرع من القائمة  ')
                :  Alert::error('خطأ ',  'يجب تحديد الوحدة  من القائمة  ');

            return back();
        }
        if (auth()->user()->permission_id == 2) {
            $owner = Shope::where('owner_id', auth()->user()->id)->first();

            $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();
          
        } else  if (auth()->user()->permission_id == 4) {
            $staff = Staff::where('user_id', auth()->user()->id)->first();

            $branchs  = Branch::where(['id' => $staff->branch_id, 'Status' => 1])->select('id', 'address')->get();
          
        }else {
            return  redirect()->route('Stored');
        }
        if ($request->branch == 'all') {
           foreach ($branchs as $key => $branch) {
                $size = $request->count * $request->size;
                $store = new Store();
                $store->Name = $request->Name;
                $store->branch_id = $branch->id;
                $store->unit_id = $request->unit;
                $store->count = $request->count;
                $store->value =  $request->size;
                $store->restValue = $size;
                $store->Status = 1;
                $store->save();
            }
        } else {
            $size = $request->count * $request->size;
            $store = new Store();
            $store->Name = $request->Name;
            $store->branch_id = $request->branch;
            $store->unit_id = $request->unit;
            $store->count = $request->count;
            $store->value =  $request->size;
            $store->restValue = $size;
            $store->Status = 1;
            $store->save();
        }
            
      /*  $staff = Staff::where('user_id', auth()->user()->id)->first();

        $notificationOld = notification::where('vacation_id', $request->idD)->first();
        $notification = new notification();
        $notification->staff_id = $staff->id;
        $notification->to_staff_id = $notificationOld->staff_id;
        $notification->resend_id =  $notificationOld->id;
        $notification->type_id     = 15;
        $notification->notes = "مرفوض";
        $notification->Status = 3;
        $notification->save();*/

    

        alert()->success('تم اضافة المنتج للمخزون ', '');

        return  redirect()->route('Stored');
    }


    protected function NewCategories(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'Cat.required' => 'يجب إدخال اسم الفئة ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'Cat' => 'required ',
        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }

        if (auth()->user()->permission_id == 2) {

            $owner = Shope::where('owner_id', auth()->user()->id)->first();

            $newCategories = new Categorie();
            $newCategories->Shope_id = $owner->id;
            $newCategories->Name = $request->Cat;
            $newCategories->Status = 1;
            $newCategories->save();
            alert()->success('تم بنجاح اضافة الفئة', '');

            return  redirect()->route('AddToMenu');
        } else {
            return back();
        }
    }
    protected function NewItem(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'Name.required' => 'يجب إدخال اسم العنصر ',   // Required
            'Cat.required' => 'يجب تحديد الفئة  ',   // Required
            'pic.image' => 'الملف المدخل ليس بضيغة الصور jpg ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'Name' => 'required ',
            'Cat' => 'required ',
            'pic' => 'mimes:jpg,bmp,png',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if ($request->Cat == '0') {

            Alert::error('خطأ ',  'يجب إدخال اسم العنصر ');

            return back();
        }
        if (auth()->user()->permission_id == 2) {
            $code = Str::random(4);
            $owner = Shope::where('owner_id', auth()->user()->id)->first();

            if ($request->pic == null) {
                $path = null;
            } else {
                $file = $request->pic;
                $extension = $file->getClientOriginalExtension();
                $destination_path = 'items' . '/';
                $file_name =  $code . 'item' . $owner->id . '.' . $extension;
                $file->move($destination_path, $file_name);
                $path =  $destination_path .  $file_name;
            }
            $newItem = new Item();
            $newItem->Shope_id = $owner->id;
            $newItem->barCode = $request->code;
            $newItem->categories_id = $request->Cat;
            $newItem->Name = $request->Name;
            $newItem->Small_Name = $request->SmallName;
            $newItem->Small_Price = $request->SmallPrice;
            $newItem->Mid_Name = $request->MidName;
            $newItem->Mid_Price = $request->MidPrice;
            $newItem->Big_Name = $request->BigName;
            $newItem->Big_Price = $request->BigPrice;
            $newItem->file = $path;
            $newItem->description = $request->description;
            $newItem->Status = 1;
            $newItem->save();

            $itemSmall =  $request->itemSmall == null ? [] : $request->itemSmall;
            $itemMid =  $request->itemMid == null ? [] : $request->itemMid;
            $itemBig =  $request->itemBig == null ? [] : $request->itemBig;

            if (count($itemSmall) > 0) {
                foreach ($itemSmall as $key => $item) {

                    $item_comp = new ItemCompound();
                    $item_comp->item_id = $newItem->id;
                    $item_comp->store_id = $item;
                    $item_comp->count = $request->countSmall[$key];
                    $item_comp->Status = 1;
                    $item_comp->size = 1;
                    $item_comp->save();
                }
            }
            if (count($itemMid) > 0) {
                foreach ($itemMid as $key => $item) {
                    $item_comp = new ItemCompound();
                    $item_comp->item_id = $newItem->id;
                    $item_comp->store_id = $item;
                    $item_comp->count = $request->countMid[$key];
                    $item_comp->Status = 1;
                    $item_comp->size = 2;
                    $item_comp->save();
                }
            }
            if (count($itemBig) > 0) {
                foreach ($itemBig as $key => $item) {
                    $item_comp = new ItemCompound();
                    $item_comp->item_id = $newItem->id;
                    $item_comp->store_id = $item;
                    $item_comp->count = $request->countBig[$key];
                    $item_comp->Status = 1;
                    $item_comp->size = 3;
                    $item_comp->save();
                }
            }

            alert()->success('تم بنجاح اضافة عنصر', '');

            return  redirect()->route('AddToMenu');
        } else {
            return back();
        }
    }
    protected function ItemUpdate(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'id.required' => 'رقم العنصر غير موجود  ',   // Required

        ];
        $validator = Validator::make($request->all(), [
            'id' => 'required ',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $owner = Shope::where('owner_id', auth()->user()->id)->first();

        foreach ($request->id as $key => $item) {
            $itemCheck = Item::where('id', $item)->first();

            if ($itemCheck->Shope_id == $owner->id) {

                Item::where(['id' => $item, 'Shope_id' => $owner->id])->update([
                    'Name' => $request->name[$key],
                    'categories_id' => $request->cat[$key],
                    'Small_Price' => $request->small[$key],
                    'Small_Name' => $request->SmallName[$key],
                    'Mid_Price' => $request->mid[$key],
                    'Mid_Name' => $request->MidName[$key],
                    'Big_price' => $request->big[$key],
                    'Big_Name' => $request->BigName[$key],
                ]);
            }
        }
        alert()->success('تم حفظ التعديلات  ', '');

        return  redirect()->route('AddToMenu');
    }
    protected function ItemIingredientsUpdate(Request $request)
    {

        $messages = [

            'itemSmall.required' => 'يجب تحديد العنصر', // for single input 
            'countSmall.required' => 'يجب تحديد القيمة', // for single input 
            'size.required' => 'يجب تحديد الحجم', // for single input 

            'itemSmall.*.required' => 'يجب تحديد العنصر',
            'countSmall.*.required' => 'يجب تحديد القيمة',
            'size.*.required' => 'يجب تحديد الحجم',

        ];
        $validator = Validator::make($request->all(), [
            'itemSmall' => 'nullable',
            'countSmall' => 'nullable',
            'size' => 'nullable',

            'itemSmall.*' => 'required',
            'countSmall.*' => 'required',
            'size.*' => 'required',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $PriemyItem = Item::where(['id' => $request->id])->first();
        $itemArray =  $request->itemSmall == null ? [] : $request->itemSmall;

        if (count($itemArray) > 0) {

            ItemCompound::where(['item_id' => $PriemyItem->id, 'size' => $request->size])->whereHas('store', function ($q) {
                $q->whereHas('Branch', function ($q) {
                    $owner = Shope::where('owner_id', auth()->user()->id)->first();
                    $q->where('shope_id', $owner->id);
                });
            })->delete();

            foreach ($itemArray as $key => $item) {

                $item_comp = new ItemCompound();
                $item_comp->item_id = $PriemyItem->id;
                $item_comp->store_id = $item;
                $item_comp->count = $request->countSmall[$key];
                $item_comp->Status = 1;
                $item_comp->size = $request->size;
                $item_comp->save();
            }
        } else {
            ItemCompound::where(['item_id' => $PriemyItem->id, 'size' => $request->size])->whereHas('store', function ($q) {
                $q->whereHas('Branch', function ($q) {
                    $owner = Shope::where('owner_id', auth()->user()->id)->first();
                    $q->where('shope_id', $owner->id);
                });
            })->delete();
        }


        alert()->success('تم تعديل العناصر ', '');

        return  redirect()->route('AddToMenu');
    }
    protected function deleteItem($id)
    {
        if ($id == null) {
            Alert::error('خطأ ', 'يجب تحديد العنصر ');

            return back();
        }

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $item = Item::where('id', $id)->first();
        if ($item == null) {
            Alert::error('خطأ ', 'يجب تحديد العنصر ');

            return back();
        }
        if ($item->Shope_id == $owner->id) {
            item::where(['id' => $id, 'Shope_id' => $owner->id])->update([
                'Status' => 2
            ]);
            alert()->success('تم حذف العنصر  ', '');

            return  redirect()->route('AddToMenu');
        } else {
            return  redirect()->route('AddToMenu');
        }
    }
    protected function deleteTopping($id)
    {
        if ($id == null) {
            Alert::error('خطأ ', 'يجب تحديد العنصر ');

            return back();
        }
        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $Topping = extra_topping::where('id', $id)->first();

        if ($Topping == null) {
            Alert::error(' يي خطأ ', 'يجب تحديد العنصر ');

            return back();
        }
        $Topping = extra_topping::where(['id' => $id, 'Shope_id' => $owner->id])->update([
            'Status' => 2
        ]);
        alert()->success('تم حذف العنصر ', '');

        return  redirect()->route('AddToMenu');
    }

    protected function AdminDashboard(Request $request)
    {

        $messages = [

            //    'branchID.required' => 'يحب تحديد الفرع',
            'branchID.numeric' => 'يحب تحديد رقم ',

        ];
        $validator = Validator::make($request->all(), [
            'branchID' => 'numeric',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }



        $FA = 'https://fa-tech-bills.com/billRbas.pdf';
        $fimeName = 'فاتورة ';
        $cap = 'السلام عليكم   فاتورتك برقم : 2  شكرا لكم ';
        //$response = Http::get("https://user.4whats.net/api/sendFile?instanceid=131450&token=d2b082aa-a3b7-4762-adb7-a0fe84c73d21&phone=966540870969&body=$FA&filename=$cap&caption=$cap" );
        if (auth()->user()->permission_id == 2) {

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $branch = Branch::where('shope_id', $owner->id)->get();
        $staff = Staff::whereRelation('Branch', 'shope_id', '=', $owner->id)->get();

        }else if (auth()->user()->permission_id == 4) {
            
            $Manager = Staff::where('user_id', auth()->user()->id)->first();
            $branch = Branch::where('id', $Manager->branch_id)->get();
            $staff = Staff::whereRelation('Branch', 'id', '=', $Manager->branch_id)->get();

        }else {
            return  redirect()->route('Home');

        }
        $branchID = $request->branchID == null ?  $branch[0]->id : $request->branchID;
        $date = new DateTime('+1 day');
        $OneDay = $date->format('Y-m-d');
        $date = new DateTime('-1 day');
        $Day = $date->format('Y-m-d');
        if (auth()->user()->permission_id == 2) {

        $Box = SequenceBill::
            /*where(FacadesDB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d')) */whereBetween(
                'Start_Date',
                [
                    $Day, $OneDay
                ]
            )->whereHas('Branch', function ($q) {
                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->get();
        }else { // Manager 4 !! 
            $Box = SequenceBill::
            /*where(FacadesDB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), date('Y-m-d')) */whereBetween(
                'Start_Date',
                [
                    $Day, $OneDay
                ]
            )->where('branch_id', $Manager->branch_id)->get();
        }
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
        $schedule =  StaffScheduling::where(['branch_id' => $branchID])->get();
        $first = [];
        $secound = [];
        $thried = [];
        $forth = [];
        foreach ($schedule as $key => $shift) {
            switch ($shift->shift) {
                case 1:
                    $first = $shift;
                    break;
                case 2:
                    $secound = $shift;
                    break;
                case 3:
                    $thried = $shift;
                    break;
                case 4:
                    $forth = $shift;
                    break;
                default:
                    # code...
                    break;
            }
        }
        $all = [
            'branch' => $branch, 'staff' => $staff, 'Box' => $Box, 'schedule' => $schedule,
            'branchID' => $branchID, 'first' => $first, 'secound' => $secound,
            'thried' => $thried, 'forth' => $forth, 'Incoming' => $Incoming
        ];
        return view('juiceAndResturant.AdminDashboard')->with('all', $all);
    }

    protected function UpdateUser(Request $request)
    {
        $messages = [

            'password.*.min' => 'الرقم السري أقصر من العدد المطلوب',
            'password.*.max' => 'الرقم السري أطول من العدد المطلوب',
            'branch.*.required' => 'يجب تحديد الفرع ',


        ];
        $validator = Validator::make($request->all(), [
            'password.*' => 'min:5 | max:30 | nullable',
            'branch.*' => ' required',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if ($request->branch == 'none') {
            Alert::error('خطأ ',  'يجب تحديد الفرع من القائمة  ');

            return back();
        }
        if (auth()->user()->permission_id == 2) {
          
     
        $owner = Shope::where('owner_id', auth()->user()->id)->first();

        foreach ($request->branch as $key => $branch) {
            $testBranch =  Branch::where(['shope_id' => $owner->id, 'id' => $branch])->first();
            if ($testBranch == null) {
                Alert::error('خطأ ',  'الرجاء تحديد فرع موجود   ');

                return back();
            }
        }
    }
    if (auth()->user()->permission_id == 2 ||auth()->user()->permission_id == 4) {

        foreach ($request->id as $key => $staff) {
            $staff_info = staff::where('id', $staff)->first();
            if (!$request->password[$key] == null) {

                User::where('id', $staff_info->user_id)->update([
                    'password' => Hash::make($request->password[$key])
                ]);
            }
            staff::where('id', $staff)->update([
                'branch_id' => $request->branch[$key]
            ]);
        }
        alert()->success('تم حفظ التعديلات  ', '');
    }
        return  redirect()->route('AdminDashboard');
    }
    protected function schedulStaff(Request $request)
    {

        $messages = [

            'IDbranch.required' => 'يجب أن يكون هناك id',
            'custody.*.required' => 'يجب أن يكون هناك id',
            'shiftOne.required' => 'يجب أن يكون هناك id',
            'start1.required' => 'خطأ في الوقت',
            'end1.required' => 'خطأ في الوقت',
            'shiftTow.required' => 'يجب أن يكون هناك id',
            'start2.required' => 'خطأ في الوقت',
            'end2.required' => 'خطأ في الوقت',
            'shiftThree.required' => 'يجب أن يكون هناك id',
            'start3.required' => 'خطأ في الوقت',
            'end3.required' => 'خطأ في الوقت',
            'shiftFore.required' => 'يجب أن يكون هناك id',
            'start4.required' => 'خطأ في الوقت',
            'end4.required' => 'خطأ في الوقت',

        ];
        $validator = Validator::make($request->all(), [
            'IDbranch' => 'required',
            'custody.*' => 'required',
            'shiftOne' => 'nullable|numeric',
            'start1' => 'nullable',
            'end1' => 'nullable',
            'shiftTow' => 'nullable|numeric',
            'start2' => 'nullable',
            'end2' => 'nullable',
            'shiftThree' => 'nullable|numeric',
            'start3' => 'nullable',
            'end3' => 'nullable',
            'shiftFore' => 'nullable|numeric',
            'start4' => 'nullable',
            'end4' => 'nullable',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }

        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $branch = Branch::where('shope_id', $owner->id)->get();
        $branchID = $request->IDbranch;
        $custody  = $request->custody;


        // first Shift
        $stShift = $request->shiftOne;
        $start1 = $request->start1;
        $end1 = $request->end1;
        // secound shift
        $secShift = $request->shiftTow;
        $start2 = $request->start2;
        $end2 = $request->end2;
        // thrid shift 
        $thShift = $request->shiftThree;
        $start3 = $request->start3;
        $end3 = $request->end3;
        // forth shitf
        $fthShift = $request->shiftFore;
        $start4 = $request->start4;
        $end4 = $request->end4;
        // shift 1
        if ($custody[0] == 'null' && $start1 == null &&  $end1 == null) {
            StaffScheduling::where(['branch_id' => $branchID, 'shift' => 1])->whereHas('Branch', function ($q) {


                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->update([
                'inventory_Officer_id' => null,
                'Start_Date' => null,
                'End_Date' =>  null,
            ]);
        } else {
            if ($custody[0] != 'null') {
                if ($start1 == null || $end1 == null) {

                    Alert::error('خطأ ', '  يجب عليك تحديد  وقت البدء و الانتهاء  لفترة الاولى  ');

                    return back();
                }
                $oldSH =  StaffScheduling::where(['branch_id' => $branchID, 'shift' => 1])->whereHas('Branch', function ($q) {
                    $owner = Shope::where('owner_id', auth()->user()->id)->first();
                    $q->where('shope_id', $owner->id);
                })->first();
                if ($oldSH == null) {
                    $schedule = new StaffScheduling();
                    $schedule->shift = $stShift;
                    $schedule->inventory_Officer_id = $custody[0];
                    $schedule->branch_id = $branchID;
                    $schedule->Start_Date = $start1;
                    $schedule->End_Date = $end1;
                    $schedule->save();
                } else {
                    StaffScheduling::where(['branch_id' => $branchID, 'shift' => 1])->whereHas('Branch', function ($q) {


                        $owner = Shope::where('owner_id', auth()->user()->id)->first();
                        $q->where('shope_id', $owner->id);
                    })->update([
                        'inventory_Officer_id' => $custody[0],
                        'Start_Date' => $start1,
                        'End_Date' =>  $end1,
                    ]);
                }
            } elseif ($custody[0] == 'null'  && ($start1 != null || $end1 != null)) {
                Alert::error('خطأ ', '  يجب عليك تحديد موظف الجرد لفترة الاولى   ');

                return back();
            }
        }
        // end shift 1
        // shift 2 
        if ($custody[1] == 'null' && $start2 == null &&  $end2 == null) {
            StaffScheduling::where(['branch_id' => $branchID, 'shift' => 2])->whereHas('Branch', function ($q) {


                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->update([
                'inventory_Officer_id' => null,
                'Start_Date' => null,
                'End_Date' =>  null,
            ]);
        } else {
            if ($custody[1] != 'null') {

                if ($start2 == null || $end2 == null) {

                    Alert::error('خطأ ', 'يجب عليك تحديد وقت البدء و الانتهاء لفترة الثانية  ');

                    return back();
                }
                $oldSH =  StaffScheduling::where(['branch_id' => $branchID, 'shift' => 2])->whereHas('Branch', function ($q) {


                    $owner = Shope::where('owner_id', auth()->user()->id)->first();
                    $q->where('shope_id', $owner->id);
                })->first();
                if ($oldSH == null) {
                    $schedule = new StaffScheduling();
                    $schedule->shift = $secShift;
                    $schedule->inventory_Officer_id = $custody[1];
                    $schedule->branch_id = $branchID;
                    $schedule->Start_Date = $start2;
                    $schedule->End_Date = $end2;
                    $schedule->save();
                } else {
                    StaffScheduling::where(['branch_id' => $branchID, 'shift' => 2])->whereHas('Branch', function ($q) {


                        $owner = Shope::where('owner_id', auth()->user()->id)->first();
                        $q->where('shope_id', $owner->id);
                    })->update([
                        'inventory_Officer_id' => $custody[1],
                        'Start_Date' => $start2,
                        'End_Date' =>  $end2,
                    ]);
                }
            } else if ($custody[1] == 'null'  && ($start2 != null || $end2 != null)) {
                Alert::error('خطأ ', '  يجب عليك تحديد موظف الجرد لفترة الثانية   ');

                return back();
            }
        }
        // end shift 2
        // shift 3

        if ($custody[2] == 'null' && $start3 == null &&  $end3 == null) {
            StaffScheduling::where(['branch_id' => $branchID, 'shift' => 3])->whereHas('Branch', function ($q) {


                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->update([
                'inventory_Officer_id' => null,
                'Start_Date' => null,
                'End_Date' =>  null,
            ]);
        } else {
            if ($custody[2] != 'null') {
                if ($start3 == null || $end3 == null) {

                    Alert::error('خطأ ', 'يجب عليك و تحديد وقت البدء و الانتهاء لفترة الثالثة ');

                    return back();
                }
                $oldSH =  StaffScheduling::where(['branch_id' => $branchID, 'shift' => 3])->whereHas('Branch', function ($q) {


                    $owner = Shope::where('owner_id', auth()->user()->id)->first();
                    $q->where('shope_id', $owner->id);
                })->first();
                if ($oldSH == null) {
                    $schedule = new StaffScheduling();
                    $schedule->shift = $thShift;
                    $schedule->inventory_Officer_id = $custody[2];
                    $schedule->branch_id = $branchID;
                    $schedule->Start_Date = $start3;
                    $schedule->End_Date = $end3;
                    $schedule->save();
                } else {
                    StaffScheduling::where(['branch_id' => $branchID, 'shift' => 3])->whereHas('Branch', function ($q) {


                        $owner = Shope::where('owner_id', auth()->user()->id)->first();
                        $q->where('shope_id', $owner->id);
                    })->update([
                        'inventory_Officer_id' => $custody[2],
                        'Start_Date' => $start3,
                        'End_Date' =>  $end3,
                    ]);
                }
            } else if ($custody[2] == 'null'  && ($start3 != null || $end3 != null)) {
                Alert::error('خطأ ', '  يجب عليك تحديد موظف الجرد لفترة الثالثة   ');

                return back();
            }
        }
        // end shift 3 
        // shift 4 

        if ($custody[3] == 'null' && $start4 == null &&  $end4 == null) {
            StaffScheduling::where(['branch_id' => $branchID, 'shift' => 4])->whereHas('Branch', function ($q) {


                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->update([
                'inventory_Officer_id' => null,
                'Start_Date' => null,
                'End_Date' =>  null,
            ]);
        } else {
            if ($custody[3] != 'null') {

                if ($start4 == null || $end4 == null) {

                    Alert::error('خطأ ', 'يجب عليك و تحديد وقت البدء و الانتهاء لفترة الرابعة ');

                    return back();
                }
                $oldSH =  StaffScheduling::where(['branch_id' => $branchID, 'shift' => 4])->whereHas('Branch', function ($q) {


                    $owner = Shope::where('owner_id', auth()->user()->id)->first();
                    $q->where('shope_id', $owner->id);
                })->first();
                if ($oldSH == null) {
                    $schedule = new StaffScheduling();
                    $schedule->shift = $fthShift;
                    $schedule->inventory_Officer_id = $custody[3];
                    $schedule->branch_id = $branchID;
                    $schedule->Start_Date = $start4;
                    $schedule->End_Date = $end4;
                    $schedule->save();
                } else {
                    StaffScheduling::where(['branch_id' => $branchID, 'shift' => 4])->whereHas('Branch', function ($q) {


                        $owner = Shope::where('owner_id', auth()->user()->id)->first();
                        $q->where('shope_id', $owner->id);
                    })->update([
                        'inventory_Officer_id' => $custody[3],
                        'Start_Date' => $start4,
                        'End_Date' =>  $end4,
                    ]);
                }
            } elseif ($custody[3] == 'null'  && ($start4 != null || $end4 != null)) {
                Alert::error('خطأ ', '  يجب عليك تحديد موظف الجرد لفترة الرابعة   ');

                return back();
            }
        }

        // end shift 4 
        alert()->success('تم حفظ جدول الدوام  ', '');
        return  redirect()->route('AdminDashboard');
    }
    protected function expenses()
    {
        if (auth()->user()->permission_id == 2) {

            $owner = Shope::where('owner_id', auth()->user()->id)->first();
            $branch = Branch::where('shope_id', $owner->id)->get();
            $Purchase = Purchase::whereHas('expense', function ($q) use($owner) {
                $q->whereRelation('Branch', 'shope_id', '=', $owner->id);
            })->get();
            
            $Expense = expense::whereRelation('Branch', 'shope_id', '=', $owner->id)->orderBy('month')->paginate(12);
            $otherExpense = otherExpense::whereHas('expense', function ($q) use($owner) {
                $q->whereRelation('Branch', 'shope_id', '=', $owner->id);
            })->get();
        }else if (auth()->user()->permission_id == 4) {
            $owner = Staff::where('user_id', auth()->user()->id)->first();
            $Purchase = Purchase::whereHas('expense', function ($q) use($owner) {
                $q->whereRelation('Branch', 'shope_id', '=', $owner->id);
            })->get();
            $branch = Branch::where('id', $owner->branch_id)->get();
            $Expense = expense::whereRelation('Branch', 'id', '=', $owner->branch_id)->orderBy('month')->paginate(12);
            $otherExpense = otherExpense::whereHas('expense', function ($q) use($owner) {
                $q->whereRelation('Branch', 'id', '=', $owner->branch_id);
            })->get();
        }else {
            return back();
        }
            $all = ['branch' => $branch, 'expense' => $Expense ,'otherExpense'=>$otherExpense , 'Purchase'=>$Purchase];
            return view('juiceAndResturant.expenses')->with('all', $all);
        
    }
    protected function NewExpense(Request $request)
    {

        // Messages for valid Input 
        $messages = [
            'branch.required' => 'يجب تحديد الفرع',   // Required
            'month.required' => 'يجب تحديد الشهر ',   // Required


        ];
        $validator = Validator::make($request->all(), [
            'month' => 'required',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if ($request->branch == 0) {


            Alert::error('خطأ ',  'يجب تحديد الفرع');

            return back();
        }
        if (auth()->user()->permission_id == 2 ||auth()->user()->permission_id == 4 ) {

            if (auth()->user()->permission_id == 2) {
                $owner = Shope::where('owner_id', auth()->user()->id)->first();

            }else {
            $owner = Staff::where('user_id', auth()->user()->id)->first();

            }
            $staff = Staff::where('user_id', auth()->user()->id)->first();
            $total = 0 ; 

            $oldExp = expense::where(['month' => $request->month . '-01', 'branch_id' => $request->branch])
                ->whereRelation('Branch', 'shope_id', '=', $owner->id)->first();
               
            if ($oldExp == []) {
                $Expense =  new expense();
                $Expense->branch_id = $request->branch;
                $Expense->month = $request->month . '-01';
                $Expense->branchRent = $request->branchRent == null  ? 0 :  $request->branchRent;
                $Expense->electricBill = $request->electricBill == null  ? 0 :  $request->electricBill;
                $Expense->waterBill = $request->waterBill == null  ? 0 :  $request->waterBill;
                $Expense->salaryBill =  $request->salaryBill == null  ? 0 :  $request->salaryBill;
                $Expense->OtherBill = $request->OtherBill == null  ? 0 :  $request->OtherBill;
                $Expense->Status = 1;
                $Expense->save();
                if ($request->priceEXp != null && $request->type == 1 ) {
                $otherExpense = new otherExpense();
                $otherExpense->staff_id =  $staff->id ;
                $otherExpense->expense_id =  $Expense->id ;
                $otherExpense->title = $request->title ;
                $otherExpense->price = $request->priceEXp ;
                $otherExpense->save();
                $total = $request->priceEXp ;

               
            } 
            if ($request->type == 2){ //  مشتريات 
                $Purchase = new Purchase();
                $Purchase->staff_id =  $staff->id ;
                $Purchase->expense_id =  $Expense->id ;
                $Purchase->Name =  $request->companyName ;
                $Purchase->IDnumber = $request->IDnum ;
                $Purchase->VATnumber = $request->VATnum ;
                $Purchase->save();
                for ($i=0; $i < count($request->name) ; $i++) { 
                    $PurchaseDetail = new PurchaseDetail();
                    $PurchaseDetail->purchase_id =  $Purchase->id ;
                    $PurchaseDetail->Name =  $request->name[$i] ;
                    $PurchaseDetail->count = $request->count[$i] ;
                    $PurchaseDetail->price = $request->price[$i] ;
                    $PurchaseDetail->save();
                    $total += $request->price[$i] ;
                }
             
            }
            $Exp = expense::where(['month' => $request->month . '-01', 'branch_id' => $request->branch])
            ->whereRelation('Branch', 'id', '=', $owner->branch_id)->update([
                'OtherBill'=>$total
            ]);
            } else {
                if ($request->priceEXp != null && $request->type == 1) {

                $otherExpense = new otherExpense();
                $otherExpense->staff_id =  $staff->id ;
                $otherExpense->expense_id =  $oldExp->id ;
                $otherExpense->title = $request->title ;
                $otherExpense->price = $request->priceEXp ;
                $otherExpense->save();
                $total =  $request->priceEXp ;
                }
                if ($request->type ==  2) { // مشتريات 
                        $Purchase = new Purchase();
                        $Purchase->staff_id =  $staff->id ;
                        $Purchase->expense_id =  $oldExp->id ;
                        $Purchase->Name =  $request->companyName ;
                        $Purchase->IDnumber = $request->IDnum ;
                        $Purchase->VATnumber = $request->VATnum ;
                        $Purchase->save();
                        $total = 0 ; 
                        for ($i=0; $i < count($request->name) ; $i++) { 
                            $PurchaseDetail = new PurchaseDetail();
                            $PurchaseDetail->purchase_id =  $Purchase->id ;
                            $PurchaseDetail->Name =  $request->name[$i] ;
                            $PurchaseDetail->count = $request->count[$i] ;
                            $PurchaseDetail->price = $request->price[$i] ;
                            $PurchaseDetail->save();
                            $total += $request->price[$i] * $request->count[$i] ;
                        }
                       
                    
                }
                expense::where(['id' => $oldExp->id ])->update([
                    'branchRent' => $request->branchRent == null  ? $oldExp->branchRent :  $request->branchRent,
                    'electricBill' => $request->electricBill == null  ? $oldExp->electricBill :  $request->electricBill,
                    'waterBill' => $request->waterBill == null  ? $oldExp->waterBill :  $request->waterBill,
                    'salaryBill' => $request->salaryBill == null  ? $oldExp->salaryBill :  $request->salaryBill,
                    'OtherBill' =>   $oldExp->OtherBill + $total ,
                ]);
            
            }

            return  redirect()->route('expenses');
        } else {
            return back();
        }
    }

    protected function Chart(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'branchSelect1' => 'يجب تحديد الفرع',   // Required
            'dateSelect1.required' => ' يجب تحديد تاريخ',   // Required
            'yearSelect1' => 'يجب تحديد تاريخ',   // Required
            'monthSelect1' => ' يجب تحديد تاريخ',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'branchSelect1' => 'nullable',
            'dateSelect1' => 'required',
            'yearSelect1' => 'nullable',
            'monthSelect1' => 'nullable',
        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }

        $shope = Shope::where('owner_id', auth()->user()->id)->first();
        $Branches =  $request->branchSelect1 == 0 ? Branch::where('shope_id', $shope->id)->get() :
            Branch::where(['shope_id' => $shope->id, 'id' => $request->branchSelect1])->get();

        switch ($request->dateSelect1) {
            case 'all':

                foreach ($Branches as $keyExp => $value) {
                    $Expense = $request->branchSelect1 == 0 ?  expense::whereRelation('Branch', 'shope_id', '=', $shope->id)->where(['branch_id' => $value->id])->orderBy('month')->sum(DB::raw('branchRent + electricBill + waterBill + salaryBill + OtherBill ')) :
                        expense::where(['branch_id' => $request->branchSelect1])->whereRelation('Branch', 'shope_id', '=', $shope->id)->orderBy('month')->sum(DB::raw('branchRent + electricBill + waterBill + salaryBill + OtherBill '));

                    $cash = $request->branchSelect1 == 0 ?   Bill::where(['Status' => 1, 'branch_id' => $value->id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash') :
                        Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash');


                    $online = $request->branchSelect1 == 0 ?  Bill::where(['Status' => 1, 'branch_id' => $value->id])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online') :
                        Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect1])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online');


                    $TotalAll = $request->branchSelect1 == 0 ?  Bill::where(['Status' => 1, 'branch_id' => $value->id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total') :
                        Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect1])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total');
                    $rest = $TotalAll - $Expense;
                    $exp[$keyExp] = ['id' => $value->id, 'branch' => $value->address . '/ البحث بشكل كامل لجميع الاشهر', 'Exp' => $Expense, 'all' => $TotalAll, 'cash' => $cash == '0' ? '0' : $cash, 'online' => $online == '0' ? '0' : $online, 'rest' => $rest];
                }

                break;
            case 'y':

                $year  = $request->yearSelect1 . '-01-01';
                foreach ($Branches as $keyExp => $value) {

                    $Expense =  $request->branchSelect1 == 0 ? expense::whereRelation('Branch', 'shope_id', '=', $shope->id)->whereYear('month', '=', $request->yearSelect1)->orderBy('month')
                        ->where('branch_id', $value->id)->sum(DB::raw('branchRent + electricBill + waterBill + salaryBill + OtherBill ')) :
                        expense::where('branch_id', $request->branchSelect1)->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereYear('month', '=', $request->yearSelect1)->orderBy('month')
                        ->sum(DB::raw('branchRent + electricBill + waterBill + salaryBill + OtherBill '));

                    $cash = $request->branchSelect1 == 0 ?
                        Bill::whereBetween('created_at', [$year, date('Y-m-d', strtotime('+1 Years', strtotime($year)))])
                        ->where(['Status' => 1, 'branch_id' => $value->id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash') :
                        Bill::whereBetween('created_at', [$year, date('Y-m-d', strtotime('+1 Years', strtotime($year)))])
                        ->where(['Status' => 1, 'branch_id' => $request->branchSelect1])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash');

                    $online = $request->branchSelect1 == 0 ?  Bill::whereBetween('created_at', [$year, date('Y-m-d', strtotime('+1 Years', strtotime($year)))])
                        ->where(['Status' => 1, 'branch_id' => $value->id])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online') :
                        Bill::whereBetween('created_at', [$year, date('Y-m-d', strtotime('+1 Years', strtotime($year)))])
                        ->where(['Status' => 1, 'branch_id' => $request->branchSelect1])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online');


                    $TotalAll = $request->branchSelect1 == 0 ?  Bill::whereBetween('created_at', [$year, date('Y-m-d', strtotime('+1 Years', strtotime($year)))])
                        ->where(['Status' => 1, 'branch_id' => $value->id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total') :
                        Bill::whereBetween('created_at', [$year, date('Y-m-d', strtotime('+1 Years', strtotime($year)))])
                        ->where(['Status' => 1, 'branch_id' => $request->branchSelect1])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total');

                    $rest = $TotalAll - $Expense;
                    $exp[$keyExp] = ['id' => $value->id, 'branch' => $value->address . '/البحث بسنة ' . $request->yearSelect1, 'Exp' => $Expense, 'all' => $TotalAll, 'cash' => $cash == '0' ? '0' : $cash, 'online' => $online == '0' ? '0' : $online, 'rest' => $rest];
                }
                //  return $exp;
                break;
            case 'm':
                $month  = $request->monthSelect1 . '-01';
                foreach ($Branches as $keyExp => $value) {
                    $Expense =  $request->branchSelect1 == 0 ? expense::where(['month' => $request->monthSelect1 . '-01', 'branch_id' => $value->id])->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum(DB::raw('branchRent + electricBill + waterBill + salaryBill + OtherBill '))  :
                        expense::where(['month' => $request->monthSelect1 . '-01', 'branch_id' => $request->branchSelect1])->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum(DB::raw('branchRent + electricBill + waterBill + salaryBill + OtherBill '));

                    $cash = $request->branchSelect1 == 0 ?   Bill::whereBetween('created_at', [$month, date('Y-m-d', strtotime('+1 Months', strtotime($month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash') :
                        Bill::whereBetween('created_at', [$month, date('Y-m-d', strtotime('+1 Months', strtotime($month)))])->where(['Status' => 1, 'branch_id' => $request->branchSelect1])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash');

                    $online = $request->branchSelect1 == 0 ?  Bill::whereBetween('created_at', [$month, date('Y-m-d', strtotime('+1 Months', strtotime($month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->id])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online') :
                        Bill::whereBetween('created_at', [$month, date('Y-m-d', strtotime('+1 Months', strtotime($month)))])->where(['Status' => 1, 'branch_id' => $request->branchSelect1])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online');


                    $TotalAll = $request->branchSelect1 == 0 ?  Bill::whereBetween('created_at', [$month, date('Y-m-d', strtotime('+1 Months', strtotime($month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total') :
                        Bill::whereBetween('created_at', [$month, date('Y-m-d', strtotime('+1 Months', strtotime($month)))])
                        ->where(['Status' => 1, 'branch_id' => $request->branchSelect1])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total');
                    $rest = $TotalAll - $Expense;
                    $exp[$keyExp] = ['id' => $value->id, 'branch' => $value->address . '/البحث بشهر ' . $request->monthSelect1, 'Exp' => $Expense, 'all' => $TotalAll, 'cash' => $cash == '0' ? '0' : $cash, 'online' => $online == '0' ? '0' : $online, 'rest' => $rest];
                }
                break;
            case 'd':

                /*  $Bills =  $request->branchSelect == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereDate('created_at', $request->daySelect)->get() :
                    Bill::where(['branch_id' => $request->branchSelect, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereDate('created_at', $request->daySelect)->get();
                foreach ($Bills as $key => $BillValue) {
                    if ($BillValue->Status == 1) {
                        if (array_key_exists($BillValue->id,  $totalIncome) == null) {
                            $totalIncome[$BillValue->id] = $BillValue->total;
                            if ($BillValue->payType == 1) { // get total of cash and Online 
                                $totalIncomeCash[$BillValue->id] = $BillValue->total;
                            } else {
                                $totalIncomeOnline[$BillValue->id] = $BillValue->total;
                            }
                        } else {
                            $totalIncome[$BillValue->id] += $BillValue->total;
                            if ($BillValue->payType == 1) { // get total of cash and Online 
                                $totalIncomeCash[$BillValue->id] += $BillValue->total;
                            } else {
                                $totalIncomeOnline[$BillValue->id] += $BillValue->total;
                            }
                        }
                    }
                }*/
                return back();
                break;
            default:
                return back();
        }
        $all = ['exp' => $exp];
        //        return $all;

        return  view('juiceAndResturant.BarChart')->with('all', $all);
    }
    protected function export(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'branchSelect' => 'يجب تحديد الفرع',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'branchSelect' => 'nullable',
        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if (auth()->user()->permission_id == 2) {

        $shope = Shope::where('owner_id', auth()->user()->id)->first();
        switch ($request->dateSelect) {
            case 'all':

                $Expense = $request->branchSelect == 0 ?  expense::whereRelation('Branch', 'shope_id', '=', $shope->id)->orderBy('month')->get() :
                    expense::where(['branch_id' => $request->branchSelect])->whereRelation('Branch', 'shope_id', '=', $shope->id)->orderBy('month')->get();
                foreach ($Expense as $keyExp => $value) {

                    $cash = $request->branchSelect == 0 ?   Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])->where(['Status' => 1,  'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash');

                    $online = $request->branchSelect == 0 ?  Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])->where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online');


                    $TotalAll = $request->branchSelect == 0 ?  Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total');
                    $totalExpense =   $value->branchRent + $value->electricBill + $value->waterBill + $value->salaryBill + $value->OtherBill;
                    $rest = $TotalAll - $totalExpense;
                    $exp[$keyExp] = ['month' => $value->month, 'Exp' => $totalExpense, 'all' => $TotalAll, 'cash' => $cash == '0' ? '0' : $cash, 'online' => $online == '0' ? '0' : $online, 'rest' => $rest];
                }

                return Excel::download(new UsersExport($exp), 'جميع الفواتير ' . '.xlsx'); // Done
                break;
            case 'y':

                $Expense =  $request->branchSelect == 0 ? expense::whereRelation('Branch', 'shope_id', '=', $shope->id)->whereYear('month', '=', $request->yearSelect)->orderBy('month')->get() :
                    expense::where('branch_id', $request->branchSelect)->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereYear('month', '=', $request->yearSelect)->orderBy('month')->get();
                //return $request->all();
                foreach ($Expense as $keyExp => $value) {

                    $cash = $request->branchSelect == 0 ?   Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])->where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash');

                    $online = $request->branchSelect == 0 ?  Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])->where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online');


                    $TotalAll = $request->branchSelect == 0 ?  Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total');
                    $totalExpense =   $value->branchRent + $value->electricBill + $value->waterBill + $value->salaryBill + $value->OtherBill;
                    $rest = $TotalAll - $totalExpense;
                    $exp[$keyExp] = ['month' => $value->month, 'Exp' => $totalExpense, 'all' => $TotalAll, 'cash' => $cash == '0' ? '0' : $cash, 'online' => $online == '0' ? '0' : $online, 'rest' => $rest];
                }

                return Excel::download(new UsersExport($exp), date('Y',  strtotime($value->month)) . '.xlsx');

                break;
            case 'm':
                $Expense =  $request->branchSelect == 0 ? expense::where(['month' => $request->monthSelect . '-01'])->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                    expense::where(['month' => $request->monthSelect . '-01', 'branch_id' => $request->branchSelect])->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();
                foreach ($Expense as $keyExp => $value) {

                    $cash = $request->branchSelect == 0 ?   Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])->where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('cash');

                    $online = $request->branchSelect == 0 ?  Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])->where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('online');


                    $TotalAll = $request->branchSelect == 0 ?  Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $value->branch_id,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total') :
                        Bill::whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->where(['Status' => 1, 'branch_id' => $request->branchSelect])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->sum('total');
                    $totalExpense =   $value->branchRent + $value->electricBill + $value->waterBill + $value->salaryBill + $value->OtherBill;
                    $rest = $TotalAll - $totalExpense;
                    $exp[$keyExp] = ['month' => $value->month, 'Exp' => $totalExpense, 'all' => $TotalAll, 'cash' => $cash == '0' ? '0' : $cash, 'online' => $online == '0' ? '0' : $online, 'rest' => $rest];
                }
                return Excel::download(new UsersExport($exp),  $value->month . '.xlsx');

                break;
            case 'd':

                /*  $Bills =  $request->branchSelect == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereDate('created_at', $request->daySelect)->get() :
                    Bill::where(['branch_id' => $request->branchSelect, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereDate('created_at', $request->daySelect)->get();
                foreach ($Bills as $key => $BillValue) {
                    if ($BillValue->Status == 1) {
                        if (array_key_exists($BillValue->id,  $totalIncome) == null) {
                            $totalIncome[$BillValue->id] = $BillValue->total;
                            if ($BillValue->payType == 1) { // get total of cash and Online 
                                $totalIncomeCash[$BillValue->id] = $BillValue->total;
                            } else {
                                $totalIncomeOnline[$BillValue->id] = $BillValue->total;
                            }
                        } else {
                            $totalIncome[$BillValue->id] += $BillValue->total;
                            if ($BillValue->payType == 1) { // get total of cash and Online 
                                $totalIncomeCash[$BillValue->id] += $BillValue->total;
                            } else {
                                $totalIncomeOnline[$BillValue->id] += $BillValue->total;
                            }
                        }
                    }
                }*/
                return back();
                break;
            default:
                $Expense = /* month or year or maybe day */  expense::where([/*'branch_id' => 1*/])->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();

                foreach ($Expense as $keyExp => $value) {
                    $cash = /* Request branch */  Bill::where(['branch_id' => 1, 'Status' => 1,])->whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->whereRelation('Branch', 'shope_id', '=', 1)->sum('total');
                    $online = /* Request branch */ Bill::where(['branch_id' => 1, 'Status' => 1,])->whereBetween('created_at', [$value->month, date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))])
                        ->whereRelation('Branch', 'shope_id', '=', 1)->sum('total');
                    $exp[$keyExp] = ['month' => $value->month, 'cash' => $cash == '0' ? '0' : $cash, 'online' => $online == '0' ? '0' : $online];
                }

                return Excel::download(new UsersExport($exp), 'users.xlsx');
                break;
        }

        }
        return back();
    }
    protected function ItemChart(Request $request)
    {
        // Messages for valid Input 
        $messages = [
            'branchSelect' => 'يجب تحديد الفرع',   // Required
        ];
        $validator = Validator::make($request->all(), [
            'branchSelect' => 'nullable',
        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $year =  $request->yearSelect2;
        $month =  $request->monthSelect2;
        $startOfMonth =  Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $endOfMonth =  Carbon::parse($month)->endOfMonth()->format('Y-m-d');
        $day =  $request->daySelect2;
        $to = $request->toSelect2 == null  ? '' : $request->toSelect2;
        $seqID = $request->seqID2;
        $i = 1;

        $all = [];
        if (auth()->user()->permission_id == 2) {
            $shope = Shope::where('owner_id', auth()->user()->id)->first();

        }else{
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        }
        if (auth()->user()->permission_id == 2) {

        switch ($request->dateSelect2) {
            case 'all':
                $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])
                    ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                    Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])
                    ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();

                foreach ($Bills as $keyExp => $Bill) {
                    $Details = BillDetail::where('Bill_id', $Bill->id)->get();
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
                                        'Name' => $item->Name . ' ' . $item->Big_Name,
                                        'OrignelPrice' => $item->Big_Price, 'count' => $Detail->count
                                    ];
                                }

                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                }

                break;
            case 'y':

                $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])->whereYear('created_at', '=', $year)
                    ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                    Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])->whereYear('created_at', '=', $year)
                    ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();

                foreach ($Bills as $keyExp => $Bill) {
                    $Details = BillDetail::where('Bill_id', $Bill->id)->get();
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

                break;
            case 'm':

                $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                    Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();

                foreach ($Bills as $keyExp => $Bill) {
                    $Details = BillDetail::where('Bill_id', $Bill->id)->get();
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
                                    $all[$item->Name . ' ' . $item->Small_Name] = [
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
                                        'Name' => $item->Name . ' ' . $item->Big_Name,
                                        'OrignelPrice' => $item->Big_Price, 'count' => $Detail->count
                                    ];
                                }

                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                }

                break;
            case 'd':
                if ($seqID == null) {
                    if ($to == '') {
                        $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])->whereDate('created_at', $day)
                            ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                            Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])->whereDate('created_at', $day)
                            ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();
                    } else {
                        $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])->whereBetween('created_at', [$day  . " 00:00:00", $to . " 23:59:59"])
                            ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                            Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])->whereBetween('created_at', [$day . " 00:00:00", $to . " 23:59:59"])
                            ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();
                    }
                } else {
                    $Bills = $request->branchSelect2 == 0 ?    Bill::where(['sequence_id' => $seqID, 'Status' => 1,])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                        Bill::where(['sequence_id' => $seqID, 'Status' => 1, 'branch_id' => $request->branchSelect2])
                        ->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();
                }

                foreach ($Bills as $keyExp => $Bill) {
                    $Details = BillDetail::where('Bill_id', $Bill->id)->get();
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
                                    $all[$item->Name . ' ' . $item->Small_Name] = [
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
                                        'Name' => $item->Name . ' ' . $item->Big_Name,
                                        'OrignelPrice' => $item->Big_Price, 'count' => $Detail->count
                                    ];
                                }

                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                }

                break;
            default:


                break;
        }
    }else {
        switch ($request->dateSelect2) {
            case 'all':
                $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])
                    ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get() :
                    Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])
                    ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get();

                foreach ($Bills as $keyExp => $Bill) {
                    $Details = BillDetail::where('Bill_id', $Bill->id)->get();
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
                                        'Name' => $item->Name . ' ' . $item->Big_Name,
                                        'OrignelPrice' => $item->Big_Price, 'count' => $Detail->count
                                    ];
                                }

                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                }

                break;
            case 'y':

                $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])->whereYear('created_at', '=', $year)
                    ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get() :
                    Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])->whereYear('created_at', '=', $year)
                    ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get();

                foreach ($Bills as $keyExp => $Bill) {
                    $Details = BillDetail::where('Bill_id', $Bill->id)->get();
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

                break;
            case 'm':

                $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get() :
                    Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get();

                foreach ($Bills as $keyExp => $Bill) {
                    $Details = BillDetail::where('Bill_id', $Bill->id)->get();
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
                                    $all[$item->Name . ' ' . $item->Small_Name] = [
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
                                        'Name' => $item->Name . ' ' . $item->Big_Name,
                                        'OrignelPrice' => $item->Big_Price, 'count' => $Detail->count
                                    ];
                                }

                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                }

                break;
            case 'd':
                if ($seqID == null) {
                    if ($to == '') {
                        $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])->whereDate('created_at', $day)
                            ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get() :
                            Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])->whereDate('created_at', $day)
                            ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get();
                    } else {
                        $Bills = $request->branchSelect2 == 0 ?    Bill::where(['Status' => 1,])->whereBetween('created_at', [$day  . " 00:00:00", $to . " 23:59:59"])
                            ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get() :
                            Bill::where(['Status' => 1, 'branch_id' => $request->branchSelect2])->whereBetween('created_at', [$day . " 00:00:00", $to . " 23:59:59"])
                            ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get();
                    }
                } else {
                    $Bills = $request->branchSelect2 == 0 ?    Bill::where(['sequence_id' => $seqID, 'Status' => 1,])
                        ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get() :
                        Bill::where(['sequence_id' => $seqID, 'Status' => 1, 'branch_id' => $request->branchSelect2])
                        ->whereRelation('Branch', 'id', '=', $staff->branch_id)->get();
                }

                foreach ($Bills as $keyExp => $Bill) {
                    $Details = BillDetail::where('Bill_id', $Bill->id)->get();
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
                                    $all[$item->Name . ' ' . $item->Small_Name] = [
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
                                        'Name' => $item->Name . ' ' . $item->Big_Name,
                                        'OrignelPrice' => $item->Big_Price, 'count' => $Detail->count
                                    ];
                                }

                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                }

                break;
            default:


                break;
        }
    }



    if (auth()->user()->permission_id == 2) {
        $owner = Shope::where('owner_id', auth()->user()->id)->first();

        $items =  Item::where(['Shope_id' => $owner->id,  'Status' => 1])->get(); 
    }else {
        $staff = Staff::where('user_id', auth()->user()->id)->first();
        $items =  Item::where(['Shope_id' => $staff->Branch->shope_id,  'Status' => 1])->get(); 

    }
        foreach ($items as $key => $item) {
            $found =   isset($all[$item->Name . ' ' . $item->Small_Name]);
            if (!$found &&  $item->Small_Price != null) {
                $all[$item->Name . ' ' . $item->Small_Name] = [
                    'price' => 0,
                    'id' => $i++,
                    'Name' => $item->Name . ' ' . $item->Small_Name,
                    'OrignelPrice' => $item->Small_Price, 'count' => 0
                ];
            }
            $found =   isset($all[$item->Name . ' ' . $item->Mid_Name]);
            if (!$found && $item->Mid_Price !=  null) {
                $all[$item->Name . ' ' . $item->Mid_Name] = [
                    'price' => 0,
                    'id' => $i++,
                    'Name' => $item->Name . ' ' . $item->Mid_Name,
                    'OrignelPrice' => $item->Mid_Price, 'count' => 0
                ];
            }
            $found =   isset($all[$item->Name . ' ' . $item->Big_Name]);
            if (!$found &&  $item->Big_Price != null) {
                $all[$item->Name . ' ' . $item->Big_Name] = [
                    'price' => 0,
                    'id' => $i++,
                    'Name' => $item->Name . ' ' . $item->Big_Name,
                    'OrignelPrice' => $item->Big_Price, 'count' => 0
                ];
            }
        }
        usort($all, function ($a, $b) {
            return reset($b) <=> reset($a);
        });

        return view('juiceAndResturant.ItemsChart')->with('all', $all);
    }
    // DashBoard Bills 
    protected function BillDashboard(Request $request)
    {
        //  return $request->all();
        // Messages for valid Input 
        try {
      
        $messages = [
            'branchID' => 'يجب تحديد الفرع',   // Required
            'day' => 'يجب تحديد اليوم',   // Required
            'month' => 'يجب تحديد الشهر',   // Required
            'year' => 'يجب تحديد السنه',   // Required
            'seqId' => 'خطأ',   // Required


        ];
        $validator = Validator::make($request->all(), [
            'branchID' => 'nullable',
            'day' => 'nullable',
            'month' => 'nullable',
            'year' => 'nullable',
            'seqId' => 'nullable',
            'to' => 'nullable',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $total = 0;
        if (auth()->user()->permission_id == 2 || auth()->user()->permission_id == 4) {
            $Bills = Bill::get();
            foreach ($Bills as $key => $Bill) {
                if ($Bill->connection_id != null) {
                    $total += $Bill->total;
                    $BillDub = Bill::where(['connection_id' =>  $Bill->connection_id, 'sequence_id' =>  $Bill->sequence_id,])->get();

                    for ($i = 1; $i < count($BillDub); $i++) {
                        Bill::where(['id' =>  $BillDub[$i]->id, 'sequence_id' =>  $BillDub[$i]->sequence_id,])->delete();
                    }
                }
            }
            $allExpense = 0;
            $allIncome  = 0;
            $allIncomeCash  = 0;
            $allIncomeOnline  = 0;
            $Expense = [];
            $totalExpense = [];
            $totalIncome = [];
            $totalIncomeCash = [];
            $totalIncomeOnline = [];
            $branch = 0;
            $to = $request->to == null ? '' : $request->to;
            $fromHour = $request->fromHour == null ? " 00:00:00" : " " . $request->fromHour . ":00";
            $toHour = $request->toHour == null ? " 23:59:59" : " " . $request->toHour . ":59";
            $branchSelect  = $request->branchID;
            $daySelect = $request->day == null ? '' : $request->day;
            $monthSelect = $request->month == null ? '' : $request->month;
            $toSelect = $request->toSelect1 == null ? '' : $request->toSelect1;
            $yearSelect  = $request->year == null ? '' : $request->year;
            $seqID = $request->seqId == null ? null : $request->seqId;
            if (auth()->user()->permission_id == 2) {
                $shope = Shope::where('owner_id', auth()->user()->id)->first();
                $branch = Branch::where('shope_id', $shope->id)->get();

            }else {
                $staff = Staff::where('user_id', auth()->user()->id)->first();
                $branch = Branch::where('id', $staff->branch_id)->get();

            }
            if (auth()->user()->permission_id == 2) {
                if ($request->year != null) {
                    $dateSelect = 'y';
                    $Bills = $request->branchID == 0 ?  Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)
                        ->whereYear('created_at', '=', $request->year)->paginate(5, ['*'], 'Bills') :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)
                        ->whereYear('created_at', '=', $request->year)->paginate(5, ['*'], 'Bills');
                    // exp 
                    $BillExp = $request->branchID == 0 ?  Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)
                        ->whereYear('created_at', '=', $request->year)->get() :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)
                        ->whereYear('created_at', '=', $request->year)->get();
                    $Expense =  $request->branchID == 0 ? expense::whereRelation('Branch', 'shope_id', '=', $shope->id)->whereYear('month', '=', $request->year)->orderBy('month')->paginate(6, ['*'], 'Exp') :
                        expense::where('branch_id', $request->branchID)->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereYear('month', '=', $request->year)->orderBy('month')->paginate(6, ['*'], 'Exp');
                } else if ($request->month != null) {
                    $dateSelect = 'm';
    
                    $Bills =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereMonth('created_at', '=', date('m', strtotime($request->month)))->paginate(100, ['*'], 'Bills') :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereMonth('created_at', '=', date('m', strtotime($request->month)))->paginate(100, ['*'], 'Bills');
                    // exp month 
                    $BillExp =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereMonth('created_at', '=', date('m', strtotime($request->month)))->get() :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereMonth('created_at', '=', date('m', strtotime($request->month)))->get();
                    $Expense =  $request->branchID == 0 ? expense::where(['month' => $request->month . '-01'])->whereRelation('Branch', 'shope_id', '=', $shope->id)->paginate(6, ['*'], 'Exp') :
                        expense::where(['month' => $request->month . '-01', 'branch_id' => $request->branchID])->whereRelation('Branch', 'shope_id', '=', $shope->id)->paginate(6, ['*'], 'Exp');
                } else if ($request->day != null && $seqID == null) {
                    $dateSelect = 'd';
                    if ($to != '') {
                        $Bills =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereBetween('created_at', [$daySelect . ' 00:00:00', $to . ' 23:59:50'])->get() :
                            Bill::where(['branch_id' => $request->branchID,  'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereBetween('created_at', [$daySelect   . ' 00:00:00', $to . ' 23:59:50'])->get();
                    } else {
                        $Bills =  $request->branchID == 0 ? Bill::whereRelation('Branch', 'shope_id', '=', $shope->id)->whereDate('created_at', '==', $request->day)->get() :
                            Bill::where(['branch_id' => $request->branchID,])->whereRelation('Branch', 'shope_id', '=', $shope->id)->whereDate('created_at', '==', $request->day)->get();
                    }
                } else if ($request->day != null && $seqID != null) {
                    $dateSelect = 'd';
    
                    $Bills =  $request->branchID == 0 ? Bill::where(['sequence_id' => $seqID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                        Bill::where(['branch_id' => $request->branchID, 'sequence_id' => $seqID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();
                } else if ($request->BillNo !=  null) {
                    $dateSelect = 'n';
                    $Bills =  $request->branchID == 0 ? Bill::where(['id' => $request->BillNo,])->whereRelation('Branch', 'shope_id', '=', $shope->id)->paginate(5, ['*'], 'Bills') :
                        Bill::where(['id' => $request->BillNo, 'branch_id' => $request->branchID,])->whereRelation('Branch', 'shope_id', '=', $shope->id)->paginate(5, ['*'], 'Bills');
                } else {
                    $dateSelect = 'all';
                    $Bills =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->paginate(5, ['*'], 'Bills') :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->paginate(5, ['*'], 'Bills');
                    $BillExp =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->get() :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'shope_id', '=', $shope->id)->get();
                    $Expense =  $request->branchID == 0 ? expense::whereRelation('Branch', 'shope_id', '=', $shope->id)->paginate(6, ['*'], 'Exp') :
                        expense::where(['branch_id' => $request->branchID])->whereRelation('Branch', 'shope_id', '=', $shope->id)->paginate(6, ['*'], 'Exp');
                }
            } else { // Premission 4 
                if ($request->year != null) {
                    $dateSelect = 'y';
                    $Bills = $request->branchID == 0 ?  Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)
                        ->whereYear('created_at', '=', $request->year)->paginate(5, ['*'], 'Bills') :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)
                        ->whereYear('created_at', '=', $request->year)->paginate(5, ['*'], 'Bills');
                    // exp 
                    $BillExp = $request->branchID == 0 ?  Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)
                        ->whereYear('created_at', '=', $request->year)->get() :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)
                        ->whereYear('created_at', '=', $request->year)->get();
                    $Expense =  $request->branchID == 0 ? expense::whereRelation('Branch', 'id', '=', $staff->branch_id)->whereYear('month', '=', $request->year)->orderBy('month')->paginate(6, ['*'], 'Exp') :
                        expense::where('branch_id', $request->branchID)->whereRelation('Branch', 'id', '=', $staff->branch_id)->whereYear('month', '=', $request->year)->orderBy('month')->paginate(6, ['*'], 'Exp');
                } else if ($request->month != null) {
                    $dateSelect = 'm';
    
                    $Bills =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->whereMonth('created_at', '=', date('m', strtotime($request->month)))->paginate(100, ['*'], 'Bills') :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->whereMonth('created_at', '=', date('m', strtotime($request->month)))->paginate(100, ['*'], 'Bills');
                    // exp month 
                    $BillExp =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->whereMonth('created_at', '=', date('m', strtotime($request->month)))->get() :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->whereMonth('created_at', '=', date('m', strtotime($request->month)))->get();
                    $Expense =  $request->branchID == 0 ? expense::where(['month' => $request->month . '-01'])->whereRelation('Branch', 'id', '=', $staff->branch_id)->paginate(6, ['*'], 'Exp') :
                        expense::where(['month' => $request->month . '-01', 'branch_id' => $request->branchID])->whereRelation('Branch', 'id', '=', $staff->branch_id)->paginate(6, ['*'], 'Exp');
                } else if ($request->day != null && $seqID == null) {
                    $dateSelect = 'd';
                    if ($to != '') {
                        $Bills =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->whereBetween('created_at', [$daySelect . ' 00:00:00', $to . ' 23:59:50'])->get() :
                            Bill::where(['branch_id' => $request->branchID,  'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->whereBetween('created_at', [$daySelect   . ' 00:00:00', $to . ' 23:59:50'])->get();
                    } else {
                        $Bills =  $request->branchID == 0 ? Bill::whereRelation('Branch', 'id', '=', $staff->branch_id)->whereDate('created_at', '==', $request->day)->get() :
                            Bill::where(['branch_id' => $request->branchID,])->whereRelation('Branch', 'id', '=', $staff->branch_id)->whereDate('created_at', '==', $request->day)->get();
                    }
                } else if ($request->day != null && $seqID != null) {
                    $dateSelect = 'd';
    
                    $Bills =  $request->branchID == 0 ? Bill::where(['sequence_id' => $seqID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->get() :
                        Bill::where(['branch_id' => $request->branchID, 'sequence_id' => $seqID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->get();
                } else if ($request->BillNo !=  null) {
                    $dateSelect = 'n';
                    $Bills =  $request->branchID == 0 ? Bill::where(['id' => $request->BillNo,])->whereRelation('Branch', 'id', '=', $staff->branch_id)->paginate(5, ['*'], 'Bills') :
                        Bill::where(['id' => $request->BillNo, 'branch_id' => $request->branchID,])->whereRelation('Branch', 'id', '=', $staff->branch_id)->paginate(5, ['*'], 'Bills');
                } else {
                    $dateSelect = 'all';
                    $Bills =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->paginate(5, ['*'], 'Bills') :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->paginate(5, ['*'], 'Bills');
                    $BillExp =  $request->branchID == 0 ? Bill::where(['Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->get() :
                        Bill::where(['branch_id' => $request->branchID, 'Status' => $request->type == 0  ? 1 : $request->type])->whereRelation('Branch', 'id', '=', $staff->branch_id)->get();
                    $Expense =  $request->branchID == 0 ? expense::whereRelation('Branch', 'id', '=', $staff->branch_id)->paginate(6, ['*'], 'Exp') :
                        expense::where(['branch_id' => $request->branchID])->whereRelation('Branch', 'id', '=', $staff->branch_id)->paginate(6, ['*'], 'Exp');
                }
            }
            
           



            if (count($Bills) == 0) {
                $branchDetails  = false;
                $DayDetails  = true;
                $data = true;
                $waringMeg = 'لا يوجد فواتير بالتاريخ المدخل ';
                $all = [
                    'branch' => $branch, 'branchDetails' => $branchDetails, 'DayDetails' => $DayDetails, 'NoData' => $data,
                    'msg' => $waringMeg,  'dateSelect' => $dateSelect, 'branchSelect' => $branchSelect,
                    'daySelect' => $daySelect, 'monthSelect' => $monthSelect, 'yearSelect' => $yearSelect, 'to' => $to
                ];
            } else {
                $data = false;
                $waringMeg = '';
                if ($request->day  != null || $request->BillNo != null) {
                    $branchDetails  = false;
                    $DayDetails  = true;
                    $day = $request->day;
                    foreach ($Bills as $key => $BillValue) {
                        if ($BillValue->Status == 1 || $BillValue->Status == 4) {
                            if (array_key_exists($BillValue->id,  $totalIncome) == null) {
                                $totalIncome[$BillValue->id] = $BillValue->total;
                                $totalIncomeCash[$BillValue->id] = $BillValue->cash;
                                $totalIncomeOnline[$BillValue->id] = $BillValue->online;
                            } else {
                                $totalIncome[$BillValue->id] += $BillValue->total;
                                $totalIncomeCash[$BillValue->id] += $BillValue->cash;
                                $totalIncomeOnline[$BillValue->id] += $BillValue->online;
                            }
                        }
                    }
                    $allIncome = array_sum($totalIncome);
                    $allIncomeCash = array_sum($totalIncomeCash);
                    $allIncomeOnline = array_sum($totalIncomeOnline);
                } else {
                    $branchDetails  = true;
                    $DayDetails  = false;
                    $day = "";

                    if ($request->branchID == 0 && $request->year == null && $request->month == null) {
                        if (auth()->user()->permission_id == 2) {

                        $Expense = expense::whereRelation('Branch', 'shope_id', '=', $shope->id)->orderBy('month')->paginate(6, ['*'], 'Exp');
                        }else {
                        $Expense = expense::whereRelation('Branch', 'id', '=', $staff->branch_id)->orderBy('month')->paginate(6, ['*'], 'Exp');
                            
                        }
                    }
                    foreach ($Expense as $key => $value) {
                        $totalExpense[$value->id] =   $value->branchRent + $value->electricBill + $value->waterBill + $value->salaryBill + $value->OtherBill;
                        $allExpense += $value->branchRent + $value->electricBill + $value->waterBill + $value->salaryBill + $value->OtherBill;
                        $totalIncome[$value->branch_id][$value->month] = 0;
                        $totalIncomeCash[$value->branch_id][$value->month] = 0;
                        $totalIncomeOnline[$value->branch_id][$value->month] = 0;

                        foreach ($BillExp as $keyBill => $BillValue) {
                            if ($BillValue->Status == 1 ||  $BillValue->Status == 4) {

                                if (
                                    $value->month   <= $BillValue->created_at->format('Y-m-d') &&
                                    $BillValue->created_at->format('Y-m-d') <= date('Y-m-d', strtotime('+1 Months', strtotime($value->month)))
                                    && $value->branch_id == $BillValue->branch_id
                                ) {
                                    if (array_key_exists($value->month, $totalIncome[$value->branch_id])) {
                                        $totalIncome[$value->branch_id][$value->month] +=  $BillValue->total;
                                        $totalIncomeCash[$value->branch_id][$value->month] +=  $BillValue->cash;
                                        $totalIncomeOnline[$value->branch_id][$value->month] +=  $BillValue->online;
                                    } else {
                                        $totalIncome[$value->branch_id][$value->month] = $BillValue->total;
                                        $totalIncomeCash[$value->branch_id][$value->month] = $BillValue->cash;
                                        $totalIncomeOnline[$value->branch_id][$value->month] = $BillValue->online;
                                    }
                                }
                            }
                        }
                        $allIncome += $totalIncome[$value->branch_id][$value->month];
                        $allIncomeCash += $totalIncomeCash[$value->branch_id][$value->month];
                        $allIncomeOnline += $totalIncomeOnline[$value->branch_id][$value->month];
                    }
                }

                // Details for each single Bill 
                $extraToppings = [];
                foreach ($Bills as $key => $value) {
                    $BillDetails[] = BillDetail::where('Bill_id', $value->id)->get();
                    // for Extra toppings 
                    if (count($BillDetails[0]) > 0) {
                        foreach ($BillDetails[0] as $key => $Detail) {
                            $extraToppings[] = Bill_Extra_Topping::where('Bill_details_id', $Detail['id'])->get();
                        }
                    }
                }
                // return $extraToppings;
                $all = [
                    'Bills' => $Bills, 'Details' => $BillDetails,
                    'expense' => $Expense, 'totalExpense' => $totalExpense, 'totalIncome' => $totalIncome,
                    'totalIncomeCash' => $totalIncomeCash, 'totalIncomeOnline' => $totalIncomeOnline,
                    'allIncomeCash' => $allIncomeCash, 'allIncomeOnline' => $allIncomeOnline,
                    'branch' => $branch, 'allIncome' => $allIncome, 'allExpense' => $allExpense,
                    'branchDetails' => $branchDetails, 'DayDetails' => $DayDetails, 'day' => $day,
                    'NoData' => $data, 'msg' => $waringMeg,  'dateSelect' => $dateSelect, 'branchSelect' => $branchSelect,
                    'daySelect' => $daySelect, 'monthSelect' => $monthSelect, 'yearSelect' => $yearSelect, 'seqID' => $seqID, 'to' => $to,
                    'extraToppings' => $extraToppings
                ];
            }
           
            return view('juiceAndResturant.BillDashboard')->with('all', $all);
        } else {
            return back();
        }
              //code...
            } catch (\Throwable $th) {
                alert()->warning('حدث خطاء ' ,'الرجاء التاكد من البيانات المدخلة ');

                return back();
            }
    }

    protected function AdvSearchFollowTheFund(Request $request)
    {
        $messages = [

            'day.date' => 'يجب تحديد التاريخ    ',   // Required
            'branch.numeric' => 'يجب تحديد الفرع   ',   // Required

        ];
        $validator = Validator::make($request->all(), [
            'day' => 'date ',
            'branch' => 'numeric ',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        if ($request->branch == 'ىعمم') {
            Alert::error('خطأ ',  'يجب تحديد الفرع من القائمة  ');

            return back();
        }

        if (auth()->user()->permission_id == 2) {

        $owner = Shope::where('owner_id', auth()->user()->id)->first();

        $branch = Branch::where('shope_id', $owner->id)->get();
        $day =  $request->day == null ?  Carbon::now()->format('Y-m-d') : $request->day;
        $selectedbranch = $request->branch == '0' ? null : $request->branch;
        $Box = $selectedbranch == null ?  SequenceBill::where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), $day)
            ->whereHas('Branch', function ($q) {
                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->paginate(100, ['*'], 'Box') :
            SequenceBill::where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), $day)
            ->where('branch_id', $selectedbranch) //    ->whereNull('branch_id', $branch[0]->id)
            ->whereHas('Branch', function ($q) {
                $owner = Shope::where('owner_id', auth()->user()->id)->first();
                $q->where('shope_id', $owner->id);
            })->paginate(100, ['*'], 'Box');
        }else  if (auth()->user()->permission_id == 4) {
            $staff = Staff::where('user_id', auth()->user()->id)->first();
            $branch = Branch::where('id', $staff->branch_id)->get();
            $day =  $request->day == null ?  Carbon::now()->format('Y-m-d') : $request->day;
            $selectedbranch = $request->branch == '0' ? null : $request->branch;
            $Box = $selectedbranch == null ?  SequenceBill::where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), $day)
                ->whereHas('Branch', function ($q) use($staff) {
                    $q->where('id', $staff->branch_id);
                })->paginate(100, ['*'], 'Box') :
                SequenceBill::where(DB::raw("DATE_FORMAT(Start_Date, '%Y-%m-%d')"), $day)
                ->where('branch_id', $selectedbranch) //    ->whereNull('branch_id', $branch[0]->id)
                ->whereHas('Branch', function ($q) use($staff) {
                    $q->where('shope_id', $staff->branch_id);
                })->paginate(100, ['*'], 'Box');
        }
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
        $all = [
            'branch' => $branch,  'Box' => $Box,
            'Incoming' => $Incoming, 'day' => $day, 'selectedbranch' => $selectedbranch
        ];
        //  return $all;
        return view('juiceAndResturant.AdvSearchFollowTheFund')->with('all', $all);
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
        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        //if ($phoneNo == null) {
        //    $bills  = Bill::groupBy('CustomerPhone', 'CustomerName')->select('CustomerPhone', 'CustomerName', DB::raw('count(*) as total'))->whereRelation('Branch', 'shope_id', '=', $owner->id)->where('CustomerPhone', '!=', null)->get();
        //    $info  = Bill::where(['CustomerPhone' => $phoneNo])->whereRelation('Branch', 'shope_id', '=', $owner->id)->orderBy('created_at', 'desc')->first();
        //} else {
        //    $bills  = Bill::groupBy('CustomerPhone', 'CustomerName')->select('CustomerPhone', 'CustomerName', DB::raw('count(*) as total'))->whereRelation('Branch', 'shope_id', '=', $owner->id)->where(['CustomerPhone' => $phoneNo,])->where('CustomerPhone', '!=', null)->get();
        //    $info  = Bill::where(['CustomerPhone' => $phoneNo])->whereRelation('Branch', 'shope_id', '=', $owner->id)->orderBy('created_at', 'desc')->first();
        //}
        if ($phoneNo == null) {
            $bills  = Bill::groupBy('CustomerPhone', 'CustomerName')->select('CustomerPhone', 'CustomerName', DB::raw('count(*) as total'))->whereRelation('Branch', 'shope_id', '=', $owner->id)->where('CustomerPhone', '!=', null)->get();
            foreach ($bills as $key => $bill) {
                if ($bill->CustomerName == null) {
                    $info = Bill::where('CustomerPhone', $bill->CustomerPhone)->orderBy('created_at', 'desc')->first();
                } else {
                    $info = Bill::where('CustomerName', $bill->CustomerName)->orderBy('created_at', 'desc')->first();
                }
                $restInfo['Info'][] = ['phone' => $bill->CustomerPhone, 'name' => $info->CustomerName, 'count' => $bill->total, 'created_at' => $info->created_at];
            }
        } else {
            $bills  = Bill::groupBy('CustomerPhone', 'CustomerName')
                ->select('CustomerPhone', 'CustomerName', DB::raw('count(*) as total'))
                ->whereRelation('Branch', 'shope_id', '=', $owner->id)->where('CustomerPhone', $phoneNo)->get();
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
        //  return $all['count'];


        return view('juiceAndResturant.Customers')->with('all', $all);
    }

    protected function DashboardHr(){

        $now = Carbon::now();
        if (auth()->user()->permission_id == 2) {
            $owner = Shope::where('owner_id', auth()->user()->id)->first();

        $attends = attend::whereHas('Staff', function ($q) use ($owner) {
            $q->whereHas('Branch', function ($p) use ($owner) {
                $p->whereHas('Shope', function ($w) use ($owner) {
                    $w->where('shope_id', $owner->id);
                });
            });
            })
            ->whereDate('created_at', $now)->get();
            $nowF = date('Y-m-d');
            $vacation = vacation::whereHas('Staff', function ($q) use ($owner)  {
                $q->whereHas('Branch', function ($p) use ($owner)  {
                    $p->whereHas('Shope', function ($w) use ($owner)  {
                        $w->where('shope_id',$owner->id);
                    });
                });
            })->where('Status',3)->whereDate('End_Date', '>=', $nowF)->get(); // Active Vaction 3 
    
        } else  if (auth()->user()->permission_id == 4){ // Premission 4 !!
            $staff = Staff::where('user_id', auth()->user()->id)->first();

            $attends = attend::whereHas('Staff', function ($q) use ($staff)  {
                $q->whereHas('Branch', function ($p) use ($staff)  {
                    $p->where('id',$staff->branch_id);
                });
        })
        ->whereDate('created_at', $now)->get();
        $nowF = date('Y-m-d');
        $vacation = vacation::whereHas('Staff', function ($q) use ($staff)  {
            $q->whereHas('Branch', function ($p) use ($staff)  {
                $p->where('id', $staff->branch_id); 
            });
        })->where('Status',3)->whereDate('End_Date', '>=', $nowF)->get(); // Active Vaction 3 
        
        }else {
            return redirect()->route('Home');
  
        }
        
      
    $all = ['attends'=>$attends , 'vacation' => $vacation ];
        return view('juiceAndResturant.DashboardHr')->with('all', $all);
    }
    protected function AttendanceFollowup(Request $request)
    {
        //return $request->all();
        $messages = [

            'branch.nullable' => 'يوجد خطاء ',
            'branch.integer' => 'يوجد خطاء ',
            'date.nullable' => 'يوجد خطاء ',
            'date.date' => 'يوجد خطاء ',

        ];
        $validator = Validator::make($request->all(), [
            'branch' => 'nullable|integer',
            'date' => 'nullable|date',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $now = Carbon::now();
        $date = $request->date == null ? $now : $request->date ; 
        $branch = $request->branch == 0 ? null : $request->branch ; 
        if (auth()->user()->permission_id == 2) {
            $owner = Shope::where('owner_id', auth()->user()->id)->first();
       
            $attends = attend::whereHas('Staff', function ($q) use ($owner , $branch)  {
                $q->whereHas('Branch', function ($p) use ($owner, $branch)  {
                    if ($branch != null) {
                        $p->where('id', $branch);
                      }
                    $p->whereHas('Shope', function ($w) use ($owner, $branch)  {

                        $w->where('shope_id',$owner->id);
                    });
                });
            })->whereDate('created_at', $date)->get();  
            $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();
    
        } else if (auth()->user()->permission_id == 4) { // staff 4 Manager

            $staff = Staff::where('user_id', auth()->user()->id)->first();

       
            $attends = attend::whereHas('Staff', function ($q) use ($staff , $branch)  {
                $q->whereHas('Branch', function ($p) use ($staff, $branch)  {
                    if ($branch != null) {
                        $p->where('id', $branch);
                      }
                    $p->whereHas('Shope', function ($w) use ($staff, $branch)  {
                        
                        $w->where('shope_id',$staff->Branch->shope_id);
                    });
                });
            })->whereDate('created_at', $date)->get();  
            $branchs  = Branch::where(['id' => $staff->branch_id, 'Status' => 1])->select('id', 'address')->get();
    
        }else {
            redirect()->route('Home');
        }
        
       
        $all = ['attends'=>$attends , 'branchs' =>$branchs];
        
        return view('juiceAndResturant.AttendanceFollowup')->with('all',$all);
    }
    protected function VacationRequests(Request $request)
    {

        $now = Carbon::now();
        $date = $request->date == null ? $now : $request->date ; 
        $branch = $request->branch == 0 ? null : $request->branch ; 
        if (auth()->user()->permission_id == 2) {
            $owner = Shope::where('owner_id', auth()->user()->id)->first();

            $vacation = vacation::whereHas('Staff', function ($q) use ($owner)  {
                $q->whereHas('Branch', function ($p) use ($owner)  {
                    $p->whereHas('Shope', function ($w) use ($owner)  {
                        $w->where('shope_id',$owner->id);
                    });
                });
            })->where('Status',1)->get(); // Active Vaction 3 

        
            if ( $date  ==  $now) {
                $oldvacation = vacation::whereHas('Staff', function ($q) use ($owner,  $branch)  {
                    $q->whereHas('Branch', function ($p) use ($owner,  $branch)  {
                        if ($branch != null) {
                            $p->where('id', $branch);
                          }
                        $p->whereHas('Shope', function ($w) use ($owner)  {
                            $w->where('shope_id',$owner->id);
                        });
                    });
                })->where('Status','!=',1)->get(); // Active Vaction 3 
            }else {
                $oldvacation = vacation::whereHas('Staff', function ($q) use ($owner,  $branch)  {
                    $q->whereHas('Branch', function ($p) use ($owner,  $branch)  {
                        if ($branch != null) {
                            $p->where('id', $branch);
                          }
                        $p->whereHas('Shope', function ($w) use ($owner)  {
                            $w->where('shope_id',$owner->id);
                        });
                    });
                })->where('Status','!=',1)->whereDate('Start_Date', $date)->get(); // Active Vaction 3 
            }
            $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();

     }else  if (auth()->user()->permission_id == 4) { // staff 4 Manager !! 
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $vacation = vacation::whereHas('Staff', function ($q) use ($staff)  {
            $q->where('branch_id',$staff->branch_id );
            
        })->where('Status',1)->get(); // Active Vaction 3 

      
        if ( $date  ==  $now) {
            $oldvacation = vacation::whereHas('Staff', function ($q) use ($staff,  $branch)  {
                $q->whereHas('Branch', function ($p) use ($staff,  $branch)  {
                        $p->where('id', $staff->branch_id);
                });
            })->where('Status','!=',1)->get(); // Active Vaction 3 
        }else {
            $oldvacation = vacation::whereHas('Staff', function ($q) use ($staff,  $branch)  {
                $q->whereHas('Branch', function ($p) use ($staff,  $branch)  {
                    $p->where('id', $staff->branch_id);
                });
             })->where('Status','!=',1)->whereDate('Start_Date', $date)->get(); // Active Vaction 3 
            }
            $branchs  = Branch::where(['id' => $staff->branch_id, 'Status' => 1])->select('id', 'address')->get();

    }else {
        redirect()->route('Home');

    }
    
        $all = ['vacation'=>$vacation , 'oldvacation' => $oldvacation , 'branchs' =>$branchs ];

        return view('juiceAndResturant.VacationRequests')->with('all', $all);
    }
    protected function vacationAccepted(Request $request)
    {
        $messages = [

            'id.required' => 'يوجد خطاء ',

        ];
        $validator = Validator::make($request->all(), [
            'id' => 'required',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $vacation = vacation::where(['id' => $request->id, 'Status' => 1])->update([
            'Status' => 3
        ]);
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $notificationOld = notification::where('vacation_id', $request->id)->first();
        $notification = new notification();
        $notification->staff_id = $staff->id;
        $notification->to_staff_id = $notificationOld->staff_id;
        $notification->resend_id =  $notificationOld->id;
        $notification->type_id     = 17;
        $notification->notes = "مقبول";
        $notification->Status = 3;
        $notification->save();
        notification::where('vacation_id', $request->id)->update([
            'resend_id' => $notification->id
        ]);
        Alert::success('تم قبول الاجازة  ');

        return back();
    }
    protected function vacationRejected(Request $request)
    {
        $messages = [

            'idD.required' => 'يوجد خطاء ',

        ];
        $validator = Validator::make($request->all(), [
            'idD' => 'required',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $vacation = vacation::where(['id' => $request->idD, 'Status' => 1])->update([
            'Status' => 2
        ]);
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $notificationOld = notification::where('vacation_id', $request->idD)->first();
        $notification = new notification();
        $notification->staff_id = $staff->id;
        $notification->to_staff_id = $notificationOld->staff_id;
        $notification->resend_id =  $notificationOld->id;
        $notification->type_id     = 17;
        $notification->notes = "مرفوض";
        $notification->Status = 3;
        $notification->save();
        notification::where('vacation_id', $request->idD)->update([
            'resend_id' => $notification->id
        ]);
        Alert::success('تم رفض الطلب   ');

        return back();
    }
    protected function CreateDocument()
    {
        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $vouchers = voucher::where('Shope_id', $owner->id)->get();

        return view('juiceAndResturant.CreateDocument')->with('vouchers', $vouchers);
    }
    protected function CreateBillDocument(Request $request)
    {
        $messages = [

            'Phone.nullable' => 'يجب تحديد  رقم فاتورة',
            'Phone.numeric' => 'رقم الهاتف يجب ان يتكون من ارقام فقط ',

        ];
        $validator = Validator::make($request->all(), [
            'type' => 'nullable',
            'name' => 'nullable',
            'price' => 'nullable',
            'for' => 'nullable',
            'paymentType' => 'nullable',
            'checkNo' => 'nullable',
            'bank' => 'nullable',
            'date' => 'nullable',
            'dateCheck' => 'nullable',

        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }


        $owner = Shope::where('owner_id', auth()->user()->id)->first();

        $voucher = new voucher();
        $voucher->type_voucher = $request->type;
        $voucher->Shope_id = $owner->id;
        $voucher->SirName = $request->name == null ? $request->nameUp : $request->name;
        $voucher->CT = $request->CTno;
        $voucher->BillNo = $request->BillNo;
        $voucher->user_ID = $request->userID;
        $voucher->city = $request->city;
        $voucher->nameCT = $request->CT;
        $voucher->price = $request->price == null ? $request->priceUp : $request->price;
        $voucher->for = $request->for;
        $voucher->type = $request->paymentType;
        $voucher->checkNo = $request->checkNo;
        $voucher->Bank = $request->bank == null ? $request->bankUp : $request->bank;
        $voucher->Date = $request->date == null ? $request->dateUp : $request->date;
        $voucher->Date_second = $request->dateCheck;
        $voucher->save();
        if ($request->type == 3) {


            $all = ['voucher' => $voucher];

            return view('juiceAndResturant.OrderPDF')->with('all', $all);
        }
        return view('juiceAndResturant.ReceiptPaymentPDF')->with('voucher', $voucher);
    }
    protected function ReceiptPaymentPDF(Request $request)
    {
        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $voucher = voucher::where(['Shope_id' => $owner->id, 'id' => $request->id])->first();
        if ($voucher->type_voucher == 3) {


            $all = ['voucher' => $voucher];

            return view('juiceAndResturant.OrderPDF')->with('all', $all);
        }

        return view('juiceAndResturant.ReceiptPaymentPDF')->with('voucher', $voucher);
    }
    protected function OrderNotePDF(Request $request)
    {

        $DateC = Carbon::now();
        $Date = Carbon::now()->format('Y-m-d');
        $three_days_later = $DateC->addDays(3)->format('Y-m-d');
        $all = ['Date' => $Date, 'hijri' => $hijriDate, 'three_days_later' => $three_days_later];
        return view('juiceAndResturant.OrderPDF')->with('all', $all);
    }
    protected function CasherBoardTransfer()
    {

        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $city = Item::where(['Shope_id' => $staff->Branch->shope_id, 'categories_id' => 17, 'Status' => 1])->get();
        $citys = [];
        foreach ($city as $item) {
            $citys[] = $item->Name;
        }
        $owner = Shope::where('owner_id', auth()->user()->id)->first();

        $TransItem = billTrans::whereHas('bill', function ($q) use ($owner) {
            $q->whereHas('Branch', function ($p) use ($owner) {
                $p->where('shope_id', $owner->id);
            });
        })->get();
        $TransItems = [];
        foreach ($TransItem as $key => $item) {
            $TransItems[] = $item->item;
        }
        $TransItems = array_values(array_unique($TransItems));
        $all = ['city' => $citys, 'TransItems' => $TransItems];
        return view('juiceAndResturant.CasherBoardTransfer')->with('all', $all);
    }
    protected function CasherBoardTransferCreate(Request $request)
    {
        $messages = [

            'city1.required' => 'يجب تحديد  مدينة العميل ',
            'item.*.required' => 'يجب تحديد  المنتج ',
            'city2.*.required' => 'يجب تحديد  المدينة (من مدينة)',
            'Tocity.*.required' => 'يجب تحديد  المدينة (الى مدينة)',
            'price.*.required' => 'يجب تحديد  قيمة الطن (السعر)',
            'qty.*.required' => 'يجب تحديد  كمية الطن  ',
            'total.*.required' => 'يجب تحديد الاجمالي ',

        ];
        $validator = Validator::make($request->all(), [
            'city1' => 'required',
            'item.*' => 'required',
            'city2.*' => 'required',
            'Tocity.*' => 'required',
            'price.*' => 'required',
            'qty.*' => 'required',
            'total.*' => 'required',


        ], $messages);

        if ($validator->fails()) {
            Alert::error('خطأ ', $validator->messages()->all());

            return back();
        }
        $staff = Staff::where('user_id', auth()->user()->id)->first();

        $seq =    SequenceBill::where(['branch_id' => $staff->branch_id, 'staff_id' => $staff->id,  'End_Date' => null, 'Status' => 1])
            ->orderBy('created_at', 'DESC')->first();
        $city = Item::where(['Status' => 1, 'Name' => $request->city1])->first();
        $tax =   $request->totalFinal * 0.15;
        $final = $request->totalFinal + $tax;
        $Bill = new Bill();
        $Bill->staff_id = $staff->id;
        $Bill->sequence_id =  $seq->id;
        $Bill->branch_id = $staff->branch_id;
        $Bill->total = $final;
        $Bill->Tax = $tax;
        $Bill->cash =   $final;
        $Bill->CustomerName =  $request->name;
        $Bill->CustomerPhone =  $request->phone;
        $Bill->CT =  $request->ct;
        $Bill->address =   $city->Name . ' - حي ' . $request->address . ' - طريق ' .  $request->street . ' - الرمز البريدي ' . $request->zipcode;
        $Bill->street =  $request->street;
        $Bill->CustomerType = 1; // $request->Ctype;
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
        $oldItem = [];
        $ii = 0;
        $detailTailor = collect($request->details)->sortBy(0)->values()->all();
        usort($detailTailor, function ($a, $b) {
            return $a[0] - $b[0];
        });
        for ($i = 0; $i < count($request->item); $i++) {

            $cityS = Item::where(['Shope_id' => $staff->Branch->shope_id, 'categories_id' => 17, 'Status' => 1, 'Name' => $request->city2[$ii]])->first();
            $tocityS = Item::where(['Shope_id' => $staff->Branch->shope_id, 'categories_id' => 17, 'Status' => 1, 'Name' => $request->Tocity[$ii]])->first();


            $billTrans = new billTrans();
            $billTrans->Bill_id = $Bill->id;
            $billTrans->item = $request->item[$ii];
            $billTrans->city_id =   $cityS->id;
            $billTrans->to_city_id =   $tocityS->id;
            $billTrans->code =  $request->code[$ii];
            $billTrans->price =  $request->price[$ii];
            $billTrans->quantity =  $request->qty[$ii];
            $billTrans->total =  $request->total[$ii];
            $billTrans->save();

            $ii++;
        }
        $billTrans  =  billTrans::where('Bill_id', $Bill->id)->get();
        $qr = Zatca::sellerName('مؤسسة طاوي البعد للنقل البري ')
            ->vatRegistrationNumber(310382716200003)
            ->timestamp($Bill->created_at)
            ->totalWithVat($final)
            ->vatTotal($tax)
            ->toBase64();
        $all = ['Bill' => $Bill, 'billTrans' => $billTrans,  'qr' => $qr];
        return view('juiceAndResturant.billPDFTrans')->with('all', $all);
    }
    protected function ReportForStore(Request $request)
    {
        //return $request->all();
        $owner = Shope::where('owner_id', auth()->user()->id)->first();
        $summary = [] ;
        $branchs  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();
        $from = $request->from == null ? '2023-01-01' : $request->from ;
        $to = $request->to == null ? '2023-12-31' : $request->to ;
        $branchID    =  'الكل' ;
        if ($request->all() == null) {
            $SelectBranch  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();

            $Store =  Store::whereHas('Branch' ,function ($q) use($owner) {
                $q->where('shope_id', $owner->id);
            } )->get();
            foreach ($Store as $key => $item) {
                $followUps = storeFollowup::where('store_id',$item->id)->get();
               
                if ($followUps->count() > 0 ) {
                  
                    $name = '';
                    $val = 0 ;
                    $branch = $item->branch_id ;
                foreach ($followUps as $key => $followUp) {
                    $name = $item->Name ; 
                    $val += $followUp->value;
                }
                $summary []= ['name'=> $name , 'val'=>$val , 'branch'=>$branch ];
            }
            } 
        }else {
      
            if ($request->branchID == "0" || $request->branchID == null ) {
                $SelectBranch  = Branch::where(['shope_id' => $owner->id, 'Status' => 1])->select('id', 'address')->get();
                $Store =  Store::whereHas('Branch' ,function ($q) use($owner) {
                    $q->where('shope_id', $owner->id);
                } )->whereHas('followUp', function ($p) use($from , $to){
                    $p->whereBetween('created_at', [$from , $to ]);
                })->get();
                //return $Store ;
            }else {
                $SelectBranch  = Branch::where(['shope_id' => $owner->id,'id'=> $request->branchID , 'Status' => 1])->select('id', 'address')->get();
                $branchID    = $SelectBranch[0]->address ;
               
                $Store =  Store::whereHas('Branch' ,function ($q) use($owner,$request ) {
                    $q->where('shope_id', $owner->id);
                    $q->where('id', $request->branchID);
                } )->whereHas('followUp', function ($p) use($from , $to){
                    $p->whereBetween('created_at', [$from , $to ]);
                })->get();
            }
           
            foreach ($Store as $key => $item) {
                $followUps = storeFollowup::where('store_id',$item->id)->get();
               
                if ($followUps->count() > 0 ) {
                  
                    $name = '';
                    $val = 0 ;
                    $branch = $item->branch_id ;
                foreach ($followUps as $key => $followUp) {
                    $name = $item->Name ; 
                    $val += $followUp->value;
                }
                $summary []= ['name'=> $name , 'val'=>$val , 'branch'=>$branch ];
            }
            } 
        }
        

     
            $all = ['branchs'=>$branchs ,'Store'=>$Store , 'summary'=>$summary ,
                    'SelectBranch'=>$SelectBranch , 'from'=>$from , 'to'=>$to , 'branchID'=>$branchID];
        return view('juiceAndResturant.ReportForStore')->with('all', $all);
    }
}
