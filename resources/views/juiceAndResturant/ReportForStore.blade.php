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
                <a class="nav-link" id="listNav/Stored" href="{{ route('Stored') }}">{{ __('المخزون') }}</a>
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
            تقارير المخزون
        </h2>
        <hr>
        <div class="notification-dashboard">
            <form method="POST" action="{{ route('ReportForStore') }}">
                @csrf
                <div class="notification-container">
                    <div class="row" style="margin-right: 15%">
                        <div class="col-sm">
                            <p id="pBill">من</p>
                            <input type="date" id="inputBill" name="from" placeholder="من يوم">
                        </div>
                        <div class="col-sm">
                            <p id="pBill">إلى</p>
                            <input type="date" id="inputBill" name="to" placeholder="إلى يوم">
                        </div>
                        <div class="col-sm">
                            <p id="pBill">اختر الفرع</p>

                            <select name="branchID" class="form-select form-select-lg mb-3" id="inpRSelec"
                                aria-label=".form-select-lg example">
                                <option value="0" selected>الكل</option>
                                @foreach ($all['branchs'] as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->address }} </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 3%;margin-bottom: 2%">
                        <button type="submit" class="btn btn-info">ابحث</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row" style="text-align: center">
            <div class="col">
                <h4>نتيجة البحث
                    @if ($all['branchID'] == 'الكل')
                        : بجميع الفروع
                    @else
                        : فرع {{ $all['branchID'] }}
                    @endif
                </h4>
            </div>
            <div class="col">
                <h4>
                    من تاريخ : {{ $all['from'] }}
                </h4>
            </div>
            <div class="col">
                <h4>
                    الى تاريخ : {{ $all['to'] }}
                </h4>
            </div>
        </div>
        <div class="notification-dashboard">
            @foreach ($all['SelectBranch'] as $branch)
                <h1>فرع {{ $branch->address }}</h1>
                <div class="notification-container">
                    <table style="text-align: center;width: 100%;">
                        <thead>
                            <tr>
                                <th style="text-align: center" scope="col">العنصر</th>
                                <th style="text-align: center" scope="col">الكمية المرسلة</th>
                                <th style="text-align: center" scope="col">المرسل</th>
                                <th style="text-align: center" scope="col">تاريخ الارسال</th>


                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($all['Store'] as $Store)
                                @if ($Store->branch_id == $branch->id && $Store->followUp->count() > 0)
                                    @foreach ($Store->followUp as $followUp)
                                        <tr>
                                            <td> {{ $followUp->store->Name }}</td>
                                            <td> {{ $followUp->value }}</td>
                                            <td> {{ $followUp->staff->user->name }}</td>
                                            <td> {{ date('d-m-Y ', strtotime($followUp->created_at)) }}</td>

                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <h3 style="margin-top: 3%;">ملخص للكميات المرسلة</h3>
                    <table style="text-align: center;width: 100%;margin-bottom: 3%">
                        <thead>
                            <tr>
                                <th style="text-align: center" scope="col">العنصر</th>
                                <th style="text-align: center" scope="col">اجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all['summary'] as $summary)
                                @if ($summary['branch'] == $branch->id)
                                    <tr>
                                        <td>{{ $summary['name'] }}</td>
                                        <td>{{ $summary['val'] }}</td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                    <hr>

                </div>
            @endforeach
        </div>
    </div>
@endsection
