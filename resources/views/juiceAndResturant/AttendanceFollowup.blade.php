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
        <h2 id="subtitle">
            متابعة الحضور والانصراف
        </h2>
        <hr class="new5" style="margin-left: 1%">
        <form method="POST" action="{{ route('AttendanceFollowup') }}">
            @csrf
            <div class="row">

                <div class="col-sm">

                    <input type="date" name="date" style="width: 50%;margin-right: 42%" placeholder="يوم">
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
            <section style="margin-top: 3%" class="row">
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
                            <tr>
                                <td>{{ $attend->Staff->user->id }}</td>
                                <td>{{ $attend->Staff->user->name }}</td>
                                <td>{{ $attend->Staff->Branch->address }}</td>
                                <td>{{ date(' H:i', strtotime($attend->Start_Date)) }}</td>
                                <td>{{ $attend->End_Date == null ? '' : date(' H:i', strtotime($attend->End_Date)) }}</td>
                            </tr>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
