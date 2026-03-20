<!DOCTYPE html>
<HTML>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<style>
    @page {
        size: auto;
        margin: 0;
    }

    @media print {
        body {
            color: black;
            /* Set text color for printing */
        }

        /* Add any other styles you want to modify for printing */
    }

    @media print {

        #test,
        #printButton,
        .excelButton {
            display: none;
        }
    }

    @media print {
        body {
            -webkit-print-color-adjust: exact;
        }
    }

    @media print,
    @media screen and (max-width: 800px) {
        #printButton {
            display: none;
            /* Hide the button when printing or generating PDF */
        }
    }
</style>

<body style="margin:25px;">
    <div class="container-fluid mt-3" id="content">
        <div class="row ">
            <div class="col">

                @if ($profile)

                    @if ($profile->logos)
                        <img src="{{ asset('logos/' . $profile->logos) }}" alt="logo"
                            style="width: 150px; height: 150px;">
                    @endif
                    <h5 style="font-weight: bolder;">
                        {{ $profile->company_name }}
                    </h5>
                    <div style="width: 120%;" class="mt-2">
                        <p>
                            <span>Address :{{ $profile->address }}
                                <br>Phone :{{ $profile->phno1 }}
                                @if ($profile->phno2)
                                    , {{ $profile->phno2 }}
                              