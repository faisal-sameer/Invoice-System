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
            مصروفات المتجر
        </h2>
        <hr class="new5" style="margin-left: 1%">
        <div class="notification-dashboard">
            <form method="POST" action="{{ route('NewOtherExpenses') }}">
                @csrf
                <div class="notification-container">
                    <div class="row" style="margin-right: 20%">
                        <div class="col-sm">
                            <p id="pBill">عنوان</p>
                            <input type="text" name="title" id="inputBill">
                        </div>
                        <div class="col-sm">
                            <p id="pBill">المبلغ</p>
                            <input type="number" name="price" id="inputBill">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5%">
                        <div class="col-sm" style="margin-right: 33%">
                            <button style="width: 50%" type="submit" class="btn btn-info">حفظ</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>


        <hr class="new5" style="margin-left: 1%">
        <div class="row">
            <div class="col-sm">
                <h4 style="text-align: center">
                    المصاريف السابقة
                </h4>
            </div>
        </div>

        <div class="row">
            <section style="margin-top: 1%" class="row">
                <table style="text-align: center;margin-right: 1%;width: 99%">
                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">الاسم</th>
                            <th style="text-align: center" scope="col">المبلغ</th>
                            <th style="text-align: center" scope="col">التاريخ</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all['otherExpense'] as $otherExpense)
                            <tr>
                                <td>{{ $otherExpense->title }}</td>
                                <td>{{ $otherExpense->price }} ريال</td>
                                <td> {{ date('d-m-Y ', strtotime($otherExpense->created_at)) }}</td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
