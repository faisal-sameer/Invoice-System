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

        p {
            color: green;
        }

        @media print {
            .pagebreak {
                page-break-after: auto;
                page-break-inside: avoid;
            }

            /* page-break-after works, as well */
        }
    </style>
</head>

<body onload="doPrint();">

    @foreach ($all['tailorDetails'] as $tailorDetails)
        <div class="text-center pagebreak" id="invoice-POS">
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

                        <h3 style="font-weight: 900 ; font-size: 1cm ; margin-top: 1%; color: #f31544">
                            {{ $all['Bill']->Branch->Shope->Name }}</h3>

                        <div class="row">
                            <div class="col-md">
                                <h4 style="font-weight: 900 ; font-size: 1cm">الرقم الضريبي :
                                    {{ $all['Bill']->Branch->Shope->VTENumber }}
                                </h4>
                            </div>
                            <div class="col-md">
                                <h1 style="font-weight: 900 ; font-size: 1.3cm"> رقم الفاتورة : {{ $all['Bill']->id }}
                                </h1>
                            </div>
                            <div class="col-md">
                                <h4 style="font-weight: 900 ; font-size: 1cm">
                                    التاريخ : {{ date('d-m-Y  H:i', strtotime($all['Bill']->created_at)) }}
                                </h4>
                            </div>
                        </div>

                    </div>
                @endif
                <hr style="border: 2px solid black ">




                <div id="info">
                    <div class="row">
                        <div class="col-md">
                            <h3 style="font-weight: 900 ; font-size: 0.6cm">
                                اسم صاحب الفاتورة : {{ $all['Bill']->CustomerName }}
                            </h3>
                        </div>
                        <div class="col-md">

                            <h3 style="font-weight: 900 ; font-size: 0.6cm">
                                جوال : {{ $all['Bill']->CustomerPhone }}
                            </h3>
                        </div>
                        <div class="col-md">

                            <h3 style="font-weight: 900 ; font-size: 0.6cm" class="text-right pr-3">
                                التاريخ : {{ date('d-m-Y  H:i', strtotime($all['Bill']->created_at)) }}
                            </h3>
                        </div>
                        <div class="col-md">

                            <h3 style="font-weight: 900 ; font-size: 0.6cm" class="text-right pr-3">
                                المبلغ : {{ $all['Bill']->total }} ريال
                            </h3>
                        </div>
                        <div class="col-md">

                            <h3 style="font-weight: 900 ; font-size: 0.6cm" class="text-right pr-3">
                                المدفوع : {{ $all['Bill']->cash + $all['Bill']->online }} ريال
                            </h3>
                        </div>
                        <div class="col-md">

                            <h3 style="font-weight: 900 ; font-size: 0.6cm" class="text-right pr-3">
                                الباقي :
                                {{ str_replace('-', ' ', $all['Bill']->cash + $all['Bill']->online - $all['Bill']->total) }}
                                ريال
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <hr style="border: 2px solid black ">

            <div class="info">

                <h3 style="font-weight: 900 ; font-size: 1cm"> معلومات التفصيل</h3>

            </div>
            <hr style="border: 2px solid black ">

            <div class="row">
                <div class="col-md">
                    <h3 style="font-weight: 900 ; font-size: 0.6cm">
                        الاسم : {{ $tailorDetails->name }}
                    </h3>
                </div>
                <div class="col-md">

                    <h3 style="font-weight: 900 ; font-size: 0.6cm">
                        نوع القماش : {{ $tailorDetails->Item->Name }}

                    </h3>
                </div>
            </div>

            <div id="table">
                <table>
                    <tr class="tabletitle">
                        <td colspan="1" class="item">
                            <p style="font-weight: 900 ; text-align: center ; font-size: 1cm">عدد الثياب </p>
                        </td>
                        <td colspan="1" class="Hours">
                            <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">الطول</p>
                        </td>
                        <td colspan="1" class="Rate">
                            <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">الكتف </p>
                        </td>
                        <td colspan="1" class="Rate">
                            <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">طول الكم </p>
                        </td>
                        <td colspan="1" class="Rate">
                            <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">الرقبة </p>
                        </td>
                        <td colspan="1" class="Rate">
                            <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">وسع الصدر </p>
                        </td>
                        <td colspan="1" class="Rate">
                            <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">وسع اليد </p>
                        </td>
                        <td colspan="1" class="Rate">
                            <p style="font-weight: 900 ;text-align: center ; font-size: 1cm">جيب أسفل </p>
                        </td>
                    </tr>

                    <tr class="service">
                        <td colspan="1" class="tableitem">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->count_no }} </p>
                        </td>
                        <td colspan="1" class="tableitem">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->length }} </p>
                        </td>
                        <td colspan="1" class="tableitem">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->shoulder }} </p>
                        </td>
                        <td colspan="1" class="tableitem">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->sleeves }} </p>
                        </td>
                        <td colspan="1" class="tableitem">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->neck }} </p>
                        </td>
                        <td colspan="1" class="tableitem">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->chest }} </p>
                        </td>
                        <td colspan="1" class="tableitem">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->expand_hand }} </p>
                        </td>
                        <td colspan="1" class="tableitem">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->under_poket }} </p>
                        </td>

                    </tr>


                </table>
            </div>
            <hr style="border: 2px solid black ">


            {{-- models --}}
            <div class="row">
                <div class="col-md"><img src="/{{ $tailorDetails->upPoket->path }}" width="50" height="100"
                        alt="">
                    <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->up_poket_details }}
                    </p>
                </div>
                <div class="col-md">
                    <img src="/{{ $tailorDetails->neckID->path }}" width="50" height="100" alt="">
                    <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->neck_details }}
                    </p>
                </div>
                <div class="col-md">
                    <img src="/{{ $tailorDetails->handID->path }}" width="50" height="100" alt="">
                    <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->hand_details }}
                    </p>
                </div>
                <div class="col-md">
                    <img src="/{{ $tailorDetails->Midstyle->path }}" width="90" height="100" alt="">
                    <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->midstyle_details }}
                    </p>
                </div>
            </div>
            <div class="row">
                <p style="font-weight: 900 ; font-size: 1cm;">
                    {{ $tailorDetails->downhand_up_details }} </p>
                <div class="row">
                    <div class="col-5">
                        <p style="font-weight: 900; font-size: 1cm;  text-align: end; margin-top: revert;">
                            {{ $tailorDetails->downhand_right_details }}
                        </p>
                    </div>
                    <div class="col-7" style=" text-align: initial;">
                        <img src="/image/downhand.png" width="100" height="100" alt=""
                            style=" margin-right: 8%;">
                    </div>

                </div>
                <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->downhand_down_details }} </p>

            </div>


            <hr style="border: 2px solid black ">

            <div class="row">
                <div class="col-md">
                    <h3 style="font-weight: 900 ; font-size: 0.6cm">
                        سحاب : @if ($tailorDetails->zipper == 1)
                            <img src="/itemsTailor/5291043.png" width="50" height="50" alt="">
                        @endif
                    </h3>
                </div>
                <div class="col-md">

                    <h3 style="font-weight: 900 ; font-size: 0.6cm">
                        خياطة دبل : @if ($tailorDetails->double_line == 1)
                            <img src="/itemsTailor/5291043.png" width="50" height="50" alt="">
                        @endif
                    </h3>
                </div>
                <div class="col-md">

                    <h3 style="font-weight: 900 ; font-size: 0.6cm">
                        أسفل : @if ($tailorDetails->under == 1)
                            <img src="/itemsTailor/5291043.png" width="50" height="50" alt="">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->under_details }} </p>
                        @endif
                    </h3>
                </div>
                <div class="col-md">

                    <h3 style="font-weight: 900 ; font-size: 0.6cm">
                        كـفــة : @if ($tailorDetails->cuff == 1)
                            <img src="/itemsTailor/5291043.png" width="50" height="50" alt="">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->cuff_details }}
                            </p>
                        @endif
                    </h3>
                </div>
                <div class="col-md">

                    <h3 style="font-weight: 900 ; font-size: 0.6cm">
                        تحت الجيب : @if ($tailorDetails->under_poket_check == 1)
                            <img src="/itemsTailor/5291043.png" width="50" height="50" alt="">
                            <p style="font-weight: 900 ; font-size: 1cm"> {{ $tailorDetails->under_poket_details }}
                            </p>
                        @endif
                    </h3>
                </div>
            </div>
            <hr style="border: 2px solid black ">

            <div class="row">
                <div class="col-md">
                    <h3 style="font-weight: 900 ; font-size: 0.8cm ; text-align: right ; padding-right: 4%; ">
                        ملاحظات : {{ $tailorDetails->notes }}

                    </h3>
                </div>
            </div>


            <div id="bot">

                <!--End Table-->


                <hr style="border: 2px solid black ">

                <div id="mid">
                    <div class="info">


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
        <br>
    @endforeach
</body>

<script>
    function doPrint() {


        setTimeout(() => {
            window.print();
            //  window.location = "/PendingBills"


        }, "100");


    }
</script>


</html>
