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
            انشاء فاتورة
        </h2>
        <hr class="new5" style="margin-left: 1%">
        <div class="row">
            <div class="col-sm">
                <h3>بيانات العميل</h3>
            </div>
        </div>
     <form method="POST" action="{{ route('CasherBoardTransferCreate') }}">
            @csrf
            <div class="row" style="margin-top: 2%;margin-right: 10%">
                <div class="col-sm">
                    <p id="pBill">الاسم</p>
                    <input type="text" id="inputBill" name="name">
                </div>
                <div class="col-sm">
                    <p id="pBill">رقم الهاتف</p>
                    <input type="number" id="inputBill" name="phone">
                </div>
                <div class="col-sm">
                    <p id="pBill">رقم السجل الضريبي</p>
                    <input type="number" id="inputBill" name="ct">
                </div>
            </div>
            <div class="row" style="margin-top: 2%;margin-right: 10%">
                <div class="col-sm">
                    <div class="search-container">
                        <p id="pBill">المدينة</p>

                        <input type="text"style="margin-top: 2%" class="FsearchCity" name="city1[]">
                        <ul></ul>
                    </div>
                </div>
                <div class="col-sm">
                    <p id="pBill">الحي</p>
                    <input type="text" id="inputBill" name="address">
                </div>
                <div class="col-sm">
                    <p id="pBill"> الطريق</p>
                    <input type="text" id="inputBill" name="street">
                </div>
                <div class="col-sm">
                    <p id="pBill">الرمز البريدي</p>
                    <input type="number" id="inputBill" name="zipcode">
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-sm">
                    <h3>تفاصيل الطلب</h3>
                </div>
                <div class="col-sm">
                    <button id="add-row" type="button" class="btn btn-success">اضافة سطر</button>
                </div>
            </div>
            <div class="row">
                <section style="margin-top: 1%;" class="row">

                    <table id="myTable" style="text-align: center;margin-left: 20%;width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">الرمز</th>
                                <th scope="col">المنتج </th>
                                <th scope="col">من مدينة </th>
                                <th scope="col">الى مدينة </th>
                                <th scope="col">السعر </th>
                                <th scope="col">الكمية - طن </th>
                                <th scope="col">الاجمالي</th>
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" style="width: 60px" name="code[]"></td>
                                <td><input type="text" class="search" style="margin-top: 2%" name="item[]">
                                    <ul></ul>
                                </td>
                                <td><input type="text"style="margin-top: 2%" class="searchCity" name="city2[]">
                                    <ul></ul>
                                </td>
                                <td><input type="text"style="margin-top: 2%" class="TosearchCity" name="Tocity[]">
                                    <ul></ul>
                                </td>

                                <td><input type="text" name="price[]" style="width: 60px" class="price-input"></td>
                                <td><input type="text" name="qty[]" class="qty-input"></td>
                                <td><input type="text" name="total[]"style="width: 100px"></td>
                                <td><button type="button" class="delete-row btn btn-danger">حذف</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </section>
            </div>
            <div class="row">
                <div class="col-md-4" style="margin-right: 34%;margin-bottom: 2%">

                    <div class="profile-card text-center">

                        <div class="profile-info">


                            <div class="col-sm">
                                <h4>المجموع : <b id="total-sum">0</b> ريال</h4>
                            </div>
                            <div class="col-sm">
                                <h4>ضريبة : <b id="total-tax">0</b> ريال</h4>
                            </div>
                            <div class="col-sm">
                                <h4> الاجمالي : <b id="total-sum-tax">0</b> ريال </h4>
                            </div>
                        </div>
                        <input type="text" name="totalFinal" hidden id="total-sumI">
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 3% ">
                <button type="submit" style="width: 50%; margin: auto" class="btn btn-info">انشاء</button>

            </div>
            </form>


    </div>
@endsection

