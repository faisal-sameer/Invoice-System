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
                <a class="nav-link" id="listNav/SendNewMessage"
                    href="{{ route('SendNewMessage') }}">{{ __('رسالة جديدة') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listNav/PreviousMessages"
                    href="{{ route('PreviousMessages') }}">{{ __('الرسائل السابقة') }}</a>
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
            الرسائل المرسلة
        </h2>
        <hr class="new5" style="margin-left: 1%">

        <div class="notification-dashboard">
            @foreach ($notifications as $notification)
                <div class="notification-container">
                    <div class="notification">
                        <div class="notification-header">
                            <h3>{{ $notification->type->name }}</h3>

                            <span class="notification-time">{{ $notification->created_at }}</span>
                        </div>
                        @if ($notification->Status == 3)
                            <p>الرد : {{ $notification->notes }}</p>
                            <p> إلى : {{ $notification->Tostaff->User->name }}</p>

                            <p> الموضوع : {{ $notification->resend['notes'] }}</p>
                        @else
                            <p>{{ $notification->notes }}</p>
                            <p> إلى : {{ $notification->Tostaff->User->name }}</p>

                            <p> الرد :

                                {{ $notification->resend_id == null ? '' : $notification->resend['notes'] }}
                            </p>
                        @endif
                    </div>

                </div>
            @endforeach

        </div>




    </div>
@endsection
