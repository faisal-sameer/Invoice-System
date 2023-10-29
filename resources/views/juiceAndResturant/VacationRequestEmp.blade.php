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
                <a class="nav-link" id="listNav/DashboardHr"
                    href="{{ route('DashboardHrForEmp') }}">{{ __('لوحة تحكم') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listNav/DashboardHr"
                href="{{ route('AttendanceFollowupForEmp') }}">{{ __('متابعة الحضور والانصراف') }}</a>
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
        <form method="POST" id="vacation" action="{{ route('vaction') }}">
            @csrf
            <h2 id="subtitle">
                طلب اجازة جديدة
            </h2>
            <hr class="new5" style="margin-left: 1%">
            <div class="row" style="margin-right: 15%">
                <div class="col-sm">
                    <p id="pBill">من</p>
                    <input type="date" id="fromDate" name="from" placeholder="من يوم">
                </div>
                <div class="col-sm">
                    <p id="pBill">إلى</p>
                    <input type="date" id="toDate" name="to" placeholder="إلى يوم">

                </div>
                <div class="col-sm">
                    <p id="pBill"> نوع الاجازة
                    </p>

                    <select name="type" class="form-select form-select-lg mb-3" style="width: 40%"
                        aria-label=".form-select-lg example">
                        @foreach ($all['typeVacation'] as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->name }}
                            </option>
                        @endforeach




                    </select>
                </div>
            </div>
            <div class="row" style="margin-right: 5%">
                <div class="col-sm" style="margin-top: 2%;margin-right: 15%">
                    <h4>
                        ملاحظات
                    </h4>
                </div>
                <div class="col-lg" style="margin-top: 2%;margin-left: 40%">

                    <div class="paper" style="height: 650%">
                        <div class="paper-content">
                            <textarea name="notes" style="white-space: nowrap;"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 20% ">
                <button type="button" onclick="checkDates()" style="width: 50%; margin: auto"
                    class="btn btn-info">ادخال</button>

            </div>
        </form>

    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function checkDates() {
            const fromDate = new Date(document.getElementById("fromDate").value);
            const toDate = new Date(document.getElementById("toDate").value);

            if (fromDate.getTime() >= toDate.getTime()) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطاء ',
                    text: 'لا يمكن تحدد وقت العودة من الاجازة قبل  وقت بد الاجازة',
                });
            } else {
                var form = document.getElementById("vacation");
                form.submit();
            }
        }
    </script>
@endsection
