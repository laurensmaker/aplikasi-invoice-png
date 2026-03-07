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
                <div class="col-lg-2 mb-3">
                    <label>Weight/pcs (kg)</label>
                    <input type="number" step="0.01" name="weight_kg" id="weight_kg" class="form-control" required>
                </div>

                <div class="col-lg-2 mb-3">
                    <label>Total Weight (kg)</label>
                    <input type="text" id="total_weight_display" class="form-control" readonly>
                    <input type="hidden" name="total_weight" id="total_weight">
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
                    {{-- <td>
                        <form action="{{ route('invoice.item.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Hapus item ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td> --}}
                    {{-- Di dalam <tbody>, ganti bagian <td> Aksi menjadi: --}}
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal(
                            {{ $item->id }},
                            {{ $item->qty }},
                            '{{ addslashes($item->description) }}',
                            {{ $item->weight_kg }},
                            {{ $item->unit_price }},
                            {{ $item->total_price }}
                        )">Edit</button>

                        <form action="{{ route('invoice.item.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Hapus item ini?')" class="d-inline">
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

{{-- ======================== --}}
{{-- MODAL EDIT ITEM          --}}
{{-- ======================== --}}
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editItemForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Qty</label>
                        <input type="number" name="qty" id="edit_qty" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" name="description" id="edit_description" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Unit Price (Rp)</label>
                        <input type="text" id="edit_unit_price_display" class="form-control" required>
                        <input type="hidden" name="unit_price" id="edit_unit_price">
                    </div>

                    <div class="mb-3">
                        <label>Total Price (Rp)</label>
                        <input type="text" id="edit_total_price_display" class="form-control" readonly>
                        <input type="hidden" name="total_price" id="edit_total_price">
                    </div>

                    <div class="mb-3">
                        <label>Weight/pcs (kg)</label>
                        <input type="number" step="0.01" name="weight_kg" id="edit_weight_kg" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Total Weight (kg)</label>
                        <input type="text" id="edit_total_weight_display" class="form-control" readonly>
                        <input type="hidden" name="total_weight" id="edit_total_weight">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ======= TAMBAH ITEM =======
    const qtyInput = document.getElementById('qty');
    const weightKgInput = document.getElementById('weight_kg');
    const unitPriceDisplay = document.getElementById('unit_price_display');
    const unitPriceHidden = document.getElementById('unit_price');
    const totalPriceDisplay = document.getElementById('total_price_display');
    const totalPriceHidden = document.getElementById('total_price');
    const totalWeightDisplay = document.getElementById('total_weight_display');
    const totalWeightHidden = document.getElementById('total_weight');

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function calculateTotal() {
        let qty = parseInt(qtyInput.value) || 0;
        let unitPrice = parseInt(unitPriceHidden.value) || 0;
        let weightKg = parseFloat(weightKgInput.value) || 0;

        let total = qty * unitPrice;
        totalPriceDisplay.value = formatNumber(total);
        totalPriceHidden.value = total;

        let totalWeight = qty * weightKg;
        totalWeightDisplay.value = totalWeight.toFixed(2);
        totalWeightHidden.value = totalWeight.toFixed(2);
    }

    unitPriceDisplay.addEventListener('input', function () {
        let value = this.value.replace(/[^0-9]/g, "");
        this.value = formatNumber(value);
        unitPriceHidden.value = value;
        calculateTotal();
    });

    qtyInput.addEventListener('input', calculateTotal);
    weightKgInput.addEventListener('input', calculateTotal);

    // ======= EDIT ITEM MODAL =======
    function openEditModal(id, qty, description, weightKg, unitPrice, totalPrice) {
        document.getElementById('editItemForm').action = `/invoice/item/${id}`;
        document.getElementById('edit_qty').value = qty;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_weight_kg').value = weightKg;

        // Hitung total weight saat modal dibuka
        let totalWeight = (qty * weightKg).toFixed(2);
        document.getElementById('edit_total_weight_display').value = totalWeight;
        document.getElementById('edit_total_weight').value = totalWeight;

        document.getElementById('edit_unit_price_display').value = formatNumber(unitPrice);
        document.getElementById('edit_unit_price').value = unitPrice;
        document.getElementById('edit_total_price_display').value = formatNumber(totalPrice);
        document.getElementById('edit_total_price').value = totalPrice;

        new bootstrap.Modal(document.getElementById('editItemModal')).show();
    }

    const editQty = document.getElementById('edit_qty');
    const editWeightKg = document.getElementById('edit_weight_kg');
    const editUnitPriceDisplay = document.getElementById('edit_unit_price_display');
    const editUnitPriceHidden = document.getElementById('edit_unit_price');
    const editTotalPriceDisplay = document.getElementById('edit_total_price_display');
    const editTotalPriceHidden = document.getElementById('edit_total_price');
    const editTotalWeightDisplay = document.getElementById('edit_total_weight_display');
    const editTotalWeightHidden = document.getElementById('edit_total_weight');

    function calculateEditTotal() {
        let qty = parseInt(editQty.value) || 0;
        let unitPrice = parseInt(editUnitPriceHidden.value) || 0;
        let weightKg = parseFloat(editWeightKg.value) || 0;

        let total = qty * unitPrice;
        editTotalPriceDisplay.value = formatNumber(total);
        editTotalPriceHidden.value = total;

        let totalWeight = qty * weightKg;
        editTotalWeightDisplay.value = totalWeight.toFixed(2);
        editTotalWeightHidden.value = totalWeight.toFixed(2);
    }

    editUnitPriceDisplay.addEventListener('input', function () {
        let value = this.value.replace(/[^0-9]/g, "");
        this.value = formatNumber(value);
        editUnitPriceHidden.value = value;
        calculateEditTotal();
    });

    editQty.addEventListener('input', calculateEditTotal);
    editWeightKg.addEventListener('input', calculateEditTotal);
</script>

@endsection