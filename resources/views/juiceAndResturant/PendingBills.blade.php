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
            الفواتير المعلقة
        </h2>
        <hr>
        <form method="POST" id="cancel" action="{{ route('cancelBill') }}">
            @csrf</form>
        <form method="POST" id="CloseBill" action="{{ route('CloseBill') }}">
            @csrf</form>

        <form method="POST" action="{{ route('PendingBills') }}">
            @csrf
            <div class="row">

                <div class="col-sm">
                    <input type="number" id="Custody1" name="Phone" style="margin-right: 35%"
                        placeholder="بحث برقم الجوال">
                </div>
                <div class="col-md">
                    <button type="submit" style="width: 50% ; margin-right: 5%;height: 50px;margin-top: 1%"
                        class="btn btn-info">بحث</button>
                </div>
            </div>
        </form>
        @if (Auth::user()->type_id == 1)
            <div class="row" style="margin-top: 5%">
                <section style="margin-top: 5% ;margin-right: 5%" class="row">
                    <table style="text-align: center;width: 90%">
                        <thead>
                            <tr>
                                <th style="text-align: center" scope="col">رقم الفاتورة</th>
                                <th style="text-align: center" scope="col">اسم العميل</th>
                                <th style="text-align: center" scope="col">رقم الجوال</th>
                                <th style="text-align: center" scope="col">تاريخ الفاتورة</th>
                                <th style="text-align: center" scope="col">المبلغ</th>
                                <th style="text-align: center" scope="col">المدفوع</th>
                                <th style="text-align: center" scope="col">المتبقي</th>
                                <th style="text-align: center" scope="col">#</th>
                                <th style="text-align: center" scope="col">#</th>


                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($all['Bills'] as $bill)
                                <tr>
                                    <td>{{ $bill->id }}</td>
                                    <td>{{ $bill->CustomerName }}</td>
                                    <td>{{ $bill->CustomerPhone }}</td>
                                    <td>{{ $bill->created_at->format(' h:i  Y-m-d') }}</td>
                                    <td>{{ $bill->total }}</td>
                                    <td>{{ $bill->cash + $bill->online }}</td>
                                    <td>{{ $bill->total - $bill->cash + $bill->online }}</td>

                                    <td> <button type="button" onclick="ClosePendingFun('{{ $bill->id }}');"
                                            form="CloseBill" class="btn btn-warning">اغلاق
                                            الفاتورة</button>
                                    </td>

                                    <td> <input type="text" name="billNo" form="cancel" hidden readonly
                                            value="{{ $bill->id }}">
                                        <button type="submit" class="btn btn-danger" form="cancel">حذف </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>
        @elseif(Auth::user()->type_id == 2)
            <div class="row" style="margin-top: 5%">
                <section style="margin-top: 5% ;margin-right: 5%" class="row">

                    {{ $all['Bills']->links() }}
                    <table style="text-align: center;width: 90%">
                        <thead>
                            <tr>
                                <th style="text-align: center" scope="col">رقم الفاتورة</th>
                                <th style="text-align: center" scope="col">اسم العميل</th>
                                <th style="text-align: center" scope="col">رقم الجوال</th>
                                <th style="text-align: center" scope="col">تاريخ الفاتورة</th>
                                <th style="text-align: center" scope="col">عدد أيام التسليم</th>
                                <th style="text-align: center" scope="col">الخياط</th>
                                <th style="text-align: center" scope="col">المبلغ</th>
                                <th style="text-align: center" scope="col">المدفوع</th>
                                <th style="text-align: center" scope="col">المتبقي</th>
                                <th style="text-align: center" scope="col">#</th>
                                <th style="text-align: center" scope="col">#</th>
                                <th style="text-align: center" scope="col">#</th>
                                <th style="text-align: center" scope="col">#</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($all['Bills'] as $bill)
                                <tr>
                                    <td>{{ $bill->id }}</td>
                                    <td>{{ $bill->CustomerName }}</td>
                                    <td>{{ $bill->CustomerPhone }}</td>
                                    <td>{{ $bill->created_at->format(' h:i  Y-m-d') }}</td>
                                    <td>{{ $bill->days }} أيام</td>
                                    <td>
                                        <select name="Tailor" form="close" class="form-select form-select-lg mb-3"
                                            aria-label=".form-select-lg example"
                                            onchange="sittailor(this.value , {{ $bill->id }});">
                                            <option value="0" selected>#
                                            </option>
                                            @foreach ($all['staffs'] as $staff)
                                                @if ($staff->id == $bill->tailor_id)
                                                    <option value="{{ $staff->id }}" selected>{{ $staff->user->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>{{ $bill->total }}</td>
                                    <td>{{ $bill->cash + $bill->online }}</td>
                                    <td>{{ str_replace('-', ' ', $bill->cash + $bill->online - $bill->total) }}</td>

                                    <td> <button type="button" onclick="ClosePendingFun('{{ $bill->id }}');"
                                            form="CloseBill" class="btn btn-warning">اغلاق
                                            الفاتورة</button>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('TailorBill') }}">
                                            @csrf
                                            <input type="hidden" hidden name="id" value="{{ $bill->id }}">
                                            <button type="submit" class="btn btn-info">فاتورة الخياط</button>
                                        </form>
                                    </td>
                                    <td>

                                        @if ($bill->Status == 6)
                                            تم ارسال رسالة استلام
                                        @else
                                            <button type="button" onclick="SendMeg('{{ $bill->id }}');"
                                                class="btn btn-success">ارسال رسالة استلام </button>
                                        @endif

                                    </td>

                                    <td> <input type="text" name="billNo" form="cancel" hidden readonly
                                            value="{{ $bill->id }}">
                                        <button type="submit" class="btn btn-danger" form="cancel">حذف </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>
            <div id="tailorModel" class="modal">
                <!-- Modal content -->
                <form method="POST" id="close" action="{{ route('sittailor') }}">
                    @csrf

                    <div class="modal-content" style="width: 60%">
                        <div class="modal-header">

                            <h2 class="exit" onclick="closestailorModel()">&times;
                            </h2>
                            تأكيد الخياط ؟
                            <p></p>
                        </div>
                        <div class="model-body">
                            <div class="row" style="margin-top: 2%;margin-bottom: 2%">
                                <div class="col-sm" style="margin-right: 33%">
                                    <input type="text" id="IDBill" name="IDBill" hidden readonly>
                                    <input type="text" id="tailorId" name="tailorId" hidden readonly>
                                    <button style="width: 40%" type="submit" class="btn btn-success">نعم</button>
                                </div>
                                <div class="col-md" style="margin-left: 20%">
                                    <button style="width: 40%" type="button" class="btn btn-danger"
                                        onclick="closestailorModel()">لا</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div id="SendMegModel" class="modal">
                <!-- Modal content -->
                <form method="POST" id="close" action="{{ route('SendCustomer') }}">
                    @csrf
                    <div class="modal-content" style="width: 60%">
                        <div class="modal-header">

                            <h2 class="exit" onclick="closes()">&times;
                            </h2>
                            هل أنت متأكد من ارسال رسالة استلام ؟
                            <p></p>
                        </div>
                        <div class="model-body">
                            <div class="row" style="margin-top: 2%;margin-bottom: 2%">
                                <div class="col-sm" style="margin-right: 33%">
                                    <input type="text" id="BillId" name="BillId" hidden readonly>
                                    <button style="width: 40%" type="submit" class="btn btn-success">نعم</button>
                                </div>
                                <div class="col-md" style="margin-left: 20%">
                                    <button style="width: 40%" type="button" class="btn btn-danger"
                                        onclick="closes()">لا</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        @else
        @endif
        <div id="ClosePendingModel" class="modal">
            <!-- Modal content -->
            <form method="POST" action="{{ route('CloseBill') }}">
                @csrf
                <div class="modal-content" style="width: 60%">
                    <div class="modal-header">

                        <h2 class="exit" onclick="ClosePendingFuncloses()">&times;
                        </h2>
                        هل أنت متأكد من اغلاق الفاتورة ؟
                        <p></p>
                    </div>
                    <div>
                        <p style="text-align: center">حدد طريقة الدفع ؟ </p>
                        <select name="payway" class="form-select form-select-lg mb-2" style="width: 50%;margin: auto"
                            aria-label=".form-select-lg example">
                            <option value="0">نوع الدفع
                            </option>
                            <option value="1">كاش
                            </option>
                            <option value="2">شبكة
                            </option>

                        </select>
                    </div>
                    <div class="model-body">
                        <div class="row" style="margin-top: 2%;margin-bottom: 2%">
                            <div class="col-sm" style="margin-right: 33%">
                                <input type="text" id="ID_Bill" name="ID_Bill" hidden readonly>
                                <button style="width: 40%" type="submit" class="btn btn-success">نعم</button>
                            </div>
                            <div class="col-md" style="margin-left: 20%">
                                <button style="width: 40%" type="button" class="btn btn-danger"
                                    onclick="ClosePendingFuncloses()">لا</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function SendMeg(id) {

            var modal = document.getElementById("SendMegModel");
            document.getElementById('BillId').value = id;
            modal.style.display = "block";

        }

        function closes() {
            var modal = document.getElementById("SendMegModel");
            modal.style.display = "none";

        }

        function ClosePendingFun(id) {
            var modal = document.getElementById("ClosePendingModel");
            document.getElementById('ID_Bill').value = id;
            modal.style.display = "block";
        }

        function ClosePendingFuncloses() {
            var modal = document.getElementById("ClosePendingModel");
            modal.style.display = "none";

        }
    </script>
    <script>
        function ClosePendingTailors() {

            var modal = document.getElementById("SendMegModel");
            modal.style.display = "block";

        }

        function closes() {
            var modal = document.getElementById("SendMegModel");
            modal.style.display = "none";

        }

        function sittailor(val, id) {
            var modal = document.getElementById("tailorModel");
            document.getElementById('IDBill').value = id;
            document.getElementById('tailorId').value = val;
            modal.style.display = "block";

        }

        function closestailorModel() {
            var modal = document.getElementById("tailorModel");
            modal.style.display = "none";
        }

        function setPayway(val, id) {
            alert(val);
            document.getElementById('payway' + id).value = val;
            alert(document.getElementById('payway' + id).value);
        }
    </script>
@endsection
