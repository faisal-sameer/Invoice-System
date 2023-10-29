<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>فاتورة {{ $all['Bill']->CustomerName }} </title>

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

<body style="padding: 4%" onload="doPrint();">
    <div class="text-center" id="invoice-POS">
        <div id="invoice">
            <div id="top">
                <div class="row">
                    <div class="col-sm" style="margin-top: 3%">
                        <p style="font-weight: 800 ; font-size: 0.6cm">
                            <b>مؤسسة طاوي البعد للنقل البري </b> <br>
                            الرياض الحي نجد الرمز البريدي 13973 <br>
                            0563338815 <br>
                            س.ت : 1010582700<br>
                            التاريخ : {{ date('d-m-Y  H:i', strtotime($all['Bill']->created_at)) }}
                        </p>
                    </div>
                    <div class="col-sm" style="margin-top: 4%">
                        <img src="/image/logot.png" width="200" height="150" alt="">

                    </div>
                    <div class="col-sm">
                        <div class="card-body" style="margin-right: 40% ; width: 50% ; mar">
                            {!! QrCode::size(150)->generate($all['qr']) !!}
                            <p style="font-weight: 800 ;  font-size: 0.6cm; margin-top: 2% ">رقم الفاتورة :
                                #{{ $all['Bill']->id }}</p>
                        </div>

                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col">
                    <div style="margin: 10px;">فاتورة ضريبية</div>
                    <div style="margin: 10px;">نقدي / شبكة </div>
                    <div style="margin: 10px;">الرقم الضريبي</div>
                    <div style="margin: 10px;">310382716200003</div>
                </div>

            </div>


            <hr style="border: 2px solid black ; ">
            <p style="font-weight: 800 ;  font-size: 0.6cm; margin-top: 2% ; text-align: right ">بيانات العميل : </p>

            <table>
                <tr>
                    <th style="font-weight: 900 ; color: black" colspan="1">الاسم</th>
                    <th style="font-weight: 900 ;   color: black" colspan="1">رقم الهاتف</th>
                    <th style="font-weight: 900 ; color: black" colspan="1">رقم التسجيل الضريبي</th>



                </tr>
                <tr>
                    <td style="font-weight: 900 ; color: black" colspan="1">{{ $all['Bill']->CustomerName }}</td>
                    <td style="font-weight: 900 ; color: black" colspan="1">{{ $all['Bill']->CustomerPhone }}</td>
                    <td style="font-weight: 900 ; color: black" colspan="1">{{ $all['Bill']->CT }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 900 ; color: black" colspan="3">العنوان : {{ $all['Bill']->address }}
                    </td>

                </tr>
            </table>
            <br>
            <p style="font-weight: 800 ;  font-size: 0.6cm; margin-top: 2% ; text-align: right ">تفاصيل الطلب : </p>

            <table>
                <tr>
                    <th style="font-weight: 900 ; color: black" colspan="1">#</th>
                    <th style="font-weight: 900 ; color: black" colspan="1">الرمز</th>
                    <th style="font-weight: 900 ; color: black" colspan="1">المنتج</th>
                    <th style="font-weight: 900 ;  color: black" colspan="1">السعر</th>
                    <th style="font-weight: 900 ;  color: black" colspan="1">الكمية</th>
                    <th style="font-weight: 900 ;  color: black" colspan="1">الاجمالي</th>
                </tr>
                @php
                    $i = 0;
                @endphp
                @foreach ($all['billTrans'] as $billTrans)
                    <tr>
                        <td style="font-weight: 800 ; color: black" colspan="1"></td>
                        <td style="font-weight: 800 ; color: black" colspan="1"> {{ $billTrans->code }}</td>
                        <td style="font-weight: 800 ; color: black" colspan="1"> نقل {{ $billTrans->item }} الى
                            {{ $billTrans->Tocity->Name }} من {{ $billTrans->city->Name }}</td>
                        <td style="font-weight: 800 ; color: black" colspan="1"> {{ $billTrans->price }}</td>
                        <td style="font-weight: 800 ; color: black" colspan="1"> {{ $billTrans->quantity }} طن </td>
                        <td style="font-weight: 800 ; color: black" colspan="1"> {{ $billTrans->total }}</td>
                        @php
                            $i += $billTrans->total;
                        @endphp
                    </tr>
                @endforeach
            </table>

            <br>
            <div class="row">
                <div class="col">
                    <table>

                        <tr>
                            <td style="font-weight: 800 ; color: black" colspan="1">المجموع الفرعي</td>
                            <td style="font-weight: 800 ; color: black" colspan="1">
                                {{ $i }} SAR</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 800 ; color: black" colspan="1">الخصم</td>
                            <td style="font-weight: 800 ; color: black" colspan="1">0.00 SAR</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 800 ; color: black" colspan="1">المجموع بعد الخصم</td>
                            <td style="font-weight: 800 ; color: black" colspan="1">{{ $i }} SAR </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 800 ; color: black" colspan="1">ضريبة</td>
                            <td style="font-weight: 800 ; color: black" colspan="1"> {{ $all['Bill']->Tax }} SAR
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 800 ; color: black" colspan="1">التوصيل</td>
                            <td style="font-weight: 800 ; color: black" colspan="1">0.00 SAR</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 800 ; color: black" colspan="1">الاجمالي</td>
                            <td style="font-weight: 800 ; color: black" colspan="1"> {{ $all['Bill']->total }} SAR
                            </td>
                        </tr>

                    </table>
                </div>
                <div class="col">

                    <div style="border: 1px solid black;  margin: auto;  text-align: right; height: 160px;  ">
                        <div style="margin: 10px;">ملاحظات : </div>
                    </div>
                </div>
            </div>







            <br style="margin-bottom: 5%">

            <div id="mid">
                <p tyle="font-weight: 900">
                    العنوان: الرياض الحي نجد الرمز البريدي 13973
                    <br>

                </p>
                <p tyle="font-weight: 900">
                    رقم الهاتف: 0563338815 <br>

                </p>
                <div class="info">
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


<script>
    function doPrint() {

        window.print();

        setTimeout(() => {
            window.location = "/CasherBoardTransfer"


        }, "100");


    }
</script>

</html>
