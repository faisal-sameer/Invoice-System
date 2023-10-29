@extends('layouts.app')


@section('navbar')
    <ul id="ulPhone" class="navbar-nav m-sm-3">
        <li class="nav-item">
            <img src="/image/list.png" onclick="OpenMenu()" class="shake-lr" id="menuImage" alt="">

        </li>
        <div id="mySidenav" class="sidenav">
            <span href="" class="closebtn" onclick="closeMenu()">&times;</span>
            <li class="nav-item">
                <a class="nav-link" id="listNav/BillDashboard" href="{{ route('Home') }}">
                    <lord-icon src="https://cdn.lordicon.com/hjbsbdhw.json" trigger="hover" style="width:47px;height:47px">
                    </lord-icon>
                </a>
            </li>
            @if (Auth::user()->permission_id == 2)
                <li class="nav-item">
                    <a class="nav-link" id="listNav/BillDashboard"
                        href="{{ route('BillDashboard') }}">{{ __('الفواتير') }}</a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link" id="listNav/CasherBoard" href="{{ route('CasherBoard') }}">{{ __('فاتورة جديدة') }}</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="listNav/PendingBills"
                    href="{{ route('PendingBills') }}">{{ __('الفواتير المعلقة') }}</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('Call') }}">
                    <lord-icon src={{ asset('css/hcndxtmn.json') }} trigger="hover" colors="primary:#ffffff"
                        style="width:47px;height:47px">
                    </lord-icon>
                </a>
            </li>
            <li class="nav-item">

                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <img src="image/logout.png" alt="تسجيل الخروج" title="تسجيل الخروج" width="35">
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </div>
    </ul>
