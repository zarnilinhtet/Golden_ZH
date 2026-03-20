<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - {{ $profile->name ?? 'Golden ZH' }}</title>

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/moment2103.js') }}"></script>

  <style>
    /* Custom Colors matching the image */
    :root {
        --zh-green: #297B38;
        --zh-light-green: #E2EFDA;
        --zh-blue: #1A5296;
    }

    body {
        font-family: 'Times New Roman', Times, serif;
        color: black;
        font-size: 15px;
    }

    .text-zh-green { color: var(--zh-green) !important; }
    .text-zh-blue { color: var(--zh-blue) !important; }

    .border-zh-green {
        border-color: var(--zh-green) !important;
    }

    /* Divider styling */
    hr.green-divider {
        border: none;
        border-top: 3px solid var(--zh-green) !important;
        opacity: 1;
        margin: 15px 0;
    }

    /* Table Styling */
    .quotation-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .quotation-table th, .quotation-table td {
        border: 1px solid black !important;
        padding: 6px 8px;
        vertical-align: middle;
    }

    .quotation-table th {
        background-color: var(--zh-light-green) !important;
        font-weight: bold;
        text-align: center;
    }

    /* --- PRINT MEDIA QUERIES (UPDATED FOR COMPACT FIT) --- */
    @media print {
        /* 1. Shrink the paper margins to give more vertical room (changed from 10mm to 5mm) */
        @page { size: A4; margin: 5mm; }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            /* 2. Slightly reduce font size when printing to save space */
            font-size: 14px !important;
        }

        #printButton { display: none !important; }
        .container { width: 100% !important; max-width: 100% !important; padding: 0 !important; }

        /* 3. Make table rows slightly tighter */
        .quotation-table th, .quotation-table td {
            padding: 4px 6px !important;
        }

        /* 4. Reduce large margins between sections just for printing */
        .mt-4 { margin-top: 1rem !important; }
        .mb-4 { margin-bottom: 1rem !important; }
        .mb-5 { margin-bottom: 1.5rem !important; }

        /* Multi-page optimizations */
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; break-inside: avoid; }
        .keep-together { page-break-inside: avoid; break-inside: avoid; }
    }
</style>
</head>
<body>

<div class="container bg-white p-4 mx-auto" style="max-width: 900px;">

    <div class="row align-items-center mb-2">
        <div class="col-3 text-center">
            <img src="{{ asset('logos/' . ($profile->logos ?? '')) }}" alt="Logo" style="max-width: 130px; height: auto;">
        </div>
        <div class="col-9 text-zh-green">
            <h1 class="fw-bold mb-3" style="font-size: 32px;">{{ $profile->name ?? 'Golden ZH Co., Ltd.' }}</h1>

            <div class="d-flex mb-1" style="font-size: 14px;">
                <div style="width: 25px;"><i class="fas fa-home"></i></div>
                <div>{{ $profile->address ?? 'No.31, 18th Street, Latha Township, Yangon.' }}</div>
            </div>
            <div class="d-flex mb-1" style="font-size: 14px;">
                <div style="width: 25px;"><i class="fas fa-phone-alt"></i></div>
                <div>{{ $profile->phno1 ?? '+95-9 9740 80404' }}, {{ $profile->phno2 ?? '+95-9 9798 94040' }}</div>
            </div>
            <div class="d-flex mb-1" style="font-size: 14px;">
                <div style="width: 25px;"><i class="fas fa-envelope"></i></div>
                <div>goldenzh.mm@gmail.com, goldenzh.sales@gmail.com</div>
            </div>
        </div>
    </div>

    <hr class="green-divider">

    <div class="text-end fw-bold mb-3" style="font-size: 16px;">
        Issued date: &nbsp;&nbsp; {{ isset($invoice->created_at) ? $invoice->created_at->format('d.m.Y') : date('d.m.Y') }}
    </div>

    <div class="mb-3">
        <h5 class="fw-bold mb-1">Dear Valued Customer,</h5>
        <p class="mb-0" style="font-size: 16px;">We are pleased to present our most competitive price for your requested information as follows.</p>
    </div>

    <div class="table-responsive">
        <table class="quotation-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Sr.</th>
                    <th style="width: 45%;">Item</th>
                    <th style="width: 15%;">Packaging Size</th>
                    <th style="width: 17.5%;">Price/Bx (MMK)</th>
                    <th style="width: 17.5%;">Unit Price (MMK)</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($invoices) && count($invoices) > 0)
                    @foreach ($invoices as $key => $invoice)
                        @foreach ($invoice->sells as $sellKey => $sell)
                            <tr class="text-center">
                                <td>{{ $sellKey + 1 }}.</td>
                                <td class="text-start ps-2">{{ $sell->part_number }}</td>
                                <td>{{ $sell->product_qty }} {{ $sell->unit }}/Bx</td>
                                <td>{{ number_format($sell->product_price, 0) }}</td>
                                <td>{{ number_format($sell->retail_price, 0) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="d-flex text-zh-blue mb-3 align-items-baseline" style="font-size: 15px;">
        <div class="fw-bold me-2">**Note:**</div>
        <div>
            @if(isset($invoice) && !empty($invoice->remark))
                <div id="thank">
                    <p class="mb-0 fw-bold" style="font-size: 14px; color: black;">
                        {{ $invoice->remark }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <p class="mb-4" style="font-size: 16px;">
        This quotation is valid within 1 month from the date of issued.<br>
        The price may be changeable without prior notice due to currency fluctuation.<br>
        We will be happy to supply any further information you may need and trust that you call on us to fill your order, which will receive our prompt and careful attention.
    </p>

    <div class="row align-items-center mt-4 keep-together">
        <div class="col-7 text-center">
            <h5 class="fw-bold mb-1">"Empowering Healthcare with Reliable Solutions"</h5>
            <h5 class="fw-bold">"Your Satisfaction is Our Priority"</h5>
        </div>

        <div class="col-5">
            <div class="border border-2 border-zh-green p-3 text-zh-green" style="border-radius: 0;">
                <h5 class="fw-bold text-center mb-3">{{ $profile->name ?? 'Golden ZH Co., Ltd.' }}</h5>

                <div class="d-flex mb-2" style="font-size: 12px;">
                    <div style="width: 20px;"><i class="fas fa-home"></i></div>
                    <div>{{ $profile->address ?? 'No.31, 18th Street, Latha Township, Yangon.' }}</div>
                </div>
                <div class="d-flex mb-2" style="font-size: 12px;">
                    <div style="width: 20px;"><i class="fas fa-phone-alt"></i></div>
                    <div>{{ $profile->phno1 ?? '+95 9974080404' }}</div>
                </div>
                <div class="d-flex mb-1" style="font-size: 12px;">
                    <div style="width: 20px;"><i class="fas fa-envelope"></i></div>
                    <div style="word-break: break-all;">goldenzh.mm@gmail.com, goldenzh.sales@gmail.com</div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5" id="printButton">
        <button onclick="window.print()" class="btn btn-success px-4 py-2">
            <i class="fas fa-print me-2"></i> Print Quotation
        </button>
    </div>

</div>

</body>
</html>
