@extends('layouts.app')
@section('navbar')
<ul id="ulPhone" class="navbar-nav m-sm-3">
    <li class="nav-item">
        <img src="/image/list.png" onclick="OpenMenu()" class="shake-lr" id="menuImage" alt="">
       
</li>
<div id="mySidenav" class="sidenav">
<span href="" class="closebtn" onclick="closeMenu()">&times;</span>
<li class="nav-item">
    <a class="nav-link" id="listNav/Home"
    href="{{ route('Home') }}"><lord-icon
    src="https://cdn.lordicon.com/hjbsbdhw.json"
    trigger="hover"
    style="width:47px;height:47px">
</lord-icon></a>
    </li>
<li class="nav-item">
<a class="nav-link" id="listNav/AddToMenu"
href="{{ route('BillDashboard') }}">{{ __('الفواتير') }}</a>
</li>
<li class="nav-item">
<a class="nav-link" href="{{ route('Call') }}">
<lord-icon src={{asset("css/hcndxtmn.json")}} trigger="hover"
colors="primary:#ffffff" style="width:47px;height:47px">
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

        <div class="row">
            @foreach ($all['exp'] as $item)
                <div class="col-lg-6 mb-6">
                    <canvas id="myChart{{ $item['id'] }}" style="width:100%;max-width:600px"></canvas>
                    <br>
                    @if ($item['all'] != null)
                        الكاش : {{ $item['cash'] }} =>
                        {{ round(($item['cash'] / $item['all']) * 100, 2) }} %
                        شبكة : {{ $item['online'] }} =>
                        {{ round(($item['online'] / $item['all']) * 100, 2) }} %
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    @foreach ($all['exp'] as $item)
        <script>
            var id = "{{ $item['id'] }}";
            var branch = "{{ $item['branch'] }}";
            var xValues = ["المكسب", "المصروف"];
            var yValues = ["{{ $item['all'] }}", "{{ $item['Exp'] }}"];
            var barColors = [
                "#00aba9",
                "#b91d47",
                "#2b5797",
                "#e8c3b9",
                "#1e7145"
            ];

            new Chart("myChart" +
                id, {
                    type: "pie",
                    data: {
                        labels: xValues,
                        datasets: [{
                            backgroundColor: barColors,
                            data: yValues
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: branch
                        }
                    }
                });
        </script>
    @endforeach
@endsection
