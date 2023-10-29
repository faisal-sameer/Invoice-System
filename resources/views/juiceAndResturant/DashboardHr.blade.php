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
            @if (Auth::user()->permission_id == 2 || Auth::user()->permission_id == 4)
                <li class="nav-item">
                    <a class="nav-link" id="listNav/DashboardHr"
                        href="{{ route('DashboardHrForEmp') }}">{{ __('الحضور و الانصراف') }}</a>
                </li>
            @endif
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
            لوحة تحكم الموارد البشرية
        </h2>
        <hr class="new5" style="margin-left: 1%">
        <div class="row">
            <div class="col-sm">
                <h4>حضور وانصراف يوم : 1-1-2023</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <a href="{{ route('AttendanceFollowup') }}" style="width: 20%;margin-right: 75%" class="btn btn-dark">بحث
                    متقدم</a>
            </div>
        </div>
        <div class="row">
            <section style="margin-top: 1%" class="row">
                <table style="text-align: center;margin-right: 1%;width: 99%">
                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">الرقم الوظيفي</th>
                            <th style="text-align: center" scope="col">الموظف</th>
                            <th style="text-align: center" scope="col">الفرع</th>
                            <th style="text-align: center" scope="col">وقت الحضور</th>
                            <th style="text-align: center" scope="col">وقت الإنصراف</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all['attends'] as $attend)
                            <tr>
                                <td>{{ $attend->Staff->user->id }}</td>
                                <td>{{ $attend->Staff->user->name }}</td>
                                <td>{{ $attend->Staff->Branch->address }}</td>
                                <td>{{ date(' H:i', strtotime($attend->Start_Date)) }}</td>
                                <td>{{ $attend->End_Date == null ? '' : date(' H:i', strtotime($attend->End_Date)) }}</td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </section>
        </div>
        <hr class="new5" style="margin-left: 1%;margin-top: 7%">
        <div class="row">
            <div class="col-sm">
                <h4>الاجازات</h4>
            </div>
            <div class="col-sm">
                <a href="{{ route('VacationRequests') }}" style="width: 20%;margin-right: 75%" class="btn btn-dark">طلبات
                    الاجازة</a>
            </div>
        </div>
        <div class="row">
            <section style="margin-top: 1%" class="row">
                <table style="text-align: center;margin-right: 1%;width: 99%">
                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">الرقم الوظيفي</th>
                            <th style="text-align: center" scope="col">الموظف</th>
                            <th style="text-align: center" scope="col">الفرع</th>
                            <th style="text-align: center" scope="col"> نوع الاجازة</th>
                            <th style="text-align: center" scope="col">بداية الاجازة</th>
                            <th style="text-align: center" scope="col">نهاية الاجازة</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all['vacation'] as $vacation)
                            <tr>
                                <td>{{ $vacation->Staff->user->id }}</td>
                                <td>{{ $vacation->Staff->user->name }}</td>
                                <td>{{ $vacation->Staff->Branch->address }}</td>
                                <td>{{ $vacation->type->name }}</td>
                                <td>{{ date(' d-m-Y', strtotime($vacation->Start_Date)) }}</td>
                                <td>{{ date(' d-m-Y', strtotime($vacation->End_Date)) }}</td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
