@extends('backend.layouts.main')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- NOTIFIKASI SESSION --}}
@if(session('success_add'))
    <script>Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success_add') }}", timer: 2000, showConfirmButton: false });</script>
@endif
@if(session('success_update'))
    <script>Swal.fire({ icon: 'success', title: 'Data Diperbarui!', text: "{{ session('success_update') }}", timer: 2000, showConfirmButton: false });</script>
@endif
@if(session('deleted'))
    <script>Swal.fire({ icon: 'success', title: 'Data Terhapus!', text: "{{ session('deleted') }}", timer: 2000, showConfirmButton: false });</script>
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
                                            {{-- <th scope="col">To Address</th> --}}
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
                                                {{-- <td>{{ $item->to_address }}</td> --}}
                                                <td>
                                                    <span class="badge bg-info text-dark">
                                                        {{ $item->items->count() }} item
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">

                                                        {{-- TOMBOL DETAIL --}}
                                                        <button
                                                            class="btn btn-info btn-sm text-white btn-detail"
                                                            style="font-size: 11px; padding: 3px 7px;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetail"
                                                            data-id="{{ $item->id }}"
                                                            data-invoice-number="{{ $item->invoice_number }}"
                                                            data-invoice-date="{{ \Carbon\Carbon::parse($item->invoice_date)->format('d M Y') }}"
                                                            data-from-company="{{ $item->from_company }}"
                                                            data-from-address="{{ $item->from_address }}"
                                                            data-to-company="{{ $item->to_company }}"
                                                            data-to-address="{{ $item->to_address }}"
                                                            data-items="{{ $item->items->toJson() }}">
                                                            <i data-feather="eye" style="width:12px; height:12px;"></i> Detail
                                                        </button>

                                                        {{-- TOMBOL BUAT PACKING LIST --}}
                                                        <button
                                                            class="btn btn-secondary btn-sm text-white btn-packing"
                                                            style="font-size: 11px; padding: 3px 7px;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalPackingList"
                                                            data-invoice-id="{{ $item->id }}"
                                                            data-invoice-number="{{ $item->invoice_number }}">
                                                            <i data-feather="package" style="width:12px; height:12px;"></i> Packing List
                                                        </button>

                                                        {{-- TOMBOL BUAT SURAT JALAN --}}
                                                        <button
                                                            class="btn btn-dark btn-sm text-white btn-surat-jalan"
                                                            style="font-size: 11px; padding: 3px 7px;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalSuratJalan"
                                                            data-invoice-id="{{ $item->id }}"
                                                            data-invoice-number="{{ $item->invoice_number }}">
                                                            <i data-feather="truck" style="width:12px; height:12px;"></i> Surat Jalan
                                                        </button>

                                                        {{-- TOMBOL CETAK --}}
                                                        <a href="{{ route('invoice.print', $item->id) }}"
                                                            class="btn btn-success btn-sm text-white"
                                                            style="font-size: 11px; padding: 3px 7px;"
                                                            target="_blank">
                                                            <i data-feather="printer" style="width:12px; height:12px;"></i> Cetak
                                                        </a>

                                                        {{-- TOMBOL EDIT --}}
                                                        <a class="btn btn-warning btn-sm text-white"
                                                            style="font-size: 11px; padding: 3px 7px;"
                                                            href="{{ route('invoice.edit', $item->id) }}">
                                                            <i data-feather="edit-3" style="width:12px; height:12px;"></i> Edit
                                                        </a>

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
            <div class="modal-header" style="background: linear-gradient(135deg, #1a73e8, #0d47a1);">
                <h5 class="modal-title text-white" id="modalDetailLabel">
                    <i class="ri-file-text-line me-2"></i>
                    Detail Invoice — <span id="modal-invoice-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <p class="text-muted small mb-1">FROM</p>
                                <h6 class="fw-bold" id="modal-from-company">-</h6>
                                <p class="text-muted mb-0 small" id="modal-from-address">-</p>
                            </div>
                        </div>
                    </div>
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
                <div class="mb-3">
                    <span class="text-muted small">Tanggal Invoice:</span>
                    <span class="fw-semibold ms-2" id="modal-invoice-date">-</span>
                </div>
                <hr>
                <h6 class="fw-semibold mb-3">
                    <i class="ri-list-check me-1 text-primary"></i> Daftar Item
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead style="background-color: #e8f0fe;">
                            <tr class="text-center">
                                <th width="5%">#</th>
                                <th>Deskripsi</th>
                                <th width="10%">Qty</th>
                                <th width="20%">Harga Satuan</th>
                                <th width="20%">Total</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-items"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Grand Total</td>
                                <td class="text-end fw-bold text-primary" id="grand-total">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="no-items-info" class="alert alert-warning d-none">
                    <i class="ri-information-line me-1"></i> Belum ada item pada invoice ini.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Tutup
                </button>
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
            <div class="modal-header" style="background: linear-gradient(135deg, #2e7d32, #1b5e20);">
                <h5 class="modal-title text-white" id="modalPackingListLabel">
                    <i class="ri-archive-line me-2"></i>
                    Buat Packing List — <span id="modal-packing-invoice-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info mb-3">
                    <i class="ri-information-line me-1"></i>
                    Membuat packing list untuk Invoice: <strong id="modal-packing-invoice-number-info"></strong>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Packing Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="packing-date">
                </div>
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