@endsection
@section('content')

    <div class="containers" style="direction: rtl" id="about">

        <h2 id="subtitle">
        </h2>
        <div class="row">
            <div class="col-md">
                <button id="myBtnBillToday" onclick="onOpenModelBillToday();" class="ButtonBillToday">فواتير اليوم</button>

            </div>
            <div class="col-sm">
                <div class="row">

                    <div class="col-sm">
                        <h3 style="text-align: left">الموظف: </h3>
                    </div>
                    <div class="col-sm">
                        @foreach ($users as $user)
                        {{ $user->user->name }}
                    @endforeach
                    

                       
                    </div>
                </div>

            </div>
            <div class="col-sm">
                <h3 id="showDate"><?php echo Date('Y-m-d', time()); ?></h3>
            </div>


            @if (!$all['Sequence'])
                <div class="col-md">
                    <label class="switch">
                        <input id="inputSwitch" type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>

                    <p id="main_content"> </p>

                </div>
            @else
                <div class="col-md">
                    <label class="switch">
                        <input id="inputSwitch" type="checkbox">
                        <span class="slider round"></span>
                    </label>

                    <p id="main_content"> </p>

                </div>
            @endif





        </div>
        @if (!$all['Sequence'])
            <div id="OpenDay" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <form method="POST" action="{{ route('openDay') }}">
                        @csrf
                        <div class="modal-header">

                            <h2 class="exit" onclick="CloseDay()">&times;
                            </h2>
                            هل تريد فتح اليوم ؟

                            <button style="background-color: transparent" type="submit">
                                <span class="checkmark">
                                    <div class="checkmark_circle"></div>
                                    <div class="checkmark_stem"></div>
                                    <div class="checkmark_kick"></div>
                                </span>
                            </button>
                        </div>

                        <div class="model-body">
                            <div class="row">

                                <div class="col-md">
                                    <input type="number" id="Custody" name="Custody" placeholder=" المبلغ في العهدة">
                                </div>


                                @if ($all['staffInventory'])
                                    <input type="text" name="Inventory" value="1" hidden readonly>

                                    <section style="margin-top: 2 %;  margin-right: 0.1%" class="row">
                                        <table style="text-align: center">
                                            <thead>
                                                <tr>
                                                    <th scope="col">اسم العنصر</th>
                                                    <th scope="col">في المخزن القيمة الحالية - الحبه </th>
                                                    <th scope="col">في المخزن القيمة الحالية - مجموع الوحدة </th>
                                                    <th scope="col">في المخزن القيمة الحالية - قيمة الوحدة في الحبة
                                                        الواحده
                                                    </th>
                                                    <th scope="col">المستخدم في اليوم - الوحده </th>
                                                    <th scope="col">في المخزن القيمة الجديده - الحبه </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($all['stores'] as $store)
                                                    <tr>
                                                        <td>{{ $store->Name }}</td>

                                                        <td>{{ $store->count }} </td>

                                                        <td>{{ $store->restValue }}</td>

                                                        <td>{{ $store->value }} </td>

                                                        <td>0</td>

                                                        <td>
                                                            <input type="text" hidden readonly
                                                                value="{{ $store->id }}" id="ClosetodyInvntory"
                                                                name="IdStore[]">
                                                            <input type="number" id="ClosetodyInvntory"
                                                                max="{{ $store->count }}" value="{{ $store->count }}"
                                                                min="0" name="newValue[]"
                                                                placeholder="ادخل القيمة " step="0.1">
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>
                                    </section>
                                @endif

                            </div>
                            <table id="keypad"
                                style="direction: ltr;  ;  margin-right: 1%;
                                width: 98%; "
                                cellpadding="10" cellspacing="3">
                                <tr>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('1');">1</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('2');">2</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('3');">3</td>
                                </tr>
                                <tr>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('4');">4</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('5');">5</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('6');">6</td>
                                </tr>
                                <tr>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('7');">7</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('8');">8</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('9');">9</td>
                                </tr>
                                <tr>
                                    <td id="texttab"
                                        style="color: white ; text-align: center ;background-color: rgb(255, 255, 255) ;font-size: xx-large;">
                                    </td>
                                    <td style="color: white ; text-align: center ;background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeCustody('0');">0</td>
                                    <td style="color: white ; text-align: center ; background-color: rgb(255, 71, 71) ; font-size: x-large;"
                                        id="texttab" onclick="addCodeCustody('del');">مسح
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>


                </div>
            </div>
        @endif

        @if ($all['Sequence'])
            <form method="POST" id="FormEnd" action="{{ route('EndDay') }}">
                @csrf
                <div id="myModalinventory" class="modal">

                    <!-- Modal content -->
                    <input type="text" name="autoClose" id="autoClose" hidden readonly value="0">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h2 class="exit" onclick="inventoryClose()">&times;
                            </h2>
                            الجرد

                            <button class="btn btn-light" type="button" onclick="CheckCloseDay();">حفظ</button>

                        </div>

                        <div class="row">
                            <div class="col-md">
                                <input type="number" id="EndCustody" name="EndCustody"
                                    placeholder=" المبلغ في الصندوق">
                            </div>
                            <table id="keypad"
                                style="direction: ltr;  ;  margin-right: 1%;
                            width: 98%; "
                                cellpadding="10" cellspacing="3">
                                <tr>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('1');">1</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('2');">2</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('3');">3</td>
                                </tr>
                                <tr>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('4');">4</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('5');">5</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('6');">6</td>
                                </tr>
                                <tr>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('7');">7</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('8');">8</td>
                                    <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('9');">9</td>
                                </tr>
                                <tr>
                                    <td id="texttab"
                                        style="color: white ; text-align: center ;background-color: rgb(255, 255, 255) ;font-size: xx-large;">
                                    </td>
                                    <td style="color: white ; text-align: center ;background-color: grey ;font-size: xx-large;"
                                        id="texttab" onclick="addCodeEndCustody('0');">0</td>
                                    <td style="color: white ; text-align: center ; background-color: rgb(255, 71, 71) ; font-size: x-large;"
                                        id="texttab" onclick="addCodeEndCustody('del');">مسح
                                    </td>
                                </tr>
                            </table>
                            @if ($all['staffInventory'])
                                <input type="text" name="Inventory" value="1" hidden readonly>

                                <section style="margin-top: 2 %;  margin-right: 0.1%" class="row">
                                    <table style="text-align: center">
                                        <thead>
                                            <tr>
                                                <th scope="col">اسم العنصر</th>
                                                <th scope="col">في المخزن القيمة الحالية - الحبه </th>
                                                <th scope="col">في المخزن القيمة الحالية - مجموع الوحدة </th>
                                                <th scope="col">في المخزن القيمة الحالية - قيمة الوحدة في الحبة الواحده
                                                </th>
                                                <th scope="col">المستخدم في اليوم - الوحده </th>
                                                <th scope="col">في المخزن القيمة الجديده - الحبه </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($all['stores'] as $store)
                                                <tr>
                                                    <td>{{ $store->Name }}</td>

                                                    <td>{{ $store->count }} </td>

                                                    <td>{{ $store->restValue }}</td>

                                                    <td>{{ $store->value }} </td>

                                                    <td>
                                                        @isset($all['UsedItems'][$store->id])
                                                            {{ $all['UsedItems'][$store->id] }}
                                                        @endisset

                                                    </td>

                                                    <td>
                                                        <input type="text" hidden readonly value="{{ $store->id }}"
                                                            id="ClosetodyInvntory" name="IdStore[]">
                                                        <input type="number" id="ClosetodyInvntory"
                                                            max="{{ $store->count }}" value="{{ $store->count }}"
                                                            min="0" name="newValue[]" placeholder="ادخل القيمة "
                                                            step="any">
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>

                                    </table>
                                </section>
                            @else
                                <input type="text" name="Inventory" value="0" hidden readonly>
                            @endif

                        </div>
                    </div>
                </div>
                <div id="CheckCloseDay" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content" style="width: 40%">
                        <div class="modal-header">

                            <h2 class="exit" onclick="closeCheckCloseDay()">&times;
                            </h2>
                            هل انت متأكد من اغلاق اليوم
                            <p></p>
                        </div>
                        <div class="model-body">
                            <div class="row" style="margin-right: 20%">

                                <div class="col-lg-4 mb-4">
                                    <strong>العهده في بداية اليوم:</strong>
                                    <p>{{ $all['Custody'] }} ريال</p>
                                </div>

                                <div class="col-lg-4 mb-4">
                                    <strong>العهده في نهاية اليوم:</strong>
                                    <p id="restCustody"></p>
                                </div>
                            </div>
                            <div class="row" style="margin-right: 20%">
                                <div class="col-lg-4 mb-4">
                                    <strong> المتوقع :</strong>
                                    <p>{{ $all['Custody'] + $all['incoming'] }} ريال </p>
                                </div>

                                <div class="col-lg-4 mb-4">
                                    <strong> الفارق :</strong>
                                    <p id="different"> </p>
                                </div>

                            </div>

                            <hr>
                            <div class="row" style="margin-right: 37%">


                                <button style="width: 20%" type="submit" class="btn btn-success">نعم</button>

                                <button style="width: 20%" type="button" class="btn btn-danger"
                                    onclick="closeCheckCloseDay()">لا</button>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif

        <hr>

        <!-- Single button -->
        @if ($all['Sequence'])
            <br><br>
            <div class="wrap">
                <form method="POST" action="{{ route('CreateBill') }}">
                    @csrf
                    <div class="left">
                        <div class="row">
                            <div class="col-md">
                                <h1>الفاتورة</h1>
                            </div>
                            <div id="closeBill" class="col-md">
                                <h3 style="text-align: left;"> معلقة</h3>
                            </div>
                            <div class="col-md" style="margin-left: 10%">

                                <label class="switch">
                                    <input id="inputSwitchStatus" name="Status" type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <h6>الصنف</h6>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <h6>الكمية</h6>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <h6>النوع</h6>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <h6>السعر</h6>
                            </div>
                        </div>

                        <div name="Bill" style="justify-content: space-around" id="BillArea"> </div>

                        <div class="row" id="add_after_me">
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <p>السعر : </p>
                                <input type="number" style="width: 80%;" value="0" readonly id="priceWOtax">
                                <input type="text" style="width: 80%;" name="discountID" value="0" readonly
                                    id="discountID">
                            </div>
                            <div class="col-md-4 mb-4">
                                <p>الضريبة : </p>
                                <input type="number" value="0" style="width: 80%;" readonly id="tax">

                            </div>
                            <div class="col-md-4 mb-4">
                                <p> النهائي : </p>
                                <input type="number" value="0" readonly style="width: 80%;" id="priceWtax">

                            </div>
                        </div>
                        <br>
                        <div class="row" style="margin-right: 5%;  margin-bottom: 5%">
                            <div class="col-sm">
                                <div class="row">
                                    <p style="width: 20%;">كاش</p>
                                    <input type="radio" name="payType" style="width: 25%" onclick="unDisable();"
                                        value="1">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="row">
                                    <p style="width: 20%">شبكة</p>
                                    <input type="radio" name="payType" style="width: 25%" onclick="unDisable();"
                                        value="2">
                                </div>
                            </div>
                        </div>
                        {{-- cash and online with model 
                        <div class="row" style="margin-right: 5%">
                            <div class="col-sm">
                                <div class="row">
                                    <p style="width: 20%;">كاش</p>
                                    <input type="number" name="cash" id="cash" onclick="KeypadCash();"
                                        step="any" onkeyup="restFun();" style="width: 25%" class="form-control">
                                </div>
                            </div>

                            <div class="col-sm">
                                <div class="row">
                                    <p style="width: 20%">شبكة</p>
                                    <input type="number" step="any" name="online" id="online"
                                        onclick="KeypadOnline();" onkeyup="restFun();"
                                        style="width: 25%"class="form-control">
                                </div>
                            </div>
                        </div>
                         --}}
                        <div class="row" style="margin-right:10% ; margin-top: 3%;margin-bottom: 5% ; display: none">
                            <div class="col-sm">
                                <div class="row">
                                    <p style="width:25%;text-align: left">الباقي</p>
                                    <input type="text" style="display: block ;width: 50%" name="rest"
                                        id="rest" />
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <button type="submit" style="width: 80%;margin-right: 4%" id="print" disabled
                                class="btn btn-info">طباعة</button>
                            {{-- 
                                <button type="submit"  onclick="onOpenModelCustomerInfo()"
                                style="width: 80%;margin-right: 4%" id="print" disabled
                                class="btn btn-info">طباعة</button> --}}

                        </div>

                        <div style="margin-top: 5%" class="row">
                            <button type="button" onclick="onOpenModelCustomerInfo()"
                                style="width: 80%;margin-right: 4%" id="print" class="btn btn-info">ادخل معلومات
                                العميل</button>
                        </div>
                        <div id="keyPadCash" class="modal">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">

                                    <h2 class="exit" onclick="KeypadCloseCash()">&times;
                                    </h2>
                                    ادخل قيمة الكاش
                                </div>
                                <section style="margin-top: 5% ; margin-right: 5%; margin-bottom: 3% ; width: 90%"
                                    class="row">
                                    <input type="text" style="text-align: center ; font-size: xx-large;"
                                        name="keypadInput" value="" id="keypadInput" class="display"
                                        readonly="readonly" />

                                    <table id="keypad" style="direction: ltr" cellpadding="20" cellspacing="3">
                                        <tr>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('1');">1</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('2');">2</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('3');">3</td>
                                        </tr>
                                        <tr>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('4');">4</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('5');">5</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('6');">6</td>
                                        </tr>
                                        <tr>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('7');">7</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('8');">8</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCode('9');">9</td>
                                        </tr>
                                        <tr>
                                            <td style="color: white ; text-align: center ; background-color: rgb(4, 214, 4) ;    font-size: xx-large;
                                            "
                                                id="texttab"onclick="KeypadCloseCash();">حفظ
                                            </td>
                                            <td style="color: white ; text-align: center ;background-color: grey ;font-size: xx-large;
"
                                                id="texttab" onclick="addCode('0');">0</td>
                                            <td style="color: white ; text-align: center ; background-color: rgb(255, 71, 71) ;     font-size: xx-large;
                                            "
                                                id="texttab" onclick="addCode('del');">مسح
                                            </td>
                                        </tr>
                                    </table>


                                    <br>
                                </section>
                            </div>
                        </div>
                        <div id="keyPadOnline" class="modal">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">

                                    <h2 class="exit" onclick="KeypadCloseOnline()">&times;
                                    </h2>
                                    ادخل قيمة الشبكة
                                </div>
                                <section style="margin-top: 5% ; margin-right: 5%; margin-bottom: 3% ; width: 90%"
                                    class="row">
                                    <input type="text" style="text-align: center ; font-size: xx-large;"
                                        name="keypadInputOnline" value="" id="keypadInputOnline" class="display"
                                        readonly="readonly" />

                                    <table id="keypad" style="direction: ltr" cellpadding="20" cellspacing="3">
                                        <tr>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('1');">1</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('2');">2</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('3');">3</td>
                                        </tr>
                                        <tr>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('4');">4</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('5');">5</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('6');">6</td>
                                        </tr>
                                        <tr>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('7');">7</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('8');">8</td>
                                            <td style="color: white ; text-align: center; background-color: grey ;font-size: xx-large;"
                                                id="texttab" onclick="addCodeOnline('9');">9</td>
                                        </tr>
                                        <tr>
                                            <td style="color: white ; text-align: center ; background-color: rgb(4, 214, 4) ;    font-size: xx-large;
                                            "
                                                id="texttab"onclick="KeypadCloseOnline();">حفظ
                                            </td>
                                            <td style="color: white ; text-align: center ;background-color: grey ;font-size: xx-large;
