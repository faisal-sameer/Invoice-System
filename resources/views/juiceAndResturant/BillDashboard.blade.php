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
            <li class="nav-item">
                <a class="nav-link" id="listNav/BillDashboard" href="{{ route('BillDashboard') }}">{{ __('الفواتير') }}</a>
            </li>

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
    <div class="container" style="direction: rtl" id="about">
        <h2 id="subtitle">

            الفواتير
        </h2>
        <hr>
        <br><br>
        <form method="POST" action="{{ route('BillDashboard') }}">
            @csrf

            <div class="row" id="rowBillDash">
                <div class="col-sm" style="margin-left: 5%">
                    <p id="pBill">من</p>
                    <input type="date" id="inputBill" name="day" placeholder="من يوم">

                </div>
                <div class="col-sm">
                    <p id="pBill">إلى</p>
                    <input type="date" id="inputBill" name="to" placeholder="إلى يوم">

                </div>
                <div class="col-sm">
                    <p id="pBill">من الساعة</p>

                    <input type="text" id="time" name="fromHour" value="" placeholder="وقت البدأ">
                </div>
                <div class="col-sm">
                    <p id="pBill">الى الساعة</p>

                    <input type="text" id="time" name="toHour" value="" placeholder="وقت الانتهاء">
                </div>

            </div>
            <div class="row" id="rowBillDash" style="margin-top: 3%">
                <div class="col-sm">
                    <p id="pBill">الشهر</p>

                    <input type="month" id="inputBill" name="month" min="2018-03" max="2023-12">

                </div>

                <div class="col-sm">
                    <p id="pBill">السنة</p>

                    <input type="text" name="year" class="form-control" name="datepicker" id="datepicker" />

                </div>
                <div class="col-sm">
                    <p id="pBill">اختر الفرع</p>

                    <select name="branchID" class="form-select form-select-lg mb-3" id="inpRSelec"
                        aria-label=".form-select-lg example">
                        <option value="0"></option>
                        @foreach ($all['branch'] as $item)
                            <option value="{{ $item->id }}">{{ $item->address }} </option>
                        @endforeach

                    </select>

                </div>
                <div class="col-sm">
                    <p id="pBill">حالة الفاتورة</p>

                    <select name='type' class="form-select form-select-lg mb-3" id="inpRSelec"
                        aria-label=".form-select-lg example">
                        <option selected value="0"> </option>
                        <option value="1">منتهي</option>
                        <option value="2">ملغية</option>
                    </select>

                </div>
                <div class="col-sm">
                    <p id="pBill">ابحث برقم الفاتورة</p>

                    <input type="number" name="BillNo" id="inpRSelec">

                </div>
            </div>



            <div class="row">
                <button type="submit" class="btn btn-info">ابحث</button>

            </div>
        </form>


        @if ($all['NoData'] == true)
            <br>
            <h1 class="text-center" style="color: red">{{ $all['msg'] }}</h1>
        @else
            @if ($all['branchDetails'] == true)
                <div class="row">
                    <section style="margin-top: 1%" class="row">

                        <div class="row">



                            <div class="col-md-4">
                                <h2>المداخيل والمصاريف</h2>
                            </div>
                        </div>
                        <div class="row" id="rowBillDash">
                            <div class="col-sm-4">
                                @if ($all['daySelect'] == null)
                                    <form method="POST" id="expID" action="{{ route('Export') }}">
                                        @csrf
                                        <input type="text" readonly hidden name="id" placeholder="id">
                                        <input type="text" readonly hidden name="dateSelect" placeholder="date"
                                            value="{{ $all['dateSelect'] }}">
                                        <input type="text" readonly hidden name="branchSelect"
                                            value="{{ $all['branchSelect'] }}">
                                        <input type="text" readonly hidden name="daySelect"
                                            value="{{ $all['daySelect'] }}">
                                        <input type="text" readonly hidden name="monthSelect"
                                            value="{{ $all['monthSelect'] }}">
                                        <input type="text" readonly hidden name="yearSelect"
                                            value="{{ $all['yearSelect'] }}">
                                        <button type="submit" class="btn btn-success">تحميل المداخيل
                                            والمصاريف</button>
                                    </form>
                                @else
                                @endif
                            </div>

                            <div class="col-sm-4" id="colBillDash">

                                <form method="POST" style="margin-bottom: 2%" action="{{ route('BarChart') }}">
                                    @csrf <input type="text" readonly hidden name="id" placeholder="id">
                                    <input type="text" readonly hidden name="dateSelect1" placeholder="date"
                                        value="{{ $all['dateSelect'] }}">

                                    <input type="text" readonly hidden name="branchSelect1"
                                        value="{{ $all['branchSelect'] }}">
                                    <input type="text" readonly hidden name="daySelect1"
                                        value="{{ $all['daySelect'] }}">
                                    <input type="text" readonly hidden name="monthSelect1"
                                        value="{{ $all['monthSelect'] }}">
                                    <input type="text" readonly hidden name="yearSelect1"
                                        value="{{ $all['yearSelect'] }}">

                                    <button style="width: 50%" type="submit" class="btn btn-outline-secondary">

                                        <div class="row">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-pie-chart" viewBox="0 0 16 16">
                                                <path
                                                    d="M7.5 1.018a7 7 0 0 0-4.79 11.566L7.5 7.793V1.018zm1 0V7.5h6.482A7.001 7.001 0 0 0 8.5 1.018zM14.982 8.5H8.207l-4.79 4.79A7 7 0 0 0 14.982 8.5zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z" />
                                            </svg>
                                            <span>احصائية</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <input type="text" readonly hidden name="dateSelect" placeholder="date"
                            value="{{ $all['dateSelect'] }}">
                        <input type="text" readonly hidden name="branchSelect" value="{{ $all['branchSelect'] }}">
                        <input type="text" readonly hidden name="daySelect" value="{{ $all['daySelect'] }}">
                        <input type="text" readonly hidden name="monthSelect" value="{{ $all['monthSelect'] }}">
                        <input type="text" readonly hidden name="yearSelect" value="{{ $all['yearSelect'] }}">

                        {{ $all['expense']->appends([
                                'Bills' => $all['Bills']->currentPage(),
                                'dateSelect' => $all['dateSelect'],
                                'year' => $all['yearSelect'],
                                'month' => $all['monthSelect'],
                                'branchID' => $all['branchSelect'],
                            ])->links() }}

                        @if ($all['expense'] != null)
                            {{ $all['expense']->appends([
                                    'Bills' => $all['Bills']->currentPage(),
                                    'dateSelect' => $all['dateSelect'],
                                    'year' => $all['yearSelect'],
                                    'month' => $all['monthSelect'],
                                    'branchID' => $all['branchSelect'],
                                ])->links() }}
                        @endif
                        <div class="table-responsive">

                            <table style="text-align: center;width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center" scope="col">الفرع</th>
                                        <th style="text-align: center" scope="col">التاريخ</th>
                                        <th style="text-align: center" scope="col">المصروفات</th>
                                        <th style="text-align: center" scope="col">المكسب</th>
                                        <th style="text-align: center" scope="col">الكاش</th>
                                        <th style="text-align: center" scope="col">شبكة</th>
                                        <th style="text-align: center" scope="col">المجموع</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all['expense'] as $key => $item)
                                        <tr>
                                            <td>
                                                <button onclick="onOpenExpenseModel('{{ $item->id }}');"
                                                    class="btns" style="background-color: white;">
                                                    <p style="color: black">{{ $item->Branch->address }}</p>
                                                </button>
                                            </td>
                                            <td>{{ $item->month }}</td>
                                            <td>{{ $all['totalExpense'][$item->id] }}
                                            </td>
                                            <td>{{ $all['totalIncome'][$item->branch_id][$item->month] }}</td>
                                            <td>{{ $all['totalIncomeCash'][$item->branch_id][$item->month] }}</td>
                                            <td>{{ $all['totalIncomeOnline'][$item->branch_id][$item->month] }}</td>
                                            <td>{{ $all['totalIncome'][$item->branch_id][$item->month] - $all['totalExpense'][$item->id] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>المجموع</td>
                                        <td></td>
                                        <td>{{ $all['allExpense'] }}</td>
                                        <td>{{ $all['allIncome'] }}</td>
                                        <td>{{ $all['allIncomeCash'] }}</td>
                                        <td>{{ $all['allIncomeOnline'] }}</td>
                                        <td>{{ $all['allIncome'] - $all['allExpense'] }}</td>
                                    </tr>





                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            @else
            @endif

            @if ($all['DayDetails'])
                <div class="row">
                    <section style="margin-top: 5%" class="row">
                        <div class="table-responsive">
                            <table style="text-align: center;width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center" scope="col">الفرع</th>
                                        <th style="text-align: center" scope="col">التاريخ</th>
                                        <th style="text-align: center" scope="col">المصروفات</th>
                                        <th style="text-align: center" scope="col">المكسب</th>

                                        <th style="text-align: center" scope="col">الكاش</th>
                                        <th style="text-align: center" scope="col">شبكة</th>

                                        <th style="text-align: center" scope="col">المجموع</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    <tr>
                                        <td>#</td>
                                        <td>{{ $all['day'] }} </td>
                                        <td>{{ $all['allExpense'] }}</td>
                                        <td>{{ $all['allIncome'] }}</td>
                                        <td>{{ $all['allIncomeCash'] }}</td>
                                        <td>{{ $all['allIncomeOnline'] }}</td>
                                        <td>{{ $all['allIncome'] - $all['allExpense'] }}</td>
                                    </tr>





                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            @else
            @endif
            <br>
            <div class="row">

                <div class="col-md-4">
                    <form method="POST" id="expID" action="{{ route('ChartItem') }}">
                        @csrf
                        <input type="text" readonly hidden name="id" placeholder="id">
                        <input type="text" readonly hidden name="dateSelect2" placeholder="date"
                            value="{{ $all['dateSelect'] }}">
                        <input type="text" readonly hidden name="branchSelect2" value="{{ $all['branchSelect'] }}">
                        <input type="date" readonly hidden name="toSelect2" placeholder="date"
                            value="{{ $all['to'] }}">
                        <input type="date" readonly hidden name="daySelect2" value="{{ $all['daySelect'] }}">
                        <input type="text" readonly hidden name="monthSelect2" value="{{ $all['monthSelect'] }}">
                        <input type="text" readonly hidden name="yearSelect2" value="{{ $all['yearSelect'] }}">
                        <input type="text" readonly hidden name="seqID2" value="{{ $all['seqID'] }}">
                        <button type="submit" class="btn btn-info">احصائية المنتجات
                            والمصاريف</button>

                    </form>
                </div>

                <div class="col-md-4">
                    <button onclick="showBill()" id="inpupformore" class="btn btn-info"> اظهار الفواتير
                    </button>
                </div>
            </div>
            <section id="dots">

            </section>
            <section style="margin-top: 5%" id="BillDatil" class="row hide3">
                <div class="row">
                    <div class="col-md-8">
                        <h2>الفواتير</h2>
                    </div>
                </div>
                @if ($all['expense'] != null)
                    {{ $all['Bills']->appends([
                            'Exp' => $all['expense']->currentPage(),
                            'dateSelect' => $all['dateSelect'],
                            'year' => $all['yearSelect'],
                            'month' => $all['monthSelect'],
                            'branchID' => $all['branchSelect'],
                        ])->links() }}
                @endif
                <table>
                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">رقم الفاتورة</th>
                            <th style="text-align: center" scope="col">الفرع</th>
                            <th style="text-align: center" scope="col">الكاشير</th>
                            <th style="text-align: center" scope="col">الحالة</th>
                            <th style="text-align: center" scope="col">طريقة الدفع</th>
                            <th style="text-align: center" scope="col" colspan="1">نوع الفاتورة </th>
                            <th style="text-align: center" scope="col">المبلغ</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all['Bills'] as $bill)
                            <tr>
                                <td style="text-align: center" id="texttab">
                                    <button onclick="onOpenBillModel('{{ $bill->id }}');" class="btn "
                                        style="background-color: white;">
                                        <p style="color: black">{{ $bill->id }}</p>
                                    </button>
                                </td>
                                <td id="texttab" style="text-align: center">{{ $bill->Branch->address }}
                                </td>
                                <td style="text-align: center" id="texttab">
                                    {{ $bill->staff->User->name }} </td>
                                <td style="text-align: center" id="texttab">
                                    @if ($bill->Status == 1 || $bill->Status == 4)
                                        منتهي
                                    @else
                                        تم الإلغاء
                                    @endif

                                </td>
                                <td style="text-align: center">
                                    @if ($bill->cash > 0 && $bill->online == 0)
                                        كاش
                                    @elseif($bill->cash == 0 && $bill->online > 0)
                                        شبكة
                                    @else
                                        كاش / شبكة
                                    @endif


                                </td>
                                <td style="text-align: center">
                                    @if ($bill->CustomerType == 4)
                                        <p> خدمة توصيل السائق : {{ $bill->driver->user->name }}</p>
                                    @elseif ($bill->CustomerType == 1)
                                        <p> محلي</p>
                                    @elseif ($bill->CustomerType == 2)
                                        <p> سفري</p>
                                    @elseif ($bill->CustomerType == 3)
                                        <p> تطبيقات توصيل</p>
                                    @endif
                                </td>
                                <td style="text-align: center" id="texttab">


                                    {{ $bill->total }}
                                    ريال
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <!-- The Modal -->
            @foreach ($all['Details'] as $bill)
                @foreach ($bill as $item)
                    <div id="myModalBill{{ $item->Bill_id }}" class="modal">

                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="closeBill{{ $item->Bill_id }}"
                                    onclick="closeBillModel('{{ $item->Bill_id }}')">&times;</span>
                            </div>
                            <div class="row" id="rowBillDash">
                                <div class="col-sm">
                                    <strong>الاسم:</strong>
                                    <p>{{ $item->bill->CustomerName }}</p>
                                </div>

                                <div class="col-sm">
                                    <strong>الجوال:</strong>
                                    <p>{{ $item->bill->CustomerPhone }}</p>
                                </div>
                                <div class="col-sm">
                                    <strong>التاريخ:</strong>
                                    <p> {{ $item->created_at }}</p>
                                </div>
                                <div class="col-sm">
                                    <strong>نوع الفاتورة :</strong>

                                    @if ($item->bill->CustomerType == 4)
                                        <p> خدمة توصيل السائق : {{ $item->bill->driver->user->name }}</p>
                                    @elseif ($item->bill->CustomerType == 1)
                                        <p> محلي</p>
                                    @elseif ($item->bill->CustomerType == 2)
                                        <p> سفري</p>
                                    @elseif ($item->bill->CustomerType == 3)
                                        <p> تطبيقات توصيل</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row" style="width: 50%; margin: auto">
                                <div class="col-sm">
                                    <strong>الكاش:</strong>
                                    <p>{{ $item->bill->cash }} ريال </p>
                                </div>

                                <div class="col-sm">
                                    <strong>شبكة:</strong>
                                    <p>{{ $item->bill->online }} ريال </p>
                                </div>
                                <div class="col-sm">
                                    <strong>المبلغ المستحق : </strong>
                                    <p> {{ $item->bill->total }} ريال </p>
                                </div>
                                <div class="col-sm">
                                    <strong>الباقي :</strong>
                                    <p> {{ $item->bill->total - ($item->bill->cash + $item->bill->online) }} ريال </p>
                                </div>


                            </div>
                            <div class="modal-body">
                                <div class="modal-body">

                                    <br><br>
                                    <div class="row">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center" scope="col">العنصر</th>
                                                    <th style="text-align: center" scope="col">الكمية</th>
                                                    <th style="text-align: center" scope="col">النوع</th>
                                                    <th style="text-align: center" scope="col">السعر</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bill as $singleItem)
                                                    <tr>
                                                        <td id="texttab" style="text-align: center">
                                                            {{ $singleItem->Item->Name }}
                                                            @foreach ($all['extraToppings'] as $extraToppings)
                                                                @if ($extraToppings->count() > 0)
                                                                    @if ($singleItem->id == $extraToppings[0]->Bill_details_id)
                                                                        +
                                                                        {{ $extraToppings[0]->ExtraTopping->Name }}
                                                                    @endif
                                                                @endif
                                                            @endforeach

                                                        </td>

                                                        <td id="texttab" style="text-align: center">
                                                            {{ $singleItem->count }}
                                                        </td>
                                                        <td style="text-align: center" id="texttab">
                                                            @if ($singleItem->size == 1)
                                                                {{ $singleItem->Item->Small_Name }}
                                                            @elseif($singleItem->size == 2)
                                                                {{ $singleItem->Item->Mid_Name }}
                                                            @elseif($singleItem->size == 3)
                                                                {{ $singleItem->Item->Big_Name }}
                                                            @else
                                                                جالون
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center" id="texttab">
                                                            {{ $singleItem->price }} ريال
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
            @if ($all['branchDetails'])
                @foreach ($all['expense'] as $item)
                    <div id="myModalExpense{{ $item->id }}" class="modal">

                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <span onclick="closeExpenseModel('{{ $item->id }}')" class="close">&times;</span>
                            </div>

                            <div class="modal-body">
                                <p>{{ $item->month }}</p>
                                <div class="row">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>ايجار المحل</th>
                                                <th>فاتورة الكهرباء</th>
                                                <th>فاتورة المياه</th>
                                                <th>رواتب الموظفين</th>
                                                <th>أخرى</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $item->branchRent }}</td>
                                                <td>{{ $item->electricBill }}</td>
                                                <td>{{ $item->waterBill }}</td>
                                                <td>{{ $item->salaryBill }}</td>
                                                <td>{{ $item->OtherBill }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @else
            @endif
        @endif

    @endsection
    @section('script')
        <script>
            function onOpenBillModel($item) {
                var modal = document.getElementById("myModalBill" + $item);
                modal.style.display = "block";

            }

            function closeBillModel($item) {
                var modal = document.getElementById("myModalBill" + $item);
                modal.style.display = "none";
            }
        </script>
        <script>
            function onOpenExpenseModel($item) {
                var modal = document.getElementById("myModalExpense" + $item);
                modal.style.display = "block";

            }

            function closeExpenseModel($item) {
                var modal = document.getElementById("myModalExpense" + $item);
                modal.style.display = "none";
            }
        </script>
        <script>
            function showBill() {
                // Get all the elements from the page
                var points =
                    document.getElementById("dots");

                var showMoreText =
                    document.getElementById("BillDatil");

                var buttonText =
                    document.getElementById("inpupformore");

                // If the display property of the dots 
                // to be displayed is already set to 
                // 'none' (that is hidden) then this 
                // section of code triggers
                if (points.style.display === "none") {

                    // Hide the text between the span
                    // elements
                    showMoreText.style.display = "none";

                    // Show the dots after the text
                    points.style.display = "inline";

                    // Change the text on button to 
                    // 'Show More'
                    buttonText.innerHTML = "اظهار  الفواتير";
                }

                // If the hidden portion is revealed,
                // we will change it back to be hidden
                else {

                    // Show the text between the
                    // span elements
                    showMoreText.style.display = "inline";

                    // Hide the dots after the text
                    points.style.display = "none";

                    // Change the text on button
                    // to 'Show Less'
                    buttonText.innerHTML = "اخفاء الفواتير";
                }
            }
        </script>
        <script>
            $("input[id=time]").clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                default: 'now',
                donetext: "Select",
                init: function() {
                    console.log("colorpicker initiated");
                },
                beforeShow: function() {
                    console.log("before show");
                },
                afterShow: function() {
                    console.log("after show");
                },
                beforeHide: function() {
                    console.log("before hide");
                },
                afterHide: function() {
                    console.log("after hide");
                },
                beforeHourSelect: function() {
                    console.log("before hour selected");
                },
                afterHourSelect: function() {
                    console.log("after hour selected");
                },
                beforeDone: function() {
                    console.log("before done");
                },
                afterDone: function() {
                    console.log("after done");
                }
            });
        </script>
    @endsection
