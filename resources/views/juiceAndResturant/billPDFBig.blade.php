<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>فاتورة </title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/app.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/templatemo-art-factory.css">
    <link rel="stylesheet" type="text/css" href="css/owl-carousel.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        html,
        body {
            direction: rtl;

        }





        #invoice-POS {

            background: #FFF;
        }

        ::selection {
            background: #f31544;
            color: #FFF;
        }

        ::moz-selection {
            background: #f31544;
            color: #FFF;
        }

        h1 {
            font-size: 1.5em;
            color: rgb(0, 0, 0);
        }

        h2 {
            font-size: .9em;
        }

        h3 {
            font-size: 10cm
        }

        p {
            font-size: .9em;
            color: rgb(0, 0, 0);
            line-height: 1.2em;
            font-weight: 900;
            color: black;
        }

        #top,
        #mid,
        #bot {
            /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
        }

        #top {
            min-height: 100px;
        }

        #mid {
            min-height: 80px;
        }

        #bot {
            min-height: 50px;
        }

        #top .logo {
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
            background-size: 60px 60px;
        }

        .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
            background-size: 60px 60px;
            border-radius: 50px;
        }

        .info {
            display: block;
            margin-left: 0;
        }

        .title {
            float: right;
        }

        .title p {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }



        .tabletitle {
            font-size: .9em;
            background: #EEE;
        }

        .service {
            border-bottom: 1px solid #EEE;
        }

        .item {
            width: 24mm;
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-width: thin;
            border-color: rgba(245, 193, 85, 255);
        }

        th {
            background-color: transparent;
        }

        #legalcopy {
            margin-top: 5mm;
        }

        #invoice {
            display: block;
        }
    </style>
</head>

