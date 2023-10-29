<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AF</title>

    <!-- Scripts -->
    <script src="{{ public_path('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <link href="http://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Styles -->
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
    <!--- test borad -->
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css">
    <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>
    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
        }

        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%;
        }

        .box {
            margin: 25px;
            width: 250px;
            height: auto;
            background: #fff;
            border: solid black;
            border-color: black;
            float: left;
        }

        .box2 {
            width: 350px;
            border-width: 3px 3px 5px 5px;
            border-radius: 4% 95% 6% 95%/95% 4% 92% 5%;
            transform: rotate(-2deg);
        }

        .evenboxinner {
            transform: rotate(2deg);
            margin: 15px;
            padding: 0 5px;
            float: right;
            background: #ddd;
            border: 1px solid #222;
            box-shadow: 3px 3px 0 #222;
            height: auto;
        }



        .nav-link:hover {
            text-decoration: underline;
        }

        #listMenuUL {
            list-style-type: decimal;
            width: 400px;
            height: auto;
            margin: 30px auto;
        }

        #listMenuUL #listMenuLI {
            padding: 10px 0;
            border-bottom: 1px solid #add8e6;
            text-align: center;
            transition: margin-left 0.3s linear, font-weight 0.2s linear,
                color 0.3s linear;
            -webkit-transition: margin-left 0.3s linear, font-weight 0.2s linear,
                color 0.3s linear;
            -moz-transition: margin-left 0.3s linear, font-weight 0.2s linear,
                color 0.3s linear;
            -o-transition: margin-left 0.3s linear, font-weight 0.2s linear,
                color 0.3s linear;
            -ms-transition: margin-left 0.3s linear, font-weight 0.2s linear,
                color 0.3s linear;
        }

        #listMenuUL #listMenuLI:hover {
            margin-left: 20px;
            font-weight: 600;
            color: #3fb6dd;
        }

        .nav-link {
            font-size: larger;
            font-weight: 900;
        }

        #inputAddToMenu,
        #inputAddToMenuSize,
        #inputAddToMenuCount {
            box-shadow: inset 0 3px 3px rgba(0, 0, 0, 0.16),
                0 4px 6px rgba(0, 0, 0, 0.45);
        }

        #myBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            border: none;
            outline: none;
            background-color: transparent;
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 4px;
        }

        #border {
            border: 2px solid rgba(0, 0, 0, 0.592);
        }

        .form-input img {
            width: 100%;
            display: none;
            margin-bottom: 30px;
        }

        .form-input input {
            display: none;
        }

        .form-inline {
            display: flex;
            flex-flow: row wrap;
            align-items: center;
        }

        .form-inline input select {
            vertical-align: middle;
            margin: 5px 10px 5px 0;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        hr.new5 {
            border: 1px solid;
            border-radius: 5px;
        }

        #Custody,
        #EndCustody {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 3px solid #ccc;
            -webkit-transition: 0.5s;
            transition: 0.5s;
            outline: none;
        }

        #Custody,
        #EndCustody[type="number"]:focus {
            border: 3px solid #555;
        }

        #Custody1 {
            width: 50%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 3px solid #ccc;
            -webkit-transition: 0.5s;
            transition: 0.5s;
            outline: none;
        }

        #Custody1[type="number"]:focus {
            border: 3px solid #555;
        }

        #ClosetodyInvntory {
            box-sizing: border-box;
            border: 3px solid #ccc;
            -webkit-transition: 0.5s;
            transition: 0.5s;
            outline: none;
        }

        #ClosetodyInvntory[type="number"]:focus {
            border: 3px solid #555;
        }

        /* قفلة اليوم */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-right: 30%;
        }

        #main_content {
            margin-right: 32%;
        }

        #secondary_content {
            margin-right: 32%;
        }

        .switch #inputSwitch,
        #inputSwitchStatus {
            opacity: 0;
            width: 0;
            height: 0;
        }

        #imageAddItem {
            width: 10%;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgb(0, 172, 37);
            -webkit-transition: 0.4s;
            transition: 0.4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: rgb(255, 255, 255);
            -webkit-transition: 0.4s;
            transition: 0.4s;
        }

        input:checked+.slider {
            background-color: #cc0000;
        }

        input::before+.slider {
            box-shadow: 0 0 1px #2196f3;
            background-color: #610e7b;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        #secondary_content {
            display: none;
        }

        #myBtnBillToday {
            width: 50%;
            margin-right: 10%;
        }

        #showDate {
            text-align: center;
        }

        /* كرت التواصل */
        .profile-card {
            background-color: #222222;
        }

        .profile-info {
            color: #bdbdbd;
            padding: 25px;
            position: relative;
            margin-top: 15px;
        }

        .profile-info h2 {
            color: #e8e8e8;
            letter-spacing: 4px;
            padding-bottom: 12px;
        }

        .profile-info span {
            display: block;
            font-size: 12px;
            color: #4cb493;
            letter-spacing: 2px;
        }

        .profile-info a {
            color: #4cb493;
        }

        .profile-info i {
            padding: 15px 35px 0px 35px;
        }

        .profile-card:hover .profile-pic {
            transform: scale(1.1);
        }

        .profile-card:hover .profile-info hr {
            opacity: 1;
        }

        /* Underline From Center */
        .hvr-underline-from-center {
            display: inline-block;
            vertical-align: middle;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            box-shadow: 0 0 1px rgba(0, 0, 0, 0);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -moz-osx-font-smoothing: grayscale;
            position: relative;
            overflow: hidden;
        }

        .hvr-underline-from-center:before {
            content: "";
            position: absolute;
            z-index: -1;
            left: 52%;
            right: 52%;
            bottom: 0;
            background: #ffffff;
            border-radius: 50%;
            height: 3px;
            -webkit-transition-property: all;
            transition-property: all;
            -webkit-transition-duration: 0.2s;
            transition-duration: 0.2s;
            -webkit-transition-timing-function: ease-out;
            transition-timing-function: ease-out;
        }

        .profile-card:hover .hvr-underline-from-center:before,
        .profile-card:focus .hvr-underline-from-center:before,
        .profile-card:active .hvr-underline-from-center:before {
            left: 0;
            right: 0;
            height: 1px;
            background: #cecece;
            border-radius: 0;
        }

        /* نهاية كرت التواصل*/
        .cards {
            /*
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;*/
            width: 100%;
            height: 100%;
        }

        .card {
            width: 100%;
            height: 100%;
            /*margin: 40px;
    
    max-width: 250px;
    max-height: 350px;*/
        }

        .card-title {
            display: block;
            text-align: center;
            color: #fff;
            background-color: #7095ba;
            padding: 2%;
            border-top-right-radius: 4px;
            border-top-left-radius: 4px;
        }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .card-desc {
            display: block;
            font-size: 1.2rem;
            position: absolute;
            height: 0;
            top: 0;
            opacity: 0;
            padding: 18px 8%;
            background-color: white;
            overflow-y: scroll;
            transition: 0.8s ease;
        }

        .card:hover .card-desc {
            opacity: 1;
            height: 100%;
            width: 100%;
        }

        .form-input label {
            display: block;
            width: 45%;
            height: 45px;
            margin-left: 25%;
            line-height: 50px;
            text-align: center;
            background: #1172c2;
            color: #fff;
            font-size: 15px;
            font-family: DejaVu Sans, sans-serif;
            text-transform: Uppercase;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }

        .checkmark {
            display: inline-block;
            width: 22px;
            height: 22px;
            -ms-transform: rotate(45deg);
            /* IE 9 */
            -webkit-transform: rotate(45deg);
            /* Chrome, Safari, Opera */
            transform: rotate(45deg);
        }

        .checkmark_circle {
            position: absolute;
            width: 22px;
            height: 22px;
            background-color: green;
            border-radius: 11px;
            left: 0;
            top: 0;
        }

        .checkmark_stem {
            position: absolute;
            width: 4px;
            height: 10px;
            background-color: #fff;
            left: 11px;
            top: 6px;
        }

        .checkmark_kick {
            position: absolute;
            width: 4px;
            height: 4px;
            background-color: #fff;
            left: 8px;
            top: 12px;
        }

        table,
        th,
        td {
            border: 1px solid;
        }

        th {
            background-color: cadetblue;
        }

        #divrow {
            margin-right: 15%;
            margin-top: 2%;
        }

        #divrow1 {
            margin-right: 20%;
        }

        #divrow2 {
            margin-right: 35%;
            margin-top: 5%;
        }

        #subtitle {
            text-align: center;
            font-family: DejaVu Sans, sans-serif;
        }

        #datepicker {
            width: 50%;
        }

        #div1 {
            margin-right: 45%;
        }

        #div2 {
            margin-right: 45%;
        }

        #div3 {
            margin-right: 45%;
        }

        #contentlogin {
            width: 50%;
            margin-right: 34%;
            margin-top: 5%;
        }

        .hide {
            display: none;
        }

        .hide2 {
            display: none;
        }

        input,
        textarea {
            border: 1px solid #eeeeee;
            box-sizing: border-box;
            margin: 0;
            outline: none;
            padding: 10px;
        }

        input[type="button"] {
            -webkit-appearance: button;
            cursor: pointer;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        .input-group {
            clear: both;
            margin: 15px 0;
            position: relative;
        }

        .input-group input[type="button"] {
            background-color: #eeeeee;
            min-width: 38px;
            width: auto;
            transition: all 300ms ease;
        }

        .input-group .button-minus,
        .input-group .button-plus {
            font-weight: bold;
            height: 60px;
            padding: 0;
            width: 100px;
            position: relative;
        }

        .input-group .quantity-field {
            position: relative;
            height: 40px;
            left: -6px;
            text-align: center;
            width: 92px;
            display: inline-block;
            font-size: 13px;
            margin: 0 0 5px;
            resize: vertical;
        }

        .button-plus {
            left: -13px;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            -webkit-appearance: none;
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -webkit-animation-name: animatetop;
            -webkit-animation-duration: 0.4s;
            animation-name: animatetop;
            animation-duration: 0.4s;
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
            from {
                top: -300px;
                opacity: 0;
            }

            to {
                top: 0;
                opacity: 1;
            }
        }

        @keyframes animatetop {
            from {
                top: -300px;
                opacity: 0;
            }

            to {
                top: 0;
                opacity: 1;
            }
        }

        /* The Close Button */
        .close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            padding: 2px 16px;
            background-color: #2481a7bb;
            color: white;
        }

        .modal-body {
            padding: 2px 16px;
        }

        .btns {
            background-color: #2481a7bb;
            border: none;
            color: white;
            padding: 12px 16px;
            font-size: 16px;
            cursor: pointer;
            width: 80%;
        }

        .btnItems {
            background-color: #494949bb;
            border: none;
            color: white;
            padding: 12px 16px;
            font-size: 16px;
            cursor: pointer;
            width: 80%;
        }

        .ButtonBillToday {
            background-color: #000000bb;
            border: none;
            color: white;
            padding: 12px 16px;
            font-size: 16px;
            cursor: pointer;
            width: 10%;
        }

        .wrap {
            display: flex;
        }

        .left {
            flex-basis: 35%;
        }

        .right {
            flex-basis: 80%;

            height: 100vh;
            overflow: auto;
        }

        body {
            margin: 0;
        }

        a {
            background-color: transparent;
        }

        [hidden] {
            display: none;
        }

        html {
            font-family: DejaVu Sans, sans-serif;

            line-height: 1.5;
        }

        *,
        :after,
        :before {
            box-sizing: border-box;
            border: 0 solid #e2e8f0;
        }

        a {
            color: inherit;
            text-decoration: inherit;
        }

        svg,
        video {
            display: block;
            vertical-align: middle;
        }

        video {
            max-width: 100%;
            height: auto;
        }

        .bg-white {
            --bg-opacity: 1;
            background-color: #fff;
            background-color: rgba(255, 255, 255, var(--bg-opacity));
        }

        .bg-gray-100 {
            --bg-opacity: 1;
            background-color: #f7fafc;
            background-color: rgba(247, 250, 252, var(--bg-opacity));
        }

        .border-gray-200 {
            --border-opacity: 1;
            border-color: #edf2f7;
            border-color: rgba(237, 242, 247, var(--border-opacity));
        }

        .border-t {
            border-top-width: 1px;
        }

        .flex {
            display: flex;
        }

        .grid {
            display: grid;
        }

        .hidden {
            display: none;
        }

        .items-center {
            align-items: center;
        }

        .justify-center {
            justify-content: center;
        }

        .font-semibold {
            font-weight: 600;
        }

        .h-5 {
            height: 1.25rem;
        }

        .h-8 {
            height: 2rem;
        }

        .h-16 {
            height: 4rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .leading-7 {
            line-height: 1.75rem;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .ml-1 {
            margin-left: 0.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .ml-2 {
            margin-left: 0.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .ml-4 {
            margin-left: 1rem;
        }

        .mt-8 {
            margin-top: 2rem;
        }

        .ml-12 {
            margin-left: 3rem;
        }

        .-mt-px {
            margin-top: -1px;
        }

        .max-w-6xl {
            max-width: 72rem;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .pt-8 {
            padding-top: 2rem;
        }

        .fixed {
            position: fixed;
        }

        .relative {
            position: relative;
        }

        .top-0 {
            top: 0;
        }

        .right-0 {
            right: 0;
        }

        .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .text-center {
            text-align: center;
        }

        .text-gray-200 {
            --text-opacity: 1;
            color: #edf2f7;
            color: rgba(237, 242, 247, var(--text-opacity));
        }

        .text-gray-300 {
            --text-opacity: 1;
            color: #e2e8f0;
            color: rgba(226, 232, 240, var(--text-opacity));
        }

        .text-gray-400 {
            --text-opacity: 1;
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--text-opacity));
        }

        .text-gray-500 {
            --text-opacity: 1;
            color: #a0aec0;
            color: rgba(160, 174, 192, var(--text-opacity));
        }

        .text-gray-600 {
            --text-opacity: 1;
            color: #718096;
            color: rgba(113, 128, 150, var(--text-opacity));
        }

        .text-gray-700 {
            --text-opacity: 1;
            color: #4a5568;
            color: rgba(74, 85, 104, var(--text-opacity));
        }

        .text-gray-900 {
            --text-opacity: 1;
            color: #1a202c;
            color: rgba(26, 32, 44, var(--text-opacity));
        }

        .underline {
            text-decoration: underline;
        }

        .antialiased {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .w-5 {
            width: 1.25rem;
        }

        .w-8 {
            width: 2rem;
        }

        .w-auto {
            width: auto;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 640px) {
            .sm\:rounded-lg {
                border-radius: 0.5rem;
            }

            .sm\:block {
                display: block;
            }

            .sm\:items-center {
                align-items: center;
            }

            .sm\:justify-start {
                justify-content: flex-start;
            }

            .sm\:justify-between {
                justify-content: space-between;
            }

            .sm\:h-20 {
                height: 5rem;
            }

            .sm\:ml-0 {
                margin-left: 0;
            }

            .sm\:px-6 {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .sm\:pt-0 {
                padding-top: 0;
            }

            .sm\:text-left {
                text-align: left;
            }

            .sm\:text-right {
                text-align: right;
            }
        }

        @media (min-width: 768px) {
            .md\:border-t-0 {
                border-top-width: 0;
            }

            .md\:border-l {
                border-left-width: 1px;
            }

            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lg\:px-8 {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        @media (prefers-color-scheme: dark) {
            .dark\:bg-gray-800 {
                --bg-opacity: 1;
                background-color: #2d3748;
                background-color: rgba(45, 55, 72, var(--bg-opacity));
            }

            .dark\:bg-gray-900 {
                --bg-opacity: 1;
                background-color: #1a202c;
                background-color: rgba(26, 32, 44, var(--bg-opacity));
            }

            .dark\:border-gray-700 {
                --border-opacity: 1;
                border-color: #4a5568;
                border-color: rgba(74, 85, 104, var(--border-opacity));
            }

            .dark\:text-white {
                --text-opacity: 1;
                color: #fff;
                color: rgba(255, 255, 255, var(--text-opacity));
            }

            .dark\:text-gray-400 {
                --text-opacity: 1;
                color: #cbd5e0;
                color: rgba(203, 213, 224, var(--text-opacity));
            }

            .dark\:text-gray-500 {
                --tw-text-opacity: 1;
                color: #6b7280;
                color: rgba(107, 114, 128, var(--tw-text-opacity));
            }
        }
    </style>
</head>

<body onload="getUrl();">

    <div id="app">


        <main class="py-4">

            <div class="container" style="direction: rtl; text-align: center" id="about">
                <h2 id="subtitle">
                    ملخص اليوم
                </h2>


                <div>
                    <h3>الشريك {{ $owner->name }} </h3>
                    <h4>ملخص لدوام الموظفين و الصندوق الموافق <?php echo Date('Y-m-d', time()); ?> </h4>
                </div>
                <hr class="new5" style="margin-left: 1%">
                <div class="row">
                    <section style="margin-top: 2%;overflow-x: auto;" class="row">
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-align: center" colspan="2" scope="col">الفرع</th>
                                    <th style="text-align: center" colspan="2" scope="col">وقت الفتح المتوقع</th>
                                    <th style="text-align: center" colspan="2" scope="col">وقت الاغلاق المتوقع
                                    </th>
                                    <th style="text-align: center" colspan="2" scope="col">وقت الفتح </th>
                                    <th style="text-align: center" colspan="2" scope="col">وقت الاغلاق </th>
                                    <th style="text-align: center" colspan="2" scope="col">العهدة بداية اليوم</th>
                                    <th style="text-align: center" colspan="2" scope="col">العهدة نهاية اليوم</th>
                                    <th style="text-align: center" colspan="2" scope="col">المتوقع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Box as $Box)
                                    <tr>
                                        <td colspan="2">{{ $Box->Branch->address }}</td>
                                        <td colspan="2">{{ date('H:i', strtotime($Box->Scheduling->Start_Date)) }}
                                        </td>
                                        <td colspan="2">{{ date('H:i', strtotime($Box->Scheduling->End_Date)) }}</td>
                                        <td colspan="2">{{ date('H:i', strtotime($Box->Start_Date)) }}</td>
                                        <td colspan="2">
                                            {{ ($Box->End_Date == null
                                                    ? ''
                                                    : $Box->Status == 3)
                                                ? 'تم اغلاق الصندوق من قبل النظام '
                                                : date('H:i', strtotime($Box->End_Date)) }}
                                        </td>
                                        <td colspan="2">{{ $Box->Start_Custody }} ريال</td>
                                        <td colspan="2">
                                            @if ($Box->Status == 3)
                                                تم اغلاق الصندوق من قبل النظام
                                            @else
                                                {{ $Box->End_Custody }} ريال
                                            @endif


                                        </td>
                                        <td colspan="2">
                                            @isset($mailData['Incoming'][$Box->id])
                                                {{ $mailData['Incoming'][$Box->id] + $Box->Start_Custody }} ريال
                                            @endisset
                                        </td>

                                    </tr>
                                @endforeach

                                </form>
                            </tbody>
                        </table>
                    </section>
                </div>
                <br><br><br>
                <h6 id="subtitle" style="text-align: center">
                </h6>
            </div>
        </main>
    </div>
    <lord-icon onclick="topFunction()" id="myBtn" src="https://cdn.lordicon.com/ribwzplp.json" trigger="hover"
        colors="primary:#30c9e8" style="width:90px;height:90px;">
    </lord-icon>


</body>
@yield('script')


</html>
