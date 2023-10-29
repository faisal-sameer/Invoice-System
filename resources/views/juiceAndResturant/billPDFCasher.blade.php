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

</head>

<body onload="doPrint();">
    <div class="text-center" id="invoice-POS">
        <input id="printButton" hidden type="button" onclick="doPrint();" />
        <div id="invoice">

            <div class="subtop">
                <h1 style="font-weight: 900 ; font-size: 2cm">تجهيز طلب </h1>
            </div>
            <hr style="border: 2px solid black ">




            <div id="mid">
                <div>

                    <h3 style="font-weight: 900 ; font-size: 1cm" class="text-right pr-3">
                        {{-- comment   فاتورة مبيعات : {{ $all['Bill']->payType == 1 ? 'نقدا' : 'شبكة' }} <br> --}}
                        التاريخ : {{ date('d-m-Y  H:i', strtotime($all['Bill']->created_at)) }} <br>
                        {{--      اسم العميل :   --}}
                    </h3>
                </div>
            </div>

            <!--End Invoice Mid-->


            <h1 style="font-weight: 900 ; font-size: 1.3cm"> رقم الفاتورة : {{ $all['BillNo'] + 1 }} </h1>
            <form method="POST" id="BillShow" action="{{ route('BillShow') }}">
                @csrf
                <input type="text" hidden name="id" value="{{ $all['Bill']->id }}" hidden readonly>
                <td scope="col"><button type="submit" hidden class="btn btn-info">عرض
                    </button>
                </td>
            </form>

            <div id="bot">

                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td colspan="2" class="item">
                                <h3 style="font-weight: 900 ; text-align: center">الصنف </h3>
                            </td>
                            <td colspan="2" class="Hours">
                                <h3 style="font-weight: 900 ; font-size: 1cm">الكمية</h3>
                            </td>

                        </tr>
                        @foreach ($all['BillDetails'] as $item)
                            <tr class="service">
                                <td colspan="2" class="tableitem">
                                    <h3 style="font-weight: 900 ; font-size: 1cm">
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
                                    </h3>
                                </td>
                                <td colspan="2" class="tableitem">
                                    <h3 style="font-weight: 900 ; font-size: 1cm">{{ $item->count }}</h3>
                                </td>

                            </tr>
                        @endforeach

                    </table>
                </div>
                <!--End Table-->

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
            // window.print();

            document.getElementById('BillShow').submit();
            //window.location = document.referrer;


        }, "100");


    }
</script>

</html>
