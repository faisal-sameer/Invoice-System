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
                <a class="nav-link" id="listNav/AdminDashboard"
                    href="{{ route('AdminDashboard') }}">{{ __('لوحة التحكم') }}</a>
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
        <form method="POST" action="{{ route('CreateDocumentBill') }}">
            @csrf
            <h2 id="subtitle">
                انشاء السندات
            </h2>
            <hr class="new5" style="margin-left: 1%">
            <div class="row">
                <div class="col-sm" style="margin-right: 37%">
                    <p id="pBill">اختر السند</p>

                    <select name="type" class="form-select form-select-lg mb-3" id="inpRSelec" onchange="showDiv(this)"
                        aria-label=".form-select-lg example">
                        <option value="0">#</option>
                        <option value="1">سند قبض</option>
                        <option value="2">سند صرف</option>
                        <option value="3">سند أمر</option>


                    </select>
                </div>
            </div>
            <div id="form1" style="display: none">
                <div class="row" style="margin-right: 10%;margin-top: 2%">
                    <div class="col-sm">
                        <p id="pBill">التاريخ</p>
                        <input type="date" id="inputBill"style="width: 80%" name="dateUp">
                    </div>

                    <div class="col-sm" id="hidden_div" style="display:none;">
                        <div id="hidden_div2" style="display:none;">
                            <p id="pBill">استلمنا من السيد / السادة</p>
                        </div>
                        <div id="hidden_div2P" style="display:none;">

                            <p id="pBill">اصرفوا الى السيد / السادة</p>
                        </div>


                        <input type="text" style="width: 80%" name="nameUp" id="inpRSelec">

                    </div>
                    <div class="col-sm">
                        <p id="pBill">مبلغ وقدره</p>
                        <input type="number" style="width: 80%" name="priceUp" id="inpRSelec">
                    </div>
                </div>
                <div class="row" style="margin-right: 10%;margin-top: 2%">

                    <div class="col-sm">
                        <p id="pBill">وذلك من </p>

                        <input type="text" style="width: 80%" name="for" id="inpRSelec">
                    </div>
                    <div class="col-sm">
                        <p id="pBill">نوع الصرف</p>
                        <label class="form-check-strong" for="inlineRadio1">نقدا</label>
                        <input class="form-check-input" type="radio" onclick="cassh();" name="paymentType"
                            value="1" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                        <label class="form-check-strong" for="inlineRadio1">شيك </label>
                        <input class="form-check-input" onclick="cassh();" type="radio" name="paymentType"
                            value="2" style="margin-left: 90% ; width: 1.5em; height: 1.5em;">
                    </div>
                    <div class="col-sm" id="input-2" style="display:none;margin-top: 2%">
                        <input type="text" style="width: 80%" name="checkNo" id="inpRSelec" placeholder="رقم الشيك">

                    </div>
                </div>
                <div class="row" style="margin-right: 10%;margin-top: 2%">
                    <div class="col-sm">
                        <p id="pBill">البنك</p>

                        <input type="text" style="width: 80%" name="bankUp" id="inpRSelec">
                    </div>
                    <div class="col-sm">
                        <p id="pBill">التاريخ</p>
                        <input type="date" style="width: 80%" id="inputBill" name="dateCheck">
                    </div>
                </div>
                <div class="row" style="margin-top: 5%">
                    <div class="col-sm" style="margin-right: 33%">
                        <button style="width: 50%" type="submit" class="btn btn-info">انشاء</button>
                    </div>
                </div>
            </div>
            <div id="form2" style="display: none;">
                <div class="row" style="margin-right: 10%;margin-top: 2%">
                    <div class="col-sm">
                        <p id="pBill">تاريخ الاستحقاق</p>
                        <input type="date"style="width: 80%" id="inputBill" name="date">
                    </div>

                    <div class="col-sm">



                        <p id="pBill">الاسم</p>


                        <input type="text"style="width: 80%" name="name" id="inpRSelec">

                    </div>
                    <div class="col-sm">
                        <p id="pBill">رقم الهوية </p>
                        <input type="text" style="width: 80%" name="userID" id="inpRSelec">
                    </div>
                </div>
                <div class="row" style="margin-right: 10%;margin-top: 2%">
                    <div class="col-sm">
                        <p id="pBill">المدينة </p>
                        <input type="text" name="city" style="width: 80%" id="inpRSelec">
                    </div>
                    <div class="col-sm">
                        <p id="pBill">مبلغ وقدره</p>
                        <input type="number" style="width: 80%" name="price" id="inpRSelec">
                    </div>
                    <div class="col-sm">
                        <p id="pBill">رقم الفاتورة</p>

                        <input type="number" style="width: 80%" name="BillNo" id="inpRSelec">
                    </div>



                </div>
                <div class="row" style="margin-right: 10%;margin-top: 2%">
                    <div class="col-sm">
                        <p id="pBill">اسم المؤسسة</p>
                        <input type="text" name="CT" style="width: 80%" id="inpRSelec">
                    </div>
                    <div class="col-sm">
                        <p id="pBill">رقم المؤسسة</p>
                        <input type="text" name="CTno" style="width: 80%" id="inpRSelec">
                    </div>
                </div>
                <div class="row" style="margin-top: 5%">
                    <div class="col-sm" style="margin-right: 33%">
                        <button style="width: 50%" type="submit" class="btn btn-info">انشاء</button>
                    </div>
                </div>
            </div>
        </form>

        <hr class="new5" style="margin-left: 1%">
        <div class="row">
            <div class="col-sm">
                <h4 style="text-align: center">
                    السندات السابقة
                </h4>
            </div>
        </div>

        <div class="row">
            <section style="margin-top: 1%" class="row">
                <table style="text-align: center;margin-right: 1%;width: 99%">
                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">التاريخ</th>
                            <th style="text-align: center" scope="col"> القبض / الصرف / لأمر</th>
                            <th style="text-align: center" scope="col">مبلغ وقدره</th>
                            <th style="text-align: center" scope="col">#</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vouchers as $voucher)
                            <tr>
                                <td>
                                    {{ date('d-m-Y ', strtotime($voucher->created_at)) }}</td>
                                <td>
                                    @if ($voucher->type_voucher == 1)
                                        استلمنا من
                                    @elseif($voucher->type_voucher == 2)
                                        اصرفوا الى
                                    @else
                                        لأمر الى
                                    @endif
                                    السيد : {{ $voucher->SirName }}

                                </td>
                                <td>{{ $voucher->price }} ريال</td>


                                <td>
                                    <form method="POST" action="{{ route('ReceiptPaymentPDF') }}">
                                        @csrf
                                        <input type="hidden" hidden name="id" value="{{ $voucher->id }}">
                                        <button type="submit" class="btn btn-info">اطلاع</button>
                                    </form>
                                </td>

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
        function showDiv(select) {
            if (select.value == 1) {
                document.getElementById('form1').style.display = "block";
                document.getElementById('hidden_div').style.display = "block";
                document.getElementById('hidden_div2').style.display = "block";
                document.getElementById('hidden_div2P').style.display = "none";


            } else if (select.value == 2) {
                document.getElementById('form1').style.display = "block";
                document.getElementById('hidden_div').style.display = "block";
                document.getElementById('hidden_div2').style.display = "none";
                document.getElementById('hidden_div2P').style.display = "block";
            } else if (select.value == 3) {
                document.getElementById('form1').style.display = "none";
                document.getElementById('form2').style.display = "block";
                document.getElementById('hidden_div').style.display = "none";
                document.getElementById('hidden_div2').style.display = "none";
                document.getElementById('hidden_div2P').style.display = "none";

            } else {
                document.getElementById('form1').style.display = "none";
                document.getElementById('form2').style.display = "none";
                document.getElementById('hidden_div').style.display = "none";
                document.getElementById('hidden_div2').style.display = "none";
                document.getElementById('hidden_div2P').style.display = "none";

            }
        }
    </script>
    <script>
        function cassh() {
            $('input[name="paymentType"]').change(function() {
                if ($(this).val() === '1') {
                    $('#input-1').show();
                    $('#input-2').hide();
                } else if ($(this).val() === '2') {
                    $('#input-1').hide();
                    $('#input-2').show();
                }
            });
        }
    </script>
@endsection
