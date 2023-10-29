<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>فاتورة </title>
    <style>
        html,
        body {
            direction: rtl;

        }

        body {
            /* font-family: "Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace !important;*/

        }



        #invoice-POS {
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
            padding: 2mm;
            width: auto;

            margin: 0 auto;
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

        h4 {
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

        td {
            //padding: 5px 0 5px 15px;
            //border: 1px solid #EEE
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



        #legalcopy {
            margin-top: 5mm;
        }

        #invoice {
            display: block;
        }
    </style>
    <link href="css/app.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/templatemo-art-factory.css">
    <link rel="stylesheet" type="text/css" href="css/owl-carousel.css">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/family.css') }}" rel="stylesheet">
    <link href="{{ asset('css/family2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-clockpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/bootstrap2.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/Chart.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ asset('js/xdjxvujz.js') }}"></script>
</head>

<body onload="doPrint();">
    <div class="text-center" id="invoice-POS">
        <input id="printButton" type="button" onclick="doPrint();" hidden />
        <div id="invoice">
            @if ($all['Bill']->Branch->Shope->file != null)
                <div id="top">
                    <div class="info">
                        <img src="/{{ $all['Bill']->Branch->Shope->file }}" width="500" height="300"
                            alt="">
                        <h3 style="font-weight: 900 ; font-size: 1cm"> {{ $all['Bill']->Branch->Shope->Name }}</h3>
                        <h4 style="font-weight: 900 ; font-size: 1cm">الرقم الضريبي :
                            {{ $all['Bill']->Branch->Shope->VTENumber }}
                        </h4>
                    </div>
                </div>
            @else
                <div class="info">

                    <h3 style="font-weight: 900 ; font-size: 1cm"> {{ $all['Bill']->Branch->Shope->Name }}</h3>
                    <h4 style="font-weight: 900 ; font-size: 1cm">الرقم الضريبي :
                        {{ $all['Bill']->Branch->Shope->VTENumber }}
                    </h4>
                </div>
            @endif
            <hr style="border: 2px solid black ">
            <div class="subtop">
                <h4 style="font-weight: 900 ; font-size: 1cm">فاتورة ضريبة مبسطة </h4>
            </div>
            <hr style="border: 2px solid black ">




            <div id="mid">
                <div>
                    <h4 style="font-weight: 900 ; font-size: 1cm">الفرع : {{ $all['Bill']->Branch->address }} </h4>
                    <h4 style="font-weight: 900 ; font-size: 1cm">امين الصندوق : {{ $all['Bill']->staff->User->name }}
                    </h4>
                    <h4 style="font-weight: 900 ; font-size: 1cm">
                        جوال : {{ $all['Bill']->Branch->phone }}
                    </h4>
                    <h4 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                        {{-- comment   فاتورة مبيعات : {{ $all['Bill']->payType == 1 ? 'نقدا' : 'شبكة' }} <br> --}}
                        التاريخ : {{ date('d-m-Y  H:i', strtotime($all['Bill']->created_at)) }} <br>
                        {{--      اسم العميل :   --}}
                        <h4 style="font-weight: 900 ; font-size: 1cm">
                            اسم العميل : {{ $all['Bill']->Branch->CustomerName }}
                        </h4>

                        <h4 style="font-weight: 900 ; font-size: 1cm">
                            جوال العميل : {{ $all['Bill']->Branch->CustomerPhone }}
                        </h4>

                    </h4>
                </div>
            </div>

            <!--End Invoice Mid-->


            <h1 style="font-weight: 900 ; font-size: 1.3cm"> رقم الفاتورة : {{ $all['Bill']->id }} </h1>


            <div id="bot">

                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td colspan="2" class="item">
                                <h4 style="font-weight: 900 ; text-align: center">الصنف </h4>
                            </td>
                            <td colspan="1" class="Hours">
                                <h4 style="font-weight: 900 ; font-size: 1cm">الكمية</h4>
                            </td>
                            <td colspan="1" class="Rate">
                                <h4 style="font-weight: 900 ; font-size: 1cm">السعر </h4>
                            </td>
                            <td colspan="1" class="Rate">
                                <h4 style="font-weight: 900 ; font-size: 1cm">المبلغ </h4>
                            </td>
                        </tr>
                        @foreach ($all['BillDetails'] as $item)
                            <tr class="service">
                                <td colspan="2" class="tableitem">
                                    <h4 style="font-weight: 900 ; font-size: 1cm">
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
                                        @if ($item->Discount_id != null)
                                            خصم {{ $item->Discount->DiscountP }}
                                            @if ($item->Discount->Discount_type == 1)
                                                %
                                            @else
                                                ريال
                                            @endif
                                        @endif
                                        @foreach ($all['extraToppings'] as $extraToppings)
                                            @if ($extraToppings['Bill_details_id'] == $item->id)
                                                +
                                                {{ $extraToppings->ExtraTopping->Name }}
                                            @endif
                                        @endforeach
                                    </h4>
                                </td>
                                <td colspan="1" class="tableitem">
                                    <h4 style="font-weight: 900 ; font-size: 1cm">{{ $item->count }}</h4>
                                </td>
                                <td colspan="1" class="tableitem">
                                    <h4 style="font-weight: 900 ; font-size: 1cm">{{ $item->price }}</h4>
                                </td>
                                <td colspan="1" class="tableitem">
                                    <h4 style="font-weight: 900 ; font-size: 1cm">{{ $item->price * $item->count }}
                                    </h4>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
                <!--End Table-->
                <div class="info">
                    <h4 style="font-weight: 900 ; font-size: 1.2cm" class="text-right  text-bold">
                        السعر : {{ $all['Bill']->total }} ريال <br>

                        الضريبة : 15% <br>
                        قيمة الضريبة :
                        {{ $all['Bill']->total - number_format($all['Bill']->total - $all['Bill']->total * 0.15, 2) }}
                        ريال
                        <br>
                        السعر قبل الضريبة :
                        {{ number_format($all['Bill']->total - $all['Bill']->total * 0.15, 2) }}
                        ريال
                        <br>
                        السعر شامل الضريبة : {{ $all['Bill']->total }} ريال <br>
                        المدفوع : {{ $all['Bill']->cash + $all['Bill']->online }} ريال <br>
                        الباقي :
                        {{ $all['Bill']->cash + $all['Bill']->online - $all['Bill']->total }}
                        ريال <br>


                    </h4>
                </div>
                <hr style="border: 2px solid black ">

                <div class="card-body" style="margin-right: 25% ; width: 80%">
                    {!! QrCode::size(250)->generate($all['qr']) !!}
                </div>
                <hr style="border: 2px solid black ">

                <div id="mid">
                    <div class="info">
                        <h4 tyle="font-weight: 900">السعر شامل الضريبة </h4>
                        <h4 tyle="font-weight: 900">
                            ** شكرا لزيارتكم ** <br>

                        </h4>
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

        //  window.print();

        setTimeout(() => {
            window.print();
            window.location = "/CasherBoard"


        }, "100");


    }
</script>

</html>
