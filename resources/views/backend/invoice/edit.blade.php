@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Edit Invoice</h3>
    <a href="{{ route('invoice.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">

        {{-- ============================ --}}
        {{-- FORM EDIT INVOICE HEADER     --}}
        {{-- ============================ --}}
        <h4 class="fs-18 mb-3">Data Invoice</h4>

        <form action="{{ route('invoice.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">

                <div class="col-lg-6 mb-3">
                    <label>Invoice Date</label>
                    <input type="date" name="invoice_date" class="form-control"
                        value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}" required>
                </div>

                <div class="col-lg-6 mb-3">
                    <label>Invoice Number</label>
                    <input type="text" name="invoice_number" class="form-control"
                        value="{{ $invoice->invoice_number }}" required>
                </div>

                <div class="col-lg-6 mb-3">
                    <label>From Company</label>
                    <input type="text" name="from_company" class="form-control"
                        value="{{ $invoice->from_company }}" required>
                </div>

                <div class="col-lg-6 mb-3">
                    <label>From Address</label>
                    <input type="text" name="from_address" class="form-control"
                        value="{{ $invoice->from_address }}" required>
                </div>

                <div class="col-lg-6 mb-3">
                    <label>To Company</label>
                    <input type="text" name="to_company" class="form-control"
                        value="{{ $invoice->to_company }}" required>
                </div>

                <div class="col-lg-6 mb-3">
                    <label>To Address</label>
                    <input type="text" name="to_address" class="form-control"
                        value="{{ $invoice->to_address }}" required>
                </div>

                <div class="col-lg-12 mt-3">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </div>
        </form>

        <hr class="my-4">

        {{-- ========================== --}}
        {{-- FORM TAMBAH ITEM BARU      --}}
        {{-- ========================== --}}
        <h4 class="fs-18 mb-3">Tambah Item</h4>

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

                <div class="col-lg-12 mt-2">
                    <button class="btn btn-primary">+ Tambah Item</button>
                </div>

            </div>
        </form>

        <hr class="my-4">

        {{-- ======================== --}}
        {{-- DAFTAR ITEM              --}}
        {{-- ======================== --}}
        <h5 class="mb-3">Daftar Item</h5>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Qty</th>
                    <th>Description</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->description }}</td>
                    <td>Rp {{ number_format($item->unit_price) }}</td>
                    <td>Rp {{ number_format($item->total_price) }}</td>
                    <td>
                        <form action="{{ route('invoice.item.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Hapus item ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada item</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="4" class="text-end">Grand Total</td>
                    <td>Rp {{ number_format($invoice->items->sum('total_price')) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

    </div>
</div>

<script>
    const qtyInput = document.getElementById('qty');
    const unitPriceDisplay = document.getElementById('unit_price_display');
    const unitPriceHidden = document.getElementById('unit_price');
    const totalPriceDisplay = document.getElementById('total_price_display');
    const totalPriceHidden = document.getElementById('total_price');

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function calculateTotal() {
        let qty = parseInt(qtyInput.value) || 0;
        let unitPrice = parseInt(unitPriceHidden.value) || 0;
        let total = qty * unitPrice;
        totalPriceDisplay.value = formatNumber(total);
        totalPriceHidden.value = total;
    }

    unitPriceDisplay.addEventListener('input', function () {
        let value = this.value.replace(/[^0-9]/g, "");
        this.value = formatNumber(value);
        unitPriceHidden.value = value;
        calculateTotal();
    });

    qtyInput.addEventListener('input', calculateTotal);
</script>

@endsection