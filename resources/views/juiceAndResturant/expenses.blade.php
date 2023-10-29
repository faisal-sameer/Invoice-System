@extends('layouts.app')


@section('navbar')
    <ul id="ulPhone" class="navbar-nav m-sm-3">
        <li class="nav-item">
            <img src="/image/list.png" onclick="OpenMenu()" class="shake-lr" id="menuImage" alt="">

        </li>
        <div id="mySidenav" class="sidenav">
            <span href="" class="closebtn" onclick="closeMenu()">&times;</span>
            <li class="nav-item">
                <a class="nav-link" id="listNav/Home" href="{{ route('Home') }}">
                    <lord-icon src="https://cdn.lordicon.com/hjbsbdhw.json" trigger="hover" style="width:47px;height:47px">
                    </lord-icon>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listNav/AdminDashboard" href="{{ route('expenses') }}">{{ __('المصروفات') }}</a>
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

            المصروفات
        </h2>
        <hr>
        <br><br>
        <form method="POST" action="{{ route('expensesNew') }}">
            @csrf
            <div class="row" id="rowAdd">
                <div class="col-md"id="rowAddExp">
                    <select name="branch" class="form-select form-select-lg mb-3" id="selectExp"
                        aria-label=".form-select-lg example">
                        <option selected value="0">اختر الفرع</option>
                        @foreach ($all['branch'] as $item)
                            <option value="{{ $item->id }}">{{ $item->address }} </option>
                        @endforeach

                    </select>
                </div>
                <div class="col-md">
                    الشهر
                    <input type="month" id="selectExp" name="month" min="2022-01" max="2030-03">

                </div>


            </div>
            <div class="row" id="ExpRow" style="margin-top: 5%">
                <div class="col-md">
                    <input type="number" name="branchRent" placeholder="ايجار المحل">
                </div>
                <div class="col-md">
                    <input type="number" name="electricBill" placeholder="فاتورة الكهرباء">
                </div>
                <div class="col-md">
                    <input name="waterBill" type="number" placeholder="فاتورة المياه">
                </div>
                <div class="col-md">
                    <input type="number" name="salaryBill" placeholder="رواتب الموظفين">
                </div>
                <div class="col-md">
                    <input type="number" onclick="AddOtherExpenses()" name="OtherBill" placeholder="أخرى">
                </div>
            </div>


            <div class="row" style="margin-top: 5%">
                <button style="width: 98%" type="submit" class="btn btn-info">حفظ</button>

            </div>

            <div class="row">
                <section style="margin-top: 5%" class="row">
                    {{ $all['expense']->links() }}

                    <table style="text-align: center">
                        <thead>
                            <tr>
                                <th style="text-align: center" scope="col">الشهر</th>
                                <th style="text-align: center" scope="col">الفرع</th>
                                <th style="text-align: center" scope="col">ايجار المحل</th>
                                <th style="text-align: center" scope="col">فاتورة الكهرباء</th>
                                <th style="text-align: center" scope="col">فاتورة المياه</th>
                                <th style="text-align: center" scope="col">رواتب الموظفين</th>
                                <th style="text-align: center" scope="col">أخرى</th>
                                <th style="text-align: center" scope="col">المجموع</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all['expense'] as $item)
                                <tr>

                                    <td>
                                        {{ $item->month }}
                                    </td>
                                    <td>
                                        {{ $item->Branch->address }}
                                    </td>
                                    <td>{{ $item->branchRent }}</td>
                                    <td>{{ $item->electricBill }}</td>
                                    <td>{{ $item->waterBill }}</td>
                                    <td>{{ $item->salaryBill }}</td>
                                    <td><strong style="border-style: groove;"
                                            onclick="OtherExpenses({{ $item->id }})">{{ $item->OtherBill }}</strong>
                                    </td>

                                    <td>{{ $item->branchRent + $item->electricBill + $item->waterBill + $item->salaryBill + $item->OtherBill }}
                                    </td>
                                </tr>
                            @endforeach




                            <!-- Modal content -->


                        </tbody>
                    </table>
                </section>
            </div>
            <div id="AddOtherExpenses" class="modal">

                <!-- Modal content -->

                <div class="modal-content" style="width: 80%">
                    <div class="modal-header">

                        <h2 class="exit" onclick="closeAddOtherExpenses()">&times;
                        </h2>
                        تسجيل مصاريف أخرى
                        <button onclick="closeAddOtherExpenses()" type="submit" class="btn btn-light">حفظ</button>
                    </div>
                    <div class="model-body">
                        <div class="row">
                            <div class="col-sm" style="margin-right: 35%;margin-top: 2%">

                                <select name="type" class="form-select form-select-lg mb-3" id="inpRSelec"
                                    onchange="showDiv(this)" aria-label=".form-select-lg example">
                                    <option value="0">نوع المصروف</option>
                                    <option value="1">مصاريف أخرى</option>
                                    <option value="2">مصاريف المشتريات</option>

                                </select>
                            </div>
                        </div>
                        <div class="notification-dashboard" style="display: none" id="form1">
                            <div class="notification-container">
                                <div class="row" style="margin-right: 10%">
                                    <div class="col-sm">
                                        <p id="pBill">عنوان</p>
                                        <input type="text" name="title" id="inputBill">
                                    </div>
                                    <div class="col-sm">
                                        <p id="pBill">المبلغ</p>
                                        <input type="number" name="priceEXp" id="inputBill">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="notification-dashboard" style="display: none" id="form2">
                            <div class="notification-container">
                                <div class="row" style="margin-right: 10%">
                                    <div class="col-sm">
                                        <p id="pBill">اسم الشركة </p>
                                        <input type="text" name="companyName" id="inputBill">
                                    </div>
                                    <div class="col-sm">
                                        <p id="pBill">السجل التجاري</p>
                                        <input type="number" name="IDnum" id="inputBill">
                                    </div>
                                    <div class="col-sm">
                                        <p id="pBill">الرقم الضريبي </p>
                                        <input type="number" name="VATnum" id="inputBill">
                                    </div>
                                </div>
                                <table id="myTable" style="margin-top: 5%">
                                    <thead>
                                        <tr>
                                            <th>الصنف</th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" onclick="addRow()">اضافة</button>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @foreach ($all['expense'] as $expense)
            <div id="OtherExpenses{{ $expense->id }}" class="modal">

                <!-- Modal content -->


                <div class="modal-content" style="width: 80%">
                    <div class="modal-header">

                        <h2 class="exit" onclick="closeOtherExpenses({{ $expense->id }})">&times;
                        </h2>
                        مصاريف أخرى للفرع
                    </div>
                    <div class="model-body">
                        <div class="notification-dashboard">
                            <div class="notification-container">
                                <div class="row">
                                    <h3>مصاريف أخرى</h3>
                                </div>
                                <div class="row">
                                    <section style="margin-top: 1%" class="row">
                                        <table style="text-align: center;margin-right: 1%;width: 99%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center" scope="col">الموظف</th>
                                                    <th style="text-align: center" scope="col">عنوان</th>
                                                    <th style="text-align: center" scope="col">المبلغ</th>
                                                    <th style="text-align: center" scope="col">التاريخ</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($all['otherExpense'] as $otherExpense)
                                                    @if ($otherExpense->expense_id == $expense->id)
                                                        <tr>
                                                            <td>{{ $otherExpense->staff->user->name }}</td>
                                                            <td>{{ $otherExpense->title }} </td>
                                                            <td>{{ $otherExpense->price }} ريال</td>
                                                            <td> {{ date('d-m-Y ', strtotime($otherExpense->created_at)) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </section>
                                </div>

                            </div>
                        </div>
                        <div class="notification-dashboard">
                            <div class="notification-container">
                                <div class="row">
                                    <h3>مصاريف المشتريات</h3>
                                </div>
                                <div class="row">
                                    <section style="margin-top: 1%" class="row">
                                        <table style="text-align: center;margin-right: 1%;width: 99%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center" scope="col">الموظف</th>
                                                    <th style="text-align: center" scope="col">الشركة</th>
                                                    <th style="text-align: center" scope="col">الرقم الضريبي</th>
                                                    <th style="text-align: center" scope="col">التاريخ</th>
                                                    <th style="text-align: center" scope="col">التكلفة الاجمالية</th>
                                                    <th style="text-align: center" scope="col">تفصيل المشتريات</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($all['Purchase'] as $Purchase)
                                                    @php
                                                        $total = 0;
                                                    @endphp
                                                    @if ($Purchase->expense_id == $expense->id)
                                                        @php
                                                            if ($Purchase->PurchaseDetail != []) {
                                                                foreach ($Purchase->PurchaseDetail as $key => $PurchaseDetail) {
                                                                    $total += $PurchaseDetail->price * $PurchaseDetail->count;
                                                                }
                                                            }
                                                            
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $Purchase->staff->user->name }}</td>
                                                            <td>{{ $Purchase->Name }}</td>
                                                            <td>{{ $Purchase->VATnumber }}</td>
                                                            <td>{{ date('d-m-Y ', strtotime($Purchase->created_at)) }}
                                                            </td>
                                                            <td>{{ $total }} ريال</td>
                                                            <td><button class="delete-button btn btn-info"
                                                                    onclick="details('{{ $Purchase->id }}')">الأصناف</button>
                                                            </td>

                                                        </tr>
                                                    @endif
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </section>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @foreach ($all['Purchase'] as $Purchase)
            <div id="details{{ $Purchase->id }}" class="modal">

                <!-- Modal content -->

                <div class="modal-content" style="width: 80%">
                    <div class="modal-header">

                        <h2 class="exit" onclick="closedetails('{{ $Purchase->id }}')">&times;
                        </h2>
                        تفاصيل المشتريات
                    </div>
                    <div class="model-body">
                        <div class="notification-dashboard">
                            <div class="notification-container">

                                <div class="row">
                                    <section style="margin-top: 1%" class="row">
                                        <table style="text-align: center;margin-right: 1%;width: 99%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center" scope="col">الصنف
                                                    </th>
                                                    <th style="text-align: center" scope="col">التكلفة
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($Purchase->PurchaseDetail as $PurchaseDetail)
                                                    <tr>
                                                        <td>{{ $PurchaseDetail->Name }}</td>
                                                        <td>{{ $PurchaseDetail->price * $PurchaseDetail->count }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </section>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
@section('script')
    <script>
        function addRow() {
            var table = document.getElementById("myTable");
            var row = table.insertRow(table.rows.length - 1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            cell1.innerHTML = '<input type="text" name="name[]">';
            cell2.innerHTML = '<input type="text" name="count[]">';
            cell3.innerHTML = '<input type="text" name="price[]">';
            cell4.innerHTML = '<button class="delete-button btn btn-danger" onclick="deleteRow(this)">حذف</button>';
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
    <script>
        function showDiv(select) {
            if (select.value == 1) {
                document.getElementById('form1').style.display = "block";
                document.getElementById('form2').style.display = "none";


            } else if (select.value == 2) {
                document.getElementById('form1').style.display = "none";
                document.getElementById('form2').style.display = "block";

            } else {
                document.getElementById('form1').style.display = "none";
                document.getElementById('form2').style.display = "none";

            }
        }
    </script>
    <script>
        function AddOtherExpenses() {
            var modal = document.getElementById("AddOtherExpenses");
            modal.style.display = "block";
        }

        function closeAddOtherExpenses() {
            var modal = document.getElementById("AddOtherExpenses");
            modal.style.display = "none";
        }
    </script>

    <script>
        function OtherExpenses(id) {
            var modal = document.getElementById("OtherExpenses" + id);
            modal.style.display = "block";
        }

        function closeOtherExpenses(id) {
            var modal = document.getElementById("OtherExpenses" + id);
            modal.style.display = "none";
        }
    </script>
    <script>
        function details(id) {
            var modal = document.getElementById("details" + id);
            modal.style.display = "block";
        }

        function closedetails(id) {
            var modal = document.getElementById("details" + id);
            modal.style.display = "none";
        }
    </script>
@endsection
