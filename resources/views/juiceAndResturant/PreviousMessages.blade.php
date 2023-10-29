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
                <a class="nav-link" id="listNav/SentMail" href="{{ route('SentMail') }}">{{ __('الرسائل المرسلة') }}</a>
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
            الرسائل السابقة
        </h2>
        <hr class="new5" style="margin-left: 1%">

        <div class="notification-dashboard">
            <h1>الرسائل الجديده</h1>
            <div class="notification-container">
                @foreach ($all['notifications'] as $notifications)
                    @if ($notifications->type_id == 16)
                        <div class="notification">
                            <div class="notification-header">
                                <h3> استلام المخزون</h3>
                                <span class="notification-time">قبل ساعه</span>
                            </div>
                            <p>تم استلام المخزون من الموظف محمد</p>
                        </div>
                    @else
                        @if ($notifications->resend['notes'] == null && $notifications->type_id != 15)
                            <div class="notification" onclick="Nrespon({{ $notifications->id }})">
                                <div class="notification-header">
                                    <h3>{{ $notifications->type->name }} </h3>
                                    <span class="notification-time">{{ $notifications->created_at }} </span>
                                </div>
                                <p>{{ $notifications->type->name }} من {{ $notifications->staff->User->name }}</p>
                            </div>
                        @else
                            <div class="notification" onclick="respon({{ $notifications->id }})">
                                <div class="notification-header">
                                    <h3>{{ $notifications->type->name }} </h3>
                                    <span class="notification-time">{{ $notifications->created_at }} </span>
                                </div>
                                <p>{{ $notifications->type->name }} من {{ $notifications->staff->User->name }}</p>
                            </div>
                        @endif

                        <div style="direction: ltr"></div>
                        {{-- Full Meassage --}}
                        <div id="respon{{ $notifications->id }}" class="modal">

                            <!-- Modal content -->
                            <div class="modal-content" style="width: 50%;">
                                <div class="modal-header">
                                    <h2 style="text-align: center">من {{ $notifications->staff->User->name }}</h2>
                                    <h2 class="exit" onclick="closeRespon({{ $notifications->id }})">&times;
                                    </h2>


                                </div>
                                <div class="model-body">
                                    <div class="row" style="margin-right: 2%">
                                        <div class="send-message" style="width: 90%">
                                            <div class="row">
                                                <h3>الرسالة</h3>
                                            </div>
                                            @if ($notifications->Status == 3)
                                                <div class="row">
                                                    <p>الرد : {{ $notifications->notes }} </p>
                                                    <p> الموضوع : {{ $notifications->resend['notes'] }} </p>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <p>الموضوع :{{ $notifications->notes }} </p>
                                                    <p> الرد : {{ $notifications->resend['notes'] }} </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>


                                </div>

                            </div>

                        </div>

                        {{-- Need to Respone --}}
                        <div id="Nrespon{{ $notifications->id }}" class="modal">

                            <!-- Modal content -->
                            <form method="POST" action="{{ route('RespoenMes') }}">
                                @csrf
                                <div class="modal-content" style="width: 50%;">
                                    <div class="modal-header">

                                        <h2 class="exit" onclick="closeNRespon({{ $notifications->id }})">&times;
                                        </h2>

                                        <button class="btn btn-light" type="submit">ارسال</button>

                                    </div>
                                    <div class="model-body">
                                        <div class="row" style="margin-right: 2%">
                                            <div class="send-message" style="width: 90%">
                                                <div class="row">
                                                    <h3>الرسالة</h3>
                                                </div>
                                                <div class="row">
                                                    <p>{{ $notifications->notes }} </p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="new5" style="margin-left: 1%">
                                        <div class="row" style="margin-right: 3%">
                                            <h3>
                                                كتابة الرد
                                            </h3>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="paper" style="width: 70%">
                                                    <input type="text" name="id" hidden readonly
                                                        value="{{ $notifications->id }} ">
                                                    <div class="paper-content">
                                                        <textarea name="notes" style="white-space: nowrap;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </form>
                        </div>
                    @endif
                @endforeach



            </div>
        </div>






    </div>
@endsection
@section('script')
    <script>
        function respon(id) {
            document.getElementById('respon' + id).style.display = 'block'
        }

        function closeRespon(id) {
            document.getElementById('respon' + id).style.display = 'none'

        }

        function Nrespon(id) {
            document.getElementById('Nrespon' + id).style.display = 'block'
        }

        function closeNRespon(id) {
            document.getElementById('Nrespon' + id).style.display = 'none'

        }
    </script>
@endsection
