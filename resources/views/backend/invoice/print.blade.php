<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #2d2d2d;
            background: #fff;
        }

        .page {
            padding: 40px 50px;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex; /* DomPDF pakai table trick */
            margin-bottom: 30px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 700;
            color: #1a73e8;
            letter-spacing: 1px;
        }

        .brand-sub {
            font-size: 11px;
            color: #888;
            margin-top: 2px;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a73e8;
            text-align: right;
            letter-spacing: 2px;
        }

        .invoice-meta {
            text-align: right;
            font-size: 12px;
            color: #555;
            margin-top: 5px;
            line-height: 1.8;
        }

        /* ===== DIVIDER ===== */
        .divider {
            border: none;
            border-top: 2px solid #1a73e8;
            margin: 20px 0;
        }

        .divider-light {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 15px 0;
        }

        /* ===== FROM TO ===== */
        .from-to-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .from-to-table td {
            vertical-align: top;
            width: 50%;
            padding: 0;
        }

        .label-small {
            font-size: 10px;
            font-weight: 700;
            color: #1a73e8;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .company-name {
            font-size: 15px;
            font-weight: 700;
            color: #1d1d1d;
            margin-bottom: 3px;
        }

        .company-address {
            font-size: 12px;
            color: #666;
            line-height: 1.6;
        }

        /* ===== TABEL ITEMS ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table thead tr {
            background-color: #1a73e8;
            color: #fff;
        }

        .items-table thead th {
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f7f9ff;
        }

        .items-table tbody td {
            padding: 10px 12px;
            font-size: 13px;
            color: #333;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-left   { text-align: left; }

        /* ===== TOTAL ===== */
        .total-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .total-table td {
            padding: 5px 12px;
            font-size: 13px;
        }

        .total-row-final {
            background-color: #1a73e8;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
        }

        .total-row-final td {
            padding: 10px 12px;
        }

        /* ===== TANDA TANGAN ===== */
        .signature-section {
            margin-top: 50px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 33%;
            text-align: center;
            padding: 0 10px;
            vertical-align: bottom;
        }

        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 60px; /* ruang untuk tanda tangan */
            font-size: 12px;
            color: #444;
        }

        .signature-title {
            font-size: 11px;
            color: #888;
            margin-top: 3px;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        /* ===== BADGE STATUS ===== */
        .badge-paid {
            display: inline-block;
            background: #e6f4ea;
            color: #1e7e34;
            border: 1px solid #b2dfdb;
            border-radius: 4px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ===== HEADER ===== --}}
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <div class="brand-name">{{ $invoice->from_company }}</div>
                <div class="brand-sub">{{ $invoice->from_address }}</div>
            </td>
            <td style="width: 50%;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    <strong>No Invoice:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- ===== FROM & TO ===== --}}
    <table class="from-to-table">
        <tr>
            <td style="padding-right: 20px;">
                <div class="label-small">FROM</div>
                <div class="company-name">{{ $invoice->from_company }}</div>
                <div class="company-address">{{ $invoice->from_address }}</div>
            </td>
            <td style="padding-left: 20px; border-left: 2px solid #e0e0e0;">
                <div class="label-small">TO</div>
                <div class="company-name">{{ $invoice->to_company }}</div>
                <div class="company-address">{{ $invoice->to_address }}</div>
            </td>
        </tr>
    </table>

    {{-- ===== TABEL ITEMS ===== --}}
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">#</th>
                <th class="text-left">Deskripsi</th>
                <th class="text-center" style="width: 8%;">Qty</th>
                <th class="text-right" style="width: 20%;">Harga Satuan</th>
                <th class="text-right" style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="color: #aaa; padding: 20px;">
                        Tidak ada item.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===== GRAND TOTAL ===== --}}
    <table class="total-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 12px; color: #555;">Subtotal</td>
                        <td class="text-right" style="padding: 6px 12px; color: #333;">
                            Rp {{ number_format($invoice->items->sum('total_price'), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="total-row-final">
                        <td><strong>GRAND TOTAL</strong></td>
                        <td class="text-right">
                            <strong>Rp {{ number_format($invoice->total_amount ?? $invoice->items->sum('total_price'), 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== TANDA TANGAN ===== --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
               
                {{-- <td>
                    <div class="signature-line">Diterima Oleh</div>
                    <div class="signature-title">{{ $invoice->to_company }}</div>
                </td> --}}
            </tr>
        </table>
    </div>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        Dokumen ini digenerate secara otomatis pada {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB.
        Invoice No: {{ $invoice->invoice_number }}
    </div>

</div>
</body>
</html>