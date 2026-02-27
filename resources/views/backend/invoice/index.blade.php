@extends('backend.layouts.main')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- NOTIFIKASI SESSION --}}
@if(session('success_add'))
    <script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success_add') }}", timer: 2000, showConfirmButton: false });
    </script>
@endif
@if(session('success_update'))
    <script>
    Swal.fire({ icon: 'success', title: 'Data Diperbarui!', text: "{{ session('success_update') }}", timer: 2000, showConfirmButton: false });
    </script>
@endif
@if(session('deleted'))
    <script>
    Swal.fire({ icon: 'success', title: 'Data Terhapus!', text: "{{ session('deleted') }}", timer: 2000, showConfirmButton: false });
    </script>
@endif

<div class="d-sm-flex text-center justify-content-between align-items-center mb-4">
    <h3 class="mb-sm-0 mb-1 fs-18">Data Invoice</h3>
    <ul class="ps-0 mb-0 list-unstyled d-flex justify-content-center">
        <li>
            <a href="index.html" class="text-decoration-none">
                <i class="ri-home-2-line" style="position: relative; top: -1px;"></i>
                <span>Invoice</span>
            </a>
        </li>
        <li>
            <span class="fw-semibold fs-14 heading-font text-dark dot ms-2">Tabel Invoice</span>
        </li>
    </ul>
