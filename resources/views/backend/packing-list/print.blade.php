<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing List {{ $packing->packing_number }}</title>
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
            color: #2e7d32;
            letter-spacing: 1px;
        }

        .brand-sub {
            font-size: 11px;
            color: #888;
            margin-top: 2px;
        }

        .packing-title {
            font-size: 28px;
            font-weight: 700;
            color: #2e7d32;
            text-align: right;
            letter-spacing: 2px;
        }

        .packing-meta {
            text-align: right;
            font-size: 12px;
            color: #555;
            margin-top: 5px;
            line-height: 1.8;
        }

        /* ===== DIVIDER ===== */
        .divider {
            border: none;
            border-top: 2px solid #2e7d32;
            margin: 20px 0;
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
            color: #2e7d32;
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
            background-color: #2e7d32;
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
            background-color: #f2f9f2;
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
        .total-row-final {
            background-color: #2e7d32;
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
            margin-top: 60px;
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
    </style>
</head>
<body>
<div class="page">

    {{-- ===== HEADER ===== --}}
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <div class="brand-name">{{ $packing->invoice->from_company }}</div>
                <div class="brand-sub">{{ $packing->invoice->from_address }}</div>
            </td>
            <td style="width: 50%;">
                <div class="packing-title">PACKING LIST</div>
                <div class="packing-meta">
                    <strong>No Packing:</strong> {{ $packing->packing_number }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($packing->packing_date)->format('d F Y') }}<br>
                    {{-- <strong>Ref. Invoice:</strong> {{ $packing->invoice->invoice_number }} --}}
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
                <div class="company-name">{{ $packing->invoice->from_company }}</div>
                <div class="company-address">{{ $packing->invoice->from_address }}</div>
            </td>
            <td style="padding-left: 20px; border-left: 2px solid #e0e0e0;">
                <div class="label-small">TO</div>
                <div class="company-name">{{ $packing->invoice->to_company }}</div>
                <div class="company-address">{{ $packing->invoice->to_address }}</div>
            </td>
        </tr>
    </table>

    {{-- ===== TABEL ITEMS ===== --}}
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">#</th>
                <th class="text-left">Deskripsi</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-center" style="width: 18%;">Berat/unit (kg)</th>
                <th class="text-center" style="width: 18%;">Total Berat (kg)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($packing->invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-center">{{ number_format($item->weight_kg, 2) }}</td>
                    <td class="text-center">{{ number_format($item->total_weight, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="color: #aaa; padding: 20px;">
                        Tidak ada item.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row-final">
                <td colspan="2" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-center">
                    <strong>{{ $packing->invoice->items->sum('qty') }}</strong>
                </td>
                <td></td>
                <td class="text-center">
                    <strong>{{ number_format($packing->invoice->items->sum('total_weight'), 2) }} kg</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- ===== TANDA TANGAN ===== --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
{{--                 
                <td>
                    <div class="signature-line">Diterima Oleh</div>
                    <div class="signature-title">{{ $packing->invoice->to_company }}</div>
                </td> --}}
            </tr>
        </table>
    </div>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        Dokumen ini digenerate secara otomatis pada {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB.
        Packing List No: {{ $packing->packing_number }} | Ref. Invoice: {{ $packing->invoice->invoice_number }}
    </div>

</div>
</body>
</html>