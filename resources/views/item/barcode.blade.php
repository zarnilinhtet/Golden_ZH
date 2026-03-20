<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SSE POS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet" />
</head>
<style>
    @media print {

        /* Define styles for printing */
        body {
            -webkit-print-color-adjust: exact;
            /* Chrome, Safari */
            color-adjust: exact;
            /* Firefox */
        }

        .page-tools {
            display: none;
        }
    }
</style>

<body>
    <div class="mt-4 container-fluid barcode-container">

        @if ($productCode)
            <div class="barcode">
                <table border="0" style="margin-left:5px;">
                    <tr>
                        <td style="font-size: 20px;font-weight:bolder" class="text-center">
                            {{ $productCode->item->item_name }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px">
                            @if (isset($productCode->variations_barcode))
                                {!! DNS1D::getBarcodeHTML($productCode->variations_barcode, 'C128') !!}
                            @else
                                <p>Barcode not available</p>
                            @endif

                        </td>


                    </tr>
                    <tr>
                        <td style="font-size: 20px;font-weight:bolder " class="text-center">
                            {{ $productCode->variations_barcode }}</td>
                    </tr>
                    {{-- <tr>
                    <td style="font-size: 10px;font-weight:bolder" class="text-center">
                        {{ $productCode->retail_price }} Kyats</td>
                </tr> --}}
                </table>
            </div>
        @else
            <div class="alert alert-danger">
                Item variation not found.
            </div>
        @endif
    </div>
    </div>
</body>

</html>
