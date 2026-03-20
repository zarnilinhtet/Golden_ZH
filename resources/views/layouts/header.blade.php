<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS CodeVerse | Point of sales</title>


    <!-- Font Awesome -->

    <link rel="shortcut icon" href="{{ asset('img/logo.jpg') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Theme style -->

    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

</head>


<style>
    .dt-buttons {
        background-color: #007BFF;

        color: #fff;

    }

    /* .main-header {
        background: linear-gradient(to bottom, #2270c9, #0966e7);
    } */
    .form-control {
        font-size: 15px;
        font-family: "Georgia", serif;

    }

    button {
        font-family: "Georgia", serif;
        font-weight: bold
    }
    .btn-danger, .btn-success ,.btn-primary{
        font-family: "Georgia", serif;
        font-weight: bold
    }


    label {
        font-family: "Georgia", serif;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: "Georgia", serif;
        font-weight: bold;
    }

    li {
        font-family: "TGeorgia", serif;
        font-weight: bold;
    }

    span {
        font-family: "Georgia", serif;
        font-weight: bold;
    }

    a {
        font-family: "Georgia", serif;
        font-size: 16px;
        /* Adjust size as needed */
        font-weight: bold;

        color: #007bff;
        /* Default link color (blue) */
        text-decoration: none;
        /* Removes underline */
    }

    a:hover {
        color: #0056b3;
        /* Darker shade on hover */
        text-decoration: underline;
        /* Adds underline on hover */
    }

    .dataTables_wrapper .dataTables_paginate {
        font-family: "Georgia", serif;
        font-size: 14px;
        /* Adjust size as needed */
        font-weight: bold;
        /* Optional: Makes text bold */
    }

    .dataTables_wrapper .dataTables_info {
        font-family: "Georgia", serif;
        font-size: 15px;
        /* Adjust as needed */
        font-weight: bold;
        /* Optional */
        color: #333;
        /* Optional: Adjust color */
    }

    .alert {
        font-family: "Georgia", serif;
        font-size: 16px;

        font-weight: bold;

        padding: 10px 15px;
        border-radius: 5px;

    }

    th {
        font-size: 14px;
        font-family: "Garamond", serif;
        font-weight: bold;
        /* text-transform: uppercase; */
    }

    td {
        font-size: 14px;
        font-family: "TGeorgia", serif;
        font-weight: bold;
    }

    .main-header {
        /* background: radial-gradient(circle, rgb(255, 100, 100), rgb(52, 52, 52)); */
        /* background: #c8c3c3; */

    }

    /*
    .modal-header,

    .modal-body,


    .card-color {
        background-color: #092366 !important;
    } */
</style>
