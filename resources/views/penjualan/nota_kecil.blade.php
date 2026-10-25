<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> </title>

    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 10pt;
        }
        table td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm auto;
            }
            html, body {
                width: 70mm;
                margin: 0;
                padding: 0;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="printReceipt()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="printReceipt()">Print</button>
    <div class="text-center">
        @include('partials.company-letterhead', ['variant' => 'receipt-small', 'showAddress' => true, 'uppercaseAddress' => true, 'uppercasePhone' => true])
    </div>
    <br>
    <div>
        <p style="float: left;">{{ date('d-m-Y') }}</p>
        <p style="float: right;">{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p>No: {{ tambah_nol_didepan($penjualan->id_penjualan, 10) }}</p>
    <p>Receipt ID: {{ $penjualan->receipt_number }}</p>
    @if($penjualan->room_unique_details)
    <p>Name: {{ strtoupper($penjualan->room_unique_details) }}</p>
    @endif
    <p>Payment Method: {{ ucfirst(str_replace(',', ', ', $penjualan->payment_method ?? 'cash')) }}</p>
    @php
        $pm = array_filter(array_map('trim', explode(',', $penjualan->payment_method ?? '')));
    @endphp
    @if(count($pm) > 0)
    <p>
        @if(in_array('cash', $pm) && $penjualan->pay_cash > 0)Cash: {{ format_uang($penjualan->pay_cash) }} @endif
        @if(in_array('transfer', $pm) && $penjualan->pay_transfer > 0) | Transfer: {{ format_uang($penjualan->pay_transfer) }} @endif
        @if(in_array('card', $pm) && $penjualan->pay_card > 0) | Card: {{ format_uang($penjualan->pay_card) }} @endif
    </p>
    @elseif($penjualan->pay_cash > 0 || $penjualan->pay_transfer > 0 || $penjualan->pay_card > 0)
    <p>Cash: {{ format_uang($penjualan->pay_cash) }} | Transfer: {{ format_uang($penjualan->pay_transfer) }} | Card: {{ format_uang($penjualan->pay_card) }}</p>
    @endif
    <p class="text-center">===================================</p>
    
    <br>
    <table width="100%" style="border: 0;">
        <tr>
            <td>Price per kg:</td>
            <td class="text-right">{{ format_uang($detail->first()->harga_jual ?? ($detail->first()->produk->harga_jual ?? 0)) }}</td>
        </tr>
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Price:</td>
            <td class="text-right">{{ format_uang($penjualan->total_harga) }}</td>
        </tr>
        <tr>
            <td>Total kg:</td>
            <td class="text-right">{{ number_format(($detail->first()->harga_jual > 0 ? ($penjualan->bayar / $detail->first()->harga_jual) : $detail->sum(function($d){return (float)$d->jumlah;})), 3, '.', ',') }}</td>
        </tr>
        <tr>
            <td>Discount:</td>
                <td class="text-right">{{ format_uang($penjualan->diskon) }}</td>
        </tr>
        <tr>
            <td>Total Pay:</td>
            <td class="text-right">{{ format_uang($penjualan->bayar) }}</td>
        </tr>
        <tr>
            <td>Received:</td>
            <td class="text-right">{{ format_uang($penjualan->diterima) }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- THANK YOU --</p>

    <script>
        function printReceipt() {
            document.title = ' ';
            window.print();
        }

        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>