{{-- ===================== MODAL SURAT JALAN ===================== --}}
<div class="modal fade" id="modalSuratJalan" tabindex="-1" aria-labelledby="modalSuratJalanLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header" style="background: linear-gradient(135deg, #37474f, #263238);">
                <h5 class="modal-title text-white" id="modalSuratJalanLabel">
                    <i class="ri-truck-line me-2"></i>
                    Buat Surat Jalan — <span id="modal-sj-invoice-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <div class="alert alert-info mb-3">
                    <i class="ri-information-line me-1"></i>
                    Membuat surat jalan untuk Invoice: <strong id="modal-sj-invoice-number-info"></strong>
                </div>

                {{-- SURAT JALAN NUMBER --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Surat Jalan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sj-number" placeholder="Contoh: SJ-2024-001">
                </div>

                {{-- NOMOR PEB --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor PEB <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sj-nomor-peb" placeholder="Contoh: PEB-2024-001">
                </div>

                {{-- TANGGAL PEB --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal PEB <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="sj-tanggal-peb">
                </div>

                {{-- NOMOR POLISI --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Polisi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sj-nomor-polisi" placeholder="Contoh: B 1234 ABC">
                </div>

                {{-- NAMA SUPIR --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Supir <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sj-nama-supir" placeholder="Nama lengkap supir">
                </div>

                {{-- JENIS KENDARAAN --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sj-jenis-kendaraan" placeholder="Contoh: Truk Box, Pickup, dll">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-dark text-white" id="btn-simpan-sj">
                    <i class="ri-save-line me-1"></i> Simpan Surat Jalan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- FORM HIDDEN PACKING LIST --}}
<form id="form-packing" action="{{ route('packinglist.store') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="invoice_id" id="form-invoice-id">
    <input type="hidden" name="packing_date" id="form-packing-date">
    <input type="hidden" name="packing_number" id="form-packing-number">
</form>

{{-- FORM HIDDEN SURAT JALAN --}}
<form id="form-surat-jalan" action="{{ route('surat-jalan.store') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="invoice_id" id="form-sj-invoice-id">
    <input type="hidden" name="surat_jalan_number" id="form-sj-number">
    <input type="hidden" name="nomor_peb" id="form-sj-nomor-peb">
    <input type="hidden" name="tanggal_peb" id="form-sj-tanggal-peb">
    <input type="hidden" name="nomor_polisi" id="form-sj-nomor-polisi">
    <input type="hidden" name="nama_supir" id="form-sj-nama-supir">
    <input type="hidden" name="jenis_kendaraan" id="form-sj-jenis-kendaraan">
</form>

<script>
// ======== MODAL DETAIL ========
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

        document.getElementById('modal-invoice-number').textContent = invoiceNumber;
        document.getElementById('modal-invoice-date').textContent   = invoiceDate;
        document.getElementById('modal-from-company').textContent   = fromCompany;
        document.getElementById('modal-from-address').textContent   = fromAddress;
        document.getElementById('modal-to-company').textContent     = toCompany;
        document.getElementById('modal-to-address').textContent     = toAddress;
        document.getElementById('btn-edit-from-modal').href         = `/invoice/${id}/edit`;

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
            const qty        = item.qty ?? 0;
            const desc       = item.description ?? '-';
            const unitPrice  = parseFloat(item.unit_price) || 0;
            const totalPrice = parseFloat(item.total_price) || 0;
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

// ======== MODAL PACKING LIST ========
document.querySelectorAll('.btn-packing').forEach(button => {
    button.addEventListener('click', function () {
        const invoiceId     = this.dataset.invoiceId;
        const invoiceNumber = this.dataset.invoiceNumber;

        document.getElementById('modal-packing-invoice-number').textContent      = invoiceNumber;
        document.getElementById('modal-packing-invoice-number-info').textContent = invoiceNumber;
        document.getElementById('btn-simpan-packing').dataset.invoiceId          = invoiceId;

        document.getElementById('packing-date').value   = '';
        document.getElementById('packing-number').value = '';
    });
});

// ======== SIMPAN PACKING LIST ========
document.getElementById('btn-simpan-packing').addEventListener('click', function () {
    const invoiceId     = this.dataset.invoiceId;
    const packingDate   = document.getElementById('packing-date').value;
    const packingNumber = document.getElementById('packing-number').value;

    if (!packingDate) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Tanggal packing wajib diisi.' });
        return;
    }
    if (!packingNumber.trim()) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Nomor packing wajib diisi.' });
        return;
    }

    document.getElementById('form-invoice-id').value     = invoiceId;
    document.getElementById('form-packing-date').value   = packingDate;
    document.getElementById('form-packing-number').value = packingNumber;
    document.getElementById('form-packing').submit();
});

// ======== MODAL SURAT JALAN ========
document.querySelectorAll('.btn-surat-jalan').forEach(button => {
    button.addEventListener('click', function () {
        const invoiceId     = this.dataset.invoiceId;
        const invoiceNumber = this.dataset.invoiceNumber;

        document.getElementById('modal-sj-invoice-number').textContent      = invoiceNumber;
        document.getElementById('modal-sj-invoice-number-info').textContent = invoiceNumber;
        document.getElementById('btn-simpan-sj').dataset.invoiceId          = invoiceId;

        // Reset semua field
        document.getElementById('sj-number').value          = '';
        document.getElementById('sj-nomor-peb').value       = '';
        document.getElementById('sj-tanggal-peb').value     = '';
        document.getElementById('sj-nomor-polisi').value    = '';
        document.getElementById('sj-nama-supir').value      = '';
        document.getElementById('sj-jenis-kendaraan').value = '';
    });
});

// ======== SIMPAN SURAT JALAN ========
document.getElementById('btn-simpan-sj').addEventListener('click', function () {
    const invoiceId      = this.dataset.invoiceId;
    const sjNumber       = document.getElementById('sj-number').value;
    const nomorPeb       = document.getElementById('sj-nomor-peb').value;
    const tanggalPeb     = document.getElementById('sj-tanggal-peb').value;
    const nomorPolisi    = document.getElementById('sj-nomor-polisi').value;
    const namaSupir      = document.getElementById('sj-nama-supir').value;
    const jenisKendaraan = document.getElementById('sj-jenis-kendaraan').value;

    // Validasi semua field
    if (!sjNumber.trim()) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Nomor surat jalan wajib diisi.' });
        return;
    }
    if (!nomorPeb.trim()) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Nomor PEB wajib diisi.' });
        return;
    }
    if (!tanggalPeb) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Tanggal PEB wajib diisi.' });
        return;
    }
    if (!nomorPolisi.trim()) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Nomor polisi wajib diisi.' });
        return;
    }
    if (!namaSupir.trim()) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Nama supir wajib diisi.' });
        return;
    }
    if (!jenisKendaraan.trim()) {
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Jenis kendaraan wajib diisi.' });
        return;
    }

    // Isi form hidden dan submit
    document.getElementById('form-sj-invoice-id').value      = invoiceId;
    document.getElementById('form-sj-number').value          = sjNumber;
    document.getElementById('form-sj-nomor-peb').value       = nomorPeb;
    document.getElementById('form-sj-tanggal-peb').value     = tanggalPeb;
    document.getElementById('form-sj-nomor-polisi').value    = nomorPolisi;
    document.getElementById('form-sj-nama-supir').value      = namaSupir;
    document.getElementById('form-sj-jenis-kendaraan').value = jenisKendaraan;
    document.getElementById('form-surat-jalan').submit();
});

// ======== FORMAT RUPIAH ========
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}
</script>
@endsection