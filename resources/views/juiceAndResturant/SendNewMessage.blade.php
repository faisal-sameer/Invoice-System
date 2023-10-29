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
                <a class="nav-link" id="listNav/Notification" href="{{ route('Notification') }}">{{ __('التنبيهات') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listNav/PreviousMessages"
                    href="{{ route('PreviousMessages') }}">{{ __('الرسائل السابقة') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listNav/SentMail" href="{{ route('SentMail') }}">{{ __('الرسائل المرسلة') }}</a>
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
            رسالة جديدة
        </h2>
        <hr class="new5" style="margin-left: 1%">
        <form method="POST" action="{{ route('SendMessage') }}">
            @csrf
            <div class="send-message">
                <div class="row" style="margin-right: 15%">
                    <div class="col-sm">
                        <p id="pBill">إلى</p>

                        <select name="staff" class="form-select form-select-lg mb-3" id="inpRSelec"
                            aria-label=".form-select-lg example">

                            <option value="0" selected>عام للجميع </option>
                            @foreach ($all['staffs'] as $staffs)
                                <option value="{{ $staffs->id }}">{{ $staffs->User->name }}</option>
                            @endforeach


                        </select>
                    </div>
                    <div class="col-sm">
                        <p id="pBill">الموضوع</p>

                        <select name="type" class="form-select form-select-lg mb-3" id="inpRSelec"
                            aria-label=".form-select-lg example">
                            @foreach ($all['typeNotification'] as $typeNotification)
                                <option value="{{ $typeNotification->id }}">{{ $typeNotification->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="paper" style="width: 70%">
                            <div class="paper-content">
                                <textarea name="notes" style="white-space: nowrap;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5%">
                    <div class="col-sm" style="margin-right: 33%">
                        <button style="width: 50%" type="submit" class="btn btn-info">ارسال</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
