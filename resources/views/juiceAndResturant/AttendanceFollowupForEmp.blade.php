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
            متابعة الحضور والانصراف
        </h2>
        <hr class="new5" style="margin-left: 1%">
        <form method="POST" action="{{ route('AttendanceFollowupForEmp') }}">
            @csrf
            <div class="row">

                <div class="col-sm">

                    <input type="date" name="date" value="" style="width: 50%;margin-right: 42%"
                        placeholder="يوم">
                </div>
                <div class="col-sm">
                    <button type="submit" style="width: 50%;margin-top: 1%;margin-right: 15%"
                        class="btn btn-info">ابحث</button>

                </div>
            </div>
        </form>
        <div class="row">
            <section style="margin-top: 3%" class="row">
                <table style="text-align: center;margin-right: 1%;width: 99%">
                    <thead>
                        <tr>

                            <th style="text-align: center" scope="col">اليوم</th>
                            <th style="text-align: center" scope="col">الفرع</th>
                            <th style="text-align: center" scope="col">وقت الحضور</th>
                            <th style="text-align: center" scope="col">وقت الإنصراف</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attend as $attends)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($attends->Start_Date)) }}</td>
                                <td>{{ $attends->Staff->Branch->address }}</td>
                                <td>{{ date(' H:i', strtotime($attends->Start_Date)) }}</td>
                                <td>{{ $attends->End_Date == null ? '' : date(' H:i', strtotime($attends->End_Date)) }}
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
