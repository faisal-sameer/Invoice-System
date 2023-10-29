@extends('layouts.app')


@section('content')
    <div class="containers" style="direction: rtl" id="about">
        <h2 id="subtitle">

        </h2>


        <div class="row">
            <div class="col-md">
                <form method="POST" id="submitExp" style="margin-top: 2%" action="{{ route('billExport') }}">
                    @csrf
                    <input type="date" id="fromB" name="fromB">
                    <input type="date" id="toB" name="toB" style="color: blue">

                    <button type="submit" class="btn btn-success">تحميل الفواتير </button>
                </form>
            </div>
            <div class="col-md">
                <form method="POST" id="ExpDetails" style="margin-top: 2%" action="{{ route('DetailExport') }}">
                    @csrf
                    <input type="date" id="fromD" name="fromD" hidden readonly style="visibility: hidden">
                    <input type="date" id="toD" name="toD" hidden readonly style="color: blue;visibility: hidden">

                    <button type="submit" class="btn btn-success">تحميل تفصيل الفواتير </button>
                </form>
            </div>
            </div>


                <form method="POST" style="margin-top: 2%" action="{{ route('billImport') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <strong>الفواتير</strong>
                    <input type="file" name="BillFile" placeholder="file">
                    <strong>تفاصيل الفاتورة</strong>
                    <input type="file" name="DetailsFile" style="background: rebeccapurple">
                    <button type="submit" class="btn btn-success">Import</button>
                </form>
    @endsection
    @section('script')
        <script>
            document.getElementById("fromB").addEventListener("change", function() {
                var input = this.value;
                var dateEntered = new Date(input);
                console.log(input); //e.g. 2015-11-13
                // console.log(dateEntered); //e.g. Fri Nov 13 2015 00:00:00 GMT+0000 (GMT Standard Time)
                document.getElementById('fromD').value = input;
            });
            document.getElementById("toB").addEventListener("change", function() {
                var input = this.value;
                var dateEntered = new Date(input);
                console.log(input); //e.g. 2015-11-13
                // console.log(dateEntered); //e.g. Fri Nov 13 2015 00:00:00 GMT+0000 (GMT Standard Time)
                document.getElementById('toD').value = input;
            });
        </script>
    @endsection
