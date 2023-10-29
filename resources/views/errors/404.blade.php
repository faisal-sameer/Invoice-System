@extends('layouts.app')


@section('content')
    <div class="container mt-5 pt-5">
        <div class="alert alert-danger text-center">
            <h3 class="display-3">404</h3>
            <p class="display-6" style="direction: rtl">نعتذر الصفحة غير موجودة ... الرجاء التواصل مع <a href="/Call"
                    class="btn btn-outline-secondary" style=" font-size: 1cm">الدعم
                    الفني</a> </p>
            <p class="display-5" style="direction: rtl">
                او العودة الى الصفحة<input action="action" class="btn btn-outline-secondary " style="font-size: 1cm"
                    onclick="window.history.go(-1); return false;" type="submit" value="السابقة" />
            </p>
        </div>
    </div>
@endsection
