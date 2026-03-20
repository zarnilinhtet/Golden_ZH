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
                                @endif

                            </span>
                        </p>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <h5 style="margin-top:30px">Stock Adjust</h5>
                <div style="font-weight: bold">Date : {{ date('d-m-Y', strtotime($inout->created_at)) }}
                </div>
            </div>
        </div>



        <br>
        <br>
        <br>
        <div class="row" style="margin-top: -20px;">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" style="border:1px solid black">
                                <thead style="background-color: #0B5ED7;color:white;">
                                    <tr class="item_header bg-gradient-directional-blue white"
                                        style="margin-bottom:10px;">
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Quantity</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td class="text-center" id="count" style="border:1px solid black">
                                        {{ $inout->part_number }}
                                    </td>
                                    <td class="text-center" id="count" style="border:1px solid black">
                                        {{ $item->item->category ?? '' }}
                                    </td>
                                    <td class="text-center" id="count" style="border:1px solid black">
                                        {{ $inout->totalQty }}{{ ' ' }} {{ $inout->unit }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </table>

                <table width="60%" class="table mt-3">
                    <tr>
                        <td class="border-0">
                            <div style="font-weight: bold;">Advisor Name
                            </div>
                            <span class="text-danger text-center"> {{ Auth::user()->name }}</span>
                        </td>
                    </tr>
                </table>
                <div id="downloadPdf" class="btn btn-primary mt-5" style="width: 65px"> Print</div>
            </div>
        </div>


</body>

</HTML>
<script src="{{ asset('backend/js/html2pdf.js') }}"></script>
<script>
    document.getElementById('downloadPdf').addEventListener('click', function() {
        // Select the element to be converted to PDF
        const element = document.getElementById('content');

        document.getElementById('downloadPdf').style.display = 'none';

        setTimeout(function() {
            document.getElementById('downloadPdf').style.display = 'block';
        }, 2000);

        // Options for the PDF generation
        const options = {
            margin: 10,
            filename: 'document.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            }
        };


        html2pdf()
            .from(element)
            .set(options)
            .save();
    });
</script>
