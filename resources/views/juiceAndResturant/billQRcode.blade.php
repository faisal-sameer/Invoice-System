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

<body>
    <div class="text-center" id="invoice-POS">
        <div id="invoice">
            @if ($all['Bill']->Branch->Shope->file != null)
                <div id="top">
                    <div class="info">
                        <img src="/{{ $all['Bill']->Branch->Shope->file }}" width="500" height="300"
                            alt="">
                        <h3 style="font-weight: 900 ; font-size: 1cm"> {{ $all['Bill']->Branch->Shope->Name }}</h3>
                        {{--     <h4 style="font-weight: 900 ; font-size: 1cm">الرقم الضريبي :
                        {{ $all['Bill']->Branch->Shope->VTENumber }}
                    </h4> --}}
                    </div>
                </div>
            @else
                <div class="info">

                    <h3 style="font-weight: 900 ; font-size: 1cm"> {{ $all['Bill']->Branch->Shope->Name }}</h3>
                    {{--     <h4 style="font-weight: 900 ; font-size: 1cm">الرقم الضريبي :
                    {{ $all['Bill']->Branch->Shope->VTENumber }}
                </h4> --}}
                </div>
            @endif
            <hr style="border: 2px solid black ">





            <!--End Invoice Mid-->


            <h1 style="font-weight: 900 ; font-size: 1.3cm"> رقم التسلسل : {{ $all['Bill']->id }} </h1>


            <div id="bot">


                <!--End Table-->

                <hr style="border: 2px solid black ">

                <div class="card-body" style="margin-right: 25% ; width: 80%">
                    {!! QrCode::size(250)->generate($all['qr']) !!}
                </div>
                <hr style="border: 2px solid black ">

                <div id="mid">
                    <div class="info">

                        <p tyle="font-weight: 900">
                            ** شكرا لزيارتكم ** <b>

                                <p tyle="font-weight: 900">
                                    ** نظام فاء ** <br>

                                </p>
                    </div>
                </div>
            </div>
        </div>
        <!--End InvoiceBot-->
    </div>
    <!--End Invoice-->
</body>


</html>
