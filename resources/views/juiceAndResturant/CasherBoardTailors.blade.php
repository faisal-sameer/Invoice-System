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
                        <h3>{{ $all['staff']->user->name }}</h3>
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
                            <table id="keypad" style="direction: ltr;  ;  margin-right: 1%; width: 98%; "
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
                                    <input id="inputSwitchStatus" name="Status" type="checkbox">
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
                        {{--                         cash and online with model 
       
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
                        </div> --}}
                        <div class="row" style="margin-right: 5%;margin-bottom: 5%">
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

                            <button type="button" onclick="onOpenModelCustomerInfo()"
                                style="width: 80%;margin-right: 4%" id="print" class="btn btn-info">طباعة</button>
                            {{-- 
                                <button type="submit"  onclick="onOpenModelCustomerInfo()"
                                style="width: 80%;margin-right: 4%" id="print" disabled
                                class="btn btn-info">طباعة</button> --}}

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
                                    <div class="form-group">
                                        <h3 for="exampleInputPassword1">مدة التسليم المقدرة</h3>
                                        <div class="row justify-content-start">
                                            <div class="col d-flex justify-content-start">
                                                <input type="number" class="form-control" style="width: 10%"
                                                    name="days" id="exampleInputPassword1">
                                                <div class="append">
                                                    <span class="input-group-text">يوم</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                        <div class="col-sm" style="margin-left: 20%">
                            <button id="myBtnBillToday" style="width: 30%" onclick="onOpenModelBillPrevious();"
                                class="ButtonBillToday">اصدار من فاتورة سابقة</button>

                        </div>
                        <!--    <div class="col-sm">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <button id="myBtnBillToday" onclick="OpenDiscount();" class="btn btn-info">الخصومات</button>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>-->
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
                                            <div class="col-sm-2 mb-2">
                                                <div class="cards">
                                                    <div class="card" id="myBtn{{ $items->id }}"
                                                        onclick="onOpenModel( {{ $items->id }} );">
                                                        <h2 class="card-title">{{ $items->Name }}</h2>
                                                        @if ($items->file == null)
                                                            <img id="imgBill" src="/itemsTailor/shopping-cart.gif"
                                                                alt="">
                                                        @else
                                                            <img id="imgBill" src="/{{ $items->file }}"
                                                                alt="">
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
                                                        كاش / شبكة
                                                    @elseif($item->cash != null && $item->online == null)
                                                        كاش
                                                    @elseif($item->cash == null && $item->online != null)
                                                        شبكة
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
            <div id="myModalBillPrevious" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">

                        <h2 class="exit" onclick="closeModelBillPrevious()">&times;
                        </h2>
                        اصدار فاتورة سابقة
                        <button onclick="closeModelBillPrevious();" class="btn btn-light">اصدار</button>

                    </div>
                    {{--  test ajax --}}

                    <form id="search-form">
                        <div class="col-sm">
                            <input type="number" id="Custody1" name="search" style="margin-right: 20%"
                                placeholder=" البحث برقم الفاتورة او برقم الجوال">
                        </div>
                    </form>
                    <hr>
                    <div class="row" style="margin-top: 3%;margin-bottom: 2%;margin-right: 3%">
                        <div class="col-sm">
                            <h4 id="billNumber">
                                رقم الفاتورة :
                            </h4>
                        </div>
                        <div class="col-sm">
                            <h4 id="CustomerBillName">
                                الاسم :
                            </h4>
                        </div>
                        <div class="col-sm">
                            <h4 id="phone">
                                رقم الجوال :
                            </h4>
                        </div>
                        <div class="col-sm">
                            <h4 id="createBill">
                                تاريخ الاصدار :
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <section style="margin-top: 2 %;  margin-right: 0.1%" class="row">
                            <table id="search-results"style="text-align: center">
                                <thead>
                                    <tr>
                                        <th>رقم الفاتورة</th>
                                        <th>الاسم</th>
                                        <th>العدد</th>
                                        <th>اصدار جديد </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </section>
                    </div>

                    {{-- end test ajax --}}


                </div>
            </div>

            {{-- comment           <div id="myModalDiscount" class="modal">

                <div class="modal-content">
                    <div class="modal-header">

                        <h2 class="exit" onclick="closeDiscount()">&times;
                        </h2>
                        الخصومات
                    </div>

                    <div class="cards">
                        <article class="card">
                            <header id="headerCard">
                                <h2 id="textCard">خصم اليوم الوطني</h2>
                            </header>
                            <div class="content" style="margin-top: 10%;white-space: normal l;text-align: center">
                                <p> خصم اليوم الوطني على جميع المنتجات </p>
                                <p>من يوم: 22 سبتمبر </p>
                                <p>الى يوم: 24 سبتمبر </p>
                                <p>قيمة الخصم: 92 ريال </p>

                            </div>

                            <hr>


                            <footer>
                                <button style="width: 90%;margin-right: 4%;margin-bottom: 2%" onclick="OpenDiscount();"
                                    class="btn btn-info">استخدم الخصم</button>
                            </footer>

                        </article>

                    </div>

                </div>
            </div>
 --}}


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
                                            src={{ asset('css/ymerwkwd.json') }} trigger="hover"
                                            style="width:80px;height:80px">
                                        </lord-icon>

                                        <input type="number" step="1" max="" value="1"
                                            name="quantity" id="Smallcount{{ $item->id }}" class="quantity-field"
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
                                        <button id="ExtraTopping" type="button" onclick="ExtraTopping(1);"
                                            class="btn-info" style="width: 80%">إضافات</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="margin-right: 5%;margin-bottom: 3%">
                                    <div class="col-sm">
                                        <h2>
                                            بيانات الثوب
                                        </h2>
                                    </div>
                                </div>
                                <div class="row" style="margin-left: 10% ;margin-bottom: 3%">
                                    <div class="col-sm">
                                        <input type="text" id="Custody1" name="nameC{{ $item->id }}"
                                            value="" style="width: 45%" placeholder="الاسم">
                                    </div>
                                </div>
                                <div class="row" style="margin-right: 5%;margin-bottom: 3%">

                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">سعودي</label>
                                        <input class="form-check-input" checked type="radio"
                                            name="dress{{ $item->id }}" value="1"
                                            style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">كويتي</label>
                                        <input class="form-check-input" type="radio" name="dress{{ $item->id }}"
                                            value="2" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">قطري</label>
                                        <input class="form-check-input" type="radio" name="dress{{ $item->id }}"
                                            value="3" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">إماراتي</label>
                                        <input class="form-check-input" type="radio" name="dress{{ $item->id }}"
                                            value="4" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">عماني</label>
                                        <input class="form-check-input" type="radio" name="dress{{ $item->id }}"
                                            value="5" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                    </div>
                                </div>

                                <div class="row" style="margin-left: 5%;margin-bottom: 3%">
                                    <div class="col-sm">
                                        <p id="pBill" style="text-align: center" >الطول</p>

                                        <input type="text" id="Custody1" name="hight{{ $item->id }}"
                                            style="width: 60%" placeholder="الطول">
                                    </div>
                                    <div class="col-sm">
                                        <p id="pBill" style="text-align: center">الكتف</p>

                                        <input type="text" id="Custody1" name="shoulder{{ $item->id }}"
                                            style="width: 60%" placeholder="الكتف">
                                    </div>
                                    <div class="col-sm">
                                        <p id="pBill" style="text-align: center">طول الكم</p>

                                        <input type="text" id="Custody1" name="sleeves{{ $item->id }}"
                                            style="width: 60%" placeholder="طول الكم">
                                    </div>
                                    <div class="col-sm">
                                        <p id="pBill" style="text-align: center">الرقبة</p>

                                        <input type="text" id="Custody1" name="neck{{ $item->id }}"
                                            style="width: 60%" placeholder="الرقبة">
                                    </div>
                                </div>
                                <div class="row" style="margin-left: 5%;margin-bottom: 3%">

                                    <div class="col-sm">
                                        <p id="pBill" style="text-align: center">وسع الصدر</p>

                                        <input type="text" id="Custody1" name="chest{{ $item->id }}"
                                            style="width: 60%" placeholder="وسع الصدر">
                                    </div>
                                    <div class="col-sm">
                                        <p id="pBill" style="text-align: center">وسع اليد</p>

                                        <input type="text" id="Custody1" name="expandHand{{ $item->id }}"
                                            style="width: 60%" placeholder="وسع اليد">
                                    </div>
                                    <div class="col-sm">
                                        <p id="pBill" style="text-align: center">جيب أسفل</p>

                                        <input type="text" id="Custody1" name="underpoket{{ $item->id }}"
                                            style="width: 60%" placeholder="جيب أسفل">
                                    </div>
                                </div>
                                <div class="row" style="margin-right: 5%;margin-bottom: 3%;margin-top: 3%">
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">سحاب</label>
                                        <input class="form-check-input" type="checkbox" name="zipper{{ $item->id }}"
                                            value="moredetails" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">خياطة دبل</label>
                                        <input class="form-check-input" type="checkbox"
                                            name="dobleline{{ $item->id }}" value="moredetails"
                                            style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">أسفل</label>
                                        <input class="form-check-input" type="checkbox" name="downW{{ $item->id }}"
                                            value="moredetails" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        <input type="text" id="Custody2" value=""
                                            style="width: 45%;margin-left: 40%;margin-top: 3%"
                                            name="downWDetails{{ $item->id }}" placeholder="المقاس">
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">كفة</label>
                                        <input class="form-check-input" type="checkbox" name="cuff{{ $item->id }}"
                                            value="moredetails" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        <input type="text" id="Custody2" value=""
                                            style="width: 45%;margin-left: 40%;margin-top: 3%"
                                            name="cuffDetails{{ $item->id }}" placeholder="المقاس">
                                    </div>
                                    <div class="col-sm">
                                        <label class="form-check-strong" for="inlineRadio1">تحت الجيب</label>
                                        <input class="form-check-input" type="checkbox"
                                            name="underPoketCheck{{ $item->id }}" value="moredetails"
                                            style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                                        <input type="text" id="Custody2" value=""
                                            style="width: 45%;margin-left: 40%;margin-top: 3%"name="underPoketDetails{{ $item->id }}"
                                            placeholder="المقاس">
                                    </div>
                                </div>
                                {{-- mid Style --}}
                                <hr>
                                <div class="row">
                                    <div class="col-sm" style="margin-left: 10%;margin-top: 2%;margin-bottom: 3%">
                                        <input type="text" id="Custody1" style="width: 40%"
                                            name="midstyle0Details{{ $item->id }}" placeholder="المقاس">
                                    </div>
                                </div>
                                <div class="row" style="margin-right: 15%">

                                    @foreach ($all['tailoritems'] as $itemtailor)
                                        @if ($itemtailor->type == 4)
                                            <div class="col-sm-3" style="margin-top: 3%">
                                                <label>
                                                    <input id="thobstyle" type="radio"
                                                        name="midstyle{{ $item->id }}"
                                                        value="{{ $itemtailor->id }}">
                                                    <img src="{{ $itemtailor->path }}" alt="Option 4">
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                {{-- poket  --}}
                                <hr>
                                <div class="row">
                                    <div class="col-sm" style="margin-left: 10%;margin-top: 2%;margin-bottom: 3%">
                                        <input type="text" id="Custody1" style="width: 40%"
                                            name="poketID0Details{{ $item->id }}" placeholder="المقاس">
                                    </div>
                                </div>
                                <div class="row" style="margin-right: 15%;margin-top: 3%">

                                    @foreach ($all['tailoritems'] as $itemtailor)
                                        @if ($itemtailor->type == 1)
                                            <div class="col-sm-3" style="margin-top: 3%">
                                                <label>
                                                    <input id="thobstyle" type="radio"
                                                        name="poketID{{ $item->id }}" value="{{ $itemtailor->id }}">
                                                    <img src="{{ $itemtailor->path }}" alt="Option 4">
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Top / Neck --}}
                                <hr>
                                <div class="row">
                                    <div class="col-sm" style="margin-left: 10%;margin-top: 2%;margin-bottom: 3%">
                                        <input type="text" id="Custody1" style="width: 40%"
                                            name="neckID0Details{{ $item->id }}" placeholder="المقاس">
                                    </div>
                                </div>
                                <div class="row" style="margin-right: 5%;margin-top: 3%">

                                    @foreach ($all['tailoritems'] as $itemtailor)
                                        @if ($itemtailor->type == 2)
                                            <div class="col-sm">
                                                <label>
                                                    <input id="thobstyle" type="radio"
                                                        name="neckID{{ $item->id }}" value="{{ $itemtailor->id }}">
                                                    <img src="{{ $itemtailor->path }}" alt="Option 4">
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- hand --}}
                                <hr>
                                <div class="row">
                                    <div class="col-sm" style="margin-left: 10%;margin-top: 2%;margin-bottom: 3%">
                                        <input type="text" id="Custody1" style="width: 40%"
                                            name="handID0Details{{ $item->id }}" placeholder="المقاس">
                                    </div>
                                </div>
                                <div class="row" style="margin-right: 10%;margin-top: 3%">

                                    @foreach ($all['tailoritems'] as $itemtailor)
                                        @if ($itemtailor->type == 3)
                                            <div class="col-sm">
                                                <label>
                                                    <input id="thobstyle" type="radio"
                                                        name="handID{{ $item->id }}" value="{{ $itemtailor->id }}">
                                                    <img src="{{ $itemtailor->path }}" alt="Option 4">
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <hr>
                                <div class="row" style="margin-right: 20%;margin-top: 2%">

                                    <div class="col-sm" style="margin-left: 60%">
                                        <input type="text" id="Custody1" style="width: 100%;"
                                            name="downhandup0{{ $item->id }}" placeholder="المقاس U">

                                    </div>
                                </div>
                                <div class="row" style="margin-top: 2%">
                                    <div class="col-sm">
                                        <input type="text" id="Custody1" style="width: 50%;"
                                            name="downhandR0{{ $item->id }}" placeholder="المقاس R">
                                    </div>
                                    <div class="col-sm" style="margin-left: 10%">
                                        <img src="/image/downhand.png" alt="Option 4">
                                    </div>
                                </div>
                                <div class="row" style="margin-right: 20%;margin-top: 2%">

                                    <div class="col-sm" style="margin-left: 60%">
                                        <input type="text" id="Custody1" style="width: 100%;"
                                            name="downhandD0{{ $item->id }}" placeholder="المقاس">

                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="margin-right: 5%">
                                    <h2>
                                        ملاحظات
                                    </h2>
                                </div>

                                <div class="paper">
                                    <div class="paper-content">
                                        <textarea name="notes{{ $item->id }}" style="white-space: nowrap;"></textarea>
                                    </div>
                                </div>

                            </div>
                        @else
                            <input class="form-check-input" id="small{{ $item->id }}"
                                onclick="show(1,'{{ $item->id }}');" type="checkbox" name="small"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;" readonly hidden id="inlineRadio1">
            @endif
            <!--end small-->


            <button class="btn btn-info" id="font-button"
                onclick="clicksubmit('{{ $item->id }}' , '{{ $item->Name }}', '{{ $item->price }}')">حفظ</button>
    </div>
    </div>

    </div>
    @endforeach
    @endif
    {{-- model zero --}}

    <div style="direction: rtl">
        <div id="myModal0" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">

                    <h2 class="exit" onclick="closeModel('0');">&times;
                    </h2>
                    <h2 id="title0"> </h2>
                </div>
                <div class="modal-body">

                    <!--start Small-->

                    <div class="row" style="margin-top: 2%">

                        <div class="form-check form-check-inline">


                            <select name="item0"
                                onchange="changeItem(this.value, this.options[this.selectedIndex].text, this.options[this.selectedIndex].getAttribute('data-price'))"
                                class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                <option value="0">اختار نوع القماش</option>
                                @foreach ($all['items'] as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->Small_Price }}">
                                        {{ $item->Name }}</option>
                                @endforeach

                            </select>
                            <input class="form-check-input" id="small0" onclick="show(1,'0');" type="checkbox"
                                name="small" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                            <label class="form-check-strong" for="inlineRadio1"
                                style="font-weight: bolder;font-size: larger;" id="smallName0">تفاصيل</label>

                        </div>
                    </div>
                </div>
                <br>
                <div id="div0small" class="input-group hide">
                    <div class="row">
                        <div class="col-md-6">
                            <lord-icon onclick="decrementValueNEw(1,'0')" src={{ asset('css/ymerwkwd.json') }}
                                trigger="hover" style="width:80px;height:80px">
                            </lord-icon>

                            <input type="number" step="1" max="" value="1" name="quantity0"
                                id="Smallcount0" class="quantity-field"
                                style="font-weight: bolder;
                   font-size: larger;">
                            <input id="SmallPrice0" hidden readonly value="price">
                            <lord-icon onclick="incrementValueNEw(1,'0')" alt="اضافة" title="اضافة"
                                src={{ asset('css/xzksbhzh.json') }} trigger="hover"
                                colors="primary:#08a88a,secondary:#ebe6ef" style="width:80px;height:80px">
                            </lord-icon>
                        </div>
                        <div class="col-md">
                            <strong id="SmallPrices0">0
                                ريال</strong>
                        </div>
                        <div class="col-md">
                            <button id="ExtraTopping" type="button" onclick="ExtraTopping(1);" class="btn-info"
                                style="width: 80%">إضافات</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="margin-right: 5%;margin-bottom: 3%">
                        <div class="col-sm">
                            <h2>
                                بيانات الثوب
                            </h2>
                        </div>
                    </div>
                    <div class="row" style="margin-left: 10%;margin-bottom: 3%">
                        <div class="col-sm">
                            <input type="text" id="Custody1" name="nameC0" value="" style="width: 45%"
                                placeholder="الاسم">
                        </div>
                    </div>
                    <div class="row" style="margin-right: 5%;margin-bottom: 3%">

                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">سعودي</label>
                            <input class="form-check-input" checked type="radio" name="dress0" value="1"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">كويتي</label>
                            <input class="form-check-input" type="radio" name="dress0" value="2"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">قطري</label>
                            <input class="form-check-input" type="radio" name="dress0" value="3"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">إماراتي</label>
                            <input class="form-check-input" type="radio" name="dress0" value="4"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">عماني</label>
                            <input class="form-check-input" type="radio" name="dress0" value="5"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                        </div>
                    </div>
                    <div class="row" style="margin-left: 5%">

                        <div class="col-sm">
                            <p id="pBill" style="text-align: center">الطول</p>

                            <input type="text" id="Custody1" name="hight0" style="width: 60%"
                                placeholder="الطول">
                        </div>
                        <div class="col-sm">
                            <p id="pBill" style="text-align: center">الكتف</p>

                            <input type="text" id="Custody1" name="shoulder0" style="width: 60%"
                                placeholder="الكتف">
                        </div>
                        <div class="col-sm">
                            <p id="pBill" style="text-align: center">طول الكم</p>

                            <input type="text" id="Custody1" name="sleeves0" style="width: 60%"
                                placeholder="طول الكم">
                        </div>
                    </div>
                    <div class="row" style="margin-left: 5%">
                        <div class="col-sm">
                            <p id="pBill" style="text-align: center">الرقبة</p>

                            <input type="text" id="Custody1" name="neck0" style="width: 60%"
                                placeholder="الرقبة">
                        </div>
                        <div class="col-sm">
                            <p id="pBill" style="text-align: center">وسع الصدر</p>

                            <input type="text" id="Custody1" name="chest0" style="width: 60%"
                                placeholder="وسع الصدر">
                        </div>
                        <div class="col-sm">
                            <p id="pBill" style="text-align: center">وسع اليد</p>

                            <input type="text" id="Custody1" name="expandHand0" style="width: 60%"
                                placeholder="وسع اليد">
                        </div>
                        <div class="col-sm">
                            <p id="pBill" style="text-align: center">جيب أسفل</p>

                            <input type="text" id="Custody1" name="underpoket0" style="width: 60%"
                                placeholder="جيب أسفل">
                        </div>
                    </div>
                    <div class="row" style="margin-right: 5%;margin-bottom: 3%;margin-top: 3%">
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">سحاب</label>
                            <input class="form-check-input" type="checkbox" name="zipper0" value="moredetails"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">خياطة دبل</label>
                            <input class="form-check-input" type="checkbox" name="dobleline0" value="moredetails"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">أسفل</label>
                            <input class="form-check-input" type="checkbox" name="downW0" value="moredetails"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                            <input type="text" id="Custody2" value=""
                                style="width: 45%;margin-left: 40%;margin-top: 3%" name="downWDetails"
                                placeholder="المقاس">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">كفة</label>
                            <input class="form-check-input" type="checkbox" name="cuff0" value="moredetails"
                                style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                            <input type="text" id="Custody2" value=""
                                style="width: 45%;margin-left: 40%;margin-top: 3%" name="cuffDetails"
                                placeholder="المقاس">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-strong" for="inlineRadio1">تحت الجيب</label>
                            <input class="form-check-input" type="checkbox" name="underPoketCheck0"
                                value="moredetails" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                            <input type="text" id="Custody2" value=""
                                style="width: 45%;margin-left: 40%;margin-top: 3%" name="underPoketDetails"
                                placeholder="المقاس">
                        </div>
                    </div>

                    {{-- mid Style --}}
                    <hr>
                    <div class="row">
                        <div class="col-sm" style="margin-left: 10%;margin-top: 2%;margin-bottom: 3%">
                            <input type="text" id="Custody1" style="width: 40%" name="midstyle0Details"
                                placeholder="المقاس">
                        </div>
                    </div>
                    <div class="row" style="margin-right: 15%">

                        @foreach ($all['tailoritems'] as $itemtailor)
                            @if ($itemtailor->type == 4)
                                <div class="col-sm-3" style="margin-top: 3%">
                                    <label>
                                        <input id="thobstyle" type="radio" name="midstyle0"
                                            value="{{ $itemtailor->id }}">
                                        <img src="{{ $itemtailor->path }}" alt="Option 4">
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    {{-- poket  --}}
                    <hr>
                    <div class="row">
                        <div class="col-sm" style="margin-left: 10%;margin-top: 2%;margin-bottom: 3%">
                            <input type="text" id="Custody1" style="width: 40%" name="poketID0Details"
                                placeholder="المقاس">
                        </div>
                    </div>
                    <div class="row" style="margin-right: 15%;margin-top: 3%">

                        @foreach ($all['tailoritems'] as $itemtailor)
                            @if ($itemtailor->type == 1)
                                <div class="col-sm-3" style="margin-top: 3%">
                                    <label>
                                        <input id="thobstyle" type="radio" name="poketID0"
                                            value="{{ $itemtailor->id }}">
                                        <img src="{{ $itemtailor->path }}" alt="Option 4">
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Top / Neck --}}
                    <hr>
                    <div class="row">
                        <div class="col-sm" style="margin-left: 10%;margin-top: 2%;margin-bottom: 3%">
                            <input type="text" id="Custody1" style="width: 40%" name="neckID0Details"
                                placeholder="المقاس">
                        </div>
                    </div>
                    <div class="row" style="margin-right: 5%;margin-top: 3%">

                        @foreach ($all['tailoritems'] as $itemtailor)
                            @if ($itemtailor->type == 2)
                                <div class="col-sm">
                                    <label>
                                        <input id="thobstyle" type="radio" name="neckID0"
                                            value="{{ $itemtailor->id }}">
                                        <img src="{{ $itemtailor->path }}" alt="Option 4">
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- hand --}}
                    <hr>
                    <div class="row">
                        <div class="col-sm" style="margin-left: 10%;margin-top: 2%;margin-bottom: 3%">
                            <input type="text" id="Custody1" style="width: 40%" name="handID0Details"
                                placeholder="المقاس">
                        </div>
                    </div>
                    <div class="row" style="margin-right: 10%;margin-top: 3%">

                        @foreach ($all['tailoritems'] as $itemtailor)
                            @if ($itemtailor->type == 3)
                                <div class="col-sm">
                                    <label>
                                        <input id="thobstyle" type="radio" name="handID0"
                                            value="{{ $itemtailor->id }}">
                                        <img src="{{ $itemtailor->path }}" alt="Option 4">
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <hr>
                    <div class="row" style="margin-right: 20%;margin-top: 2%">

                        <div class="col-sm" style="margin-left: 60%">
                            <input type="text" id="Custody1" style="width: 100%;" name="downhandup0"
                                placeholder="المقاس">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 2%">
                        <div class="col-sm">
                            <input type="text" id="Custody1" style="width: 50%;" name="downhandR0"
                                placeholder="المقاس">
                        </div>
                        <div class="col-sm">
                            <img src="/image/downhand.png" alt="Option 4">
                        </div>
                    </div>
                    <div class="row" style="margin-right: 20%;margin-top: 2%">

                        <div class="col-sm" style="margin-left: 60%">
                            <input type="text" id="Custody1" style="width: 100%;" name="downhandD0"
                                placeholder="المقاس">
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="margin-right: 5%">
                        <h2>
                            ملاحظات
                        </h2>
                    </div>

                    <div class="paper">
                        <div class="paper-content">
                            <textarea name="notes0" style="white-space: nowrap;"></textarea>
                        </div>
                    </div>

                </div>

                <!--end small-->


                <button class="btn btn-info" id="font-button0">حفظ</button>
            </div>
        </div>

    </div>


    {{-- end model zero --}}





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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $.ajax({
            method: 'GET',
            url: '/CasherBoardTailors',
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
        $(document).ready(function() {
            $('#search-form input[name="search"]').on('keyup', function() {
                searchUsers();
            });

            function searchUsers() {
                var search = $('#search-form input[name="search"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('search') }}',
                    data: {
                        search: search
                    },
                    success: function(data) {
                        var tableRows = '';
                        // Detail = [1, 2, 3, 4, 5];

                        $.each(data['details'], function(index, details) {

                            tableRows += `<tr>
                            
                  <td scope="col">${details.Bill_id}</td>
                  <td scope="col">${details.name}</td>
                  <td scope="col">${details.count_no}</td>
                  <td scope="col"><lord-icon onclick="onOpenModel(0); 
                  putValues(['${details.Bill_id}','${details.name}' ,
                   '${details.count_no}' , '${details.length}' , '${details.shoulder}',
                    '${details.sleeves}' , '${details.neck}' ,'${details.chest}',
                    '${details.expand_hand}' , '${details.under_poket}' ,'${details.notes}', '${details.zipper}',
                    '${details.double_line}','${details.under}','${details.cuff}','${details.under_poket_check}'
                    ,'${details.up_poket_details}','${details.neck_details}','${details.hand_details}'
                    ,'${details.midstyle_details}','${details.downhand_up_details}','${details.downhand_right_details}',
                    '${details.downhand_down_details}','${details.under_details}','${details.cuff_details}',
                    '${details.under_poket_details}']  );" src="https://cdn.lordicon.com/rfbqeber.json" trigger="hover" style="width:47px;height:47px"></lord-icon></td>
                </tr>`;
                        });
                        var date = new Date(data['bill']['created_at']);
                        var formattedDate = date.toLocaleDateString({
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit'
                        });

                        document.getElementById("createBill").innerHTML = " تاريخ الاصدار :" +
                            formattedDate;
                        document.getElementById("phone").innerHTML = "   رقم الجوال :" + data['bill'][
                            'CustomerPhone'
                        ];
                        document.getElementById("CustomerBillName").innerHTML = " الاسم :" + data[
                            'bill'][
                            'CustomerName'
                        ];
                        document.getElementById("billNumber").innerHTML = " رقم الفاتورة :" + data[
                            'bill']['id'];
                        $('#search-results tbody').html(tableRows);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                        alert('Error fetching search results.');
                    }
                });
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
        function putValues(data) {
            console.log(data);
            document.getElementById("Smallcount0").value = data[2];
            document.getElementsByName("nameC0")[0].value = data[1];
            var hight = document.getElementsByName("hight0")[0].value = data[3];
            var shoulder = document.getElementsByName("shoulder0")[0].value = data[4];
            var sleeves = document.getElementsByName("sleeves0")[0].value = data[5];
            var neck = document.getElementsByName("neck0")[0].value = data[6];
            var chest = document.getElementsByName("chest0")[0].value = data[7];
            var expandHand = document.getElementsByName("expandHand0")[0].value = data[8];
            var underpoket = document.getElementsByName("underpoket0")[0].value = data[9];
            var zipper = document.getElementsByName("zipper0")[0].checked = data[11] == '1' ? true : false;
            var dobleline = document.getElementsByName("dobleline0")[0].checked = data[12] == '1' ? true : false;
            var downW = document.getElementsByName("downW0")[0].checked = data[13] == '1' ? true : false;
            var cuff = document.getElementsByName("cuff0")[0].checked = data[14] == '1' ? true : false;
            var underPoketCheck = document.getElementsByName("underPoketCheck0")[0].checked = data[15] == '1' ? true :
                false;
            var notes = document.getElementsByName("midstyle0Details")[0].value = data[16];
            var notes = document.getElementsByName("poketID0Details")[0].value = data[17];
            var notes = document.getElementsByName("neckID0Details")[0].value = data[18];
            var notes = document.getElementsByName("handID0Details")[0].value = data[19];
            var notes = document.getElementsByName("notes0")[0].value = data[10];

            var downWDetails = document.getElementsByName("downWDetails")[0].value = data[23];
            var cuffDetails = document.getElementsByName("cuffDetails")[0].value = data[24];
            var underPoketDetails = document.getElementsByName("underPoketDetails")[0].value = data[25];
            var downhandup = document.getElementsByName("downhandup0")[0].value = data[20];
            var downhandR = document.getElementsByName("downhandR0")[0].value = data[21];
            var downhandD = document.getElementsByName("downhandD0")[0].value = data[22];

        }



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

        function onOpenModelBillPrevious() {
            var modal = document.getElementById("myModalBillPrevious");
            modal.style.display = "block";

        }

        function closeModelBillPrevious() {
            var modal = document.getElementById("myModalBillPrevious");
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

                default:
                    break;
            }
        }

        function incrementValueNEw($size, $item) {
            switch ($size) {
                case 1:

                    document.getElementById('Smallcount' + $item).value++;

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

                default:
                    break;
            }
        }
    </script>


    <script>
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

                        newPeiceWTax.value = parseInt($newPrice) + parseFloat(oldtax.value);
                        document.getElementById("rest").value = parseInt($newPrice) + parseFloat(oldtax.value);
                    }
                    myobj.remove();
                }
            }
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

        function changeItem(id, name, price) {
            document.getElementById("font-button0").setAttribute("onclick", "clicksubmit('" + id +
                "', '" + name +
                "', 130);");

            console.log(id, name, price);
            console.log('Fa');
        }
        var $i = 1;


        function clicksubmit($item, $name, $price) {
            try {


                console.log("Here");
                console.log($item, $name, $price);
                var detailsTailor = [];
                var extras = @json($all['extraToppings']);

                if (document.getElementById('small' + $item).checked == true) {

                    var oldBill = document.getElementById('BillArea').innerText;
                    var smallName = document.getElementById('smallName' + $item).innerText;
                    var countSmall = document.getElementById('Smallcount' + $item).value;
                    var priceSmall = document.getElementById('SmallPrice' + $item).value;
                    if (smallTopping) {
                        var nameNew = $name;
                        $name = nameNew + ' اضافة ' + toppingsValue;
                        priceSmall = parseInt(priceSmall) + parseInt(toppingsPrice);
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
                    // details get inputs 
                    document.getElementById("rest").value = parseInt(oldprice.value);

                    var dress = document.querySelector('input[type="radio"][name="dress' + $item + '"]:checked').value;
                    var name = document.getElementsByName("nameC" + $item)[0].value;
                    var hight = document.getElementsByName("hight" + $item)[0].value;
                    var shoulder = document.getElementsByName("shoulder" + $item)[0].value;
                    var sleeves = document.getElementsByName("sleeves" + $item)[0].value;
                    var neck = document.getElementsByName("neck" + $item)[0].value;
                    var chest = document.getElementsByName("chest" + $item)[0].value;
                    var expandHand = document.getElementsByName("expandHand" + $item)[0].value;
                    var underpoket = document.getElementsByName("underpoket" + $item)[0].value;
                    var zipper = document.getElementsByName("zipper" + $item)[0].checked;
                    var dobleline = document.getElementsByName("dobleline" + $item)[0].checked;
                    var downW = document.getElementsByName("downW" + $item)[0].checked;
                    var cuff = document.getElementsByName("cuff" + $item)[0].checked;
                    var underPoketCheck = document.getElementsByName("underPoketCheck" + $item)[0].checked;
                    var notes = document.getElementsByName("notes" + $item)[0].value;

                    var midstyle = document.querySelector('input[type="radio"][name="midstyle' + $item + '"]:checked')
                        .value;
                    var poketID = document.querySelector('input[type="radio"][name="poketID' + $item + '"]:checked').value;
                    var neckID = document.querySelector('input[type="radio"][name="neckID' + $item + '"]:checked').value;
                    var handID = document.querySelector('input[type="radio"][name="handID' + $item + '"]:checked').value;

                    var midstyleDetails = document.getElementsByName("midstyle0Details" + $item)[0].value;
                    var poketIDDetails = document.getElementsByName("poketID0Details" + $item)[0].value;
                    var neckIDDetails = document.getElementsByName("neckID0Details" + $item)[0].value;
                    var handIDDetails = document.getElementsByName("handID0Details" + $item)[0].value;
                    var downWDetails = document.getElementsByName("downWDetails" + $item)[0].value;
                    var cuffDetails = document.getElementsByName("cuffDetails" + $item)[0].value;
                    var underPoketDetails = document.getElementsByName("underPoketDetails" + $item)[0].value;
                    var downhandup = document.getElementsByName("downhandup0" + $item)[0].value;
                    var downhandR = document.getElementsByName("downhandR0" + $item)[0].value;
                    var downhandD = document.getElementsByName("downhandD0" + $item)[0].value;


                    detailsTailor.push([$i, name, dress, hight, shoulder, sleeves, neck, chest, expandHand, underpoket,
                        zipper,
                        dobleline, downW, cuff, underPoketCheck, midstyle, poketID, neckID, handID, midstyleDetails,
                        poketIDDetails,
                        neckIDDetails, handIDDetails, downhandup, downhandR, downhandD, downWDetails, cuffDetails,
                        underPoketDetails
                    ]);

                    document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                        " <div class=" +
                        'col-lg-3 mb-3' +
                        ">  <p id=" +
                        'add' + $i + " " +
                        "onclick = " +
                        " Af(" + $i + "); " +
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
                    // details 
                    document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                        "<div ><input hidden id='add" + $i +
                        "' style='display: none;' name='details[]' value='" + detailsTailor + "'></div>");
                    // notes 
                    document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                        "<div ><input hidden id='add" + $i +
                        "' style='display: none;' name='notes[]' value='" + notes + "'></div>");

                    $i++;


                }
                if (document.getElementById('small0').checked == true) {

                    var oldBill = document.getElementById('BillArea').innerText;
                    var smallName = document.getElementById('smallName' + $item).innerText;
                    var countSmall = document.getElementById('Smallcount' + $item).value;
                    var priceSmall = document.getElementById('SmallPrice' + $item).value;
                    if (smallTopping) {
                        var nameNew = $name;
                        $name = nameNew + ' اضافة ' + toppingsValue;
                        priceSmall = parseInt(priceSmall) + parseInt(toppingsPrice);
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
                    // details get inputs 
                    document.getElementById("rest").value = parseInt(oldprice.value);

                    var dress = document.querySelector('input[type="radio"][name="dress0"]:checked').value;
                    var name = document.getElementsByName("nameC0")[0].value;
                    var hight = document.getElementsByName("hight0")[0].value;
                    var shoulder = document.getElementsByName("shoulder0")[0].value;
                    var sleeves = document.getElementsByName("sleeves0")[0].value;
                    var neck = document.getElementsByName("neck0")[0].value;
                    var chest = document.getElementsByName("chest0")[0].value;
                    var expandHand = document.getElementsByName("expandHand0")[0].value;
                    var underpoket = document.getElementsByName("underpoket0")[0].value;
                    var zipper = document.getElementsByName("zipper0")[0].checked;
                    var dobleline = document.getElementsByName("dobleline0")[0].checked;
                    var downW = document.getElementsByName("downW0")[0].checked;
                    var cuff = document.getElementsByName("cuff0")[0].checked;
                    var underPoketCheck = document.getElementsByName("underPoketCheck0")[0].checked;
                    var notes = document.getElementsByName("notes0")[0].value;

                    var midstyle = document.querySelector('input[type="radio"][name="midstyle0"]:checked').value;
                    var poketID = document.querySelector('input[type="radio"][name="poketID0"]:checked').value;
                    var neckID = document.querySelector('input[type="radio"][name="neckID0"]:checked').value;
                    var handID = document.querySelector('input[type="radio"][name="handID0"]:checked').value;

                    var midstyleDetails = document.getElementsByName("midstyle0Details")[0].value;
                    var poketIDDetails = document.getElementsByName("poketID0Details")[0].value;
                    var neckIDDetails = document.getElementsByName("neckID0Details")[0].value;
                    var handIDDetails = document.getElementsByName("handID0Details")[0].value;
                    var downWDetails = document.getElementsByName("downWDetails")[0].value;
                    var cuffDetails = document.getElementsByName("cuffDetails")[0].value;
                    var underPoketDetails = document.getElementsByName("underPoketDetails")[0].value;
                    var downhandup = document.getElementsByName("downhandup0")[0].value;
                    var downhandR = document.getElementsByName("downhandR0")[0].value;
                    var downhandD = document.getElementsByName("downhandD0")[0].value;



                    detailsTailor.push([$i, name, dress, hight, shoulder, sleeves, neck, chest, expandHand, underpoket,
                        zipper,
                        dobleline, downW, cuff, underPoketCheck, midstyle, poketID, neckID, handID, midstyleDetails,
                        poketIDDetails,
                        neckIDDetails, handIDDetails, downhandup, downhandR, downhandD, downWDetails, cuffDetails,
                        underPoketDetails
                    ]);

                    document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                        " <div class=" +
                        'col-lg-3 mb-3' +
                        ">  <p id=" +
                        'add' + $i + " " +
                        "onclick = " +
                        " Af(" + $i + "); " +
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
                    // details 
                    document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                        "<div ><input hidden id='add" + $i +
                        "' style='display: none;' name='details[]' value='" + detailsTailor + "'></div>");
                    // notes 
                    document.getElementById("add_after_me").insertAdjacentHTML("afterbegin",
                        "<div ><input hidden id='add" + $i +
                        "' style='display: none;' name='notes[]' value='" + notes + "'></div>");

                    $i++;

                    var modal = document.getElementById("myModal0");
                    modal.style.display = "none";
                    var modal = document.getElementById("myModal" + $item);
                    modal.style.display = "none";
                }


                var modal = document.getElementById("myModal" + $item);
                modal.style.display = "none";
            } catch (error) {
                Swal.fire({
                    icon: 'warning',
                    title: 'خطاء ',
                    text: 'الرجاء التاكد من ادخال جميع البيانات بالشكل الصحيح ',
                });
                console.log(error)
            }
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

        function EditBill() {
            document.getElementById('EditBill').style.display = "block";
        }

        function CloseEditBill() {
            document.getElementById('EditBill').style.display = "none"
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
