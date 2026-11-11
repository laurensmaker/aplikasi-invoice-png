@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Form Invoice</h3>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">

        {{-- ============================ --}}
        {{-- STEP 1 : FORM INVOICE HEADER --}}
        {{-- ============================ --}}
        @if(!isset($invoice))
            <h4 class="fs-18 mb-3">Step 1 — Invoice</h4>

            <form action="{{ route('invoice.storeHeader') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-lg-6 mb-3">
                        <label>Invoice Date</label>
                        <input type="date" name="invoice_date" class="form-control" required>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label>Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" required>
                    </div>

                    {{-- <div class="col-lg-6 mb-3">
                        <label>From Company</label>
                        <select name="from_company" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Perusahaan --</option>
                            <option value="PT. AMASU AMPROYAB PAPUA" selected>PT. AMASU AMPROYAB PAPUA</option>
                            <option value="PT. BINTANG KEJORA">PT. BINTANG KEJORA</option>
                        </select>
                    </div> --}}
                    <input type="hidden" name="from_company" value="PT. AMASU AMPROYAB PAPUA">
                    <input type="hidden" name="from_address" value="HAMADI TANJUNG 2/6 ARGAPURA KOTA JAYAPURA, PAPUA INDONESIA">

                    {{-- <div class="col-lg-6 mb-3">
                        <label>From Address</label>
                        <input type="text" name="from_address" class="form-control" required>
                    </div> --}}

                    <div class="col-lg-6 mb-3">
                        <label>To Company</label>
                        <select name="to_company" class="form-control" required>
                            <option value="" disabled selected>-- Select Company--</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->name }}" {{ old('to_company') == $company->name ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label>To Address</label>
                        <input type="text" name="to_address" class="form-control" required>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <button class="btn btn-primary">Next → Tambah Item</button>
                    </div>

                </div>
            </form>
        @endif



        {{-- ========================== --}}
        {{-- STEP 2 : FORM ITEM INVOICE --}}
        {{-- ========================== --}}
        @if(isset($invoice))

            <h4 class="fs-18 mb-3">Step 2 — Tambah Item</h4>

            <form action="{{ route('invoice.storeItem') }}" method="POST">
                @csrf

                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                <div class="row">

                    <div class="col-lg-2 mb-3">
                        <label>Qty</label>
                        <input type="number" name="qty" id="qty" class="form-control" required>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label>Unit Price (Rp)</label>
                        <input type="text" id="unit_price_display" class="form-control" required>
                        <input type="hidden" name="unit_price" id="unit_price">
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label>Total Price (Rp)</label>
                        <input type="text" id="total_price_display" class="form-control" readonly>
                        <input type="hidden" name="total_price" id="total_price">
                    </div>

                    {{-- FIELD BARU --}}
                    <div class="col-lg-3 mb-3">
                        <label>Weight per Item (kg)</label>
                        <input type="number" step="0.01" name="weight_kg" id="weight_kg" class="form-control" required>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label>Total Weight (kg)</label>
                        <input type="text" id="total_weight_display" class="form-control" readonly>
                        <input type="hidden" name="total_weight" id="total_weight">
                    </div>
                    {{-- END FIELD BARU --}}

                    <div class="col-lg-12 mt-3">
                        <button class="btn btn-primary">Tambah Item</button>
                        <a href="{{ route('invoice.index') }}" class="btn btn-success">Selesai</a>
                    </div>

                </div>
            </form>


    {{-- ======================== --}}
    {{-- DAFTAR ITEM SUDAH ADA --}}
    {{-- ======================== --}}
    <hr>
    <h5 class="mt-4">Daftar Item</h5>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Qty</th>
                <th>Description</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Weight/Item (kg)</th>
                <th>Total Weight (kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ number_format($item->unit_price) }}</td>
                <td>{{ number_format($item->total_price) }}</td>
                <td>{{ (float) $item->weight_kg }}</td>
                <td>{{ $item->total_weight }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endif

    </div>
</div>
<script>
    const qtyInput       = document.getElementById('qty');
    const unitPriceDisplay = document.getElementById('unit_price_display');
    const unitPriceHidden  = document.getElementById('unit_price');
    const totalPriceDisplay = document.getElementById('total_price_display');
    const totalPriceHidden  = document.getElementById('total_price');
    const weightKgInput     = document.getElementById('weight_kg');
    const totalWeightDisplay = document.getElementById('total_weight_display');
    const totalWeightHidden  = document.getElementById('total_weight');

    // FUNGSI FORMAT BARU (Titik untuk ribuan, Koma untuk desimal)
    function formatNumber(num) {
        // Memisahkan bagian bulat dan desimal
        let parts = num.toString().split(".");
        
        // Format bagian bulat dengan titik setiap 3 digit
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
        // Gabungkan kembali dengan koma jika ada desimal
        return parts.join(",");
    }

    function calculateTotal() {
        let qty       = parseFloat(qtyInput.value) || 0;
        // Ambil nilai hidden, ganti koma jadi titik agar bisa di-parse sebagai float
        let unitPrice = parseFloat(unitPriceHidden.value) || 0; 
        let weightKg  = parseFloat(weightKgInput.value) || 0;

        // Hitung total price
        let total = qty * unitPrice;
        totalPriceDisplay.value = formatNumber(total);
        totalPriceHidden.value  = total;

        // Hitung total weight
        let totalWeight = qty * weightKg;
        // Format total weight juga agar konsisten (opsional)
        totalWeightDisplay.value = totalWeight % 1 === 0 ? totalWeight : totalWeight.toFixed(2).replace(".", ",");
        totalWeightHidden.value  = totalWeight;
    }

    unitPriceDisplay.addEventListener('input', function () {
        // 1. Hapus semua karakter selain angka dan koma
        let value = this.value.replace(/[^0-9,]/g, "");
        
        // 2. Pastikan hanya ada satu koma
        let parts = value.split(",");
        if (parts.length > 2) {
            value = parts[0] + "," + parts.slice(1).join("");
        }

        // 3. Format bagian ribuan (sebelum koma)
        let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
        // 4. Gabungkan kembali
        let formattedValue = parts.length > 1 ? integerPart + "," + parts[1] : integerPart;
        
        // 5. Tampilkan ke input
        this.value = formattedValue;

        // 6. Simpan nilai asli (angka) ke hidden input untuk perhitungan
        // Ganti titik (ribuan) dengan string kosong, dan koma dengan titik (desimal)
        let rawValue = value.replace(/\./g, "").replace(",", ".");
        unitPriceHidden.value = rawValue;

        calculateTotal();
    });

    qtyInput.addEventListener('input', calculateTotal);
    weightKgInput.addEventListener('input', calculateTotal);
</script>
@endsection