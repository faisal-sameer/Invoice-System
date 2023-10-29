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
                <a class="nav-link" id="listNav/AdminDashboard"
                    href="{{ route('AdminDashboard') }}">{{ __('لوحة التحكم') }}</a>
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
            البحث المتقدم
        </h2>
        <hr class="new5" style="margin-left: 1%">
        <form method="POST" id="formSechdel" action="{{ route('AdvSearchFollowTheFund') }}">
            @csrf
            <div class="row">

                <div class="col-sm">

                    <input type="date" name="day" value="{{ $all['day'] }}" style="width: 50%;margin-right: 42%"
                        placeholder="يوم">
                </div>
                <div class="col-sm">
                    <select name="branch" style="width: 50%;margin-right: 5%" class="form-select form-select-lg mb-3"
                        aria-label=".form-select-lg example">
                        <option value="0" selected>الكل
                        </option>
                        @foreach ($all['branch'] as $branchs)
                            <option value="{{ $branchs->id }}">{{ $branchs->address }}
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
            {{ $all['Box']->appends(['Box' => $all['Box']->currentPage(), 'day' => $all['day'], 'branch' => $all['selectedbranch']])->links() }}

            <section style="margin-top: 2%" class="row">
                <div class="table-responsive">
                    <table style="text-align: center;margin-right: 1%;width: 99%">
                        <thead>
                            <tr>
                                <th style="text-align: center" scope="col">الموظف</th>

                                <th style="text-align: center" scope="col">الفرع</th>
                                <th style="text-align: center" scope="col">وقت الفتح </th>
                                <th style="text-align: center" scope="col">وقت الاغلاق </th>
                                <th style="text-align: center" scope="col">العهدة بداية الفترة</th>
                                <th style="text-align: center" scope="col">العهدة نهاية الفترة</th>
                                <th style="text-align: center" scope="col">الدخل</th>
                                <th style="text-align: center" scope="col">(العهدة بداية الفترة + الدخل)</th>
                                <th style="text-align: center" scope="col">العجز</th>
                                <th style="text-align: center" scope="col">#</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all['Box'] as $Box)
                                <tr>
                                    <td>{{ $Box->staff->User->name }}</td>

                                    <td>{{ $Box->Branch->address }}</td>
                                    <td>{{ date('H:i', strtotime($Box->Start_Date)) }}</td>
                                    <td>
                                        @if ($Box->Status == 3)
                                            تم اغلاق الصندوق من قبل النظام
                                        @elseif($Box->End_Date != null)
                                            {{ date('H:i', strtotime($Box->End_Date)) }}
                                        @else
                                        @endif


                                    </td>
                                    <td>{{ $Box->Start_Custody }} ريال</td>
                                    <td>
                                        @if ($Box->Status == 3)
                                            تم اغلاق الصندوق من قبل النظام
                                        @elseif($Box->Status == 2)
                                            {{ $Box->End_Custody }} ريال
                                        @endif


                                    </td>
                                    <td>
                                        @isset($all['Incoming'][$Box->id])
                                            {{ $all['Incoming'][$Box->id] }}
                                            ريال
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($all['Incoming'][$Box->id])
                                            <?php $incomingStrart = $all['Incoming'][$Box->id] + $Box->Start_Custody;
                                            ?>
                                            @if ($Box->Start_Custody > $Box->End_Custody)
                                                <p style="color: red">
                                                    {{ $incomingStrart }}
                                                    ريال
                                                </p>
                                            @elseif($Box->Start_Custody <= $Box->End_Custody)
                                                <p style="color: green"> {{ $incomingStrart }}
                                                    ريال
                                                </p>
                                            @else
                                                <p> {{ $incomingStrart }}
                                                    ريال
                                                </p>
                                            @endif
                                        @else
                                            {{ $Box->Start_Custody }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($all['Incoming'][$Box->id])
                                            <?php $incomingStrart = $all['Incoming'][$Box->id] + $Box->Start_Custody - $Box->End_Custody;
                                            ?>
                                            @if ($Box->Start_Custody > $Box->End_Custody)
                                                <p style="color: red">
                                                    {{ $incomingStrart }}
                                                    ريال
                                                </p>
                                            @elseif($Box->Start_Custody <= $Box->End_Custody)
                                                <p style="color: green"> {{ $incomingStrart }}
                                                    ريال
                                                </p>
                                            @else
                                                <p> {{ $incomingStrart }}
                                                    ريال
                                                </p>
                                            @endif
                                        @else
                                            {{ $Box->Start_Custody }}
                                        @endisset

                                    </td>
                                    <td>
                                        <form method="POST" id="formSechdel" action="{{ route('BillDashboard') }}">
                                            @csrf
                                            <input type="text" name="day" hidden readonly
                                                value="{{ date('Y-m-d', strtotime($Box->created_at)) }}">
                                            <input type="text" name="seqId" hidden readonly
                                                value="{{ $Box->id }}">
                                            <button class="btn btn-info" type="submit">تفاصيل</button>

                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            </form>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

    </div>
@endsection
