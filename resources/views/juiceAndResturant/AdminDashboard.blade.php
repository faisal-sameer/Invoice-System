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
                    href="{{ route('CreateDocument') }}">{{ __('انشاء السندات') }}</a>
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
            لوحة التحكم
        </h2>
        <hr class="new5" style="margin-left: 1%">
        <div class="row">
            <section style="margin-top: 1%" class="row">
                <table style="text-align: center;margin-right: 1%;width: 99%">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th style="text-align: center" scope="col">الرقم الوظيفي</th>
                            <th style="text-align: center" scope="col">الفرع</th>
                            <th style="text-align: center" scope="col">الرقم السري</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" id="form" action="{{ route('UpdateUser') }}"
                            enctype="multipart/form-data">
                            @foreach ($all['staff'] as $staff)
                                @csrf

                                <tr>
                                    <td>
                                        <p>{{ $staff->user->name }}</p>
                                    </td>
                                    <td>
                                        <input type="text " name="id[]" value="{{ $staff->id }}" hidden readonly>
                                        <p>{{ $staff->user_id }}</p>
                                    </td>
                                    <td style="width: 15%">
                                        <select name="branch[]" class="form-select form-select-lg mb-3"
                                            aria-label=".form-select-lg example">
                                            @foreach ($all['branch'] as $branchs)
                                                @if ($staff->branch_id == $branchs->id)
                                                    <option value="{{ $staff->branch_id }}" selected>
                                                        {{ $staff->branch->address }}
                                                    </option>
                                                @else
                                                    <option value="{{ $branchs->id }}">{{ $branchs->address }}
                                                    </option>
                                                @endif
                                            @endforeach

                                        </select>
                                    </td>
                                    <td>
                                        <input type="password" id="inputpassword" style="width: 55%" name="password[]"
                                            placeholder="">
                                    </td>
                                </tr>
                            @endforeach
                            <div class="row">
                                <div class="col-sm">
                                    <h4>ادارة الحسابات</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <input class="slide" type="image" id="save" name="category" alt="حفظ"
                                        title="حفظ" src="/image/download.gif">
                                </div>
                            </div>
                        </form>
                    </tbody>
                </table>
            </section>
        </div>
        {{-- comment   <hr class="new5" style="margin-left: 1%">
        <div class="row" style="margin-top: 3%">
            <h4>مواعيد الدوام</h4>
        </div>
        <form method="POST" id="formBranch" action="{{ route('AdminDashboard') }}">
            @csrf
        </form>
        <div class="row" style="margin-top: 3%">
            <div class="col-sm" style="margin-right: 30%">
                <select name="branchID" onchange="submitBRanch()" id="selectBranch" form="formBranch" style="width: 50%"
                    class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                    @foreach ($all['branch'] as $branchs)
                        @if ($branchs->id == $all['branchID'])
                            <option value="{{ $branchs->id }}" selected>
                                {{ $branchs->address }}
                            </option>
                        @else
                            <option value="{{ $branchs->id }}">
                                {{ $branchs->address }}
                            </option>
                        @endif
                    @endforeach

                </select>
            </div>
        </div>
        <form method="POST" id="formSechdel" action="{{ route('schedulStaff') }}">
            @csrf
            <input type="text" hidden readonly name="IDbranch" id="IDbranch">
            <div class="row">
                <div class="col-sm">
                    <label>الفترة الأولى</label>
                </div>
                <div class="col-sm">
                    <label>الفترة الثانية</label>
                </div>
            </div>
            <div class="row" style="border-style: solid">
                <div class="col-sm" id="border">
                    <select name="custody[]" style="width: 30%" aria-label=".form-select-lg example">
                        <option value="null" selected> موظف الجرد
                        </option>
                        @foreach ($all['staff'] as $staff)
                            @if (!$all['first'] == null)
                                @if ($all['first']->inventory_Officer_id == $staff->id)
                                    <option selected value="{{ $staff->id }}">{{ $staff->user->name }}
                                    </option>
                                @else
                                    <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                    </option>
                                @endif
                            @else
                                <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                </option>
                            @endif
                        @endforeach

                    </select>
                    <input type="text" hidden readonly name="shiftOne" value="1">
                    @if (!$all['first'] == null)
                        <input type="text" style="width: 30%" id="time" name="start1"
                            value="{{ $all['first']->Start_Date }}" placeholder="حدد موعد البدأ">
                        <input type="text" style="width: 30%" id="time" name="end1"
                            value="{{ $all['first']->End_Date }}" placeholder="حدد موعد الانتهاء">
                    @else
                        <input type="text" style="width: 30%" id="time" name="start1"
                            placeholder="حدد موعد البدأ">
                        <input type="text" style="width: 30%" id="time" name="end1"
                            placeholder="حدد موعد الانتهاء">
                    @endif
                </div>

                <div class="col-sm" id="border">
                    <select name="custody[]" style="width: 30%" aria-label=".form-select-lg example">
                        <option value="null" selected> موظف الجرد
                        </option>
                        @foreach ($all['staff'] as $staff)
                            @if (!$all['secound'] == null)
                                @if ($all['secound']->inventory_Officer_id == $staff->id)
                                    <option selected value="{{ $staff->id }}">{{ $staff->user->name }}
                                    </option>
                                @else
                                    <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                    </option>
                                @endif
                            @else
                                <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                </option>
                            @endif
                        @endforeach

                    </select>
                    <input type="text" hidden readonly name="shiftTow" value="2">
                    @if (!$all['secound'] == null)
                        <input type="text" style="width: 30%" id="time" name="start2"
                            value="{{ $all['secound']->Start_Date }}" placeholder="حدد موعد البدأ">
                        <input type="text" style="width: 30%" id="time" name="end2"
                            value="{{ $all['secound']->End_Date }}" placeholder="حدد موعد الانتهاء">
                    @else
                        <input type="text" style="width: 30%" id="time" name="start2"
                            placeholder="حدد موعد البدأ">
                        <input type="text" style="width: 30%" id="time" name="end2"
                            placeholder="حدد موعد الانتهاء">
                    @endif

                </div>

            </div>
            <div class="row">
                <div class="col-sm">
                    <label>الفترة الثالثة</label>
                </div>
                <div class="col-sm">
                    <label>الفترة الرابعة</label>
                </div>
            </div>
            <div class="row" style="margin-bottom: 2%">
                <div class="col-sm" id="border">
                    <select name="custody[]" style="width: 30%" aria-label=".form-select-lg example">
                        <option value="null" selected> موظف الجرد
                        </option>
                        @foreach ($all['staff'] as $staff)
                            @if (!$all['thried'] == null)
                                @if ($all['thried']->inventory_Officer_id == $staff->id)
                                    <option selected value="{{ $staff->id }}">{{ $staff->user->name }}
                                    </option>
                                @else
                                    <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                    </option>
                                @endif
                            @else
                                <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                </option>
                            @endif
                        @endforeach

                    </select>
                    <input type="text" hidden readonly name="shiftThree" value="3">
                    @if (!$all['thried'] == null)
                        <input type="text" style="width: 30%" id="time" name="start3"
                            value="{{ $all['thried']->Start_Date }}" placeholder="حدد موعد البدأ">
                        <input type="text" style="width: 30%" id="time" name="end3"
                            value="{{ $all['thried']->End_Date }}" placeholder="حدد موعد الانتهاء">
                    @else
                        <input type="text" style="width: 30%" id="time" name="start3"
                            placeholder="حدد موعد البدأ">
                        <input type="text" style="width: 30%" id="time" name="end3"
                            placeholder="حدد موعد الانتهاء">
                    @endif

                </div>
                <div class="col-sm" id="border">
                    <select name="custody[]" style="width: 30%" aria-label=".form-select-lg example">
                        <option value="null" selected> موظف الجرد
                        </option>
                        @foreach ($all['staff'] as $staff)
                            @if (!$all['forth'] == null)
                                @if ($all['forth']->inventory_Officer_id == $staff->id)
                                    <option selected value="{{ $staff->id }}">{{ $staff->user->name }}
                                    </option>
                                @else
                                    <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                    </option>
                                @endif
                            @else
                                <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <input type="text" hidden readonly name="shiftFore" value="4">

                    @if (!$all['forth'] == null)
                        <input type="text" style="width: 30%" id="time" name="start4"
                            value="{{ $all['forth']->Start_Date }}" placeholder="حدد موعد البدأ">
                        <input type="text" style="width: 30%" id="time" name="end4"
                            value="{{ $all['forth']->End_Date }}" placeholder="حدد موعد الانتهاء">
                    @else
                        <input type="text" style="width: 30%" id="time" name="start4"
                            placeholder="حدد موعد البدأ">
                        <input type="text" style="width: 30%" id="time" name="end4"
                            placeholder="حدد موعد الانتهاء">
                    @endif
                </div>
            </div>

            <div class="row" style="margin-top: 3%;margin-bottom: 3%">
                <button type="submit" class="btn btn-info">حفظ</button>
            </div>
        </form>
     --}}
        <hr class="new5" style="margin-left: 1%">
        <div class="row" style="margin-top: 3%">
            <div class="col-sm">
                <h4>متابعة الصندوق</h4>
            </div>

            <div class="col-sm">
                <a href="{{ route('AdvSearchFollowTheFund') }}" style="width: 60%;margin-right: 20%"
                    class="btn btn-dark">بحث متقدم</a>
            </div>
        </div>
        <div class="row">
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

@section('script')
    <script>
        function submitForm() {
            document.getElementById("form").submit();
        }
    </script>
    <script>
        function submitBRanch() {


            document.getElementById("formBranch").submit();

        }

        window.onload = function() {
            var e = document.getElementById("selectBranch");
            var id = e.value;
            document.getElementById("IDbranch").value = id;
        }
    </script>
    <script>
        $("input[id=time]").clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            default: 'now',
            donetext: "Select",
            init: function() {
                console.log("colorpicker initiated");
            },
            beforeShow: function() {
                console.log("before show");
            },
            afterShow: function() {
                console.log("after show");
            },
            beforeHide: function() {
                console.log("before hide");
            },
            afterHide: function() {
                console.log("after hide");
            },
            beforeHourSelect: function() {
                console.log("before hour selected");
            },
            afterHourSelect: function() {
                console.log("after hour selected");
            },
            beforeDone: function() {
                console.log("before done");
            },
            afterDone: function() {
                console.log("after done");
            }
        });
    </script>
    <script></script>
@endsection
