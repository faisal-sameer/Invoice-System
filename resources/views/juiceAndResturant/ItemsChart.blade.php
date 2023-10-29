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
    <div class="container" style="direction: rtl">
        <div class="row">
            <section style="margin-top: 5%" class="row">
                <div class="panel panel-default">
                    <div class="panel-heading my-2">احصائية المنتجات </div>
                    <div class="col-lg-12">
                        <canvas id="userChart" class="rounded shadow"></canvas>
                    </div>
                </div>
            </section>
            <section style="margin-top: 5%" class="row">
                <table style="text-align: center">
                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">#</th>
                            <th style="text-align: center" scope="col">اسم العنصر</th>
                            <th style="text-align: center" scope="col">قيمة العنصر</th>
                            <th style="text-align: center" scope="col">عدد مرات الطلب </th>
                            <th style="text-align: center" scope="col">المربح</th>


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all as $item)
                            <tr>


                                <td id="{{ $item['id'] }}"></td>
                                <td>{{ $item['Name'] }}</td>
                                <td>{{ $item['OrignelPrice'] }}</td>
                                <td>{{ $item['count'] }}</td>
                                <td>{{ $item['price'] }}</td>


                            </tr>
                        @endforeach





                    </tbody>
                </table>


            </section>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var ctx = document.getElementById('userChart').getContext('2d');
        var xValues = [];
        var yValues = [];
        var colors = [];
        @foreach ($all as $item)
            xValues.push("{{ $item['Name'] }}");
            yValues.push("{{ $item['count'] }}  ");
            colors.push("#" + Math.floor(Math.random() * 16777215).toString(16));
        @endforeach

        console.log(colors);
        $i = 0;
        @foreach ($all as $item)
            document.getElementById("{{ $item['id'] }}").style.backgroundColor = colors[
                $i++];
            console.log("{{ $item['id'] }}" - 1);
        @endforeach

        // var xValues = ["المكسب", "المصروف", "FA"];
        // var yValues = ["10", "100"];

        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'bar',
            // The data for our dataset
            data: {
                labels: xValues,
                datasets: [{
                    label: 'عدد مرات الطلب  ',
                    backgroundColor: colors,
                    data: yValues,
                }, ]
            },
            // Configuration options go here
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'المكسب '

                        }
                    }],

                },
                legend: {
                    labels: {
                        // This more specific font property overrides the global property
                        fontColor: '#122C4B',
                        fontFamily: "'Muli', sans-serif",
                        padding: 25,
                        boxWidth: 25,
                        fontSize: 14,
                    }
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 0,
                        bottom: 10
                    }
                }
            }
        });
    </script>
@endsection
