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
            @if (Auth::user()->permission_id == 2)
                <li class="nav-item">
                    <a class="nav-link" id="listNav/ReportForStore"
                        href="{{ route('ReportForStore') }}">{{ __('تقارير المخزون') }}</a>
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
            المخزون
        </h2>
        <hr>

        <!-- Single button -->

        <br><br>
        <form method="POST" action="{{ route('SaveStored') }}">
            @csrf
            <div id="rowAdd" class="row">

                <div class="col-lg-6 ">
                    <input type="text" id="selectStored" name="Name" placeholder="اسم العنصر">
                </div>

                <div class="col-lg-6 ">
                    <select name="branch" class="form-select form-select-lg mb-3" id="selectStored"
                        aria-label=".form-select-lg example">
                        <option value="none">الفرع
                        </option>
                        @foreach ($all['branchs'] as $item)
                            <option value="{{ $item->id }}">{{ $item->address }}</option>
                        @endforeach
                        <option value="all">الكل</option>

                    </select>
                </div>
            </div>
            <div class="form-inline" id="storedCalc">
                <input type="number" id="inputAddToMenuCount" name="count" style="margin-right: 3%" placeholder="العدد">
                <Strong style="margin-right: 3%">
                    X
                </Strong>
                <select style="width: 20%;margin-right: 3%" id="unit" name="unit" id="selectExp"
                    class="form-select form-select-lg sm-3" aria-label=".form-select-lg example">


                    <option value="none">الوحده
                    </option>
                    @foreach ($all['units'] as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->Name }}</option>
                    @endforeach

                </select>
                <input type="number" id="inputAddToMenuSize" style="margin-right: 3%" name="size"
                    placeholder="قيمتها في الوحده الواحده ">
                <img src="/image/calculator.gif" alt="احسب" title="احسب" id="imagecalc" onclick="total();">

            </div>

            <div class="row" id="totalStore">
                <div class="col-md">
                    <h3 style="text-align: left" id="total"> المجموع :
                    </h3>
                </div>
                <div class="col-md">
                    <h3 style="text-align: right"></h3>
                </div>
            </div>
            <div class="row" style="margin-top: 5%">
                <button type="submit" style="width: 50%; margin: auto" class="btn btn-info">ادخال</button>

            </div>
        </form>

        <hr class="new5" style="margin-left: 1%">
        {{-- stored table --}}
        <div class="row" style="margin-top: 1%">
            <section style="margin-top: 5%" class="row">

                <button style="background-color: transparent" type="submit" form="formEdit" style="">
                    <img src="/image/download.gif" id="imagecalc" style="margin-right: 90%;"></button>

                <div class="table-responsive">

                    @foreach ($all['branchs'] as $branch)
                        <table style="text-align: center;width: 100%;">
                            <thead>
                                <tr>
                                    <th style="text-align: center" scope="col">العنصر</th>
                                    <th style="text-align: center" scope="col">العددالحالي</th>
                                    <th style="text-align: center" scope="col">الوحدة</th>
                                    <th style="text-align: center" scope="col">قيمتها في الوحده الواحده</th>
                                    <th style="text-align: center" scope="col">المجموع العددالحالي</th>
                                    <th style="text-align: center" scope="col"></th>
                                    <th style="text-align: center" scope="col"></th>


                                </tr>
                            </thead>
                            <tbody>
                                <form action="{{ route('EditItemNameStore') }}" id="formEdit" method="POST">
                                    @csrf
                                </form>
                                <form action="{{ route('deleteStore') }}" id="formDelete" method="POST">
                                    @csrf
                                </form>
                                @foreach ($all['store'] as $store)
                                    @if ($store['branch'] == $branch->id)
                                        <tr>


                                            <td><input type="text" id="inputAddToMenu" name="Name[]" form="formEdit"
                                                    value="{{ $store['Name'] }}">
                                                <input type="text" name="itemId[]" form="formEdit"
                                                    value="{{ $store['id'] }}" hidden readonly>



                                            </td>
                                            <td>
                                                <p>{{ $store['count'] }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $store['unit'] }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $store['value'] }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $store['restValue'] }}</p>
                                            </td>
                                            <td style="width: 10%">
                                                <lord-icon onclick="AddInInvontry({{ $store['id'] }})" alt="اضافة"
                                                    title="اضافة" src="https://cdn.lordicon.com/xzksbhzh.json"
                                                    trigger="hover" colors="primary:#08a88a,secondary:#ebe6ef"
                                                    style="width:80px;height:80px">
                                                </lord-icon>

                                            </td>


                                            <td>
                                                @if ($store['found'])
                                                    <lord-icon alt="حذف" title="حذف"
                                                        src="https://cdn.lordicon.com/qsloqzpf.json" trigger="hover"
                                                        onclick="setId({{ $store['id'] }})" colors="primary:#c71f16"
                                                        style="width:47;height:47px">
                                                    </lord-icon>
                                                @else
                                                    <lord-icon alt="حذف" title="حذف"
                                                        src="https://cdn.lordicon.com/qsloqzpf.json" trigger="hover"
                                                        onclick="deleteItem({{ $store['id'] }})"
                                                        colors="primary:#c71f16" style="width:47;height:47px">
                                                    </lord-icon>
                                                @endif


                                            </td>

                                        </tr>
                                    @endif
                                @endforeach


                            </tbody>
                            <h2>فرع {{ $branch->address }} </h2>

                        </table>

                        <br><br>
                    @endforeach
                </div>
                <input type="text" id="IdItem" readonly hidden name="ID" form="formDelete">
            </section>
        </div>
        @foreach ($all['store'] as $store)
            <div id="AddInInvontry{{ $store['id'] }}" class="modal">

                <!-- Modal content -->
                <form action="{{ route('EditItem') }}" method="post">
                    @csrf
                    <div class="modal-content" style="width: 60%">
                        <div class="modal-header">

                            <h2 class="exit" onclick="closeAddInInvontry({{ $store['id'] }})">&times;
                            </h2>
                            اضافة للمخزون {{ $store['Name'] }}
                            <button onclick="closeAddInInvontry({{ $store['id'] }});" class="btn btn-light">حفظ</button>
                        </div>
                        <div class="model-body">
                            <strong id="unitModel{{ $store['id'] }}" hidden>{{ $store['unit'] }}</strong>
                            <input type="text" name="id" hidden readonly value="{{ $store['id'] }}">
                            <input type="text" name="ValuesCount" hidden readonly id="Value{{ $store['id'] }}">
                            <div class="container">
                                <form class="form-inline" style="margin-top: 2%;margin-right: 7%">
                                    <lord-icon onclick="decrementValueNEw({{ $store['id'] }})"
                                        src="https://cdn.lordicon.com/ymerwkwd.json" trigger="hover" id="lord-icon">
                                    </lord-icon>

                                    <input style="text-align: center;" type="number" step="1" max=""
                                        value="0" name="quantity" id="NewCount{{ $store['id'] }}"
                                        class="quantity-field">

                                    <lord-icon onclick="incrementValueNEw({{ $store['id'] }})" alt="اضافة"
                                        title="اضافة" src="https://cdn.lordicon.com/xzksbhzh.json" trigger="hover"
                                        colors="primary:#08a88a,secondary:#ebe6ef" id="lord-icon">
                                    </lord-icon>
                                    <img src="/image/calculator.gif" alt="احسب" title="احسب" id="imagecalc"
                                        onclick="totalPlus({{ $store['id'] }});">

                                </form>

                                <div class="row" style="margin-top: 3%;margin-right: 8%">
                                    <p>زيادة عدد المجموع</p>

                                    <div class="col-sm">

                                        <input style="text-align: center" type="number" id="Values{{ $store['id'] }}"
                                            placeholder="القيمة" class="quantity-field">
                                    </div>
                                </div>
                                <hr>

                                <div class="row" id="rowStoreModel">
                                    <div class="col-lg-4 mb-4">
                                        <strong> المجموع :</strong>
                                        <p>{{ $store['restValue'] }} {{ $store['unit'] }}</p>
                                    </div>

                                    <div class="col-lg-4 mb-4">
                                        <strong> المجموع النهائي بعد الزيادة :</strong>
                                        <p id="NewTotal{{ $store['id'] }}"></p>
                                    </div>

                                    <div class="col-lg-4 mb-4">
                                        <strong>العدد بعد الزيادة:</strong>
                                        <p id="CountNew{{ $store['id'] }}"></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach

        @foreach ($all['store'] as $store)
            @if ($store['found'])
                <div id="setId{{ $store['id'] }}" class="modal">

                    <div class="modal-content" style="width: 60%">
                        <div class="modal-header">

                            <h2 class="exit" onclick="ClosesetId({{ $store['id'] }})">&times;
                            </h2>
                            العنصر موجود في القائمة هل تريد استبداله بأحد العناصر من المخزون
                            <button onclick="changeItem({{ $store['id'] }});" style="width: 15%"
                                class="btn btn-success">حفظ</button>
                            <button onclick="deleteItem({{ $store['id'] }})" style="width: 15%"
                                class="btn btn-danger">لا</button>

                        </div>
                        <div class="model-body">
                            <form action="{{ route('formChangeItem') }}" id="ChangeItem{{ $store['id'] }}"
                                method="POST">
                                @csrf
                                <div class="container">
                                    <input type="text" name="idstore" form="ChangeItem{{ $store['id'] }}" hidden
                                        readonly value="{{ $store['id'] }}">

                                    <div class="row" style="margin-top: 3%;margin-right: 10%">
                                        <select form="ChangeItem{{ $store['id'] }}" name="secondaryID"
                                            id="secondary{{ $store['id'] }}" class="form-select form-select-lg mb-3"
                                            style="width:80%" aria-label=".form-select-lg example">
                                            <option selected>العنصر</option>

                                            @foreach ($all['store'] as $item)
                                                @if ($item['id'] != $store['id'])
                                                    <option value="{{ $item['id'] }}">{{ $item['Name'] }} - فرع
                                                        {{ $item['BranchName'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                    <hr>
                                    <div class="row">
                                        <h4>العنصر موجود كوصفه في القائمة التالية:</h4>
                                    </div>

                                    <ul id="listMenuUL">
                                        @foreach ($all['comItems'][$store['id']] as $itemCom)
                                            <li id="listMenuLI">{{ $itemCom['Item']->Name }}
                                                @if ($itemCom->size == 1)
                                                    صغير
                                                @elseif($itemCom->size == 2)
                                                    وسط
                                                @elseif($itemCom->size == 3)
                                                    كبير
                                                @endif
                                                الحجم
                                            </li>
                                        @endforeach


                                    </ul>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            @endif
        @endforeach


    </div>
@endsection

@section('script')
    <script>
        function setId(id) {

            var modal = document.getElementById("setId" + id);
            modal.style.display = "block";
        }

        function ClosesetId(id) {
            var modal = document.getElementById("setId" + id);
            modal.style.display = "none";
        }

        function changeItem(id) {
            document.getElementById('ChangeItem' + id).submit();

        }

        function deleteItem(id) {
            document.getElementById("IdItem").value = id;
            console.log(document.getElementById("IdItem").value);
            document.getElementById('formDelete').submit();
        }
    </script>
    <script>
        function total() {

            var size = document.getElementById("inputAddToMenuSize");
            var count = document.getElementById("inputAddToMenuCount");
            var unit = document.getElementById("unit");
            var text = unit.options[unit.selectedIndex].text;

            $total = parseFloat(count.value * size.value).toFixed(2);
            document.getElementById("total").innerText = 'المجموع : ' + parseFloat($total) + ' ' + text;


        }
    </script>
    <script>
        function totalPlus($id) {
            var contents = @json($all['store']);
            var result = contents.find(({
                id
            }) => id === $id);

            var newCount = document.getElementById("NewCount" + result['id']); //  العدد الجديد 
            var unit = document.getElementById("unitModel" + $id);
            var values = document.getElementById("Values" + $id).value;
            var values = values == '' ? 0 : values;
            const CountNew = parseFloat(result['count']) + parseFloat(newCount.value);
            const NewValue = result['value'] * newCount.value + parseFloat(values);
            const total = result['restValue'] + NewValue;
            document.getElementById("Value" + $id).value = document.getElementById("Values" + $id).value;

            document.getElementById("NewTotal" + $id).innerText = 'المجموع : ' + parseFloat(total) + ' ' + unit.innerText;
            document.getElementById('CountNew' + $id).innerText = parseFloat(CountNew);

        }
    </script>
    <script>
        function AddInInvontry($id) {
            var modal = document.getElementById("AddInInvontry" + $id);
            modal.style.display = "block";
        }

        function closeAddInInvontry($id) {
            var modal = document.getElementById("AddInInvontry" + $id);
            modal.style.display = "none";
        }
    </script>
    <script>
        function incrementValueNEw($id) {
            document.getElementById('NewCount' + $id).value++;
        }

        function decrementValueNEw($id) {
            if (document.getElementById('NewCount' + $id).value-- <= 0) {
                document.getElementById('NewCount' + $id).value++;
            }
        }
    </script>
@endsection
