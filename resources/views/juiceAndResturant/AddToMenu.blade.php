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

            {{-- <li class="nav-item">
  <a class="nav-link" id="listNav/Discount"
  href="{{ route('Discount') }}">{{ __('الخصومات') }}</a>
  </li> --}}
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
            تعديل القائمة
        </h2>
        <hr>

        <!-- Single button -->

        <br><br>

        <div class="row" id="divrow1">
            <div class="col-lg-4 mb-4">
                <label class="form-check-strong" for="inlineRadio1">الفئة</label>

                <input class="form-check-input" onclick="showmenu1();" type="radio" name="small" value="yes"
                    style="margin-left: 90% ; width: 1.5em; height: 1.5em;">

            </div>
            <div class="col-lg-4 mb-4">
                <label class="form-check-strong" for="inlineRadio1">العنصر</label>

                <input class="form-check-input" onclick="showmenu2();" type="radio" name="small" value="yes"
                    style="margin-left: 90% ; width: 1.5em; height: 1.5em;">

            </div>
            <div class="col-lg-4 mb-4">
                <label class="form-check-strong" for="inlineRadio1">الإضافات</label>

                <input class="form-check-input" onclick="showmenu3();" type="radio" name="small" value="yes"
                    style="margin-left: 90% ; width: 1.5em; height: 1.5em;">

            </div>
        </div>

        <div class="container hide2" id="div4">
            <form method="POST" action="{{ route('CreateCat') }}">
                @csrf
                <div class="row">

                    <div class="col-sm">
                        <input type="text" name="Cat" placeholder="اكتب الفئة">
                    </div>
                    <div class="col-sm">
                        <button type="submit" class="btn btn-info">ادخال</button>
                    </div>

                </div>
            </form>

        </div>

        <div class="container hide2" id="div5">
            <form method="POST" action="{{ route('CreateItem') }}" enctype="multipart/form-data">
                @csrf
                <div id="AddIngredients1" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content" style="width: 60%">
                        <div class="modal-header">

                            <h2 class="exit" onclick="closeAddIngredients(1)">&times;
                            </h2>
                            اضافة مكونات العنصر
                            <p></p>
                        </div>
                        <div class="model-body">
                            <div class="container pt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">العنصر</th>
                                                <th>الكمية</th>

                                                <th class="text-center">حذف العنصر</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody1">

                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-md btn-primary" id="addBtn1" type="button">
                                    اضافة عنصر اخرى
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="AddIngredients2" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content" style="width: 60%">
                        <div class="modal-header">

                            <h2 class="exit" onclick="closeAddIngredients(2)">&times;
                            </h2>
                            اضافة مكونات العنصر
                            <p></p>
                        </div>
                        <div class="model-body">
                            <div class="container pt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">العنصر</th>
                                                <th>الكمية</th>

                                                <th class="text-center">حذف العنصر</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody2">

                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-md btn-primary" id="addBtn2" type="button">
                                    اضافة عنصر اخرى
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="AddIngredients3" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content" style="width: 60%">
                        <div class="modal-header">

                            <h2 class="exit" onclick="closeAddIngredients(3)">&times;
                            </h2>
                            اضافة مكونات العنصر
                            <p></p>
                        </div>
                        <div class="model-body">
                            <div class="container pt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">العنصر</th>
                                                <th>الكمية</th>

                                                <th class="text-center">حذف العنصر</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody3">



                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-md btn-primary" id="addBtn3" type="button">
                                    اضافة عنصر اخرى
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm">
                        <input type="text" name="Name" placeholder="اسم العنصر">
                    </div>
                    <div class="col-sm">
                        <select name="Cat" class="form-select form-select-lg mb-3" style="width: 43%"
                            aria-label=".form-select-lg example">
                            <option selected value="0">الفئة</option>
                            @foreach ($all['categories'] as $item)
                                <option value="{{ $item->id }}">{{ $item->Name }}</option>
                            @endforeach

                        </select>

                    </div>
                    <div class="col-sm">
                        <input type="text" name="code" placeholder="رمز العنصر">
                    </div>

                </div>
                <div class="row" style="margin-top: 5%">
                    <div class="col-sm">
                        <input type="text" name="SmallName" placeholder="1">
                        <input type="number" step="any" name="SmallPrice" placeholder="حدد السعر ">
                        <lord-icon alt="اضافة المكونات" title="اضافة المكونات" onclick="AddIngredients(1)"
                            src="https://cdn.lordicon.com/puvaffet.json" trigger="hover"
                            colors="primary:#121331,secondary:#08a88a" style="width:47px;height:47px">
                        </lord-icon>
                    </div>



                    <div class="col-sm">
                        <input type="text" name="MidName" placeholder="2">
                        <input type="number" step="any" name="MidPrice" placeholder="حدد السعر ">
                        <lord-icon alt="اضافة المكونات" title="اضافة المكونات" onclick="AddIngredients(2)"
                            src="https://cdn.lordicon.com/puvaffet.json" trigger="hover"
                            colors="primary:#121331,secondary:#08a88a" style="width:47px;height:47px">
                        </lord-icon>
                    </div>



                </div>
                <div class="row" style="margin-top: 5%">
                    <div class="col-sm">
                        <input type="text" name="BigName" placeholder="3">
                        <input type="number" step="any" name="BigPrice " placeholder="حدد السعر ">
                        <lord-icon alt="اضافة المكونات" title="اضافة المكونات" onclick="AddIngredients(3)"
                            src="https://cdn.lordicon.com/puvaffet.json" trigger="hover"
                            colors="primary:#121331,secondary:#08a88a" style="width:47px;height:47px">
                        </lord-icon>
                    </div>

                    <div class="col-sm">
                        <input type="text" name="description" placeholder="الوصف">
                    </div>

                </div>
                <div class="row" style="margin-top: 5%">
                    <div class="col-sm">
                        <div class="preview">
                            <img style="width: 50%; height: 20% ;" id="file-ip-1-preview">
                        </div>
                        <lord-icon src="https://cdn.lordicon.com/kgyyczfx.json" trigger="hover"
                            colors="primary:#eee966,secondary:#2ca58d,tertiary:#9cc2f4" style="width:80px;height:80px">
                        </lord-icon> <input type="file" name="pic" id="file-ip-1" accept="image/*.jpg"
                            onchange="showPreview(event);">
                    </div>
                </div>
                <br>
                <div class="row">
                    <button type="submit" style="width: 50%; margin: auto" class="btn btn-info">ادخال</button>

                </div>


            </form>
        </div>
        <div class="container hide2" id="div6">
            <form method="POST" action="{{ route('ExtraToppings') }}" enctype="multipart/form-data">
                @csrf
                <div class="row" style="margin-right: 10%">
                    <div class="col-sm">
                        <input type="text" name="Name" placeholder="اكتب الإضافة">
                    </div>
                    <div class="col-sm">
                        <select name="store" style="width: 50%" class="form-select form-select-lg mb-3"
                            aria-label=".form-select-lg example">

                            <option value="0" selected>اختار
                            </option>
                            @foreach ($all['store'] as $store)
                                <option value="{{ $store['id'] }}">{{ $store['Name'] }} / {{ $store['unit'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-right: 10%">
                    <div class="col-sm">
                        <input type="number" step="any" name="ToppingPrice" placeholder="سعر الاضافة">
                    </div>
                    <div class="col-sm" style="margin-right: 2%">
                        <input type="number" step="any" name="ToppingCount" placeholder="الكمية">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg" style="margin-top: 3%;margin-right: 35%">
                        <button type="submit" style="width: 40%" class="btn btn-info">اضافة</button>
                    </div>
                </div>
            </form>
        </div>
        <hr class="new5" style="margin-left: 1%">
        <div class="row hide2" id="tableItem">
            <section style="margin-top: 5%" class="row">

                <table style="text-align: center">
                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">العنصر</th>
                            <th style="text-align: center" scope="col">الفئة</th>
                            <th style="text-align: center" scope="col">#</th>
                            <th style="text-align: center" scope="col">#</th>
                            <th style="text-align: center" scope="col">#</th>
                            <th style="text-align: center" scope="col"></th>


                            <td>

                                <input id="inputAddToMenu" name="name[]" type="text" value="{{ $item['Name'] }}">
                                <input type="text" name="id[]" value="{{ $item['id'] }}" readonly hidden>
                            </td>
                            <td>
                                <select name="cat[]" class="form-select form-select-lg mb-3"
                                    aria-label=".form-select-lg example">
                                    @foreach ($all['categories'] as $cat)
                                        @if ($cat->id == $item['categories_id'])
                                            <option value="{{ $cat->id }}" selected>{{ $cat->Name }}
                                            </option>
                                        @else
                                            <option value="{{ $cat->id }}">{{ $cat->Name }}</option>
                                        @endif
                                    @endforeach

                                </select>
                            </td>
                            <td>
                                <lord-icon onclick="EditItem()" src="https://cdn.lordicon.com/rfbqeber.json"
                                    trigger="hover" style="width:47px;height:47px">
                                </lord-icon>
                                </a>
                            </td>
                            <!--<td>
                                                <input type="text" id="inputAddToMenu" style="width: 45%" placeholder="1"
                                                value="{{ $item['Small_Name'] }}" name="SmallName[]">

                                                <input id="inputAddToMenu" type="number"  step="any" style="width: 25%" name="small[]"
                                                    value="{{ $item['Small_Price'] }}">
                                                    <lord-icon  alt="تعديل على المكونات" title="تعديل على المكونات" onclick="EditIngredients({{ $item->id }},1)"
                                                    src="https://cdn.lordicon.com/puvaffet.json"
                                                    trigger="hover"
                                                    colors="primary:#121331,secondary:#08a88a"
                                                    style="width:47px;height:47px">
                                                        </lord-icon>
                                            </td>
                                            <td>
                                                <input type="text" id="inputAddToMenu" style="width: 45%"  placeholder="2"
                                                value="{{ $item['Mid_Name'] }}" name="MidName[]">

                                                <input id="inputAddToMenu" type="number"  step="any" style="width: 25%" name="mid[]"
                                                    value="{{ $item['Mid_Price'] }}">
                                                    <lord-icon  alt="تعديل على المكونات" title="تعديل على المكونات" onclick="EditIngredients({{ $item->id }},2)"
                                                        src="https://cdn.lordicon.com/puvaffet.json"
                                                        trigger="hover"
                                                        colors="primary:#121331,secondary:#08a88a"
                                                        style="width:47px;height:47px">
                                                            </lord-icon>

                                                </td>
                                            <td>
                                                <input type="text" id="inputAddToMenu" style="width: 45%"  placeholder="3"
                                                value="{{ $item['Big_Name'] }}"name="BigName[]">

                                                <input id="inputAddToMenu" type="number"  step="any"  style="width: 25%" name="big[]"
                                                    value="{{ $item['Big_Price'] }}">
                                                    <lord-icon  alt="تعديل على المكونات" title="تعديل على المكونات" onclick="EditIngredients({{ $item->id }},3)"
                                                    src="https://cdn.lordicon.com/puvaffet.json"
                                                    trigger="hover"
                                                    colors="primary:#121331,secondary:#08a88a"
                                                    style="width:47px;height:47px">
                                                        </lord-icon>
                                                </td>-->

                            <td> <a href="/delete-Item-{{ $item->id }}">
                                    <lord-icon alt="حذف" title="حذف" src="https://cdn.lordicon.com/qsloqzpf.json"
                                        trigger="hover" colors="primary:#c71f16" style="width:47;height:47px">
                                    </lord-icon>
                                </a>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="POST" id="form" action="{{ route('UpdateItem') }}"
                            enctype="multipart/form-data">

                            @foreach ($all['items'] as $item)
                                @csrf
                                <tr>

                                    <td>

                                        <input id="inputAddToMenu" name="name[]" type="text"
                                            value="{{ $item['Name'] }}">
                                        <input type="text" name="id[]" value="{{ $item['id'] }}" readonly
                                            hidden>
                                    </td>
                                    <td style="width: 15%">
                                        <select name="cat[]" class="form-select form-select-lg mb-3"
                                            aria-label=".form-select-lg example">
                                            @foreach ($all['categories'] as $cat)
                                                @if ($cat->id == $item['categories_id'])
                                                    <option value="{{ $cat->id }}" selected>{{ $cat->Name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $cat->id }}">{{ $cat->Name }}</option>
                                                @endif
                                            @endforeach

                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="inputAddToMenu" style="width: 45%" placeholder="1"
                                            value="{{ $item['Small_Name'] }}" name="SmallName[]">

                                        <input id="inputAddToMenu" type="number" step="any" style="width: 25%"
                                            name="small[]" value="{{ $item['Small_Price'] }}">
                                        <lord-icon alt="تعديل على المكونات" title="تعديل على المكونات"
                                            onclick="EditIngredients({{ $item->id }},1)"
                                            src="https://cdn.lordicon.com/puvaffet.json" trigger="hover"
                                            colors="primary:#121331,secondary:#08a88a" style="width:47px;height:47px">
                                        </lord-icon>
                                    </td>
                                    <td>
                                        <input type="text" id="inputAddToMenu" style="width: 45%" placeholder="2"
                                            value="{{ $item['Mid_Name'] }}" name="MidName[]">

                                        <input id="inputAddToMenu" type="number" step="any" style="width: 25%"
                                            name="mid[]" value="{{ $item['Mid_Price'] }}">
                                        <lord-icon alt="تعديل على المكونات" title="تعديل على المكونات"
                                            onclick="EditIngredients({{ $item->id }},2)"
                                            src="https://cdn.lordicon.com/puvaffet.json" trigger="hover"
                                            colors="primary:#121331,secondary:#08a88a" style="width:47px;height:47px">
                                        </lord-icon>

                                    </td>
                                    <td>
                                        <input type="text" id="inputAddToMenu" style="width: 45%" placeholder="3"
                                            value="{{ $item['Big_Name'] }}"name="BigName[]">

                                        <input id="inputAddToMenu" type="number" step="any" style="width: 25%"
                                            name="big[]" value="{{ $item['Big_Price'] }}">
                                        <lord-icon alt="تعديل على المكونات" title="تعديل على المكونات"
                                            onclick="EditIngredients({{ $item->id }},3)"
                                            src="https://cdn.lordicon.com/puvaffet.json" trigger="hover"
                                            colors="primary:#121331,secondary:#08a88a" style="width:47px;height:47px">
                                        </lord-icon>
                                    </td>
                                    <td> <a href="/delete-Item-{{ $item->id }}">
                                            <lord-icon alt="حذف" title="حذف"
                                                src="https://cdn.lordicon.com/qsloqzpf.json" trigger="hover"
                                                colors="primary:#c71f16" style="width:47;height:47px">
                                            </lord-icon>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            {{ $all['items']->links() }}
                            <input onclick="submitForm()" class="slide" type="image"
                                style="width: 5%;margin-right: 90% ; border: none" name="category" alt="حفظ"
                                title="حفظ" src="/image/download.gif">


                        </form>


                    </tbody>
                </table>
                @foreach ($all['items'] as $item)
                    <?php $i = 4; ?>

                    @foreach ($all['compounds'] as $compounds)
                        @if ($compounds['item_id'] == $item->id)
                            @if ($compounds['size'] == 1)
                                {{-- Size 1  --}}

                                <form method="POST" id="Update" action="{{ route('UpdateItemIingredients') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" hidden readonly name="id" value="{{ $item->id }}">
                                    <input type="text" hidden readonly name="size" value="1">

                                    <div id="EditIngredients1{{ $item->id }}" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content" style="width: 60%">
                                            <div class="modal-header">

                                                <h2 class="exit" onclick="closeEditIngredients({{ $item->id }},1)">
                                                    &times;
                                                </h2>
                                                اضافة مكونات العنصر
                                                <button onclick="closeEditIngredients({{ $item->id }},1)"
                                                    type="submit" class="btn btn-light">حفظ</button>
                                            </div>

                                            <div class="model-body">
                                                <div class="container pt-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">العنصر</th>
                                                                    <th>الكمية</th>

                                                                    <th class="text-center">حذف العنصر</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tbody{{ $i }}">
                                                                @foreach ($all['compounds'] as $storeItems)
                                                                    @if ($storeItems['item_id'] == $compounds['item_id'] && $storeItems['size'] == 1)
                                                                        <tr id="R{{ $i }}">

                                                                            <td class="row-index text-center">

                                                                                <select name="itemSmall[]"
                                                                                    class="form-select form-select-lg mb-3"
                                                                                    aria-label=".form-select-lg example">
                                                                                    @foreach ($all['store'] as $store)
                                                                                        @if ($storeItems['store_id'] == $store['id'])
                                                                                            <option
                                                                                                value="{{ $store['id'] }}"
                                                                                                selected>
                                                                                                {{ $store['Name'] }}
                                                                                                {{ $store['unit'] }}
                                                                                            </option>
                                                                                        @else
                                                                                            <option
                                                                                                value="{{ $store['id'] }}">
                                                                                                {{ $store['Name'] }}
                                                                                                {{ $store['unit'] }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>


                                                                            </td>
                                                                            <td class="text-center"><input type="number"
                                                                                    name="countSmall[]" step="any"
                                                                                    value="{{ $storeItems['count'] }}">
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <button class="btn btn-danger remove"
                                                                                    type="button">Remove</button>
                                                                            </td>

                                                                        </tr>
                                                                    @endif
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button class="btn btn-md btn-primary" id="addBtn{{ $i }}"
                                                        type="button">
                                                        اضافة عنصر اخرى
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            @elseif ($compounds['size'] == 2)
                                {{-- Size 2  --}}

                                <form method="POST" id="Update" action="{{ route('UpdateItemIingredients') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" hidden readonly name="id" value="{{ $item->id }}">
                                    <input type="text" hidden readonly name="size" value="2">
                                    <div id="EditIngredients2{{ $item->id }}" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content" style="width: 60%">
                                            <div class="modal-header">

                                                <h2 class="exit" onclick="closeEditIngredients({{ $item->id }},2)">
                                                    &times;
                                                </h2>
                                                اضافة مكونات العنصر
                                                <button onclick="closeEditIngredients({{ $item->id }},2)"
                                                    type="submit" class="btn btn-light">حفظ</button>
                                            </div>
                                            <div class="model-body">
                                                <div class="container pt-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">العنصر</th>
                                                                    <th>الكمية</th>

                                                                    <th class="text-center">حذف العنصر</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tbody{{ $i }}">
                                                                @foreach ($all['compounds'] as $storeItems)
                                                                    @if ($storeItems['item_id'] == $compounds['item_id'] && $storeItems['size'] == 2)
                                                                        <tr id="R{{ $i }}">

                                                                            <td class="row-index text-center">

                                                                                <select name="itemSmall[]"
                                                                                    class="form-select form-select-lg mb-3"
                                                                                    aria-label=".form-select-lg example">
                                                                                    @foreach ($all['store'] as $store)
                                                                                        @if ($storeItems['store_id'] == $store['id'])
                                                                                            <option
                                                                                                value="{{ $store['id'] }}"
                                                                                                selected>
                                                                                                {{ $store['Name'] }}
                                                                                                {{ $store['unit'] }}
                                                                                            </option>
                                                                                        @else
                                                                                            <option
                                                                                                value="{{ $store['id'] }}">
                                                                                                {{ $store['Name'] }}
                                                                                                {{ $store['unit'] }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>


                                                                            </td>
                                                                            <td class="text-center"><input type="number"
                                                                                    name="countSmall[]"
                                                                                    value="{{ $storeItems['count'] }}">
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <button class="btn btn-danger remove"
                                                                                    type="button">Remove</button>
                                                                            </td>

                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button class="btn btn-md btn-primary" id="addBtn{{ $i }}"
                                                        type="button">
                                                        اضافة عنصر اخرى
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @elseif ($compounds['size'] == 3)
                                {{-- Size 3  --}}
                                <form method="POST" id="Update" action="{{ route('UpdateItemIingredients') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" hidden readonly name="id" value="{{ $item->id }}">
                                    <input type="text" hidden readonly name="size" value="3">

                                    <div id="EditIngredients3{{ $item->id }}" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content" style="width: 60%">
                                            <div class="modal-header">

                                                <h2 class="exit" onclick="closeEditIngredients({{ $item->id }},3)">
                                                    &times;
                                                </h2>
                                                اضافة مكونات العنصر
                                                <button onclick="closeEditIngredients({{ $item->id }},3)"
                                                    type="submit" class="btn btn-light">حفظ</button>
                                            </div>
                                            <div class="model-body">
                                                <div class="container pt-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">العنصر</th>
                                                                    <th>الكمية</th>

                                                                    <th class="text-center">حذف العنصر</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tbody{{ $i }}">
                                                                @foreach ($all['compounds'] as $storeItems)
                                                                    @if ($storeItems['item_id'] == $compounds['item_id'] && $storeItems['size'] == 3)
                                                                        <tr id="R{{ $i }}">

                                                                            <td class="row-index text-center">

                                                                                <select name="itemSmall[]"
                                                                                    class="form-select form-select-lg mb-3"
                                                                                    aria-label=".form-select-lg example">
                                                                                    @foreach ($all['store'] as $store)
                                                                                        @if ($storeItems['store_id'] == $store['id'])
                                                                                            <option
                                                                                                value="{{ $store['id'] }}"
                                                                                                selected>
                                                                                                {{ $store['Name'] }}
                                                                                                {{ $store['unit'] }}
                                                                                            </option>
                                                                                        @else
                                                                                            <option
                                                                                                value="{{ $store['id'] }}">
                                                                                                {{ $store['Name'] }}
                                                                                                {{ $store['unit'] }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>


                                                                            </td>
                                                                            <td class="text-center"><input type="number"
                                                                                    name="countSmall[]"
                                                                                    value="{{ $storeItems['count'] }}">
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <button class="btn btn-danger remove"
                                                                                    type="button">Remove</button>
                                                                            </td>

                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button class="btn btn-md btn-primary" id="addBtn{{ $i }}"
                                                        type="button">
                                                        اضافة عنصر اخرى
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                            {{-- WithOut  --}}
                        @endif
                        <?php ++$i; ?>
                    @endforeach
                @endforeach
                <form method="POST" id="Update" action="{{ route('UpdateItemIingredients') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="text" hidden readonly name="id" id="0id" value="">
                    <input type="text" hidden readonly name="size" id="0size">

                    <div id="EditIngredients0" class="modal">

                        <!-- Modal content -->
                        <div class="modal-content" style="width: 60%">
                            <div class="modal-header">

                                <h2 class="exit" onclick="closeEditIngredients(0,0)">&times;
                                </h2>
                                اضافة مكونات العنصر
                                <button onclick="closeEditIngredients(0,0)" type="submit"
                                    class="btn btn-light">حفظ</button>
                            </div>
                            <div class="model-body">
                                <div class="container pt-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">العنصر</th>
                                                    <th>الكمية</th>

                                                    <th class="text-center">حذف العنصر</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody0">

                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-md btn-primary" id="addBtn0" type="button">
                                        اضافة عنصر اخرى
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
        </div>
        </section>
    </div>

    <!--                 ------>
    <div class="row hide2" id="tableAddToItem">
        <section style="margin-top: 5%" class="row">
            <table style="text-align: center">
                <thead>
                    <tr>
                        <th style="text-align: center" scope="col">الاضافة</th>
                        <th style="text-align: center" scope="col">من المخزون</th>
                        <th style="text-align: center" scope="col">السعر</th>
                        <th style="text-align: center" scope="col">الكمية</th>
                        <th style="text-align: center" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <form method="POST" id="changeExtra" action="{{ route('changeExtraTopping') }}"
                        enctype="multipart/form-data">

                        @foreach ($all['extraToppings'] as $extraTopping)
                            @csrf
                            <tr>
                                <input type="text" name="idExtra[]" hidden readonly value="{{ $extraTopping->id }}">
                                <td> <input type="text" name="NameExtra[]" value="{{ $extraTopping->Name }}">
                                </td>
                                <td><select name="storeExtra[]"class="form-select form-select-lg mb-3"
                                        aria-label=".form-select-lg example">

                                        @foreach ($all['store'] as $store)
                                            @if ($store['id'] == $extraTopping->store_id)
                                                <option value="{{ $store['id'] }}" selected>{{ $store['Name'] }} /
                                                    {{ $store['unit'] }} </option>
                                            @else
                                                <option value="{{ $store['id'] }}">{{ $store['Name'] }} /
                                                    {{ $store['unit'] }} </option>
                                            @endif
                                        @endforeach
                                    </select></td>
                                <td>
                                    <input type="number" step="any" name="ToppingPrice[]"
                                        value="{{ $extraTopping->price }}">ريال
                                </td>
                                <td> <input type="number" step="any" name="ToppingCount[]"
                                        value="{{ $extraTopping->count }}">
                                </td>
                                <td><a href="/delete-Topping-{{ $extraTopping->id }}">
                                        <lord-icon alt="حذف" title="حذف"
                                            src="https://cdn.lordicon.com/qsloqzpf.json" trigger="hover"
                                            colors="primary:#c71f16" style="width:47;height:47px">
                                        </lord-icon>
                                    </a></td>
                            </tr>
                        @endforeach
                    </form>
                    <input onclick="submitChangeExtra();" class="slide" type="image"
                        style="width: 5%;margin-right: 90% ; border: none" name="category" alt="حفظ" title="حفظ"
                        src="/image/download.gif">

                </tbody>
            </table>
            <!--                 ---->
        </section>
    </div>
    </div>
@endsection
@section('script')
    <script>
        $(":input").keypress(function(event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();
            }
        });
    </script>



    <script>
        function EditIngredients($id, $size) {
            var contents = @json($all['compounds']);
            var result = 0;
            var result = contents.findIndex(({
                item_id,
                size
            }) => item_id == $id && size == $size);
            if (result != -1) {
                var modal = document.getElementById("EditIngredients" + $size + $id);
                modal.style.display = "block";

            } else {


                document.getElementById("0size").value = $size;
                document.getElementById("0id").value = $id;
                var modal = document.getElementById("EditIngredients0");
                modal.style.display = "block";
            }
            console.log($id);
            console.log($size);
            console.log(result);


        }

        function closeEditIngredients($id, $size) {
            if ($id == 0 || $size == 0) {

                var modal = document.getElementById("EditIngredients" + $id);
                modal.style.display = "none";
            } else {

                var modal = document.getElementById("EditIngredients" + $size + $id);
                modal.style.display = "none";
            }

        }
    </script>
    <script>
        function AddIngredients($size) {
            var modal = document.getElementById("AddIngredients" + $size);
            modal.style.display = "block";


        }

        function closeAddIngredients($size) {
            var modal = document.getElementById("AddIngredients" + $size);
            modal.style.display = "none";

        }
    </script>
    <script>
        function submitForm() {
            document.getElementById("form").submit();
        }

        function submitChangeExtra() {
            document.getElementById("changeExtra").submit();

        }
    </script>
    <script>
        $(document).ready(function() {

            // Denotes total number of rows
            var rowIdx = 0;

            // jQuery button click event to add a row
            $('#addBtn0').on('click', function() {

                // Adding a row inside the tbody.
                $('#tbody0').append(`
       
        <tr id="R${++rowIdx}">
          
             <td class="row-index text-center">
     
    
    
                                <select name="itemSmall[]" class="form-select form-select-lg mb-3"
                                            aria-label=".form-select-lg example">
                                            @foreach ($all['store'] as $store)
                                            <option value="{{ $store['id'] }}"  >{{ $store['Name'] }}  {{ $store['unit'] }}
                                                    </option>
                                               
                                            @endforeach
    
                                        </select>
    
    
             </td>
             <td class="text-center"><input type = "number" name="countSmall[]" ></td>
              <td class="text-center">
                <button class="btn btn-danger remove"
                  type="button">Remove</button>
                </td>
    
              </tr>
    
              `);
            });

            // jQuery button click event to remove a row.
            $('#tbody0').on('click', '.remove', function() {

                // Getting all the rows next to the row
                // containing the clicked button
                var child = $(this).closest('tr').nextAll();

                // Iterating across all the rows 
                // obtained to change the index
                child.each(function() {

                    // Getting <tr> id.
                    var id = $(this).attr('id');

                    // Getting the <p> inside the .row-index class.
                    var idx = $(this).children('.row-index').children('p');

                    // Gets the row number from <tr> id.
                    var dig = parseInt(id.substring(1));

                    // Modifying row index.
                    idx.html(`Row ${dig - 1}`);

                    // Modifying row id.
                    $(this).attr('id', `R${dig - 1}`);
                });

                // Removing the current row.
                $(this).closest('tr').remove();

                // Decreasing total number of rows by 1.
                rowIdx--;
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // Denotes total number of rows
            var rowIdx = 0;

            // jQuery button click event to add a row
            $('#addBtn1').on('click', function() {

                // Adding a row inside the tbody.
                $('#tbody1').append(`
       
        <tr id="R${++rowIdx}">
          
             <td class="row-index text-center">
     
    
    
                                <select name="itemSmall[]" class="form-select form-select-lg mb-3"
                                            aria-label=".form-select-lg example">
                                            @foreach ($all['store'] as $store)
                                            <option value="{{ $store['id'] }}"  >{{ $store['Name'] }}  {{ $store['unit'] }}
                                                    </option>
                                               
                                            @endforeach
    
                                        </select>
    
    
             </td>
             <td class="text-center"><input type = "number" name="countSmall[]" ></td>
              <td class="text-center">
                <button class="btn btn-danger remove"
                  type="button">Remove</button>
                </td>
    
              </tr>
    
              `);
            });

            // jQuery button click event to remove a row.
            $('#tbody1').on('click', '.remove', function() {

                // Getting all the rows next to the row
                // containing the clicked button
                var child = $(this).closest('tr').nextAll();

                // Iterating across all the rows 
                // obtained to change the index
                child.each(function() {

                    // Getting <tr> id.
                    var id = $(this).attr('id');

                    // Getting the <p> inside the .row-index class.
                    var idx = $(this).children('.row-index').children('p');

                    // Gets the row number from <tr> id.
                    var dig = parseInt(id.substring(1));

                    // Modifying row index.
                    idx.html(`Row ${dig - 1}`);

                    // Modifying row id.
                    $(this).attr('id', `R${dig - 1}`);
                });

                // Removing the current row.
                $(this).closest('tr').remove();

                // Decreasing total number of rows by 1.
                rowIdx--;
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // Denotes total number of rows
            var rowIdx = 0;

            // jQuery button click event to add a row
            $('#addBtn2').on('click', function() {

                // Adding a row inside the tbody.
                $('#tbody2').append(`
       
        <tr id="R${++rowIdx}">
          
             <td class="row-index text-center">
     
    
    
                                <select name="itemMid[]" class="form-select form-select-lg mb-3"
                                            aria-label=".form-select-lg example">
                                            @foreach ($all['store'] as $store)
                                            <option value="{{ $store['id'] }}"  >{{ $store['Name'] }}  {{ $store['unit'] }}
                                                    </option>
                                               
                                            @endforeach
    
                                        </select>
    
    
             </td>
             <td class="text-center"><input type = "number" name="countMid[]" ></td>
              <td class="text-center">
                <button class="btn btn-danger remove"
                  type="button">Remove</button>
                </td>
    
              </tr>
    
              `);
            });

            // jQuery button click event to remove a row.
            $('#tbody2').on('click', '.remove', function() {

                // Getting all the rows next to the row
                // containing the clicked button
                var child = $(this).closest('tr').nextAll();

                // Iterating across all the rows 
                // obtained to change the index
                child.each(function() {

                    // Getting <tr> id.
                    var id = $(this).attr('id');

                    // Getting the <p> inside the .row-index class.
                    var idx = $(this).children('.row-index').children('p');

                    // Gets the row number from <tr> id.
                    var dig = parseInt(id.substring(1));

                    // Modifying row index.
                    idx.html(`Row ${dig - 1}`);

                    // Modifying row id.
                    $(this).attr('id', `R${dig - 1}`);
                });

                // Removing the current row.
                $(this).closest('tr').remove();

                // Decreasing total number of rows by 1.
                rowIdx--;
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // Denotes total number of rows
            var rowIdx = 0;

            // jQuery button click event to add a row
            $('#addBtn3').on('click', function() {

                // Adding a row inside the tbody.
                $('#tbody3').append(`
       
        <tr id="R${++rowIdx}">
          
             <td class="row-index text-center">
     
    
    
                                <select name="itemBig[]" class="form-select form-select-lg mb-3"
                                            aria-label=".form-select-lg example">
                                            @foreach ($all['store'] as $store)
                                            <option value="{{ $store['id'] }}"  >{{ $store['Name'] }}  {{ $store['unit'] }}
                                                    </option>
                                               
                                            @endforeach
    
                                        </select>
    
    
             </td>
             <td class="text-center"><input type = "number" name="countBig[]" ></td>
              <td class="text-center">
                <button class="btn btn-danger remove"
                  type="button">Remove</button>
                </td>
    
              </tr>
    
              `);
            });

            // jQuery button click event to remove a row.
            $('#tbody3').on('click', '.remove', function() {

                // Getting all the rows next to the row
                // containing the clicked button
                var child = $(this).closest('tr').nextAll();

                // Iterating across all the rows 
                // obtained to change the index
                child.each(function() {

                    // Getting <tr> id.
                    var id = $(this).attr('id');

                    // Getting the <p> inside the .row-index class.
                    var idx = $(this).children('.row-index').children('p');

                    // Gets the row number from <tr> id.
                    var dig = parseInt(id.substring(1));

                    // Modifying row index.
                    idx.html(`Row ${dig - 1}`);

                    // Modifying row id.
                    $(this).attr('id', `R${dig - 1}`);
                });

                // Removing the current row.
                $(this).closest('tr').remove();

                // Decreasing total number of rows by 1.
                rowIdx--;
            });
        });
    </script>
    <script>
        for (let index = 4; index <= 20; index++) {
            $(document).ready(function() {

                // Denotes total number of rows
                var rowIdx = 4;

                // jQuery button click event to add a row
                $('#addBtn' + index).on('click', function() {

                    // Adding a row inside the tbody.
                    $('#tbody' + index).append(`
       
        <tr id="R${++rowIdx}">
          
             <td class="row-index text-center">
     
    
    
                                <select name="itemSmall[]" class="form-select form-select-lg mb-3"
                                            aria-label=".form-select-lg example">
                                            @foreach ($all['store'] as $store)
                                            <option value="{{ $store['id'] }}"  >{{ $store['Name'] }}  {{ $store['unit'] }}
                                                    </option>
                                               
                                            @endforeach
    
                                        </select>
    
    
             </td>
             <td class="text-center"><input type = "number" name="countSmall[]" ></td>
              <td class="text-center">
                <button class="btn btn-danger remove"
                  type="button">Remove</button>
                </td>
    
              </tr>
    
              `);
                });

                // jQuery button click event to remove a row.
                $('#tbody' + index).on('click', '.remove', function() {

                    // Getting all the rows next to the row
                    // containing the clicked button
                    var child = $(this).closest('tr').nextAll();

                    // Iterating across all the rows 
                    // obtained to change the index
                    child.each(function() {

                        // Getting <tr> id.
                        var id = $(this).attr('id');

                        // Getting the <p> inside the .row-index class.
                        var idx = $(this).children('.row-index').children('p');

                        // Gets the row number from <tr> id.
                        var dig = parseInt(id.substring(1));

                        // Modifying row index.
                        idx.html(`Row ${dig - 1}`);

                        // Modifying row id.
                        $(this).attr('id', `R${dig - 1}`);
                    });

                    // Removing the current row.
                    $(this).closest('tr').remove();

                    // Decreasing total number of rows by 1.
                    rowIdx--;
                });
            });
        }
    </script>
@endsection
