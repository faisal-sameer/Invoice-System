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
            معلومات التواصل


        </h2>
        <hr>
        <div class="row">
            <div class="col-md-4" style="margin-right: 34%;margin-top: 5%">

                <div class="profile-card text-center">

                    <div class="profile-info">



                        <h2 class="hvr-underline-from-center">فاء<span>Programer / Designer </span></h2>
                        <div>أرقام التواصل : 0598875516 / 0540870969
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
