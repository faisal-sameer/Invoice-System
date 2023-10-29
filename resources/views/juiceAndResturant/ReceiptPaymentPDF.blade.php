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
            background-size: 60px 60px;
        }

        .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
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

        .dotted-underline {
            display: inline-block;
            text-decoration: none;
            border-bottom: 1px dotted black;
        }
    </style>
</head>

<body style="padding: 5% ; " onload="doPrint();">
    <div style="border: 1px solid black;    padding: 1%; ">
        <hr style="border: 50px solid #f6bd2d ; ">

        <div class="text-center" id="invoice-POS">

            <div id="invoice">
                <div>
                    <div class="row">
                        <div class="col" style="margin-top: 2% ">
                            <p style="font-weight: 900 ; font-size: 0.7cm">
                                مؤسسة اغاريد الورد
                            </p>
                            <p style="font-weight: 200 ; font-size: 0.7cm">
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
                            <p style="font-weight: 900 ; font-size: 0.7cm">
                                <b>AGARED ROSES FOUNDATION</b>
                            </p>
                            <p style="font-weight: 200 ; font-size: 0.7cm">
                                LIC NO. : 4032256792 <br>
                                VAT NO. : 311186087400003 <br>
                                Kingdom Saudi Arabia <br>
                                Riyadh - Dhahrat Laban
                            </p>
                        </div>
                    </div>

                </div>

                <div class="row" style="margin: auto">

                    <div
                        style="border: 1px solid rgba(245,193,85,255); width: 10%; margin: auto; margin-top: 3%;  text-align: center;">
                        <div style="margin: 10px;"> رقم</div>
                    </div>
                    <div
                        style="border: 1px solid rgba(245,193,85,255); width: 25%; margin: auto; margin-top: 3%;  text-align: center;">
                        <div style="margin: 10px;"> {{ $voucher->id }}</div>
                    </div>

                    <div class="col">
                        @if ($voucher->type_voucher == 1)
                            <div
                                style="border: 1px solid rgba(245,193,85,255); width: 50%; margin: auto; margin-top: 3%;  text-align: center;">
                                <div style="margin: 10px;">سنــــــــــــــد قبــــــــــــــض </div>
                            </div>
                            <div
                                style="border: 1px solid rgba(245,193,85,255); width: 50%; margin: auto;  text-align: center;">
                                <div style="margin: 10px;">Receipt Voucher</div>
                            </div>
                        @else
                            <div
                                style="border: 1px solid rgba(245,193,85,255); width: 50%; margin: auto; margin-top: 3%;  text-align: center;">
                                <div style="margin: 10px;">سنــــــــــــــــد صـــــــــــــــــرف </div>
                            </div>
                            <div
                                style="border: 1px solid rgba(245,193,85,255); width: 50%; margin: auto;  text-align: center;">
                                <div style="margin: 10px;">PAYMENT VOUCHER </div>
                            </div>
                        @endif

                    </div>
                    <div class="col" style="margin-top: 2% ">
                        <p style="font-weight: 800 ; font-size: 0.8cm ; direction: ltr">
                            No. {{ $voucher->id }}
                        </p>
                    </div>
                </div>


                <hr style="border: 2px solid black ; ">
                {{-- Date --}}
                <div class="row">

                    <div class="col">

                        <h4 style="text-align: right">التاريخ : </h4>
                    </div>
                    <div class="col">

                        <h4><u class="dotted-underline">{{ date('d-m-Y ', strtotime($voucher->Date)) }} </u></h4>
                    </div>
                    <div class="col" style="direction: ltr">

                        <h4 style="text-align: left"> Date : </h4>
                    </div>
                </div>
                @if ($voucher->type_voucher == 1)
                    {{-- Receipt  --}}

                    <div class="row">

                        <div class="col">

                            <h4 style="text-align: right">استلمنا من السيد / السادة : </h4>
                        </div>
                        <div class="col">

                            <h4><u class="dotted-underline"> {{ $voucher->SirName }}</u></h4>
                        </div>
                        <div class="col" style="direction: ltr">

                            <h4 style="text-align: left"> Received from Mr. /Gentlemen : </h4>
                        </div>
                    </div>
                @else
                    {{-- Payment  --}}
                    <div class="row">

                        <div class="col">

                            <h4 style="text-align: right">إصرفوا إلى السيد / السادة : </h4>
                        </div>
                        <div class="col">

                            <h4><u class="dotted-underline"> {{ $voucher->SirName }}</u></h4>
                        </div>
                        <div class="col" style="direction: ltr">

                            <h4 style="text-align: left"> Pay to Mr. /Gentlemen : </h4>
                        </div>
                    </div>
                @endif

                {{-- Amount  --}}
                <div class="row">

                    <div class="col">

                        <div style="text-align: right">مبلغ وقدره : </div>
                    </div>
                    <div class="col">

                        <h4><u class="dotted-underline"> {{ $voucher->price }} ريال</u></h4>
                    </div>
                    <div class="col" style="direction: ltr">

                        <h4 style="text-align: left"> Amount of : </h4>
                    </div>
                </div>

                {{-- from  --}}
                <div class="row">

                    <div class="col">

                        <h4 style="text-align: right">وذلك من : </h4>
                    </div>
                    <div class="col">

                        <h4><u class="dotted-underline"> {{ $voucher->for }}</u></h4>
                    </div>
                    <div class="col" style="direction: ltr">

                        <h4 style="text-align: left"> And that from : </h4>
                    </div>
                </div>
                <br>
                {{-- info  --}}
                <div class="row" style="width: 100%">

                    <div class="col">
                        @if ($voucher->type_voucher == 1)
                            <h5 style="text-align: right">
                                نقداََ :
                                <img src="/image/check.png" width="40" height="40" alt="">
                            </h5>
                        @else
                            <h5 style="text-align: right">
                                شيك رقم :
                                <img src="/image/check.png" width="30" height="30" alt="">
                            </h5>
                        @endif
                    </div>
                    @if ($voucher->type_voucher == 2)
                        <div class="col">
                            <h5><u class="dotted-underline"> {{ $voucher->checkNo }}</u></h5>
                        </div>
                    @endif
                    <div class="col">
                        <h5 style="direction: ltr">
                            @if ($voucher->type_voucher == 1)
                                Cash :
                            @else
                                Check No. :
                            @endif
                        </h5>
                    </div>
                    <div class="col">

                        <h5 style="text-align: right">بنك : </h5>
                    </div>
                    <div class="col">

                        <h5><u class="dotted-underline"> {{ $voucher->Bank }}</u></h5>
                    </div>
                    <div class="col" style="direction: ltr">

                        <h5 style="text-align: left"> Bank : </h5>
                    </div>
                    <div class="col">

                        <h5 style="text-align: right">تاريخ : </h5>
                    </div>
                    <div class="col">

                        <h5><u class="dotted-underline">{{ date('d-m-Y ', strtotime($voucher->Date_second)) }}</u></h5>
                    </div>
                    <div class="col" style="direction: ltr">

                        <h5 style="text-align: left"> Date : </h5>
                    </div>
                </div>
            </div>

            <div class="row">



                <div class="col" style="margin-top:5%">

                    <h4>المستلم : Revicer</h4>
                    <br>
                    <br>
                    <hr style="border-top: 2px solid black;margin: auto" width="40%">

                </div>

                <div class="col" style="margin-top:5%">
                    <h4>المحاسب : Accountant</h4>
                    <br> <br>

                    <hr style="border-top: 2px solid black;margin: auto" width="40%">
                </div>

                <div class="col" style="margin-top:5%">
                    <h4>المدير : manager</h4>
                    <br> <br>

                    <hr style="border-top: 2px solid black;margin: auto" width="40%">
                </div>


            </div>







            <br style="margin-bottom: 5%">


        </div>
    </div>
    <!--End InvoiceBot-->
    </div>
    <!--End Invoice-->
    </div>
</body>


<script>
    function doPrint() {

        window.print();

        setTimeout(() => {
            window.location = "/CreateDocument"


        }, "100");


    }
</script>

</html>
