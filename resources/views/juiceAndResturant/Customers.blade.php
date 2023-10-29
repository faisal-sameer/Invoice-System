@extends('layouts.app')


@section('navbar')
    <ul id="ulPhone" class="navbar-nav m-sm-3">
        <li class="nav-item">
            <img src="/image/list.png" onclick="OpenMenu()" class="shake-lr" id="menuImage" alt="">

        </li>
        <div id="mySidenav" class="sidenav">
            <span href="" class="closebtn" onclick="closeMenu()">&times;</span>
            <li class="nav-item">
                <a class="nav-link" id="listNav/BillDashboard" href="{{ route('Home') }}">
                    <lord-icon src="https://cdn.lordicon.com/hjbsbdhw.json" trigger="hover" style="width:47px;height:47px">
                    </lord-icon>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listNav/Customers" href="{{ route('Customers') }}">{{ __('العملاء') }}</a>
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
            العملاء
        </h2>
        <hr>
        <form method="POST" id="CustomerForm">
            @csrf
            <div class="row">
                <div class="col-sm" id="Custody2">
                    <input type="number" id="Custody1" name="Phone" placeholder="بحث برقم الجوال">
                </div>
                <div class="col-md">
                    <button type="submit" id="search-customer" class="btn btn-info">بحث</button>
                </div>
            </div>
        </form>

        <div class="row" style="margin-top: 5%">
            <section style="margin-top: 5% ;margin-right: 5%" class="row">
                <input onclick="" class="slide" type="image" style="width: 5%;margin-right: 80% ; border: none"
                    name="category" alt="حفظ" title="حفظ" src="/image/download.gif">

                <table style="text-align: center;width: 90%">
                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">اسم العميل</th>
                            <th style="text-align: center" scope="col">جوال العميل</th>
                            <th style="text-align: center" scope="col">عدد مرات الزيارة</th>
                            <th style="text-align: center" scope="col">اخر زيارة</th>



                        </tr>
                    </thead>
                    <tbody>
                        @if ($all[0] != null)
                            @foreach ($all[0]['Info'] as $info)
                                <tr>
                                    <td>
                                        <input id="inputAddToMenu" name="name[]" type="text"
                                            value="{{ $info['name'] }}">
                                    </td>
                                    <td>{{ $info['phone'] }}</td>
                                    <td>{{ $info['count'] }}</td>
                                    <td>{{ $info['created_at'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function CustomerForm() {
            if (window.location.pathname == '/CustomersOwner') {
                document.CustomerForm.action = "/CustomersOwner";

            } else {
                document.CustomerForm.action = "/Customers";

            }
        }
    </script>
@endsection
