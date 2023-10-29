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
                    href="{{ route('AttendanceFollowupForEmp') }}">{{ __('متابعة الحضور والانصراف') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listNav/DashboardHr"
                    href="{{ route('VacationRequestEmp') }}">{{ __('طلب اجازة جديدة') }}</a>
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
            لوحة تحكم
        </h2>
        <hr class="new5" style="margin-left: 1%">

        <div class="row">
            <div class="col-md-4" style="margin-right: 34%;margin-bottom: 2%">

                <div class="profile-card text-center">

                    <div class="profile-info">
                        <h2 class="hvr-underline-from-center" style="font-family: 'Handlee', cursive;">معلومات الموظف</h2>

                        <div class="col-sm">
                            <h4>الاسم : {{ $all['staff']->user->name }}</h4>
                        </div>

                        <div class="col-sm">
                            <h4>الرقم الوظيفي : {{ $all['staff']->user->id }}</h4>
                        </div>
                        <div class="col-sm">
                            <h4>الفرع : {{ $all['staff']->Branch->address }}</h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <hr class="new5" style="margin-left: 1%">
        <form method="POST" id="attend" action="{{ route('attend') }}">
            @csrf
            <div class="row">
                <div class="col-sm">
                    <h4> تسجيل حضور وانصراف :</h4>
                </div>

                <input type="text" name="id" hidden readonly value="{{ $all['staff']->id }}">
                <div class="col-sm">
                    <button type="submit" style="width: 30%;margin-right: 65%" class="btn btn-success">حضور</button>
                </div>
                <input type="text" name="id" hidden readonly value="{{ $all['staff']->id }}">
                <div class="col-sm">
                    <button type="button" onclick="attendType();" style="width: 30%;margin-right: 65%"
                        class="btn btn-primary">انصراف</button>
                </div>
            </div>
        </form>

        <div class="row">
            <section style="margin-top: 1%" class="row">
                <table style="text-align: center;margin-right: 1%;width: 99%">
                    <thead>
                        <tr>
                            <th>الموافق</th>
                            <th style="text-align: center" scope="col">وقت الحضور</th>
                            <th style="text-align: center" scope="col">وقت الإنصراف</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all['attend'] as $attend)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($attend->Start_Date)) }}</td>
                                <td>{{ date(' H:i', strtotime($attend->Start_Date)) }}</td>
                                <td>{{ $attend->End_Date == null ? '' : date(' H:i', strtotime($attend->End_Date)) }}</td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </section>
        </div>
        <hr class="new5" style="margin-left: 1%;margin-top: 3%">
        <div class="row">
            <div class="col-sm">
                <h4>الاجازات</h4>
            </div>
            <div class="col-sm">
                <a href="{{ route('VacationRequestEmp') }}" style="width: 20%;margin-right: 75%" class="btn btn-dark">طلب
                    اجازة جديدة</a>
            </div>
        </div>
        <div class="row">
            <section style="margin-top: 1%" class="row">
                <table style="text-align: center;margin-right: 1%;width: 99%">
                    <thead>
                        <tr>

                            <th style="text-align: center" scope="col"> نوع الاجازة</th>
                            <th style="text-align: center" scope="col">بداية الاجازة</th>
                            <th style="text-align: center" scope="col">نهاية الاجازة</th>
                            <th style="text-align: center" scope="col">الحالة</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all['vacation'] as $vacation)
                            <tr>
                                <td>{{ $vacation->type->name }}</td>
                                <td>{{ date('d-m-Y', strtotime($vacation->Start_Date)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($vacation->End_Date)) }}</td>
                                <td>
                                    @if ($vacation->Status == 1)
                                        في انتظار الموافقة
                                    @elseif($vacation->Status == 2)
                                        مرفوضة
                                    @elseif($vacation->Status == 3)
                                        مقبولة
                                    @endif
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function attendType() {
            var form = document.getElementById("attend");

            form.action = "{{ route('leaving') }}";
            form.submit();

        }
    </script>
@endsection
