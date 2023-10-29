@extends('layouts.app')


@section('content')
    <div class="containers" style="direction: rtl" id="about">

        <h2 id="subtitle">
            متابعة المتاجر
        </h2>
        <form method="POST" action="{{ route('FAStored') }}">
            @csrf
            <div class="row" style="margin-top: 5%;margin-right: 20%">
                <div class="col-md">
                    <input type="date" name="day" style="width: 50%" placeholder="اليوم">
                </div>
                <select name="shope" class="form-select form-select-lg mb-2" style="width: 20%"
                    aria-label=".form-select-lg example">
                    <option selected value="0">الفئة</option>
                    @foreach ($all['Shope'] as $Shope)
                        <option value="{{ $Shope->id }}">{{ $Shope->Name }}</option>
                    @endforeach

                </select>
                <div class="col-md">
                    <button type="submit" style="width: 50%" class="btn btn-info">ابحث</button>

                </div>
            </div>
        </form>
        <div class="row" style="margin-top: 5%">
            <section style="margin-top: 5% ;margin-right: 5%" class="row">

                <table style="text-align: center;width: 90%">
                    {{ $all['Box']->appends(['Box' => $all['Box']->currentPage(), 'day' => $all['day'], 'shope' => $all['shope']])->links() }}

                    <thead>
                        <tr>
                            <th style="text-align: center" scope="col">اسم المحل</th>
                            <th style="text-align: center" scope="col">اليوم</th>
                            <th style="text-align: center" scope="col">تاريخ الفتح</th>
                            <th style="text-align: center" scope="col">تاريخ الاغلاق</th>
                            <th style="text-align: center" scope="col">وقت الفتح</th>
                            <th style="text-align: center" scope="col">وقت الاغلاق</th>
                            <th style="text-align: center" scope="col">الحالة</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all['Box'] as $Box)
                            <tr>
                                <td style="text-align: center" scope="col">{{ $Box->Branch->Shope->Name }}</td>
                                <td>{{ date('d-m-y ', strtotime($Box->created_at)) }}</td>
                                <td>{{ $Box->Scheduling->Start_Date }}</td>
                                <td>{{ $Box->Scheduling->End_Date }}</td>
                                <td>{{ date('H:i', strtotime($Box->Start_Date)) }}</td>
                                <td>
                                    @if ($Box->Status == 3)
                                        تم اغلاق الصندوق من قبل النظام
                                    @elseif($Box->End_Date != null)
                                        {{ date('H:i', strtotime($Box->End_Date)) }}
                                    @else
                                    @endif


                                </td>
                                <td>
                                    @if ($Box->Status == 3)
                                        تم اغلاق الصندوق من قبل النظام
                                    @elseif($Box->Status == 2)
                                        تم اغلاق الصندوق من قبل الموظف
                                    @else
                                        يعمل
                                    @endif


                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