<body onload="doPrint();">
    <div class="text-center" id="invoice-POS">
        <div id="invoice">
            <div id="top">
                <div class="row">
                    <div class="col" style="margin-top: 2% ">
                        <p style="font-weight: 800 ; font-size: 0.8cm"> <b>مؤسسة اغاريد الورد</b> <br>
                            س.ت.رقم : 4032256792 <br>
                            الرقم الضريبي : 311186087400003 <br>
                            المملكة العربية السعودية <br>
                            الرياض - ظهرة لبن
                        </p>
                    </div>
                    <div class="col">
                        <div class="info">
                            <img src="/image/Logotter.png" width="300" height="300" alt="">
                            {{-- <h4 style="font-weight: 900 ; font-size: 1cm">الرقم الضريبي :
                                {{ $all['Bill']->Branch->Shope->VTENumber }}
                            </h4>  --}}
                        </div>
                    </div>
                    <div class="col" style="margin-top: 2% ">
                        <p style="font-weight: 800 ; font-size: 0.8cm"> <b>AGARED ROSES FOUNDATION</b> <br>
                            LIC NO. : 4032256792 <br>
                            VAT NO. : 311186087400003 <br>
                            Kingdom Saudi Arabia <br>
                            Riyadh - Dhahrat Laban
                        </p>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col" style="margin-top: 2% ">
                    <p style="font-weight: 800 ; font-size: 0.8cm">
                        اسم العميل : {{ $all['Bill']->CustomerName }} <br>

                    </p>
                    <p style="font-weight: 800 ; font-size: 0.8cm">
                        السجل التجاري : {{ $all['Bill']->CT }} <br>

                    </p>
                </div>
                <div class="col">
                    <div
                        style="border: 1px solid rgba(245,193,85,255); width: 50%; margin: auto; margin-top: 3%;  text-align: center;">
                        <div style="margin: 10px;">فاتورة</div>
                    </div>
                    <div style="border: 1px solid rgba(245,193,85,255); width: 50%; margin: auto;  text-align: center;">
                        <div style="margin: 10px;">INVOICE</div>
                    </div>
                </div>
                <div class="col" style="margin-top: 2% ">
                    <p style="font-weight: 800 ; font-size: 0.8cm">
                        تاريخ الفاتورة : {{ date('d-m-Y  H:i', strtotime($all['Bill']->created_at)) }}<br>
                        رقم الفاتورة : {{ $all['Bill']->id }}
                    </p>
                </div>
            </div>


            <hr style="border: 2px solid black ; ">

            <table>
                <tr>
                    <th style="font-weight: 900 ; color: black" colspan="1">رقم</th>
                    <th style="font-weight: 900 ; width: 190px ;  color: black" colspan="3">الصنف</th>
                    <th style="font-weight: 900 ; color: black" colspan="1">عدد</th>
                    <th style="font-weight: 900 ; color: black" colspan="1">السعر</th>
                    <th style="font-weight: 900 ;  color: black" colspan="1">الاجمالي</th>
                </tr>
                @php
                    $i = 1;
                    $totalCount = 0;
                    $totalPrice = 0;
                    $total = 0;
                @endphp
                @foreach ($all['BillDetails'] as $Details)
                    @php
                        $totalCount += $Details->count;
                        $totalPrice += $Details->price;
                        $total += $Details->price * $Details->count;
                    @endphp
                    <tr>
                        <td style="font-weight: 800 ; color: black" colspan="1">{{ $i++ }}</td>
                        <td style="font-weight: 800 ;width: 190px ;   color: black" colspan="3">
                            {{ $Details->Item->Name }} @if ($Details->size == 1)
                                {{ $Details->Item->Small_Name }}
                            @elseif($Details->size == 2)
                                {{ $Details->Item->Mid_Name }}
                            @elseif($Details->size == 3)
                                {{ $Details->Item->Big_Name }}
                            @else
                                جالون
                            @endif
                        </td>
                        <td style="font-weight: 800 ; color: black" colspan="1">{{ $Details->count }}</td>
                        <td style="font-weight: 800 ; color: black" colspan="1">{{ $Details->price }}</td>
                        <td style="font-weight: 800 ;   color: black" colspan="1">
                            {{ $Details->price * $Details->count - $Details->price * $Details->count * 0.15 }}</td>
                    </tr>
                @endforeach


                {{-- Total --}}
                <tr>
                    <td style="font-weight: 800 ; color: black ; text-align: left" colspan="4">الاجمالي الفرعي</td>
                    <td style="font-weight: 800 ; color: black" colspan="1">{{ $totalCount }}</td>
                    <td style="font-weight: 800 ; color: black" colspan="1">{{ $totalPrice }}</td>
                    <td style="font-weight: 800 ;   color: black" colspan="1">{{ $total }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 800 ; color: black ; text-align: left" colspan="4">ضريبة المبيعات</td>
                    <td style="font-weight: 800 ; color: black" colspan="1"></td>
                    <td style="font-weight: 800 ; color: black" colspan="1"></td>
                    <td style="font-weight: 800 ;   color: black" colspan="1">{{ $total * 0.15 }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 800 ; color: black ; text-align: left" colspan="4">الاجمالي</td>
                    <td style="font-weight: 800 ; color: black" colspan="1"></td>
                    <td style="font-weight: 800 ; color: black" colspan="1"></td>
                    <td style="font-weight: 800 ;   color: black" colspan="1">{{ $total }}</td>
                </tr>
            </table>



            <div class="row">
                <div class="col">
                    <div class="card-body" style="margin-right: 40% ; width: 50% ; mar">
                        {!! QrCode::size(200)->generate($all['qr']) !!}
                    </div>
                </div>



                <div class="col" style="margin-top:10%">
                    <div class="info">
                        <img src="/image/stamp.png" width="100" height="100" alt="">

                    </div><br>
                    <hr style="margin: auto" width="20%">
                    <p>توقيع مدير الؤسسة</p>
                </div>

            </div>




            <br style="margin-bottom: 5%">
            {{-- <div class="subtop">
                <h3 style="font-weight: 900 ; font-size: 1cm">فاتورة ضريبة مبسطة </h3>
            </div> 
            <hr style="border: 2px solid black ">




            <div id="mid">
                <div>
                    <h3 style="font-weight: 900 ; font-size: 1cm">الفرع : {{ $all['Bill']->Branch->address }} </h3>
                    <h3 style="font-weight: 900 ; font-size: 1cm">امين الصندوق : {{ $all['Bill']->staff->User->name }}
                    </h3>
                    <h3 style="font-weight: 900 ; font-size: 1cm">
                        جوال : {{ $all['Bill']->Branch->phone }}
                    </h3>
                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                        التاريخ : {{ date('d-m-Y  H:i', strtotime($all['Bill']->created_at)) }} <br>
                    </h3>
                </div>
            </div>

            <!--End Invoice Mid-->


            <h1 style="font-weight: 900 ; font-size: 1.3cm"> رقم الفاتورة : {{ $all['BillNo'] + 1 }} </h1>
            <h1 style="font-weight: 900 ; font-size: 1.3cm"> رقم التسلسل : {{ $all['Bill']->id }} </h1>


            <div id="bot">

                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td colspan="2" class="item">
                                <p style="font-weight: 900 ; text-align: center ; font-size: 1cm">الصنف </p>
                            </td>
                            <td colspan="1" class="Hours">
                                <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">الكمية</p>
                            </td>
                            <td colspan="1" class="Rate">
                                <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">السعر </p>
                            </td>
                            <td colspan="1" class="Rate">
                                <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">المبلغ </p>
                            </td>
                        </tr>
                        @foreach ($all['BillDetails'] as $item)
                            <tr class="service">
                                <td colspan="2" class="tableitem">
                                    <p style="font-weight: 900 ; font-size: 1cm">
                                        {{ $item->Item->Name }}


                                        @if ($item->size == 1)
                                            {{ $item->Item->Small_Name }}
                                        @elseif($item->size == 2)
                                            {{ $item->Item->Mid_Name }}
                                        @elseif($item->size == 3)
                                            {{ $item->Item->Big_Name }}
                                        @else
                                            جالون
                                        @endif
                                        @foreach ($all['extraToppings'] as $extraToppings)
                                            @if ($extraToppings['Bill_details_id'] == $item->id)
                                                +
                                                {{ $extraToppings->ExtraTopping->Name }}
                                            @endif
                                        @endforeach
                                    </p>
                                </td>
                                <td colspan="1" class="tableitem">
                                    <p style="font-weight: 900 ; font-size: 1cm">{{ $item->count }}</p>
                                </td>
                                <td colspan="1" class="tableitem">
                                    <p style="font-weight: 900 ; font-size: 1cm">{{ $item->price }}</p>
                                </td>
                                <td colspan="1" class="tableitem">
                                    <p style="font-weight: 900 ; font-size: 1cm">{{ $item->price * $item->count }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
                <!--End Table-->
                <div class="info">
                    <p style="font-weight: 900 ; font-size: 1.2cm" class="text-right  text-bold">
                        السعر : {{ $all['Bill']->total }} ريال <br>
                        {{--    الضريبة : 15% <br>
                        قيمة الضريبة : {{ number_format($all['Bill']->total - $all['Bill']->total / 1.15, 2) }} ريال
                        <br>
                        السعر قبل الضريبة :
                        {{ $all['Bill']->total - number_format($all['Bill']->total - $all['Bill']->total / 1.15, 2) }}
                        ريال
                        <br>
                        السعر شامل الضريبة : {{ $all['Bill']->total }} ريال <br> 
                        المدفوع : {{ $all['Bill']->cash + $all['Bill']->online }} ريال <br>
                        الباقي :
                        {{ $all['Bill']->cash + $all['Bill']->online - $all['Bill']->total }}
                        ريال <br>


                    </p>
                </div>
                <hr style="border: 2px solid black ">
                {{-- 
                <div class="card-body" style="margin-right: 25% ; width: 80%">
                    {!! QrCode::size(250)->generate($all['qr']) !!}
                </div> 
                <hr style="border: 2px solid black ">
--}}
            <div id="mid">
                <div class="info">
                    {{--  
                        <h3 tyle="font-weight: 900">السعر شامل الضريبة </h3> --}}
                    <p tyle="font-weight: 900">
                        ** شكرا لزيارتكم ** <br>

                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--End InvoiceBot-->
    </div>
    <!--End Invoice-->
</body>

<script defer>
    function doPrint() {

        window.print();

        setTimeout(() => {
            //  window.print();
            window.location = "/CasherBoardTailors"


        }, "100");


    }
</script>


</html>
