<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta
        name="description"content="يعمل نظام فاء على نظام سحابي آمن ويعمل على جميع الأجهزة ويسهل النظام ادارة ومتابعة متجرك ">
    <meta name="keywords" content="فاء,فواتير,سحابي">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="FA">
    <meta name="keywords" content="فاء,فواتير,سحابي,كاشير,نظام, FA">

    <title>فاء </title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" ></script>comment --}}

    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/bootstrap2.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/Chart.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    {{--  <script src="{{ asset('js/jquery-clockpicker.min.js') }}"></script> --}}
    <script src="{{ asset('js/xdjxvujz.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script> 
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script> --}}
    {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
 --}}
    {{--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> --}}
    {{--  <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script> --}}
    <!-- Styles -->
    <!--- test borad -->
    {{-- <script src="//code.jquery.com/jquery-1.11.0.min.js"></script> --}}
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
    {{-- <script src="https://cdn.lordicon.com/xdjxvujz.js"></script> --}}




    <!-- Fonts -->
    <!--<link rel="dns-prefetch" href="//fonts.gstatic.com">-->
    {{--  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    {{--  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> comment --}}
    {{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
--}}
    <link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/family.css') }}" rel="stylesheet">
    <link href="{{ asset('css/family2.css') }}" rel="stylesheet">
    {{--  <link href="{{ asset('css/jquery-clockpicker.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">

</head>

<body onload="getURL(); ">
    <div class="middle" id="loading" style="display: none  ; ">
        <img src="/image/logoFA.png" class="fade-in-bck" width="100" height="80" alt="">

    </div>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a id="logoPhone" class="navbar-brand" href="{{ url('/') }}">
                    <img src="/image/logoFA.png" width="100" height="80" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <img src="/image/list.png" onclick="OpenMenu()" class="shake-lr" id="imagePhone" alt="">
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li>

                        </li>
                    </ul>

                    <ul class="navbar-nav ms-3" id="clck" style="margin-right: 43%">
                        <li>
                            <svg id="svgClock" width="100" height="100">

                                <g stroke="#696969" stroke-width="2" stroke-linecap="round">
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(0  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(90  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(180  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(270  50 50)" />
                                </g>

                                <g stroke="#696969" stroke-width="2" stroke-linecap="round">
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(30  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(60  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(120  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(150  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(210  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(240  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(300  50 50)" />
                                    <line x1="50" y1="0" x2="50" y2="15"
                                        transform="rotate(330  50 50)" />
                                </g>

                                <g stroke="#696969" stroke-width="1" stroke-linecap="round">
                                    <line id="second" x1="50" y1="5" x2="50"
                                        y2="60" transform="rotate(0  50 50)" />
                                </g>

                                <g stroke="#696969" stroke-width="2" stroke-linecap="round">
                                    <line id="minute" x1="50" y1="10" x2="50"
                                        y2="55" transform="rotate(0  50 50)" />
                                </g>

                                <g stroke="#696969" stroke-width="3" stroke-linecap="round">
                                    <line id="hour" x1="50" y1="25" x2="50"
                                        y2="55" transform="rotate(0  50 50)" />
                                </g>
                            </svg>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    @yield('navbar')

                </div>
            </div>
        </nav>

        <main class="py-4">
            @include('sweetalert::alert')

            @yield('content')
        </main>
    </div>
    <lord-icon onclick="topFunction()" id="myBtn" src={{ asset('css/ribwzplp.json') }} trigger="hover"
        colors="primary:#696969" style="width:90px;height:90px;">
    </lord-icon>

</body>
@yield('script')
<script>
    $(document).ready(function() {
        $("button[type=submit]").on('click', function(event) {
            $('#app').hide();
            $('#loading').show();

        });
        $("a").on('click', function(event) {
            $('#app').hide();
            $('#loading').show();
        });
        $("input[type=submit]").on('click', function(event) {
            $('#app').hide();
            $('#loading').show();
        });

    });
</script>
<script>
    //Get the button
    var mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>
<script>
    function show1() {
        document.getElementById('div1').style.display = 'block';
    }

    function show2() {
        document.getElementById('div2').style.display = 'block';
    }

    function show3() {
        document.getElementById('div3').style.display = 'block';
    }



    function showmenu1() {
        document.getElementById('div4').style.display = 'block';
        document.getElementById('div5').style.display = 'none';
        document.getElementById('tableItem').style.display = 'block';
        document.getElementById('div6').style.display = 'none';
        document.getElementById('tableAddToItem').style.display = 'none';

    }
    document.getElementById('tableItem').style.display = 'block';


    function showmenu2() {
        document.getElementById('div5').style.display = 'block';
        document.getElementById('div4').style.display = 'none';
        document.getElementById('tableItem').style.display = 'block';
        document.getElementById('div6').style.display = 'none';
        document.getElementById('tableAddToItem').style.display = 'none';

    }

    function showmenu3() {
        document.getElementById('div4').style.display = 'none';
        document.getElementById('div5').style.display = 'none';
        document.getElementById('tableItem').style.display = 'none';
        document.getElementById('div6').style.display = 'block';
        document.getElementById('tableAddToItem').style.display = 'block';
    }
    /*
        function showApp() {
            document.getElementById('application').style.display = 'block';
        }

        function hideApp() {
            document.getElementById('application').style.display = 'none';

        }*/
</script>
<script>
    /*
    function incrementValue() {
        document.getElementById('count').value++;

    }

    function decrementValue(e) {
        document.getElementById('count').value--;

    }

    $('.input-group').on('click', '.button-plus', function(e) {
        incrementValue(e);
    });

    $('.input-group').on('click', '.button-minus', function(e) {
        decrementValue(e);
    });*/
</script>
<script>
    /*
    $(document).ready(function() {
        $("#datepicker").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
    });*/
</script>
<script>
    function showPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            preview.style.display = "block";
        }
    }

    function showPreviewEdit(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-2-preview");
            preview.src = src;
            preview.style.display = "block";
        }
    }
</script>
<script>
    var fixHands = function() {
        var d = new Date()
        var t = Math.floor((d.getTime() - d.getTimezoneOffset() * 60000) / 1000);

        var h = t % (12 * 3600) / 120;
        var n = t % 3600 / 10;
        var s = t % 60 * 6;

        document.getElementById('hour').setAttribute('transform', 'rotate(' + h + ' 50 50)');
        document.getElementById('minute').setAttribute('transform', 'rotate(' + n + ' 50 50)');
        document.getElementById('second').setAttribute('transform', 'rotate(' + s + ' 50 50)');
    };

    setInterval(fixHands, 200);
    fixHands();
</script>
<script>
    function getURL() {
        if (window.location.pathname != '/') {


            document.getElementById("listNav" + window.location.pathname).style.cssText = ` color:#2481a7bb ;
            text-decoration: underline; font-size: larger;font-weight: 900; `;
            //document.getElementById("listNav" + window.location.pathname).style.color = "#2481a7bb" ;

        }

    }
</script>
<script>
    $(document).ready(function() {
        $("button[type=submit]").on('click', function(event) {
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');


            $('#app').hide();
            $('#loading').show();

        });
        $("a").on('click', function(event) {
            $(this).find('a').attr('disabled', 'disabled');


            $('#app').hide();
            $('#loading').show();
        });
        $("input[type=submit]").on('click', function(event) {
            $(this).find('input[type="submit"]').attr('disabled', 'disabled');


            $('#app').hide();
            $('#loading').show();
        });

    });
</script>
<script>
    function OpenMenu() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeMenu() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>

</html>
