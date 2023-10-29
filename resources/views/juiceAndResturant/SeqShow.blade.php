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
        <input id="printButton" hidden type="button" onclick="doPrint();" />
        <div id="invoice">

            <div class="subtop">
                <h1 style="font-weight: 900 ; font-size: 2cm">عهدة {{ $all['seq']->staff->User->name }} </h1>
            </div>
            <hr style="border: 2px solid black ">




            <div id="mid">
                <div>

                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                        بداية الصندوق : {{ date('Y-m-d H:i', strtotime($all['seq']->Start_Date)) }} <br>
                    </h3>
                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                        نهاية الصندوق : {{ date('Y-m-d H:i', strtotime($all['seq']->End_Date)) }} <br>
                    </h3>


                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                        الكاش : {{ $all['cash'] }} <br>
                    </h3>
                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                        شبكة : {{ $all['online'] }} <br>
                    </h3>
                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                        المجموع : {{ $all['total'] }} <br>
                    </h3>
                    <div id="table">

                        <table>
                            <tr class="tabletitle">
                                <td colspan="2" class="item">
                                    <h3 style="font-weight: 900 ; text-align: center">
                                        بداية العهدة </h3>
                                </td>
                                <td colspan="2" class="item">
                                    <h3 style="font-weight: 900 ; text-align: center">نهاية العهدة </h3>
                                </td>
                                <td colspan="2" class="item">
                                    <h3 style="font-weight: 900 ; text-align: center">المتوقع في الصندوق</h3>
                                </td>
                            </tr>

                            <tr class="service">
                                <td colspan="2" class="item">
                                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                                        {{ $all['seq']->Start_Custody }} <br>
                                    </h3>
                                </td>
                                <td colspan="2" class="item">

                                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                                        {{ $all['seq']->End_Custody }} <br>
                                    </h3>
                                </td>
                                <td colspan="2" class="item">
                                    <h1 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                                        {{ $all['total'] + $all['seq']->Start_Custody }} <br>
                                    </h1>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <hr style="border: 2px solid black ">
            <br><br>
            <!--End Invoice Mid-->
            <div class="subtop">
                <h1 style="font-weight: 900 ; font-size: 2cm">المنتجات المصروفة </h1>
            </div>
            <hr style="border: 2px solid black ">




            <div id="bot">

                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td colspan="2" class="item">
                                <h3 style="font-weight: 900 ; text-align: center">الصنف </h3>
                            </td>
                            <td colspan="1" class="Hours">
                                <h3 style="font-weight: 900 ; font-size: 1cm">الكمية</h3>
                            </td>
                            <td colspan="1" class="Hours">
                                <h3 style="font-weight: 900 ; font-size: 1cm">القيمة</h3>
                            </td>

                        </tr>
                        @foreach ($all['items'] as $item)
                            <tr class="service">
                                <td colspan="2" class="tableitem">
                                    <h3 style="font-weight: 900 ; font-size: 1cm">
                                        {{ $item['Name'] }}

                                    </h3>
                                </td>
                                <td colspan="1" class="tableitem">
                                    <h3 style="font-weight: 900 ; font-size: 1cm">{{ $item['count'] }}</h3>
                                </td>
                                <td colspan="1" class="tableitem">
                                    <h3 style="font-weight: 900 ; font-size: 1cm">{{ $item['price'] }}</h3>
                                </td>

                            </tr>
                        @endforeach

                    </table>
                </div>
                <!--End Table-->
                {{-- EXp Toady  --}}
                <div class="subtop">
                    <h1 style="font-weight: 900 ; font-size: 2cm">المصروفات </h1>
                </div>
                <hr style="border: 2px solid black ">




                <div id="bot">

                    <div id="table">
                        <table>
                            <tr class="tabletitle">
                                <td colspan="2" class="item">
                                    <h3 style="font-weight: 900 ; text-align: center">عنوان </h3>
                                </td>
                                <td colspan="1" class="Hours">
                                    <h3 style="font-weight: 900 ; font-size: 1cm">المبلغ</h3>
                                </td>


                            </tr>
                            @foreach ($all['TodayExp'] as $TodayExp)
                                <tr class="service">
                                    <td colspan="2" class="tableitem">
                                        <h3 style="font-weight: 900 ; font-size: 1cm">
                                            {{ $TodayExp->title }}

                                        </h3>
                                    </td>

                                    <td colspan="1" class="tableitem">
                                        <h3 style="font-weight: 900 ; font-size: 1cm">{{ $TodayExp->price }}</h3>
                                    </td>

                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
            <!--End InvoiceBot-->
        </div>
        <!--End Invoice-->
    </div>
</body>
<script defer>
    function doPrint() {

        window.print();

        setTimeout(() => {
            // window.print();

            // document.getElementById('BillShow').submit();

            window.location = document.referrer;


        }, "100");


    }
</script>

</html>
