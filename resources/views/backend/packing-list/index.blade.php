@extends('backend.layouts.main')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success_add'))
    <script>Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success_add') }}", timer: 2000, showConfirmButton: false });</script>
@endif
@if(session('deleted'))
    <script>Swal.fire({ icon: 'success', title: 'Terhapus!', text: "{{ session('deleted') }}", timer: 2000, showConfirmButton: false });</script>
@endif

<div class="d-sm-flex text-center justify-content-between align-items-center mb-4">
    <h3 class="mb-sm-0 mb-1 fs-18">Data Packing List</h3>
    <ul class="ps-0 mb-0 list-unstyled d-flex justify-content-center">
        <li>
            <a href="#" class="text-decoration-none">
                <i class="ri-home-2-line" style="position: relative; top: -1px;"></i>
                <span>Home</span>
            </a>
        </li>
        <li>
            <span class="fw-semibold fs-14 heading-font text-dark dot ms-2">Packing List</span>
        </li>
    </ul>
</div>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-body p-4">

                <div class="d-sm-flex text-center justify-content-between align-items-center border-bottom pb-20 mb-20">
                    <h4 class="fw-semibold fs-18 mb-sm-0">Data Packing List</h4>
                    {{-- <a href="{{ route('packing-list.create') }}"
                        class="border-0 btn btn-primary py-2 px-3 px-sm-4 text-white fs-14 fw-semibold rounded-3">
                        <span class="py-sm-1 d-block">
                            <i class="ri-add-line text-white"></i>
                            <span>Packing List Baru</span>
                        </span>
                    </a> --}}
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Packing Date</th>
                                <th>Packing Number</th>
                                <th>Total Item</th>
                                <th>Total Weight</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($packingLists as $index => $packing)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($packing->packing_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $packing->packing_number }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $packing->invoice->items->count() ?? 0 }} item
                                        </span>
                                    </td>                                    
                                    <td>
                                        <span class="fw-semibold">
                                            {{ number_format($packing->invoice->items->sum('total_weight'), 2) }} kg
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 flex-wrap">

                                            {{-- TOMBOL DETAIL --}}
                                            <button class="btn btn-info btn-sm text-white btn-detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetail"
                                                data-id="{{ $packing->id }}"
                                                data-packing-number="{{ $packing->packing_number }}"
                                                data-packing-date="{{ \Carbon\Carbon::parse($packing->packing_date)->format('d M Y') }}"
                                                data-invoice-number="{{ $packing->invoice->invoice_number ?? '-' }}"
                                                data-from-company="{{ $packing->invoice->from_company ?? '-' }}"
                                                data-from-address="{{ $packing->invoice->from_address ?? '-' }}"
                                                data-to-company="{{ $packing->invoice->to_company ?? '-' }}"
                                                data-to-address="{{ $packing->invoice->to_address ?? '-' }}"
                                                data-items="{{ $packing->invoice->items->toJson() ?? '[]' }}">
                                                <i data-feather="eye"></i> Detail
                                            </button>

                                            {{-- TOMBOL CETAK --}}
                                            <a href="{{ route('packing-list.print', $packing->id) }}"
                                                class="btn btn-success btn-sm text-white" target="_blank">
                                                <i data-feather="printer"></i> Cetak
                                            </a>

                                            {{-- TOMBOL EDIT --}}
                                            {{-- <a href="{{ route('packing-list.edit', $packing->id) }}"
                                                class="btn btn-warning btn-sm text-white">
                                                <i data-feather="edit-3"></i> Edit
                                            </a> --}}

                                            {{-- TOMBOL HAPUS --}}
                                            <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $packing->id }}"
                                                data-nama="{{ $packing->packing_number }}">
                                                <i data-feather="trash-2"></i> Hapus
                                            </a>
                                            <form id="form-delete-{{ $packing->id }}"
                                                action="{{ route('packing-list.destroy', $packing->id) }}"
                                                method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="ri-archive-line fs-24"></i>
                                        <p class="mt-2">Belum ada data packing list.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL DETAIL PACKING LIST ===================== --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header" style="background: linear-gradient(135deg, #2e7d32, #1b5e20);">
                <h5 class="modal-title text-white" id="modalDetailLabel">
                    <i class="ri-archive-line me-2"></i>
                    Packing List — <span id="modal-packing-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                {{-- INFO HEADER --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <span class="text-muted small">No. Packing:</span>
                        <div class="fw-semibold text-success" id="modal-packing-number-info">-</div>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted small">No. Invoice:</span>
                        <div class="fw-semibold text-primary" id="modal-invoice-number">-</div>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted small">Tanggal Packing:</span>
                        <div class="fw-semibold" id="modal-packing-date">-</div>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted small">Total Barang:</span>
                        <div><span class="badge bg-info text-dark fs-12" id="modal-total-item">0 item</span></div>
                    </div>
                </div>

                {{-- FROM & TO --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <p class="text-muted small mb-1 fw-semibold">FROM</p>
                                <h6 class="fw-bold" id="modal-from-company">-</h6>
                                <p class="text-muted mb-0 small" id="modal-from-address">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <p class="text-muted small mb-1 fw-semibold">TO</p>
                                <h6 class="fw-bold" id="modal-to-company">-</h6>
                                <p class="text-muted mb-0 small" id="modal-to-address">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- TABEL ITEMS --}}
                <h6 class="fw-semibold mb-3">
                    <i class="ri-list-check me-1 text-success"></i> Daftar Barang
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead style="background-color: #e8f5e9;">
                            <tr class="text-center">
                                <th width="5%">#</th>
                                <th>Deskripsi Barang</th>
                                <th width="10%">Qty</th>
                                <th width="15%">Harga Satuan</th>
                                <th width="15%">Total Harga</th>
                                <th width="12%">Berat/unit (kg)</th>
                                <th width="12%">Total Berat (kg)</th>
                                {{-- <th width="10%">Cek</th> --}}
                            </tr>
                        </thead>
                        <tbody id="tbody-packing-items"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total</td>
                                <td class="text-center fw-bold text-success" id="modal-total-qty">0</td>
                                <td colspan="2" class="text-end fw-bold text-primary" id="modal-grand-total">Rp 0</td>
                                <td></td>
                                <td class="text-center fw-bold text-danger" id="modal-total-weight">0 kg</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="no-items-alert" class="alert alert-warning d-none">
                    <i class="ri-information-line me-1"></i> Belum ada item pada packing list ini.
                </div>

                {{-- CATATAN --}}
                <div class="mt-3 p-3 border rounded bg-light">
                    <p class="fw-semibold mb-1 small text-muted">CATATAN PENGIRIMAN:</p>
                    <p class="mb-0 small text-muted fst-italic">
                        Harap periksa kondisi barang saat diterima. Kerusakan setelah pengiriman bukan tanggung jawab pengirim.
                    </p>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Tutup
                </button>
                <a id="btn-print-from-modal" href="#" class="btn btn-success text-white" target="_blank">
                    <i class="ri-printer-line me-1"></i> Cetak Packing List
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// ======== BUKA MODAL DETAIL ========
document.querySelectorAll('.btn-detail').forEach(button => {
    button.addEventListener('click', function () {
        const id            = this.dataset.id;
        const packingNumber = this.dataset.packingNumber;
        const packingDate   = this.dataset.packingDate;
        const invoiceNumber = this.dataset.invoiceNumber;
        const fromCompany   = this.dataset.fromCompany;
        const fromAddress   = this.dataset.fromAddress;
        const toCompany     = this.dataset.toCompany;
        const toAddress     = this.dataset.toAddress;
        const items         = JSON.parse(this.dataset.items || '[]');

        // Isi header
        document.getElementById('modal-packing-number').textContent      = packingNumber;
        document.getElementById('modal-packing-number-info').textContent = packingNumber;
        document.getElementById('modal-packing-date').textContent        = packingDate;
        document.getElementById('modal-invoice-number').textContent      = invoiceNumber;
        document.getElementById('modal-from-company').textContent        = fromCompany;
        document.getElementById('modal-from-address').textContent        = fromAddress;
        document.getElementById('modal-to-company').textContent          = toCompany;
        document.getElementById('modal-to-address').textContent          = toAddress;

        // Link cetak
        document.getElementById('btn-print-from-modal').href = `/packing-list/${id}/print`;

        const tbody      = document.getElementById('tbody-packing-items');
        const noAlert    = document.getElementById('no-items-alert');
        tbody.innerHTML  = '';

        if (items.length === 0) {
            noAlert.classList.remove('d-none');
            document.getElementById('modal-total-item').textContent   = '0 item';
            document.getElementById('modal-total-qty').textContent    = '0';
            document.getElementById('modal-grand-total').textContent  = formatRupiah(0);
            document.getElementById('modal-total-weight').textContent = '0 kg';
            return;
        }

        noAlert.classList.add('d-none');

        let totalQty    = 0;
        let grandTotal  = 0;
        let totalWeight = 0;

        items.forEach((item, index) => {
            const qty         = parseInt(item.qty) || 0;
            const desc        = item.description ?? '-';
            const unitPrice   = parseFloat(item.unit_price) || 0;
            const totalPrice  = parseFloat(item.total_price) || 0;
            const weightKg    = parseFloat(item.weight_kg) || 0;      // field dari migration
            const totalW      = parseFloat(item.total_weight) || 0;   // field dari migration

            totalQty    += qty;
            grandTotal  += totalPrice;
            totalWeight += totalW;

            tbody.innerHTML += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${desc}</td>
                    <td class="text-center fw-semibold">${qty}</td>
                    <td class="text-end">${formatRupiah(unitPrice)}</td>
                    <td class="text-end fw-semibold">${formatRupiah(totalPrice)}</td>
                    <td class="text-center">${weightKg} kg</td>
                    <td class="text-center fw-semibold text-danger">${totalW} kg</td>
                    <td class="text-center">
                        <input class="form-check-input" type="checkbox" style="width:20px;height:20px;">
                    </td>
                </tr>
            `;
        });

        document.getElementById('modal-total-item').textContent   = items.length + ' item';
        document.getElementById('modal-total-qty').textContent    = totalQty;
        document.getElementById('modal-grand-total').textContent  = formatRupiah(grandTotal);
        document.getElementById('modal-total-weight').textContent = totalWeight.toFixed(2) + ' kg';
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
            text: 'Packing List: ' + nama,
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
</script>
@endsection