"
                                                id="texttab" onclick="addCodeOnline('0');">0</td>
                                            <td style="color: white ; text-align: center ; background-color: rgb(255, 71, 71) ;     font-size: xx-large;
                                            "
                                                id="texttab" onclick="addCodeOnline('del');">مسح
                                            </td>
                                        </tr>
                                    </table>


                                    <br>
                                </section>
                            </div>
                        </div>
                        <div id="myModalCustomer" class="modal">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">

                                    <h2 class="exit" onclick="closeModelCustomerInfo()">&times;
                                    </h2>
                                    معلومات العميل
                                </div>
                                <section style="margin-right: 10%" class="row">
                                    <div class="form-group">
                                        <h3 for="exampleInputEmail1">اسم العميل </h3>
                                        <input type="text" class="form-control" style="width: 80%" name="name"
                                            id="exampleInputEmail1" aria-describedby="emailHelp">

                                    </div>
                                    <div class="form-group">
                                        <h3 for="exampleInputPassword1">جوال العميل</h3>
                                        <input type="number" class="form-control" style="width: 80%" name="phone"
                                            id="exampleInputPassword1">
                                    </div>
                                    <div class="row" style="margin-top: 4%">
                                        <div class="col-md">
                                            <h3>نوع الفاتورة :</h3>
                                        </div>
                                    </div>
                                    <div class="row" id="customer">
                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">محلي</label>
                                            <input class="form-check-input" onclick="isDriver(1);" type="radio"
                                                name="customer" value="1"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>
                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">سفري</label>
                                            <input class="form-check-input" onclick="isDriver(2);" type="radio"
                                                name="customer" value="2"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>

                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">تطبيقات التوصيل</label>
                                            <input class="form-check-input" onclick="isDriver(3);" type="radio"
                                                name="customer" value="3"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>
                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">خدمة التوصيل</label>
                                            <input class="form-check-input" onclick="isDriver(4);" type="radio"
                                                name="customer" value="4"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>
                                    </div>
                                    <div style="justify-items: center ; display: none" id="driverDetails">
                                        <p>حدد السائق : </p>
                                        <select name="driverID" style="text-align-last:center;"
                                            class="form-select form-select-lg items-center " id="inpRSelec"
                                            aria-label=".form-select-lg example">
                                            @foreach ($all['driver'] as $driver)
                                                <option value="{{ $driver->id }}">{{ $driver->user->name }} </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    {{--      <div class="row hide3" id="application">
                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">هنقر ستيشن</label>
                                            <input class="form-check-input" type="radio" name="App" value="yes"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>
                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">مرسول</label>
                                            <input class="form-check-input" type="radio" name="App" value="yes"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>
                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">شقردي</label>
                                            <input class="form-check-input" type="radio" name="App" value="yes"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>
                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">جاهز</label>
                                            <input class="form-check-input" type="radio" name="App" value="yes"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>
                                        <div class="col-sm">
                                            <label class="form-check-strong" for="inlineRadio1">تويو</label>
                                            <input class="form-check-input" type="radio" name="App" value="yes"
                                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        </div>
                                    </div>
 --}}
                                    <button type="submit" style="width: 80%; margin-bottom: 2%; margin-top: 2%"
                                        class="btn btn-primary">حفظ</button>
                                    <br>
                                </section>
                            </div>
                        </div>

                    </div>
                </form>

                <div class="right">
                    <div class="row">
                        <div class="col-sm">
                            <input type="text" style="background: greenyellow" onkeyup="searchCode();" id="code"
                                name="code" autofocus placeholder="رمز العنصر">
                        </div>
                        <div class="col-sm">
                            <button id="myBtnBillToday" onclick="OpenDiscount();" class="btn btn-info">الخصومات</button>

                        </div>
                    </div>
                    <div style="height: 1800px;">
                        @foreach ($all['Categories'] as $categorires)
                            <div class="row">

                                <h2>{{ $categorires->Name }}</h2>


                                <div class="row"
                                    style="flex-shrink: 0;
                                width: 100%;
                                max-width: 100%;
                                margin-top: var(--bs-gutter-y);">
                                    @foreach ($all['items'] as $items)
                                        @if ($items->categories_id == $categorires->id)
                                            @if ($all['staff']->Branch->shope_id == 109)
                                                <div class="col-sm-2 mb-2">
                                                @else
                                                    <div class="col-lg-3 mb-4">
                                            @endif
                                            <div class="cards">
                                                <div class="card" id="myBtn{{ $items->id }}"
                                                    onclick="onOpenModel( {{ $items->id }} );">
                                                    <h2 class="card-title">{{ $items->Name }}</h2>
                                                    @if ($items->file == null)
                                                        <img id="imgBill" src="/image/shopping-cart.gif"
                                                            alt="">
                                                    @else
                                                        <img id="imgBill" src="/{{ $items->file }}" alt="">
                                                    @endif
                                                    <p class="card-desc">
                                                        {{ $items->description }}
                                                    </p>
                                                </div>

                                            </div>

                                </div>
                            @else
                        @endif
        @endforeach
    </div>


    </div>
    @endforeach

    </div>
    </div>
    </div>
    <div id="myModalBillToday" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">

                <h2 class="exit" onclick="closeModelBillToday()">&times;
                </h2>
                فواتير اليوم
            </div>
            <div class="row">
                <section style="margin-top: 2 %;  margin-right: 0.1%" class="row">
                    <table style="text-align: center">
                        <thead>
                            <tr>
                                <th scope="col">رقم الفاتورة</th>
                                <th scope="col">القيمة</th>
                                <th scope="col">طريقة الدفع </th>
                                <th scope="col">نوع الفاتورة </th>
                                <th scope="col">الحالة </th>
                                <th scope="col">#</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($all['todayBill'] as $item)
                                <tr>
                                    <form method="POST" action="{{ route('cancelBill') }}">
                                        @csrf
                                        <input type="text" name="billNo" hidden readonly
                                            value="{{ $item->id }}">
                                        <td scope="col">{{ $item->id }}</td>
                                        <td scope="col">{{ $item->total }}</td>
                                        <td scope="col">
                                            @if ($item->cash != null && $item->online != null)
                                                لم يتم الدفع
                                            @elseif($item->cash != null && $item->online == 0)
                                                كاش
                                            @elseif($item->cash == 0 && $item->online != null)
                                                شبكة
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->CustomerType == 4)
                                                خدمة توصيل السائق : {{ $item->driver->user->name }}
                                            @elseif ($item->CustomerType == 1)
                                                محلي
                                            @elseif ($item->CustomerType == 2)
                                                سفري
                                            @elseif ($item->CustomerType == 3)
                                                تطبيقات توصيل
                                            @endif
                                        </td>
                                        <td scope="col">
                                            @if ($item->Status == 1)
                                                منتهي
                                            @elseif($item->Status == 2)
                                                ملغي
                                            @else
                                            @endif
                                        </td>
                                        <td scope="col">
                                            @if ($item->Status == 1)
                                                <button type="submit" class="btn btn-danger">حذف </button>
                                            @elseif($item->Status == 2)
                                                تم الغاء الفاتورة
                                            @else
                                            @endif
                                        </td>
                                    </form>
                                    <form method="POST" action="{{ route('BillShow') }}">
                                        @csrf
                                        <input type="text" name="id" value="{{ $item->id }}" hidden
                                            readonly>
                                        <td scope="col"><button type="submit" class="btn btn-info">عرض
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </section>
            </div>
        </div>
    </div>


    <div id="myModalDiscount" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">

                <h2 class="exit" onclick="closeDiscount()">&times;
                </h2>
                العروض
            </div>

            <div class="cards">
                @foreach ($all['Discounts'] as $Discount)
                    <article class="card">
                        <header id="headerCard">
                            <h2 id="textCard">{{ $Discount->title }}</h2>
                        </header>
                        <div class="content" style="margin-top: 10%;white-space: normal l;text-align: center">
                            <p>{{ $Discount->Description }} </p>
                            <p>من يوم: {{ date('d-m-Y ', strtotime($Discount->from)) }}</p>
                            <p>الى يوم: {{ date('d-m-Y ', strtotime($Discount->to)) }} </p>
                            <p>قيمة الخصم:
                                @if ($Discount->Discount_for == 1)
                                    {{ $Discount->DiscountP }} ريال
                                @else
                                    {{ $Discount->DiscountP }} %
                                @endif
                            </p>

                        </div>

                        <hr>


                        <footer>
                            <button style="width: 90%;margin-right: 4%;margin-bottom: 2%"
                                onclick="userDiscount( {{ $Discount->id }});" class="btn btn-info">استخدم
                                الخصم</button>
                        </footer>

                    </article>
                @endforeach

            </div>

        </div>
    </div>



    <!-- The Modal -->
    @foreach ($all['items'] as $item)
        <div style="direction: rtl">
            <div id="myModal{{ $item->id }}" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">

                        <h2 class="exit" onclick="closeModel('{{ $item->id }}');">&times;
                        </h2>
                        <h2> {{ $item->Name }}</h2>
                    </div>
                    <div class="modal-body">
                        @if ($item->Small_Price != null)
                            <!--start Small-->

                            <div class="row" style="margin-top: 2%">

                                <div class="form-check form-check-inline">

                                    <input class="form-check-input" id="small{{ $item->id }}"
                                        onclick="show(1,'{{ $item->id }}');" type="checkbox" name="small"
                                        value="yes" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                    <label class="form-check-strong" for="inlineRadio1"
                                        style="font-weight: bolder;
                               font-size: larger;"
                                        id="smallName{{ $item->id }}">{{ $item->Small_Name }}</label>

                                </div>
                            </div>
                    </div>
                    <br>
                    <div id="div{{ $item->id }}small" class="input-group hide">
                        <div class="row">
                            <div class="col-md-6">
                                <lord-icon onclick="decrementValueNEw(1,'{{ $item->id }}')"
                                    src={{ asset('css/ymerwkwd.json') }} trigger="hover" style="width:80px;height:80px">
                                </lord-icon>

                                <input type="number" step="1" max="" value="1" name="quantity"
                                    id="Smallcount{{ $item->id }}" class="quantity-field"
                                    style="font-weight: bolder;
                               font-size: larger;">
                                <input id="SmallPrice{{ $item->id }}" hidden readonly
                                    value="{{ $item->Small_Price }}">
                                <lord-icon onclick="incrementValueNEw(1,'{{ $item->id }}')" alt="اضافة"
                                    title="اضافة" src={{ asset('css/xzksbhzh.json') }} trigger="hover"
                                    colors="primary:#08a88a,secondary:#ebe6ef" style="width:80px;height:80px">
                                </lord-icon>
                            </div>
                            <div class="col-md">
                                <strong id="SmallPrices{{ $item->id }}">{{ $item->Small_Price }}
                                    ريال</strong>
                            </div>
                            <div class="col-md">
                                <button id="ExtraTopping" type="button" onclick="ExtraTopping(1);" class="btn-info"
                                    style="width: 80%">إضافات</button>
                            </div>
                        </div>
                    </div>
                @else
                    <input class="form-check-input" id="small{{ $item->id }}"
                        onclick="show(1,'{{ $item->id }}');" type="checkbox" name="small"
                        style="margin-left: 90% ; width: 1.5em; height: 1.5em;" readonly hidden id="inlineRadio1">
    @endif
    <!--end small-->

    <br>
    @if ($item->Mid_Price != null)
        <!--start Mid-->

        <div class="row" style="margin-top: 2%">
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="mid{{ $item->id }}" onclick="show(2,'{{ $item->id }}');"
                    type="checkbox" name="mid" style="margin-left: 90% ; width: 1.5em; height: 1.5em;"
                    id="inlineRadio1">
                <label class="form-check-strong" for="inlineRadio1"
                    style="font-weight: bolder;
           font-size: larger;"
                    id="midName{{ $item->id }}">{{ $item->Mid_Name }}</label>
            </div>
        </div>
        <br>
        <div id="div{{ $item->id }}mid" class="input-group hide">
            <div class="row">
                <div class="col-md-6">
                    <lord-icon onclick="decrementValueNEw(2,'{{ $item->id }}')" src={{ asset('css/ymerwkwd.json') }}
                        trigger="hover" style="width:80px;height:80px">
                    </lord-icon>

                    <input type="number" step="1" max="" value="1" name="quantity"
                        style="font-weight: bolder;
               font-size: larger;" id="Midcount{{ $item->id }}"
                        class="quantity-field">
                    <input id="MidPrice{{ $item->id }}" hidden readonly value="{{ $item->Mid_Price }}">
                    <lord-icon onclick="incrementValueNEw(2,'{{ $item->id }}')" alt="اضافة" title="اضافة"
                        src={{ asset('css/xzksbhzh.json') }} trigger="hover" colors="primary:#08a88a,secondary:#ebe6ef"
                        style="width:80px;height:80px">
                    </lord-icon>

                </div>
                <div class="col-md">
                    <strong id="MidPrices{{ $item->id }}">{{ $item->Mid_Price }} ريال</strong>

                </div>
                <div class="col-md">
                    <button id="ExtraTopping" type="button" onclick="ExtraTopping(2);" class="btn-info"
                        style="width: 80%">إضافات</button>

                </div>

            </div>
        </div>
        <br>
    @else
        <input class="form-check-input" id="mid{{ $item->id }}" onclick="show(2,'{{ $item->id }}');"
            type="checkbox" name="mid" style="margin-left: 90% ; width: 1.5em; height: 1.5em;" readonly hidden
            id="inlineRadio1"><br>
    @endif
    <!--end Mid-->
    @if ($item->Big_Price != null)
        <!--start Big-->
        <div class="row" style="margin-top: 2%">
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="big{{ $item->id }}" type="checkbox"
                    onclick="show(3,'{{ $item->id }}');" name="big"
                    style="margin-left: 90% ; width: 1.5em; height: 1.5em;" id="inlineRadio1">
                <label class="form-check-strong" for="inlineRadio1"
                    style="font-weight: bolder;
           font-size: larger;"
                    id="bigName{{ $item->id }}">{{ $item->Big_Name }}</label>
            </div>
        </div>
        <div class="row">
            <div id="div{{ $item->id }}big" class="input-group hide">
                <div class="row">
                    <div class="col-md-6">
                        <lord-icon onclick="decrementValueNEw(3,'{{ $item->id }}')"
                            src={{ asset('css/ymerwkwd.json') }} trigger="hover" style="width:80px;height:80px">
                        </lord-icon>
                        <input type="number" step="1" max="" value="1" name="quantity"
                            style="font-weight: bolder;
                   font-size: larger;" min="0"
                            id="Bigcount{{ $item->id }}" class="quantity-field">
                        <input type="text" hidden readonly value="{{ $item->Big_Price }}"
                            id="BigPrice{{ $item->id }}">
                        <lord-icon onclick="incrementValueNEw(3,'{{ $item->id }}')" alt="اضافة" title="اضافة"
                            src={{ asset('css/xzksbhzh.json') }} trigger="hover"
                            colors="primary:#08a88a,secondary:#ebe6ef" style="width:80px;height:80px">
                        </lord-icon>

                    </div>

                    <div class="col-md">
                        <strong id="MidPrices{{ $item->id }}">{{ $item->Big_Price }} ريال</strong>
                    </div>
                    <div class="col-md">
                        <button id="ExtraTopping" type="button" onclick="ExtraTopping(3);" class="btn-info"
                            style="width: 80%">إضافات</button>

                    </div>
                </div>
            </div>
        </div>
    @else
        <input class="form-check-input" id="big{{ $item->id }}" type="checkbox"
            onclick="show(3,'{{ $item->id }}');" name="big"
            style="margin-left: 90% ; width: 1.5em; height: 1.5em;" id="inlineRadio1" hidden readonly>
    @endif
    <!--end Big-->
    <button class="btn btn-info" id="font-button"
        onclick="clicksubmit('{{ $item->id }}' , '{{ $item->Name }}', '{{ $item->price }}')">حفظ</button>
    </div>

    </div>

    </div>
    @endforeach
    @endif
    <div id="myModalExtraTopping" class="modal">

        <!-- Modal content -->
        <div class="modal-content" style="width: 50%;">
            <div class="modal-header">

                <h2 class="exit" onclick="closeExtraTopping()">&times;
                </h2>
                الاضافات
                <button class="btn btn-light" type="button" onclick="toppingSubmit();">حفظ</button>

            </div>
            <div class="box box2">
                <input type="text" name="szie" hidden readonly id="sizeBox">
                <div class="evenboxinner">
                    <ul style="  list-style-type: decimal;">
                        @foreach ($all['extraToppings'] as $topping)
                            <li>
                                <label style="font-size: 20px" class="container">{{ $topping->Name }}/
                                    {{ $topping->price }} ريال
                                    <input type="checkbox" name="vaBox[]" value="{{ $topping->id }}"
                                        style="width: 20px;height:20px;">
                                    <span class="checkmark"></span>
                                </label>
                            </li>
                        @endforeach



                    </ul>
                </div>
            </div>

        </div>

    </div>
    </div>

