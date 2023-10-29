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
                <a class="nav-link" id="listNav/AddToMenu" href="{{ route('AddToMenu') }}">{{ __('تعديل القائمة') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listNav/Discount" href="{{ route('Discount') }}">{{ __('الخصومات') }}</a>
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
            انشاء الخصومات
        </h2>
        <form method="POST" action="{{ route('CreateDiscount') }}">
            @csrf
            <div class="row" style="margin-top: 5%;margin-right: 20%">
                <div class="col-sm">
                    <input type="text" name="title" placeholder="العنوان">
                </div>

                <div class="col-sm">
                    <input type="text" name="Description" placeholder="وصف الخصم">
                </div>


            </div>

            <div class="row" style="margin-right: 20%;margin-top: 3%">
                <div class="col-sm">
                    <p>من</p>
                    <input type="date" name="from">
                </div>
                <div class="col-sm">
                    <p>الى</p>
                    <input type="date" name="to">
                </div>
            </div>
            <div class="row" style="margin-right: 20%;margin-top: 3%">
                <div class="col-sm">
                    <select name="branch" class="form-select form-select-lg mb-3" style="width: 55%"
                        aria-label=".form-select-lg example">
                        <option value="none">الفرع
                        </option>
                        @foreach ($all['branchs'] as $item)
                            <option value="{{ $item->id }}">{{ $item->address }}</option>
                        @endforeach
                        <option value="all">الكل</option>

                    </select>
                </div>
                <div class="col-sm">
                    <label class="form-check-strong" for="inlineRadio1">تلقائي</label>

                    <input class="form-check-input" id="autodis" onclick="showDiscount1();" type="checkbox" name="auto"
                        style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                </div>

            </div>
            <div class="row" style="margin-top: 3%;margin-right: 20%">
                <div class="col-sm-6">
                    <select name="discouttype" class="form-select form-select-lg mb-3" style="width: 55%" id="typeDis"
                        aria-label=".form-select-lg example">
                        <option value="0">الخصم يطبق على :</option>
                        <option value="cata">الفئات</option>
                        <option value="elemnt">العناصر</option>
                        <option value="bill">الفاتورة</option>

                    </select>

                </div>
            </div>
            <div class="row" style="margin-top: 3%;margin-right: 20%">
                <div class="col-sm">
                    <label class="form-check-strong" for="inlineRadio1">الخصم بالريال</label>

                    <input class="form-check-input" onclick="showdis1();" type="radio" name="disFor" value="SR"
                        style="margin-left: 90% ; width: 1.5em; height: 1.5em;">

                </div>
                <div class="col-sm">
                    <label class="form-check-strong" for="inlineRadio1">الخصم بالنسبة</label>

                    <input class="form-check-input" onclick="showdis2();" type="radio" name="disFor" value="Pr"
                        style="margin-left: 90% ; width: 1.5em; height: 1.5em;">

                </div>
                <div class="col-sm">
                    <input type="text" name="amount" class="hide" id="amount" placeholder="الخصم بالريال">
                </div>
            </div>
            <!--تيبل تحديد فئة الخصم-->
            <div class="container hide2" id="discCat">
                <div class="row" id="tableDiscountCat">
                    <section style="margin-top: 5% ;margin-right: 20%" class="row">
                        <table style="text-align: center;width: 60%;">
                            <thead>
                                <tr>
                                    <th style="text-align: center" scope="col">الفئة</th>
                                    <th style="text-align: center" scope="col">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all['categories'] as $categories)
                                    <tr>
                                        <td>{{ $categories->Name }}</td>
                                        <td>
                                            <input class="form-check-input" id="autodis" name="cat[]"
                                                type="checkbox" value="{{ $categories->id }}"
                                                style="width: 1.5em; height: 1.5em;">
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </section>
                </div>

            </div>
            <!--تيبل تحديد فئة الخصم End-->
            <!-- تيبل تحديد عنصر الخصم-->
            <div class="container hide2" id="discElemnt">
                <div class="row" id="tableDiscountCat">
                    <section style="margin-top: 5% ;margin-right: 20%" class="row">
                        <table style="text-align: center;width: 60%;">
                            <thead>
                                <tr>
                                    <th style="text-align: center" scope="col">العنصر</th>
                                    <th style="text-align: center" scope="col">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all['items'] as $items)
                                    <tr>
                                        <td>{{ $items->Name }} </td>
                                        <td>
                                            <input class="form-check-input" name="item[]" id="autodis"
                                                type="checkbox" value="{{ $items->id }}"
                                                style="width: 1.5em; height: 1.5em;">
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                </div>

            </div>
            <div class="row">
                <button type="submit" style="width: 50%; margin: auto;margin-top: 3%"
                    class="btn btn-info">ادخال</button>

            </div>
        </form>
        <!--تيبل تحديد عنصر الخصم End-->
        <hr style="margin-top: 5%">
        <h2 id="subtitle">
            الخصومات الفعاله
        </h2>
        <!--تيبل التحكم بالخصومات الفعاله-->
        <div class="container">
            <div class="row" id="tableDiscountCat">
                <section style="margin-top: 5% ;margin-right: 20%" class="row">
                    <table style="text-align: center;width: 60%;">
                        <thead>
                            <tr>
                                <th style="text-align: center" scope="col">التفاصيل</th>
                                <th style="text-align: center" scope="col">العنوان</th>
                                <th style="text-align: center" scope="col">من</th>
                                <th style="text-align: center" scope="col">إلى</th>
                                <th style="text-align: center" scope="col">الخصم يطبق على:</th>
                                <th style="text-align: center" scope="col">الخصم</th>
                                <th style="text-align: center" scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all['Discounts'] as $Discount)
                                <tr>
                                    <td>
                                        <lord-icon src="https://cdn.lordicon.com/ckatldkn.json"
                                            onclick="DiscDitals({{ $Discount->id }})" trigger="hover"
                                            style="width:47px;height:47px">
                                        </lord-icon>
                                    </td>
                                    <td>{{ $Discount->title }}</td>
                                    <td> {{ date('d-m-Y ', strtotime($Discount->from)) }}</td>
                                    <td> {{ date('d-m-Y ', strtotime($Discount->to)) }}</td>
                                    <td>
                                        @if ($Discount->Discount_type == 1)
                                            فئات
                                        @elseif($Discount->Discount_type == 2)
                                            العناصر
                                        @else
                                            الفاتورة
                                        @endif
                                    </td>
                                    <td>
                                        @if ($Discount->Discount_for == 1)
                                            {{ $Discount->DiscountP }} ريال
                                        @else
                                            {{ $Discount->DiscountP }} %
                                        @endif

                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('DeleteDiscount') }}">
                                            @csrf
                                            <input type="text" readonly hidden name="id"
                                                value="{{ $Discount->id }}">
                                            <button type="submit" style="background-color: transparent">
                                                <lord-icon src="https://cdn.lordicon.com/qsloqzpf.json" alt="حذف"
                                                    title="حذف" trigger="hover" colors="primary:#c71f16"
                                                    style="width:47;height:47px">
                                                </lord-icon>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>

        </div>

        <!--تيبل التحكم بالخصومات الفعاله End-->
        <!--موديل تفاصيل الخصم-->
        @foreach ($all['Discounts'] as $Discount)
            <div id="DiscDitals{{ $Discount->id }}" class="modal">

                <div class="modal-content" style="width: 60%">
                    <div class="modal-header">

                        <h2 class="exit" onclick="CloseDiscDitals({{ $Discount->id }})">&times;
                        </h2>
                        تفاصيل الخصم
                    </div>
                    <div class="model-body">

                        <div class="container">
                            <div class="row">
                                <div class="col-sm">
                                    <h3>وصف الخصم : </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <p>
                                        {{ $Discount->Description }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm">
                                    <h3>
                                        @if ($Discount->Discount_type == 1)
                                            الفئات المخصومه
                                        @elseif($Discount->Discount_type == 2)
                                            العناصر المخصومه
                                        @else
                                            الفاتورة
                                        @endif

                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <section style="margin-top: 5% ;margin-right: 20%" class="row">
                                    <table style="text-align: center;width: 60%;">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center" scope="col">التفاصيل</th>
                                                <th style="text-align: center" scope="col">العنوان</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($all['DiscountItems'] as $DiscountItem)
                                                @if ($DiscountItem->Discount_id == $Discount->id)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>
                                                            @if ($DiscountItem->Discount->Discount_type == 1)
                                                                {{ $DiscountItem->Cat->Name }}
                                                            @else
                                                                {{ $DiscountItem->Item->Name }}
                                                            @endif

                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach


                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach







        <!--موديل تفاصيل الخصم End-->

    </div>
@endsection
@section('script')
    <script>
        // الخصم بالريال
        function showdis1() {

            document.getElementById('amount').style.display = 'block';
            document.getElementById('amount').placeholder = 'الخصم بالريال';

        }
        // الخصم بالريالEnd
        //الخصم بالنسبة
        function showdis2() {
            document.getElementById('amount').style.display = 'block';

            document.getElementById('amount').placeholder = 'الخصم بالنسبة';

        }
        //الخصم بالنسبةEnd
        // فتح موديل تفاصيل الخصم 
        function DiscDitals(id) {
            var Ditals = document.getElementById('DiscDitals' + id);
            Ditals.style.display = 'block';
        }
        // فتح موديل تفاصيل الخصم End    
        //اغلاق موديل تفاصيل الخصم
        function CloseDiscDitals(id) {
            var Ditals = document.getElementById('DiscDitals' + id);
            Ditals.style.display = 'none';
        }
        //اغلاق موديل تفاصيل الخصمEnd
    </script>

    <script>
        // فانكشن اذا كان الخصم يطبق بتلقائية ويمنع تطبيق الخصم على الفاتورة 
        function showDiscount1() {
            var checkAuto = document.getElementById('autodis').checked;
            var x = document.getElementById("typeDis");

            x.selectedIndex = 0;
            if (checkAuto) {
                var y = document.getElementById("typeDis").options[3].disabled = true;
            } else {
                var y = document.getElementById("typeDis").options[3].disabled = false;

            }
        } //End فانكشن اذا كان الخصم يطبق بتلقائية ويمنع تطبيق الخصم على الفاتورة 
    </script>
    <script>
        //لاظهار جدول الفئات 
        const el = document.getElementById('typeDis');
        const box = document.getElementById('discCat');
        el.addEventListener('change', function handleChange(event) {
            if (event.target.value === 'cata') {
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        });
        //لاظهار جدول الفئات End
    </script>
    <script>
        //لاظهار جدول العناصر
        const sel = document.getElementById('typeDis');
        const op = document.getElementById('discElemnt');
        sel.addEventListener('change', function handleChange(event) {
            if (event.target.value === 'elemnt') {
                op.style.display = 'block';
            } else {
                op.style.display = 'none';
            }
        });
        //Endلاظهار جدول العناصر
    </script>
@endsection