</div>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-body p-4">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="preview-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-sm-flex text-center justify-content-between align-items-center border-bottom pb-20 mb-20">
                            <h4 class="fw-semibold fs-18 mb-sm-0">Data Invoice</h4>
                            <a href="{{ route('invoice.create') }}"
                                class="border-0 btn btn-primary py-2 px-3 px-sm-4 text-white fs-14 fw-semibold rounded-3">
                                <span class="py-sm-1 d-block">
                                    <i class="ri-add-line text-white"></i>
                                    <span>Invoice Baru</span>
                                </span>
                            </a>
                        </div>

                        <div class="default-table-area members-list">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col">#</th>
                                            <th scope="col">Invoice Date</th>
                                            <th scope="col">Invoice Number</th>
                                            <th scope="col">From Company</th>
                                            <th scope="col">From Address</th>
                                            <th scope="col">To Company</th>
                                            <th scope="col">To Address</th>
                                            <th scope="col">Items</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $index => $item)
                                            <tr class="text-center">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->invoice_date)->format('d M Y') }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $item->invoice_number }}</span>
                                                </td>
                                                <td>{{ $item->from_company }}</td>
                                                <td>{{ $item->from_address }}</td>
                                                <td>{{ $item->to_company }}</td>
                                                <td>{{ $item->to_address }}</td>
                                                <td>
                                                    <span class="badge bg-info text-dark">
                                                        {{ $item->items->count() }} item
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-1">

                                                        {{-- TOMBOL LIHAT DETAIL --}}
                                                        <button
                                                            class="btn btn-info btn-sm text-white btn-detail"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetail"
                                                            data-id="{{ $item->id }}"
                                                            data-invoice-number="{{ $item->invoice_number }}"
                                                            data-invoice-date="{{ \Carbon\Carbon::parse($item->invoice_date)->format('d M Y') }}"
                                                            data-from-company="{{ $item->from_company }}"
                                                            data-from-address="{{ $item->from_address }}"
                                                            data-to-company="{{ $item->to_company }}"
                                                            data-to-address="{{ $item->to_address }}"
                                                            data-items="{{ $item->items->toJson() }}">  {{-- ← ini sudah benar, tinggal JS-nya --}}
                                                            <i data-feather="eye"></i> Detail
                                                        </button>

                                                        <button
                                                            class="btn btn-secondary btn-sm text-white btn-packing"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalPackingList"
                                                            data-invoice-id="{{ $item->id }}"
                                                            data-invoice-number="{{ $item->invoice_number }}">
                                                            <i data-feather="package"></i> <br> Buat Packing List
                                                        </button>

                                                        <a href="{{ route('invoice.print', $item->id) }}"
                                                        class="btn btn-success btn-sm text-white"
                                                        target="_blank">
                                                            <i data-feather="printer"></i> Cetak
                                                        </a>

                                                        {{-- TOMBOL EDIT --}}
                                                        <a class="btn btn-warning btn-sm text-white"
                                                            href="{{ route('invoice.edit', $item->id) }}">
                                                            <i data-feather="edit-3" ></i> Edit
                                                        </a>

                                                        {{-- TOMBOL HAPUS --}}
                                                        {{-- <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                            data-id="{{ $item->id }}"
                                                            data-nama="{{ $item->invoice_number }}">
                                                            <i data-feather="trash-2"></i> Hapus
                                                        </a>
                                                        <form id="form-delete-{{ $item->id }}"
                                                            action="{{ route('invoice.destroy', $item->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL DETAIL INVOICE ===================== --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header" style="background: linear-gradient(135deg, #1a73e8, #0d47a1);">
                <h5 class="modal-title text-white" id="modalDetailLabel">
                    <i class="ri-file-text-line me-2"></i>
                    Detail Invoice — <span id="modal-invoice-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                {{-- INFO FROM & TO --}}
                <div class="row mb-4">
                    {{-- FROM --}}
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <p class="text-muted small mb-1">FROM</p>
                                <h6 class="fw-bold" id="modal-from-company">-</h6>
                                <p class="text-muted mb-0 small" id="modal-from-address">-</p>
                            </div>
                        </div>
                    </div>
                    {{-- TO --}}
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <p class="text-muted small mb-1">TO</p>
                                <h6 class="fw-bold" id="modal-to-company">-</h6>
                                <p class="text-muted mb-0 small" id="modal-to-address">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TANGGAL --}}
                <div class="mb-3">
                    <span class="text-muted small">Tanggal Invoice:</span>
                    <span class="fw-semibold ms-2" id="modal-invoice-date">-</span>
                </div>

                <hr>

                {{-- TABEL ITEMS --}}
                <h6 class="fw-semibold mb-3">
                    <i class="ri-list-check me-1 text-primary"></i> Daftar Item
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                       <thead style="background-color: #e8f0fe;">
                            <tr class="text-center">
                                <th width="5%">#</th>
                                <th>Deskripsi</th>      {{-- description --}}
                                <th width="10%">Qty</th>  {{-- qty --}}
                                <th width="20%">Harga Satuan</th>  {{-- unit_price --}}
                                <th width="20%">Total</th>  {{-- total_price --}}
                            </tr>
                        </thead>
                        <tbody id="tbody-items">
                            {{-- Diisi JavaScript --}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Grand Total</td>
                                <td class="text-end fw-bold text-primary" id="grand-total">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Jika tidak ada item --}}
                <div id="no-items-info" class="alert alert-warning d-none">
                    <i class="ri-information-line me-1"></i> Belum ada item pada invoice ini.
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Tutup
                </button>
                {{-- Tombol cetak dari modal --}}
                {{-- <a id="btn-print-from-modal" href="#" class="btn btn-success text-white" target="_blank">
                    <i class="ri-printer-line me-1"></i> Cetak PDF
                </a> --}}
                <a id="btn-edit-from-modal" href="#" class="btn btn-warning text-white">
                    <i class="ri-edit-line me-1"></i> Edit Invoice
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL PACKING LIST ===================== --}}
<div class="modal fade" id="modalPackingList" tabindex="-1" aria-labelledby="modalPackingListLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header" style="background: linear-gradient(135deg, #2e7d32, #1b5e20);">
                <h5 class="modal-title text-white" id="modalPackingListLabel">
                    <i class="ri-archive-line me-2"></i>
                    Buat Packing List — <span id="modal-packing-invoice-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                {{-- INFO INVOICE --}}
                <div class="alert alert-info mb-3">
                    <i class="ri-information-line me-1"></i>
                    Membuat packing list untuk Invoice: <strong id="modal-packing-invoice-number"></strong>
                </div>

                {{-- PACKING DATE --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Packing Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="packing-date">
                </div>

                {{-- PACKING NUMBER --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Packing Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="packing-number" placeholder="Contoh: PL-2024-001">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-success text-white" id="btn-simpan-packing">
                    <i class="ri-save-line me-1"></i> Simpan Packing List
                </button>
            </div>

        </div>
    </div>
</div>

<form id="form-packing" action="{{ route('packinglist.store') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="invoice_id" id="form-invoice-id">
    <input type="hidden" name="packing_date" id="form-packing-date">
    <input type="hidden" name="packing_number" id="form-packing-number">
</form>

<script>
// ======== BUKA MODAL DETAIL ========
document.querySelectorAll('.btn-detail').forEach(button => {
    button.addEventListener('click', function () {
        const invoiceNumber = this.dataset.invoiceNumber;
        const invoiceDate   = this.dataset.invoiceDate;
        const fromCompany   = this.dataset.fromCompany;
        const fromAddress   = this.dataset.fromAddress;
        const toCompany     = this.dataset.toCompany;
        const toAddress     = this.dataset.toAddress;
        const id            = this.dataset.id;
        const items         = JSON.parse(this.dataset.items || '[]');

        // Isi info header
        document.getElementById('modal-invoice-number').textContent = invoiceNumber;
        document.getElementById('modal-invoice-date').textContent   = invoiceDate;
        document.getElementById('modal-from-company').textContent   = fromCompany;
        document.getElementById('modal-from-address').textContent   = fromAddress;
        document.getElementById('modal-to-company').textContent     = toCompany;
        document.getElementById('modal-to-address').textContent     = toAddress;

        // Link tombol edit
        document.getElementById('btn-edit-from-modal').href = `/invoice/${id}/edit`;

        // Render tabel items
        const tbody   = document.getElementById('tbody-items');
        const noItems = document.getElementById('no-items-info');
        tbody.innerHTML = '';

        if (items.length === 0) {
            noItems.classList.remove('d-none');
            document.getElementById('grand-total').textContent = formatRupiah(0);
            return;
        }

        noItems.classList.add('d-none');
        let grandTotal = 0;

        items.forEach((item, index) => {
            // ✅ Sesuai field migration: qty, description, unit_price, total_price
            const qty        = item.qty ?? 0;
            const desc       = item.description ?? '-';
            const unitPrice  = parseFloat(item.unit_price) ?? 0;
            const totalPrice = parseFloat(item.total_price) ?? 0;

            grandTotal += totalPrice;

            tbody.innerHTML += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${desc}</td>
                    <td class="text-center">${qty}</td>
                    <td class="text-end">${formatRupiah(unitPrice)}</td>
                    <td class="text-end fw-semibold">${formatRupiah(totalPrice)}</td>
                </tr>
            `;
        });

        document.getElementById('grand-total').textContent = formatRupiah(grandTotal);
    });
});

// ======== FORMAT RUPIAH ========
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}



// ======== HAPUS DATA ========
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const id   = this.dataset.id;
        const nama = this.dataset.nama;

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Invoice: " + nama,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        });
    });
});

// ======== BUKA MODAL PACKING LIST ========
document.querySelectorAll('.btn-packing').forEach(button => {
    button.addEventListener('click', function () {
        const invoiceNumber = this.dataset.invoiceNumber;
        const items         = JSON.parse(this.dataset.items || '[]');

        // Set judul modal
        document.getElementById('modal-packing-invoice-number').textContent = invoiceNumber;

        // Reset form
        document.getElementById('packing-date').value   = '';
        document.getElementById('packing-number').value = '';

        // Isi dropdown item
        const select = document.getElementById('select-invoice-item');
        select.innerHTML = '<option value="">-- Pilih Item --</option>';

        items.forEach(item => {
            const option = document.createElement('option');
            option.value       = item.id;
            option.textContent = `${item.description} (Qty: ${item.qty})`;
            select.appendChild(option);
        });
    });
});

// ======== BUKA MODAL PACKING LIST ========
document.querySelectorAll('.btn-packing').forEach(button => {
    button.addEventListener('click', function () {
        const invoiceId     = this.dataset.invoiceId;
        const invoiceNumber = this.dataset.invoiceNumber;

        // Set judul modal
        document.getElementById('modal-packing-invoice-number').textContent = invoiceNumber;

        // Simpan invoice id ke tombol simpan sebagai data attribute
        document.getElementById('btn-simpan-packing').dataset.invoiceId = invoiceId;

        // Reset form
        document.getElementById('packing-date').value   = '';
        document.getElementById('packing-number').value = '';
    });
});

// ======== SIMPAN PACKING LIST ========
document.getElementById('btn-simpan-packing').addEventListener('click', function () {
    const invoiceId     = this.dataset.invoiceId;
    const packingDate   = document.getElementById('packing-date').value;
    const packingNumber = document.getElementById('packing-number').value;

    // Validasi
    if (!packingDate) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Tanggal packing wajib diisi.' });
        return;
    }
    if (!packingNumber.trim()) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Nomor packing wajib diisi.' });
        return;
    }

    // Isi form dan submit
    document.getElementById('form-invoice-id').value    = invoiceId;
    document.getElementById('form-packing-date').value  = packingDate;
    document.getElementById('form-packing-number').value = packingNumber;
    document.getElementById('form-packing').submit();
});
</script>
@endsection