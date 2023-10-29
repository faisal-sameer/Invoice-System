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
@php
    $timezone = new DateTimeZone('Asia/Riyadh'); // Set your desired timezone
    $date = new DateTime('now', $timezone); // Get the current date and time in the specified timezone
    $date->setTimeZone(new DateTimeZone('UTC')); // Set the timezone to UTC for accurate conversion
    
    $formatter = new IntlDateFormatter('ar_SA', IntlDateFormatter::FULL, IntlDateFormatter::FULL, 'Asia/Riyadh', IntlDateFormatter::TRADITIONAL, 'yyyy-MM-dd'); // Create a new formatter object
    $formatter->setCalendar(IntlDateFormatter::TRADITIONAL); // Set the calendar to Hijri
    $formatter->setPattern('yyyy-MM-dd'); // Set the output format
    
    $hijriDate = $formatter->format($date); // Convert the date to Hijri format
@endphp


<body style="padding: 5% ; " onload="doPrint();">
    <div style="border: 1px solid black;    padding: 1%; ">

        <div class="text-center" id="invoice-POS">

            <div id="invoice">
                <div>
                    <div class="row">
                        <div class="col" style="margin-top: 2% ">
                            <p style="font-weight: 900 ; font-size: 0.6cm">
                                مؤسسة اغاريد الورد
                            </p>
                            <p style="font-weight: 200 ; font-size: 0.5cm">
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
                            <p style="font-weight: 900 ; font-size: 0.6cm">
                                <b>AGARED ROSES FOUNDATION</b>
                            </p>
                            <p style="font-weight: 200 ; font-size: 0.5cm">
                                LIC NO. : 4032256792 <br>
                                VAT NO. : 311186087400003 <br>
                                Kingdom Saudi Arabia <br>
                                Riyadh - Dhahrat Laban
                            </p>
                        </div>
                    </div>

                </div>

                <div>
                    <div class="row">
                        <div class="col">
                            <h4>تاريخ الإنشاء : {{ date('d-m-Y ', strtotime($all['voucher']->created_at)) }} م</h4>

                        </div>
                        <div class="col">
                            <h4> سنــــــــــــــــد لأمر</h4>
                        </div>
                        <div class="col">
                            <h4>مكان الإنشاء : {{ $all['voucher']->city }} - المملكة العربية السعودية </h4>
                        </div>
                    </div>

                </div>
                <hr style="border: 2px solid black ; ">

                <div class="row" style="margin: auto">


                    <h4> </h4>
                    <br>
                    {{--   <h4>أتعهد بأن أدفع بموجب هذا السند لأمر البنك {{ $all['voucher']->Bank }} بمدينة الرياض ، مبلغ و
                        قدره {{ $all['voucher']->price }} ريال ،
                        وذلك لفاتورة مبيعات رقم {{ $all['voucher']->BillNo }} .
                    </h4> --}}
                    <h4>نتعهد نحن مؤسسة {{ $all['voucher']->nameCT }}
                        بان ندفع لمؤسسة اغاريد الورد بمدينة {{ $all['voucher']->city }} مبلغ و قدرة
                        {{ $all['voucher']->price }} ريال ،
                        وذلك لفاتورة مبيعات رقم ({{ $all['voucher']->BillNo }} ) ، و سوف يكون استحقاق المبلغ في تاريخ :
                        {{ date('d-m-Y ', strtotime($all['voucher']->Date)) }} م
                        دون تاخير او مماطله .
                    </h4>
                    <br>
                    <br>
                    <br>
                    <br>
                    {{-- <h4> و لحامل هذا السند حق الرجوع بدون مصاريف أو احتجاج و دون الحاجة لتقديم السند لقبوله أو الاخطار .
                    </h4> --}}
                </div>



                <hr style="border: 2px solid black ; ">
                {{-- Date --}}






                <div class="container" style="margin-top:5% ;">

                    <div class="row row-cols-2">



                        <div class="col" style="text-align: start">
                            <h4>اسم المؤسسة : <u>{{ $all['voucher']->nameCT }}</u></h4>
                        </div>

                        <div class="col" style="text-align: end">
                            <h4> الرقم الضريبي : <u> {{ $all['voucher']->CT }} </u></h4>
                        </div>



                    </div>
                </div>
                <div class="container" style="margin-top:3% ;">

                    <div class="row row-cols-2">



                        <div class="col" style="text-align: start">
                            <h4>مدير المؤسسة : <u>{{ $all['voucher']->SirName }}</u></h4>
                        </div>

                        <div class="col" style="text-align: end">
                            <h4>رقم الهوية : <u> {{ $all['voucher']->user_ID }} </u></h4>
                        </div>
                    </div>
                </div>



                <div class="container" style="margin-top:3% ;">

                    <div class="row row-cols-2">

                        <div class="col" style="text-align: start">
                            <h4>توقيع :

                            </h4>
                        </div>

                        <div class="col">
                            <h4 style="margin-right: 23%">الختم : </h4>
                        </div>




                    </div>
                    <br>
                    <strong>
                        <h4 style=" margin-top: 5% ;  margin-right: 70%; font-weight: 800">محرر السند</h4>
                    </strong>
                    <h4 style=" margin-top: 5% ;     margin-right: 70%;">الاسم : محمد محسن الطويرقي </h4>
                    <h4 style="     margin-right: 52%;">التوقيع : </h4>




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