@section('script')
    {{-- test --}}
    <script>
        var Fcity = {!! json_encode($all['city']) !!};
        var Fcityfield = document.querySelector('.FsearchCity');
        Fcityfield.addEventListener('input', function() {
            var filter = this.value.toLowerCase();
            var matches = Fcity.filter(function(item) {
                return item.toLowerCase().includes(filter);
            });
            var html = '';
            matches.forEach(function(match) {
                html += '<li>' + match + '</li>';
            });
            var results = this.nextElementSibling;
            results.innerHTML = html;
            var lis = results.getElementsByTagName('li');
            for (var j = 0; j < lis.length; j++) {
                lis[j].addEventListener('click', function() {
                    Fcityfield.value = this.textContent;
                    results.innerHTML = '';
                });
            }
        });
        var city = {!! json_encode($all['city']) !!};
        var cityfield = document.querySelector('table tbody tr:first-child .searchCity');
        cityfield.addEventListener('input', function() {
            var filter = this.value.toLowerCase();
            var matches = city.filter(function(item) {
                return item.toLowerCase().includes(filter);
            });
            var html = '';
            matches.forEach(function(match) {
                html += '<li>' + match + '</li>';
            });
            var results = this.nextElementSibling;
            results.innerHTML = html;
            var lis = results.getElementsByTagName('li');
            for (var j = 0; j < lis.length; j++) {
                lis[j].addEventListener('click', function() {
                    cityfield.value = this.textContent;
                    results.innerHTML = '';
                });
            }
        });
        var tocity = {!! json_encode($all['city']) !!};
        var tocityfield = document.querySelector('table tbody tr:first-child .TosearchCity');
        tocityfield.addEventListener('input', function() {
            var filter = this.value.toLowerCase();
            var matches = tocity.filter(function(item) {
                return item.toLowerCase().includes(filter);
            });
            var html = '';
            matches.forEach(function(match) {
                html += '<li>' + match + '</li>';
            });
            var results = this.nextElementSibling;
            results.innerHTML = html;
            var lis = results.getElementsByTagName('li');
            for (var j = 0; j < lis.length; j++) {
                lis[j].addEventListener('click', function() {
                    tocityfield.value = this.textContent;
                    results.innerHTML = '';
                });
            }
        });
        var items = {!! json_encode($all['TransItems']) !!};
        var firstSearch = document.querySelector('table tbody tr:first-child .search');
        firstSearch.addEventListener('input', function() {
            var filter = this.value.toLowerCase();
            var matches = items.filter(function(item) {
                return item.toLowerCase().includes(filter);
            });
            var html = '';
            matches.forEach(function(match) {
                html += '<li>' + match + '</li>';
            });
            var results = this.nextElementSibling;
            results.innerHTML = html;
            var lis = results.getElementsByTagName('li');
            for (var j = 0; j < lis.length; j++) {
                lis[j].addEventListener('click', function() {
                    firstSearch.value = this.textContent;
                    results.innerHTML = '';
                });
            }
        });

        function addRow() {
            var tbody = document.querySelector('table tbody');
            var tr = document.createElement('tr');
            tr.innerHTML = `
  <td><input type="text" name="code[]" style="width: 60px"></td>
  <td><input type="text" class="search" style="margin-top: 2%" name="item[]"><ul></ul></td>
  <td><input type="text" class="searchCity" style="margin-top: 2%" name="city2[]"><ul></ul> </td>
  <td><input type="text"style="margin-top: 2%" class="TosearchCity" name="Tocity[]"> <ul></ul> </td>
  <td><input type="text" name="price[]"  style="width: 60px" class="price-input" ></td>
  <td ><input type="text" name="qty[]" class="qty-input" ></td>
  <td ><input type="text"  name="total[]"  style="width: 100px" ></td> 
  <td><button  class="delete-row btn btn-danger">حذف</button></td>
`;
            tbody.appendChild(tr);

            // Add event listeners for the new row
            var search = tr.querySelector('.search');
            var results = tr.querySelector('ul');
            search.addEventListener('input', function() {
                var filter = this.value.toLowerCase();
                var matches = items.filter(function(item) {
                    return item.toLowerCase().includes(filter);
                });
                var html = '';
                matches.forEach(function(match) {
                    html += '<li>' + match + '</li>';
                });
                results.innerHTML = html;
                var lis = results.getElementsByTagName('li');
                for (var j = 0; j < lis.length; j++) {
                    lis[j].addEventListener('click', function() {
                        search.value = this.textContent;
                        results.innerHTML = '';
                    });
                }
            });
            var searchCity = tr.querySelector('.searchCity');
            var resultsCity = searchCity.nextElementSibling;
            searchCity.addEventListener('input', function() {
                var filter = this.value.toLowerCase();
                var matches = city.filter(function(item) {
                    return item.toLowerCase().includes(filter);
                });
                var html = '';
                matches.forEach(function(match) {
                    html += '<li>' + match + '</li>';
                });
                resultsCity.innerHTML = html;
                var lis = resultsCity.getElementsByTagName('li');
                for (var j = 0; j < lis.length; j++) {
                    lis[j].addEventListener('click', function() {
                        searchCity.value = this.textContent;
                        resultsCity.innerHTML = '';
                    });
                }
            });
            var TosearchCity = tr.querySelector('.TosearchCity');
            var ToresultsCity = TosearchCity.nextElementSibling;
            TosearchCity.addEventListener('input', function() {
                var filter = this.value.toLowerCase();
                var matches = city.filter(function(item) {
                    return item.toLowerCase().includes(filter);
                });
                var html = '';
                matches.forEach(function(match) {
                    html += '<li>' + match + '</li>';
                });
                ToresultsCity.innerHTML = html;
                var lis = ToresultsCity.getElementsByTagName('li');
                for (var j = 0; j < lis.length; j++) {
                    lis[j].addEventListener('click', function() {
                        TosearchCity.value = this.textContent;
                        ToresultsCity.innerHTML = '';
                    });
                }
            });

            var deleteButton = tr.querySelector('.delete-row');
            deleteButton.addEventListener('click', function() {
                tr.remove();
                var totalElements = document.querySelectorAll('table tbody tr td input[name="total[]"]');
                var totalSum = 0;
                for (var i = 0; i < totalElements.length; i++) {
                    if (totalElements[i].value !== "") {
                        totalSum += parseFloat(totalElements[i].value);
                    }
                }
                var totalElementTax = document.getElementById("total-tax");
                var totalElementSumTax = document.getElementById("total-sum-tax");
                var totalElementInput = document.getElementById("total-sumI");
                tax = totalSum * 0.15;
                totalElementInput.value = totalSum.toFixed(2);

                totalElementTax.innerHTML = tax.toFixed(2);
                sumwithTax = totalSum + tax;
                totalElementSumTax.innerHTML = sumwithTax.toFixed(2);
                document.querySelector('#total-sum').textContent = totalSum.toFixed(2);
            });
        }

        document.querySelector('#add-row').addEventListener('click', function() {
            addRow();

        });
        var tbody = document.querySelector('table tbody');
        tbody.addEventListener('input', function(event) {
            // check if input is in price or qty column
            if (event.target.name === 'price[]' || event.target.name === 'qty[]') {
                // get row and calculate total
                var row = event.target.closest('tr');
                var price = parseFloat(row.querySelector('[name="price[]"]').value);
                var qty = parseFloat(row.querySelector('[name="qty[]"]').value);
                var total = price * qty;
                // set value of total input
                row.querySelector('[name="total[]"]').value = total.toFixed(2);
                var totalInputs = document.getElementsByName("total[]");
                var sum = 0;
                for (var i = 0; i < totalInputs.length; i++) {
                    var value = parseFloat(totalInputs[i].value);
                    if (!isNaN(value)) {
                        sum += value;
                    }
                }
                var totalElement = document.getElementById("total-sum");
                var totalElementInput = document.getElementById("total-sumI");
                var totalElementTax = document.getElementById("total-tax");
                var totalElementSumTax = document.getElementById("total-sum-tax");
                if (totalElement) {

                    totalElement.innerHTML = sum.toFixed(2);
                    totalElementInput.value = sum.toFixed(2);
                    tax = sum * 0.15;
                    totalElementTax.innerHTML = tax.toFixed(2);
                    sumwithTax = sum + tax;
                    totalElementSumTax.innerHTML = sumwithTax.toFixed(2);
                }
            }
        });
    </script>

    {{-- end test --}}
@endsection
