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
                    href="{{ route('DashboardHr') }}">{{ __('لوحة تحكم الموارد البشرية') }}</a>
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

        <div class="row">
            <div class="col-sm">
                <h4>طلبات الاجازة المعلقة</h4>
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
                            <th style="text-align: center" scope="col">ملاحظات</th>
                            <th>#</th>
                            <th>#</th>


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
                                <td>{{ $vacation->notes }}</td>
                                <td>
                                    <form method="POST" action="{{ route('vacationAccept') }}">
                                        @csrf
                                        <input type="text" hidden name="id" value="{{ $vacation->id }}">
                                        <button type="submit" class="btn btn-success">قبول</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('vacationReject') }}">
                                        @csrf
                                        <input type="text" hidden name="idD" value="{{ $vacation->id }}">
                                        <button type="submit" class="btn btn-danger">رفض</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </section>
        </div>
        <hr style="margin-top: 3%">
        <div class="row">
            <div class="col-sm">
                <h4> متابعة الاجازات
                </h4>
            </div>
        </div>
        <form method="POST" action="{{ route('VacationRequests') }}">
            @csrf
            <div class="row" style="margin-top: 3%">

                <div class="col-sm">

                    <input type="date" name="date" value="" style="width: 50%;margin-right: 42%"
                        placeholder="يوم">
                </div>
                <div class="col-sm">
                    <select name="branch" style="width: 50%;margin-right: 5%" class="form-select form-select-lg mb-3"
                        aria-label=".form-select-lg example">
                        <option value="0" selected>الكل
                        </option>
                        @foreach ($all['branchs'] as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->address }}
                            </option>
                        @endforeach


                    </select>
                </div>

            </div>
            <div class="row" style="margin-top: 3%;margin-bottom: 1%">
                <button type="submit" class="btn btn-info">ابحث</button>
            </div>
        </form>
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
                            <th>الحالة</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all['oldvacation'] as $oldvacation)
                            @php
                                $end_date = strtotime($oldvacation->End_Date);
                                
                            @endphp
                            <tr>
                                <td>{{ $oldvacation->Staff->user->id }}</td>
                                <td>{{ $oldvacation->Staff->user->name }}</td>
                                <td>{{ $oldvacation->Staff->Branch->address }}</td>
                                <td>{{ $oldvacation->type->name }}</td>
                                <td>{{ date(' d-m-Y', strtotime($oldvacation->Start_Date)) }}</td>
                                <td>{{ date(' d-m-Y', strtotime($oldvacation->End_Date)) }}</td>
                                <td>
                                    @if ($oldvacation->Status == 2)
                                        <p style="color: red">
                                            مرفوضة
                                        </p>
                                    @else
                                        @if ($end_date < time())
                                            <p style="color: red">
                                                انتهت
                                            </p>
                                        @else
                                            <p style="color: green">
                                                فعال
                                            </p>
                                        @endif
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
