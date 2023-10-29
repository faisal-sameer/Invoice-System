@extends('layouts.app')

@section('navbar')
    <ul id="ulPhone" class="navbar-nav m-sm-3">
        <li class="nav-item">
            <img src="/image/list.png" onclick="OpenMenu()" class="shake-lr" id="menuImage" alt="">

        </li>
        <div id="mySidenav" class="sidenav">
            <span href="" class="closebtn" onclick="closeMenu()">&times;</span>
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
        <div class="row" style="margin-top: 5%">
            <main class="grid2">
                @if (Auth::user()->permission_id == 2 || Auth::user()->permission_id == 4)
                    <article onclick="AdminDashboard()">
                        <img src="/image/control-panel.png" class="avatar" alt="Sample photo">
                        <div class="text2">
                            <h2>لوحة التحكم</h2>
                        </div>
                    </article>
                @endif
                <article onclick="BillDashboard()">
                    <img src="/image/bill.png" class="avatar" alt="Sample photo">
                    <div class="text2">
                        <h2>الفواتير</h2>
                    </div>
                </article>
                <article onclick="expenses()">
                    <img src="/image/accounting.png" class="avatar" alt="Sample photo">
                    <div class="text2">
                        <h2>المصروفات</h2>
                    </div>
                </article>
                @if (Auth::user()->permission_id == 2)
                    <article onclick="AddToMenu()">
                        <img src="/image/documents.png" class="avatar" alt="Sample photo">
                        <div class="text2">
                            <h2>القائمة والعروض</h2>
                        </div>
                    </article>
                @endif
                @if (Auth::user()->permission_id == 2 || Auth::user()->permission_id == 4)
                    <article onclick="Stored()">
                        <img src="/image/stocks.png" class="avatar" alt="Sample photo">
                        <div class="text2">
                            <h2>المخزون</h2>
                        </div>
                    </article>
                @endif
                @if (Auth::user()->permission_id == 2 || Auth::user()->permission_id == 4)
                    <article onclick="HR()">
                        <img src="/image/human-resources.png" class="avatar" alt="Sample photo">
                        <div class="text2">
                            <h2>الموارد البشرية</h2>
                        </div>
                    </article>
                @else
                    <article onclick="HR()">
                        <img src="/image/human-resources.png" class="avatar" alt="Sample photo">
                        <div class="text2">
                            <h2>لوحة تحكم</h2>
                        </div>
                    </article>
                @endif
                @if ($all['notifications'] > 0)
                    <article style="height: 83%;" onclick="notification()">

                        <div style="padding-right: 4%;padding-top: 4%">
                            <span class="dot">
                                <p style="text-align: center ; ">{{ $all['notifications'] }}
                                </p>
                            </span>
                        </div>


                        <img src="/image/bell.png" class="avatar" alt="Sample photo">
                        <div class="text2">
                            <h2>الاشعارات</h2>
                        </div>
                    </article>
                @else
                    <article onclick="notification()">
                        <img src="/image/bell.png" class="avatar" alt="Sample photo">
                        <div class="text2">
                            <h2>الاشعارات</h2>
                        </div>
                    </article>
                @endif
                <article onclick="Customers()">
                    <img src="/image/client.png" class="avatar" alt="Sample photo">
                    <div class="text2">
                        <h2>العملاء</h2>
                    </div>
                </article>
            </main>
        </div>



    </div>
@endsection
@section('script')
    <script>
        function AdminDashboard() {
            window.location.href = '{{ route('AdminDashboard') }}';
        }

        function BillDashboard() {
            if ("{{ Auth::user()->permission_id }}" == 2 || "{{ Auth::user()->permission_id }}" == 4) {
                window.location.href = '{{ route('BillDashboard') }}';
            } else {
                window.location.href = '{{ route('CasherBoard') }}';
            }
        }

        function expenses() {
            if ("{{ Auth::user()->permission_id }}" == 2 || "{{ Auth::user()->permission_id }}" == 4) {
                window.location.href = '{{ route('expenses') }}';
            } else {
                window.location.href = '{{ route('OtherExpenses') }}';

            }
        }

        function AddToMenu() {
            window.location.href = '{{ route('AddToMenu') }}';
        }

        function HR() {
            if ("{{ Auth::user()->permission_id }}" == 2 || "{{ Auth::user()->permission_id }}" == 4) {
                window.location.href = '{{ route('DashboardHr') }}';
            } else {
                window.location.href = '{{ route('DashboardHrForEmp') }}';
            }

        }

        function Stored() {
            window.location.href = '{{ route('Stored') }}';
        }

        function notification() {
            window.location.href = '{{ route('Notification') }}';

        }

        function Customers() {
            if ("{{ Auth::user()->permission_id }}" == 2) {
                window.location.href = '{{ route('CustomersOwner') }}';
            } else {
                window.location.href = '{{ route('Customers') }}';
            }
        }
    </script>
@endsection