@endsection
@section('script')
    <script>
        $.ajax({
            method: 'GET',
            url: '/CasherBoard',
            success: function(response) {
                // Handle the response
                console.log(response.message);
                console.log("FA");
            }
        });
    </script>

    <script>
        function KeypadCash(type) {
            var modal = document.getElementById("keyPadCash");
            modal.style.display = "block";

        }

        function KeypadCloseCash() {
            var modal = document.getElementById("keyPadCash");
            modal.style.display = "none";

        }

        function KeypadOnline(type) {
            var modal = document.getElementById("keyPadOnline");
            modal.style.display = "block";

        }

        function KeypadCloseOnline() {
            var modal = document.getElementById("keyPadOnline");
            modal.style.display = "none";

        }

        function addCodeOnline(key) {
            var code = document.getElementById("keypadInputOnline");
            var onlineValue = document.getElementById("online").value;
            var online = onlineValue == null ? 0 : onlineValue;
            if (key == "del") {
                code.value = code.value.slice(0, -1);
                document.getElementById("online").value = code.value;
                restFun();

            } else {
                code.value = code.value + key;
                document.getElementById("online").value = code.value;
                restFun();
            }


        }

        function addCodeCustody(key) {
            var Custody = document.getElementById("Custody");
            Custody.value == null ? 0 : Custody.value;
            if (key == "del") {
                Custody.value = Custody.value.slice(0, -1);

            } else {
                Custody.value = Custody.value + key;
            }


        }

        function addCodeEndCustody(key) {
            var EndCustody = document.getElementById("EndCustody");
            EndCustody.value == null ? 0 : EndCustody.value;
            if (key == "del") {
                EndCustody.value = EndCustody.value.slice(0, -1);
                CustodyRest();
            } else {
                EndCustody.value = EndCustody.value + key;
                CustodyRest();
            }


        }


        function addCode(key) {
            var code = document.getElementById("keypadInput");
            var cashValue = document.getElementById("cash").value;
            var cash = cashValue == null ? 0 : cashValue;
            if (key == "del") {
                code.value = code.value.slice(0, -1);
                document.getElementById("cash").value = code.value;
                restFun();

            } else {
                code.value = code.value + key;
                document.getElementById("cash").value = code.value;
                restFun();
            }


        }
    </script>
    <script>
        $(":input").keypress(function(event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();
            }
        });
    </script>
    <script>
        const button = document.getElementById('print');
        const selectElement = document.querySelector('#inputSwitchStatus');

        selectElement.addEventListener('change', (event) => {
            button.disabled = event.target.checked ? true : false;
        });
    </script>
    <script>
        function unDisable() {
            button.disabled = false;

        }

        function Cash() {
            document.getElementById("paid").style.display = "block";
            document.getElementById("rest").style.display = "block";
            document.getElementById("restText").style.display = "block";

            document.getElementById("rest").value = document.getElementById("priceWtax").value;

        }


        function restFun() {
            var totalprice = document.getElementById("priceWtax").value;
            var cashValue = document.getElementById("cash").value;
            var onlineValue = document.getElementById("online").value;
            var cash = cashValue == null ? 0 : cashValue;
            var online = onlineValue == null ? 0 : onlineValue;
            var paid = +cash + +online;
            console.log(paid);
            document.getElementById("rest").value = parseFloat(paid - totalprice).toFixed(2);
            $finalPrice = document.getElementById("rest").value;
            if (parseFloat(totalprice) <= paid) {
                button.disabled = false;

            } else {
                button.disabled = true;

            }
        }

        function CustodyRest() {
            var totalprice = document.getElementById("EndCustody").value;
            var diff = parseFloat(@json($all['Custody'])) + parseFloat(@json($all['incoming']));

            document.getElementById('different').innerText = parseFloat(totalprice - diff) +
                ' ريال  ';
            document.getElementById("restCustody").innerText = parseFloat(totalprice) + 'ريال ';


        }

        function searchCode() {

            var code = document.getElementById('code').value;
            if (code.length > 10) {
                var contents = @json($all['items']);
                var result = contents.find(({
                    barCode
                }) => barCode === code);

                console.log(result['id']);
                onOpenModel(result['id']);
                document.getElementById('small' + result['id']).checked = true;
                clicksubmit(result['id'], result['Name'], result['price']);
            }



        }

        function online() {
            document.getElementById("paid").style.display = "none";
            document.getElementById("rest").style.display = "none";
            document.getElementById("restText").style.display = "none";
            button.disabled = false;
        }
    </script>
    <script>
        function onOpenModelBillToday() {
            var modal = document.getElementById("myModalBillToday");
            modal.style.display = "block";

        }

        function closeModelBillToday() {
            var modal = document.getElementById("myModalBillToday");
            modal.style.display = "none";

        }
    </script>
    <script>
        function OpenDiscount() {
            var modal = document.getElementById("myModalDiscount");
            modal.style.display = "block";

        }

        function closeDiscount() {
            var modal = document.getElementById("myModalDiscount");
            modal.style.display = "none";

        }
    </script>
    <script>
        function onOpenModelCustomerInfo($item) {
            var modal = document.getElementById("myModalCustomer");
            modal.style.display = "block";

        }

        function closeModelCustomerInfo($item) {
            var modal = document.getElementById("myModalCustomer");
            modal.style.display = "none";

        }
    </script>
    <script>
        function onOpenModel($item) {
            var modal = document.getElementById("myModal" + $item);
            modal.style.display = "block";

        }

        function closeModel($item) {
            var modal = document.getElementById("myModal" + $item);
            modal.style.display = "none";

        }

        function show($size, $item) {

            switch ($size) {
                case 1:
                    if (document.getElementById('small' + $item).checked == true) {
                        document.getElementById('div' + $item + 'small').style.display = 'block';

                    } else {
                        document.getElementById('div' + $item + 'small').style.display = 'none';

                    }

                    break;
                case 2:
                    if (document.getElementById('mid' + $item).checked == true) {
                        document.getElementById('div' + $item + 'mid').style.display = 'block';

                    } else {
                        document.getElementById('div' + $item + 'mid').style.display = 'none';

                    }
                    break;

                case 3:
                    if (document.getElementById('big' + $item).checked == true) {
                        document.getElementById('div' + $item + 'big').style.display = 'block';

                    } else {
                        document.getElementById('div' + $item + 'big').style.display = 'none';

                    }
                    break;
                default:
                    break;
            }
        }

        function incrementValueNEw($size, $item) {
            switch ($size) {
                case 1:

                    document.getElementById('Smallcount' + $item).value++;

                    break;
                case 2:
                    document.getElementById('Midcount' + $item).value++;
                    break;
                case 3:
                    document.getElementById('Bigcount' + $item).value++;
                    break;
                default:
                    break;
            }

        }

        function decrementValueNEw($size, $item) {
            switch ($size) {
                case 1:
                    if (document.getElementById('Smallcount' + $item).value-- <= 1) {
                        document.getElementById('Smallcount' + $item).value++;
                    }

                    break;
                case 2:
                    if (document.getElementById('Midcount' + $item).value-- <= 1) {
                        document.getElementById('Midcount' + $item).value++;
                    }
                    break;
                case 3:
                    if (document.getElementById('Bigcount' + $item).value-- <= 1) {
                        document.getElementById('Bigcount' + $item).value++;
                    }
                    break;
                default:
                    break;
            }
        }
    </script>


    <script>
        oldestprice = [];

        function Af($item) {
            for (var index = 0; index <= 20; index++) {
                var myobj = document.getElementById("add" + $item);
                if (myobj != null) {


                    if (index == 1) { // count 
                        $count = document.getElementById("add" + $item).value;
                    }
                    if (index == 3) { // price 
                        // Price with out tax 
                        $oldprice = document.getElementById('priceWOtax').value;
                        $newPrice = document.getElementById('priceWOtax').value - (document.getElementById("add" + $item)
                            .value * $count);
                        document.getElementById('priceWOtax').value = $newPrice;

                        var oldtax = document.getElementById('tax');
                        oldtax.value = ($newPrice * 0.15).toFixed(2);
                        // Tax with Price
                        var newPeiceWTax = document.getElementById('priceWtax');

                        newPeiceWTax.value = parseFloat($newPrice);
                        document.getElementById("rest").value = parseFloat($newPrice) + parseFloat(oldtax.value);
                    }

                    myobj.remove();


                }
            }

            var price = document.getElementsByName("price[]");
            oldestprice.length = 0;
            for (let index = 0; index < price.length; index++) {
                oldestprice.push(price[index].value);

            }
            console.log(oldestprice);

            /*
                        var Rearr = oldestprice.reverse();
                        console.log(Rearr);

                        delete Rearr[$item - 1];

                        var arr = Rearr.reverse();
                        oldestprice = arr.filter(Boolean);

                        console.log('item ' + $item);
                        console.log('old ' + oldestprice);
                        console.log('Rearr ' + Rearr);
                        console.log('arr ' + arr);
            */


        }

        function ExtraTopping(size) {
            document.getElementById('sizeBox').value = size;
            var modal = document.getElementById("myModalExtraTopping");
            modal.style.display = "block";

        }

        function closeExtraTopping() {
            var modal = document.getElementById("myModalExtraTopping");
            modal.style.display = "none";

        }
        var sizeTopping;
        var toppingsAll;
        var smallTopping;
        var midTopping;
        var bigTopping;
        var toppingsValue = '';
        var toppingsPrice = 0;

        function toppingSubmit() {
            sizeTopping = document.getElementById('sizeBox').value;
            var toppings = document.getElementsByName('vaBox[]');
            var extras = @json($all['extraToppings']);
            toppingsAll = toppings;
            switch (sizeTopping) {
                case '1':
                    smallTopping = true;

                    for (var topping of toppings) {
                        if (topping.checked) {
                            var result = extras.find(({
                                id
                            }) => id == topping.value);

                            toppingsValue += '+' + result['Name'];
                            toppingsPrice += parseInt(result['price']);
                        }

                    }
                    closeExtraTopping();
                    break;
                case '2':
                    midTopping = true;
                    for (var topping of toppings) {
                        if (topping.checked) {
                            var result = extras.find(({
                                id
                            }) => id == topping.value);
                            toppingsValue += '+' + result['Name'];
                            toppingsPrice += parseInt(result['price']);

                        }

                    }
                    closeExtraTopping();
                    break;
                case '3':
                    bigTopping = true;
                    for (var topping of toppings) {
                        if (topping.checked) {
                            var result = extras.find(({
                                id
                            }) => id == topping.value);
                            toppingsValue += '+' + result['Name'];
                            toppingsPrice += parseInt(result['price']);
                        }

                    }
                    closeExtraTopping();
                    break;
                default:
                    break;
            }



        }
        var $i = 1;

        function clicksubmit($item, $name, $price) {
            var extras = @json($all['extraToppings']);

            if (document.getElementById('small' + $item).checked == true) {

                var oldBill = document.getElementById('BillArea').innerText;
                var smallName = document.getElementById('smallName' + $item).innerText;
                var countSmall = document.getElementById('Smallcount' + $item).value;
                var priceSmall = document.getElementById('SmallPrice' + $item).value;
                if (smallTopping) {
                    var nameNew = $name;
                    $name = nameNew + ' اضافة ' + toppingsValue;
                    priceSmall = parseInt(priceSmall) + parseInt(toppingsPrice) * countSmall;
                    smallTopping = false;
                    toppingsValue = '';
                    toppingsPrice = 0;

                    for (var topping of toppingsAll) {
                        if (topping.checked) {
                            var result = extras.find(({
                                id
                            }) => id == topping.value);
                            var extraName = 'e' + result['id'];
                            document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                                " <div " +
                                ">  <input hidden  id=" +
                                'add' + $i + " " +
                                "name='item[]'" +
                                "value = " +
                                extraName +
                                "> " +
                                " </div>");
                        }
                    }
                }
                // new Price Small
                var oldprice = document.getElementById('priceWOtax');
                oldprice.value = (priceSmall * countSmall) + parseInt(oldprice.value);
                // new Tax
                var oldtax = document.getElementById('tax');
                oldtax.value = (oldprice.value * 0.15).toFixed(2);
                // tax with Price 
                var newPeiceWTax = document.getElementById('priceWtax');
                newPeiceWTax.value = parseInt(oldprice.value);
                document.getElementById("rest").value = parseInt(oldprice.value);
                oldestprice.unshift(priceSmall);
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    "name='priceShow[]'" +
                    " " +
                    ">" +
                    priceSmall +
                    "</p>  </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " + smallName +
                    "</p> </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " +
                    countSmall +
                    "</p> </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " +
                    $name +
                    "</p> </div>");
                //Input Fiald
                // Price
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='price[]'" +
                    "value = " +
                    priceSmall +
                    "> " +
                    " </div>");
                // Szie 1 Small
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='size[]'" +
                    "value = " +
                    "1" +
                    "> " +
                    " </div>");
                // Count
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='count[]'" +
                    "value = " +
                    countSmall +
                    "> " +
                    " </div>");
                // Item
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='item[]'" +
                    "value = " +
                    $item +
                    "> " +
                    " </div>");

                $i++;

            }

            if (document.getElementById('mid' + $item).checked == true) {
                var oldBill = document.getElementById('BillArea').innerText;
                var midName = document.getElementById('midName' + $item).innerText;
                var countMid = document.getElementById('Midcount' + $item).value;
                var priceMid = document.getElementById('MidPrice' + $item).value;
                if (midTopping) {
                    var nameNew = $name;
                    $name = nameNew + ' اضافة ' + toppingsValue;
                    priceMid = parseInt(priceMid) + parseInt(toppingsPrice);
                    midTopping = false;
                    toppingsValue = '';
                    toppingsPrice = 0;

                    for (var topping of toppingsAll) {
                        if (topping.checked) {
                            var result = extras.find(({
                                id
                            }) => id == topping.value);
                            var extraName = 'e' + result['id'];
                            document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                                " <div " +
                                ">  <input hidden id=" +
                                'add' + $i + " " +
                                "name='item[]'" +
                                "value = " +
                                extraName +
                                "> " +
                                " </div>");
                        }
                    }
                }
                // new price Mid
                var oldprice = document.getElementById('priceWOtax');
                oldprice.value = (priceMid * countMid) + parseInt(oldprice.value);
                // new Tax Mid
                var oldtax = document.getElementById('tax');
                oldtax.value = (oldprice.value * 0.15).toFixed(2);
                // Tax with Price
                var newPeiceWTax = document.getElementById('priceWtax');
                newPeiceWTax.value = parseInt(oldprice.value);
                document.getElementById("rest").value = parseInt(oldprice.value);

                oldestprice.unshift(priceMid);
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    "name='priceShow[]'" +
                    " " +
                    ">" +
                    priceMid +
                    "</p> </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " + midName +
                    "</p> </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " +
                    countMid +
                    "</p> </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " +
                    $name +
                    "</p> </div>");
                //Input Fiald
                // Price
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='price[]'" +
                    "value = " +
                    priceMid +
                    "> " +
                    " </div>");
                // Szie 2 Mid
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='size[]'" +
                    "value = " +
                    "2" +
                    "> " +
                    " </div>");
                // Count
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='count[]'" +
                    "value = " +
                    countMid +
                    "> " +
                    " </div>");
                // Item
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='item[]'" +
                    "value = " +
                    $item +
                    "> " +
                    " </div>");

                $i++;
            }

            if (document.getElementById('big' + $item).checked == true) {
                var oldBill = document.getElementById('BillArea').innerText;
                var bigName = document.getElementById('bigName' + $item).innerText;
                var countBig = document.getElementById('Bigcount' + $item).value;
                var priceBig = document.getElementById('BigPrice' + $item).value;
                if (bigTopping) {
                    var nameNew = $name;
                    $name = nameNew + ' اضافة ' + toppingsValue;
                    priceBig = parseInt(priceBig) + parseInt(toppingsPrice);
                    bigTopping = false;
                    toppingsValue = '';
                    toppingsPrice = 0;
                    for (var topping of toppingsAll) {
                        if (topping.checked) {
                            var result = extras.find(({
                                id
                            }) => id == topping.value);
                            var extraName = 'e' + result['id'];
                            document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                                " <div " +
                                ">  <input hidden id=" +
                                'add' + $i + " " +
                                "name='item[]'" +
                                "value = " +
                                extraName +
                                "> " +
                                " </div>");
                        }
                    }
                }
                // New Price
                var oldprice = document.getElementById('priceWOtax');
                oldprice.value = (priceBig * countBig) + parseInt(oldprice.value);
                // new Tax
                var oldtax = document.getElementById('tax');
                oldtax.value = (oldprice.value * 0.15).toFixed(2);
                // Tax with Price
                var newPeiceWTax = document.getElementById('priceWtax');
                newPeiceWTax.value = parseInt(oldprice.value);
                document.getElementById("rest").value = parseInt(oldprice.value);

                oldestprice.unshift(priceBig);

                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    "name='priceShow[]'" +
                    " " +
                    ">" +
                    priceBig +
                    "</p>  </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " + bigName +
                    "</p> </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " +
                    countBig +
                    "</p> </div>");
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <p id=" +
                    'add' + $i + " " +
                    "onclick = " +
                    " Af(" + $i + "); " +
                    " " +
                    "> " +
                    $name +
                    "</p> </div>");
                //Input Fiald
                // Price
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='price[]'" +
                    "value = " +
                    priceBig +
                    "> " +
                    " </div>");
                // Szie 3 Big
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='size[]'" +
                    "value = " +
                    "3" +
                    "> " +
                    " </div>");
                // Count
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='count[]'" +
                    "value = " +
                    countBig +
                    "> " +
                    " </div>");
                // Item
                document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                    " <div class=" +
                    'col-lg-3 mb-3' +
                    ">  <input hidden id=" +
                    'add' + $i + " " +
                    "name='item[]'" +
                    "value = " +
                    $item +
                    "> " +
                    " </div>");

                $i++;

            }


            var modal = document.getElementById("myModal" + $item);
            modal.style.display = "none";
            document.getElementById("code").value = "";
            document.getElementById("code").focus();


        }
        dis = 0;
        oldDis = 0;



        function userDiscount(DisId) {
            document.getElementById('discountID').value = DisId;
            reDiscount();
            key = 0;


            let num = document.getElementsByName("price[]").length;

            var Discounts = @json($all['Discounts']);
            var Discount = Discounts.find(({
                id
            }) => DisId == id);
            console.log('num  : ' + num);
            for (let index = 0; index < num; index++) {
                if (key < index) {
                    key = index;
                }
                console.log('( key) : ' + key);
                console.log('( index) : ' + index);

                item = document.getElementsByName("item[]")[key].value;
                price = document.getElementsByName("price[]")[index].value;
                count = document.getElementsByName("count[]")[index].value;
                priceShow = document.getElementsByName("priceShow[]")[index];
                console.log('( item) : ' + item);
                console.log('( price) : ' + price);

                if (item.charAt(0) === 'e' && index + 1 != num) {
                    key++;
                    --index;

                }
                if (Discount['Discount_type'] == 1) {

                    var items = @json($all['items']);
                    var itemDetail = items.find(({
                        id
                    }) => item == id); // get item cat

                    var DiscountsCat = @json($all['DiscountItems']);
                    var DiscountCat = DiscountsCat.find(({
                            categorie_id
                        }) =>
                        itemDetail['categories_id'] == categorie_id
                    );
                    console.log('Discount_id ' + DiscountCat['Discount_id']);

                    if (DiscountCat != null && Discount['id'] == DiscountCat['Discount_id']) {


                        switch (Discount['Discount_for']) {
                            case 1:
                                newPeice = (price - Discount['DiscountP']);
                                subTotal = Discount['DiscountP'] * count;
                                console.log('newPeice :' + newPeice);

                                priceShow.innerText = newPeice;
                                document.getElementsByName("price[]")[index].value = newPeice;
                                console.log('subTotal :' + subTotal);

                                break;
                            case 2:
                                newPeice = (price - Discount['DiscountP'] / 100 * price).toFixed(2);
                                subTotal = (Discount['DiscountP'] / 100 * price * count).toFixed(2);
                                priceShow.innerText = newPeice;
                                document.getElementsByName("price[]")[index].value = newPeice;
                                console.log('subTotal :' + subTotal);

                                break;
                            default:
                                break;
                        }
                        // price
                        var priceWOtax = parseFloat(document.getElementById('priceWOtax').value) - parseFloat(subTotal);
                        document.getElementById('priceWOtax').value = (parseFloat(priceWOtax)).toFixed(2);
                        // new Tax 
                        var tax = (priceWOtax * 0.15).toFixed(2);
                        document.getElementById('tax').value = parseFloat(tax);
                        // Tax with Price
                        var newPeiceWTax = document.getElementById('priceWtax');
                        document.getElementById('priceWtax').value = (parseFloat(priceWOtax)).toFixed(2);
                    } else {
                        //  oldDis = 0;
                    }


                } else if (Discount['Discount_type'] == 2) { // get item id 

                    var DiscountsCat = @json($all['DiscountItems']);
                    var DiscountCat = DiscountsCat.find(({
                        item_id
                    }) => item == item_id);

                    if (DiscountCat != null && Discount['id'] == DiscountCat['Discount_id']) {

                        switch (Discount['Discount_for']) {
                            case 1:
                                newPeice = (price - Discount['DiscountP']);
                                subTotal = Discount['DiscountP'] * count;
                                console.log('newPeice :' + newPeice);

                                priceShow.innerText = newPeice;
                                document.getElementsByName("price[]")[index].value = newPeice;
                                console.log('subTotal :' + subTotal);

                                break;
                            case 2:
                                newPeice = (price - Discount['DiscountP'] / 100 * price).toFixed(2);
                                subTotal = (Discount['DiscountP'] / 100 * price * count).toFixed(2);
                                priceShow.innerText = newPeice;
                                document.getElementsByName("price[]")[index].value = newPeice;
                                console.log('subTotal :' + subTotal);

                                break;
                            default:
                                break;
                        }
                        // price
                        var priceWOtax = parseFloat(document.getElementById('priceWOtax').value) - parseFloat(subTotal);
                        document.getElementById('priceWOtax').value = (parseFloat(priceWOtax)).toFixed(2);
                        // new Tax 
                        var tax = (priceWOtax * 0.15).toFixed(2);
                        document.getElementById('tax').value = parseFloat(tax);
                        // Tax with Price
                        var newPeiceWTax = document.getElementById('priceWtax');
                        document.getElementById('priceWtax').value = (parseFloat(priceWOtax)).toFixed(2);
                    } else {
                        //   oldDis = 0;
                    }
                } else {

                }
            }


            console.log('oldestprice  : ' + oldestprice);
            closeDiscount();
        }

        function reDiscount() {
            let num = oldestprice.length;
            var total = 0;
            var priceWOtax = document.getElementById("priceWOtax");

            for (var index = 0; index < num; index++) {

                price = document.getElementsByName("price[]")[index].value;
                priceShow = document.getElementsByName("priceShow[]")[index];
                count = document.getElementsByName("count[]")[index].value;

                document.getElementsByName("price[]")[index].value = oldestprice[index];
                document.getElementsByName("priceShow[]")[index].innerText = parseFloat(oldestprice[index]);


                total += parseFloat(oldestprice[index] * count);

                console.log('total price  : ' + total);
                console.log('after priceShow  : ' + priceShow.innerText);

            }
            priceWOtax.value = parseFloat(total);


            // new Tax 
            var tax = (parseFloat(total) * 0.15).toFixed(2);
            console.log(tax);

            document.getElementById('tax').value = parseFloat(tax);

            // Tax with Price
            var newPeiceWTax = document.getElementById('priceWtax');
            document.getElementById('priceWtax').value = parseFloat(total);

            /*
                        var oldKey = -1;
                        var total = 0.00;
                        var nontotal = 0.00;
                        var totalCount = 0;
                        var haveDis = false;
                        var Discounts = @json($all['Discounts']);
                        var Discount = Discounts.find(({
                            id
                        }) => DisId === id);
                        for (let index = 0; index < num; index++) {

                            item = document.getElementsByName("item[]")[index].value;
                            price = document.getElementsByName("price[]")[index].value;
                            count = document.getElementsByName("count[]")[index].value;
                            count = document.getElementsByName("count[]")[index].value;
                            priceShow = document.getElementsByName("priceShow[]")[index];
                            if (item.charAt(0) === 'e') {
                                break;
                            }


                            if (Discount['Discount_type'] == 1) {
                                var items = @json($all['items']);
                                var itemDetail = items.find(({
                                    id
                                }) => item == id); // get item cat

                                var DiscountsCat = @json($all['DiscountItems']);
                                var DiscountCat = DiscountsCat.find(({
                                    categorie_id
                                }) => itemDetail['categories_id'] === categorie_id);

                                if (DiscountCat != null && Discount['id'] == DiscountCat['Discount_id']) {
                                    switch (Discount['Discount_for']) {
                                        case 1:
                                            newPeice = parseFloat(price) + Discount['DiscountP'];

                                            document.getElementsByName("price[]")[index].value = newPeice;
                                            total += parseFloat(newPeice * count);
                                            totalCount += parseInt(count);
                                            console.log('price   : ' + price);

                                            haveDis = true;
                                            priceShow.innerText = newPeice;
                                            break;
                                        case 2:
                                            newPeice = parseFloat(price) / parseFloat((1 - Discount['DiscountP'] / 100));

                                            document.getElementsByName("price[]")[index].value = newPeice;
                                            total += parseFloat(newPeice * count);
                                            totalCount += parseInt(count);
                                            console.log('price   : ' + price);

                                            haveDis = true;
                                            priceShow.innerText = newPeice;
                                            break;
                                    }

                                } else {
                                    switch (Discount['Discount_for']) {
                                        case 1:
                                            newPeice = parseFloat(price) / parseFloat((1 - Discount['DiscountP'] / 100));

                                            document.getElementsByName("price[]")[index].value = newPeice;
                                            break;
                                        case 2:
                                            newPeice = parseFloat(price) + Discount['DiscountP'];
                                            document.getElementsByName("price[]")[index].value = newPeice;
                                            break;
                                    }

                                    nontotal += parseFloat(price);
                                    console.log('nontotal in loop  : ' + nontotal);
                                    haveDis = false;


                                }

                            } else if (Discount['Discount_type'] == 2) { // get item id 

                                var DiscountsCat = @json($all['DiscountItems']);
                                var DiscountCat = DiscountsCat.find(({
                                    item_id
                                }) => item == item_id);
                                if (DiscountCat != null && Discount['id'] == DiscountCat['Discount_id']) {

                                    switch (Discount['Discount_for']) {
                                        case 1:
                                            newPeice = parseFloat(price) + Discount['DiscountP'];
                                            console.log('total befor   : ' + total);

                                            document.getElementsByName("price[]")[index].value = newPeice;
                                            total += parseFloat(newPeice * count);
                                            totalCount += parseInt(count);
                                            console.log('price   : ' + price);
                                            console.log('count   : ' + count);
                                            console.log('newPeice   : ' + newPeice);
                                            console.log('total   : ' + total);
                                            haveDis = true;
                                            priceShow.innerText = newPeice;
                                            break;
                                        case 2:
                                            newPeice = parseFloat(price) / parseFloat((1 - Discount['DiscountP'] / 100));
                                            console.log('total befor   : ' + total);

                                            document.getElementsByName("price[]")[index].value = newPeice;
                                            total += parseFloat(newPeice * count);
                                            totalCount += parseInt(count);
                                            console.log('price   : ' + price);
                                            console.log('count   : ' + count);
                                            console.log('newPeice   : ' + newPeice);
                                            console.log('total   : ' + total);
                                            haveDis = true;
                                            priceShow.innerText = newPeice;
                                            break;
                                    }

                                } else {

                                    switch (Discount['Discount_for']) {
                                        case 1:
                                            newPeice = parseFloat(price) / parseFloat((1 - Discount['DiscountP'] / 100));

                                            document.getElementsByName("price[]")[index].value = newPeice;
                                            break;
                                        case 2:
                                            newPeice = parseFloat(price) + Discount['DiscountP'];
                                            document.getElementsByName("price[]")[index].value = newPeice;
                                            break;
                                    }

                                    nontotal += parseFloat(price);
                                    console.log('nontotal in loop  : ' + nontotal);
                                    haveDis = false;





                                }

                            }


                        }

                        var priceWOtax = document.getElementById("priceWOtax");
                        priceWOtax.value = parseFloat(priceWOtax.value) - parseFloat(nontotal);
                        console.log('Discount type  :' + Discount['Discount_type']);
                        console.log('priceWOtax   :' + priceWOtax.value);
                        console.log('DiscountP   :' + Discount['DiscountP']);
                        console.log('totalCount   :' + totalCount);
                        console.log('nontotal   :' + nontotal);
                        if (Discount['Discount_for'] == 1 && haveDis) {
                            newPrice = parseFloat(Discount['DiscountP']) * parseFloat(totalCount);
                            priceWOtax.value = parseFloat(priceWOtax.value) + parseFloat(newPrice);
                            console.log('total after add  :' + priceWOtax.value);
                            console.log('newPrice   :' + newPrice);

                            priceWOtax.value = parseFloat(priceWOtax.value) + parseFloat(nontotal);
                            console.log('total after add none :' + priceWOtax.value);

                        } else if (Discount['Discount_for'] == 2 && haveDis) {
                            priceWOtax.value = (priceWOtax.value / (1 - Discount['DiscountP'] / 100)).toFixed(2);
                            priceWOtax.value = parseFloat(priceWOtax.value) + parseFloat(nontotal);
                            console.log('xxxx none :' + nontotal);


                        }

                        console.log('total price :' + priceWOtax.value);
                        console.log("non :" + nontotal);
                        olditem = [];
                        oldprice = [];
                        oldcount = [];
                        console.log("DOne!!");
            */
        }
    </script>

    <script>
        let mainText = document.getElementById('main_content');


        document.querySelector('.switch input').addEventListener('change', e => {

            mainText.innerText = e.target.checked ? "مغلق" : "   فتح";
            e.target.checked ? inventoryOpen() : OpenDay();

        });
        window.onload = function() {
            mainText = document.getElementById('main_content');
            dayOff = @json($all['Sequence']);
            mainText.innerText = dayOff ? "فتح" : " مغلق";
        };


        switch (@json($all['close'])) {
            case 1:
                inventoryOpen();
                console.log('colse');
                break;
            case 2:
                document.getElementById('autoClose').value = 1;
                document.getElementById('FormEnd').submit();
                console.log("Now Close ");
                break;
            case 0:
                console.log('not yet ');

                break;
            default:
                break;
        }




        function inventoryOpen() {
            var modal = document.getElementById("myModalinventory");
            modal.style.display = "block";

        }

        function OpenDay() {
            var modal = document.getElementById("OpenDay");
            modal.style.display = "block";

        }

        function inventoryClose() {
            var modal = document.getElementById("myModalinventory");
            modal.style.display = "none";

        }

        function CloseDay() {
            var modal = document.getElementById("OpenDay");
            modal.style.display = "none";

        }

        function CheckCloseDay() {
            var modal = document.getElementById("CheckCloseDay");
            modal.style.display = "block";

        }

        function closeCheckCloseDay() {
            var modal = document.getElementById("CheckCloseDay");
            modal.style.display = "none";

        }

        function isDriver(type) {
            if (type == 4) {
                var modal = document.getElementById("driverDetails");
                modal.style.display = "block";
            } else {
                var modal = document.getElementById("driverDetails");
                modal.style.display = "none";
            }
        }
    </script>
    <script>
        $('.form-check-input').on('change', function() {
            if ($(this).is(':checked')) {
                $(".modal").modal({
                    backdrop: 'static',
                    eyboard: false
                });
            }
        });

        $('.exit').click(function() {
            $('.form-check-input').attr('checked', false);
        });
    </script>
@endsection
