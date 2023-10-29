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
<lord-icon src={{asset("css/hcndxtmn.json")}} trigger="hover"
colors="primary:#ffffff" style="width:47px;height:47px">
</lord-icon>
</a>
</li>
</div>
</ul>
@endsection
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/index.js"></script>
    <div class="containers" style="direction: rtl;" id="about">
        <h2 id="subtitle">
            تسجيل الدخول


        </h2>
        <hr>


        <div class="row" id="contentlogin">
                <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div  class="form-group">
                            <strong for="exampleInputEmail1">الرقم الوظيفي</strong>
                            <input id="log" type="text" class="form-control" name="id" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                    </div>

                </div>
                <div class="row" id="contentlogin">
                        <div class="form-group">
                            <strong for="exampleInputPassword1">الرقم السري</strong>
                            <input id="log" type="password" class="form-control" name="password" id="exampleInputPassword1">
                    </div></div>

                        <div  class="row">
                            <div id="col-but" class="col-sm">

                            <button type="submit" id="log" class="btn btn-info">دخول</button>
                            </div>
                        </div>
                    </form>

          
    </div>
@endsection
