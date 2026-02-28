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
    <h3 class="mb-sm-0 mb-1 fs-18">Data Surat Jalan</h3>
    <ul class="ps-0 mb-0 list-unstyled d-flex justify-content-center">
        <li>
            <a href="#" class="text-decoration-none">
                <i class="ri-home-2-line" style="position: relative; top: -1px;"></i>
                <span>Home</span>
            </a>
        </li>
        <li>
            <span class="fw-semibold fs-14 heading-font text-dark dot ms-2">Surat Jalan</span>
        </li>
    </ul>
</div>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-body p-4">

                <div class="d-sm-flex text-center justify-content-between align-items-center border-bottom pb-20 mb-20">
                    <h4 class="fw-semibold fs-18 mb-sm-0">Data Surat Jalan</h4>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                       <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nomor Surat</th>
                            <th>Nomor PEB</th>
                            <th>Tanggal PEB</th>
                            <th>No Polisi</th>
                            <th>Nama Supir</th>
                            <th>Jenis Kendaraan</th>
                            <th>Total Item</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratJalans as $index => $sj)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-dark">{{ $sj->surat_jalan_number }}</span>
                                </td>
                                <td>{{ $sj->nomor_peb ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($sj->tanggal_peb)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ $sj->nomor_polisi }}</span>
                                </td>
                                <td>{{ $sj->nama_supir }}</td>
                                <td>{{ $sj->jenis_kendaraan }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $sj->invoice->items->count() ?? 0 }} item
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">

                                        {{-- TOMBOL DETAIL --}}
                                        <button class="btn btn-info btn-sm text-white btn-surat-jalan"
                                            style="font-size:11px; padding:3px 7px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalSuratJalan"
                                            data-sj-id="{{ $sj->id }}"
                                            data-sj-number="{{ $sj->surat_jalan_number }}"
                                            data-nomor-peb="{{ $sj->nomor_peb }}"
                                            data-tanggal-peb="{{ \Carbon\Carbon::parse($sj->tanggal_peb)->format('d M Y') }}"
                                            data-nomor-polisi="{{ $sj->nomor_polisi }}"
                                            data-nama-supir="{{ $sj->nama_supir }}"
                                            data-jenis-kendaraan="{{ $sj->jenis_kendaraan }}"
                                            data-from-company="{{ $sj->invoice->from_company ?? '-' }}"
                                            data-from-address="{{ $sj->invoice->from_address ?? '-' }}"
                                            data-to-company="{{ $sj->invoice->to_company ?? '-' }}"
                                            data-to-address="{{ $sj->invoice->to_address ?? '-' }}"
                                            data-items="{{ $sj->invoice->items->toJson() }}">
                                            <i data-feather="eye" style="width:12px;height:12px;"></i> Detail
                                        </button>

                                        {{-- TOMBOL CETAK --}}
                                        <a href="{{ route('surat-jalan.print', $sj->id) }}"
                                            class="btn btn-success btn-sm text-white"
                                            style="font-size:11px; padding:3px 7px;"
                                            target="_blank">
                                            <i data-feather="printer" style="width:12px;height:12px;"></i> Cetak
                                        </a>

                                        {{-- TOMBOL HAPUS --}}
                                        <a href="#" class="btn btn-danger btn-sm btn-delete"
                                            style="font-size:11px; padding:3px 7px;"
                                            data-id="{{ $sj->id }}"
                                            data-nama="{{ $sj->surat_jalan_number }}">
                                            <i data-feather="trash-2" style="width:12px;height:12px;"></i> Hapus
                                        </a>
                                        <form id="form-delete-{{ $sj->id }}"
                                            action="{{ route('surat-jalan.destroy', $sj->id) }}"
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
                                    <i class="ri-truck-line fs-24"></i>
                                    <p class="mt-2">Belum ada data surat jalan.</p>
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
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2e7d32, #1b5e20);">
                <h5 class="modal-title text-white">
                    <i class="ri-archive-line me-2"></i>
                    Packing List — <span id="modal-packing-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
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
                        <div><span class="badge bg-info text-dark" id="modal-total-item">0 item</span></div>
                    </div>
                </div>
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
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="no-items-alert" class="alert alert-warning d-none">
                    <i class="ri-information-line me-1"></i> Belum ada item.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Tutup
                </button>
                <a id="btn-print-packing" href="#" class="btn btn-success text-white" target="_blank">
                    <i class="ri-printer-line me-1"></i> Cetak Packing List
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL DETAIL SURAT JALAN ===================== --}}
<div class="modal fade" id="modalSuratJalan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #37474f, #263238);">
                <h5 class="modal-title text-white">
                    <i class="ri-truck-line me-2"></i>
                    Surat Jalan — <span id="sj-modal-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">

                {{-- INFO KENDARAAN --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <p class="text-muted small mb-1 fw-semibold">PENGIRIM</p>
                                <h6 class="fw-bold" id="sj-from-company">-</h6>
                                <p class="text-muted mb-0 small" id="sj-from-address">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <p class="text-muted small mb-1 fw-semibold">PENERIMA</p>
                                <h6 class="fw-bold" id="sj-to-company">-</h6>
                                <p class="text-muted mb-0 small" id="sj-to-address">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFO SJ --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <p class="text-muted small mb-1">No. Surat Jalan</p>
                            <div class="fw-bold text-dark" id="sj-number-info">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <p class="text-muted small mb-1">Nomor PEB</p>
                            <div class="fw-bold" id="sj-nomor-peb">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <p class="text-muted small mb-1">Tanggal PEB</p>
                            <div class="fw-bold" id="sj-tanggal-peb">-</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded" style="background:#f3f4f6;">
                            <p class="text-muted small mb-1"><i class="ri-car-line me-1"></i>Jenis Kendaraan</p>
                            <div class="fw-bold" id="sj-jenis-kendaraan">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded" style="background:#f3f4f6;">
                            <p class="text-muted small mb-1"><i class="ri-roadster-line me-1"></i>Nomor Polisi</p>
                            <div class="fw-bold text-danger" id="sj-nomor-polisi">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded" style="background:#f3f4f6;">
                            <p class="text-muted small mb-1"><i class="ri-user-line me-1"></i>Nama Supir</p>
                            <div class="fw-bold" id="sj-nama-supir">-</div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- TABEL BARANG --}}
                <h6 class="fw-semibold mb-3">
                    <i class="ri-list-check me-1 text-dark"></i> Daftar Barang
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead style="background-color: #eceff1;">
                            <tr class="text-center">
                                <th width="5%">#</th>
                                <th>Deskripsi Barang</th>
                                <th width="12%">Qty</th>
                                <th width="15%">Berat/unit (kg)</th>
                                <th width="15%">Total Berat (kg)</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-sj-items"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total</td>
                                <td class="text-center fw-bold" id="sj-total-qty">0</td>
                                <td></td>
                                <td class="text-center fw-bold text-danger" id="sj-total-weight">0 kg</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="no-sj-items-alert" class="alert alert-warning d-none">
                    <i class="ri-information-line me-1"></i> Belum ada item.
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Tutup
                </button>
                <a id="btn-print-sj" href="#" class="btn btn-dark text-white" target="_blank">
                    <i class="ri-printer-line me-1"></i> Cetak Surat Jalan
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// ======== MODAL DETAIL PACKING ========
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

        document.getElementById('modal-packing-number').textContent      = packingNumber;
        document.getElementById('modal-packing-number-info').textContent = packingNumber;
        document.getElementById('modal-packing-date').textContent        = packingDate;
        document.getElementById('modal-invoice-number').textContent      = invoiceNumber;
        document.getElementById('modal-from-company').textContent        = fromCompany;
        document.getElementById('modal-from-address').textContent        = fromAddress;
        document.getElementById('modal-to-company').textContent          = toCompany;
        document.getElementById('modal-to-address').textContent          = toAddress;
        document.getElementById('btn-print-packing').href                = `/packing-list/${id}/print`;

        const tbody     = document.getElementById('tbody-packing-items');
        const noAlert   = document.getElementById('no-items-alert');
        tbody.innerHTML = '';

        if (items.length === 0) {
            noAlert.classList.remove('d-none');
            document.getElementById('modal-total-item').textContent   = '0 item';
            document.getElementById('modal-total-qty').textContent    = '0';
            document.getElementById('modal-grand-total').textContent  = formatRupiah(0);
            document.getElementById('modal-total-weight').textContent = '0 kg';
            return;
        }

        noAlert.classList.add('d-none');
        let totalQty = 0, grandTotal = 0, totalWeight = 0;

        items.forEach((item, index) => {
            const qty        = parseInt(item.qty) || 0;
            const desc       = item.description ?? '-';
            const unitPrice  = parseFloat(item.unit_price) || 0;
            const totalPrice = parseFloat(item.total_price) || 0;
            const weightKg   = parseFloat(item.weight_kg) || 0;
            const totalW     = parseFloat(item.total_weight) || 0;

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
                </tr>
            `;
        });

        document.getElementById('modal-total-item').textContent   = items.length + ' item';
        document.getElementById('modal-total-qty').textContent    = totalQty;
        document.getElementById('modal-grand-total').textContent  = formatRupiah(grandTotal);
        document.getElementById('modal-total-weight').textContent = totalWeight.toFixed(2) + ' kg';
    });
});

// ======== MODAL DETAIL SURAT JALAN ========
document.querySelectorAll('.btn-surat-jalan').forEach(button => {
    button.addEventListener('click', function () {
        const sjId           = this.dataset.sjId;
        const sjNumber       = this.dataset.sjNumber;
        const nomorPeb       = this.dataset.nomorPeb;
        const tanggalPeb     = this.dataset.tanggalPeb;
        const nomorPolisi    = this.dataset.nomorPolisi;
        const namaSupir      = this.dataset.namaSupir;
        const jenisKendaraan = this.dataset.jenisKendaraan;
        const fromCompany    = this.dataset.fromCompany;
        const fromAddress    = this.dataset.fromAddress;
        const toCompany      = this.dataset.toCompany;
        const toAddress      = this.dataset.toAddress;
        const items          = JSON.parse(this.dataset.items || '[]');

        document.getElementById('sj-modal-number').textContent    = sjNumber;
        document.getElementById('sj-number-info').textContent     = sjNumber;
        document.getElementById('sj-nomor-peb').textContent       = nomorPeb;
        document.getElementById('sj-tanggal-peb').textContent     = tanggalPeb;
        document.getElementById('sj-nomor-polisi').textContent    = nomorPolisi;
        document.getElementById('sj-nama-supir').textContent      = namaSupir;
        document.getElementById('sj-jenis-kendaraan').textContent = jenisKendaraan;
        document.getElementById('sj-from-company').textContent    = fromCompany;
        document.getElementById('sj-from-address').textContent    = fromAddress;
        document.getElementById('sj-to-company').textContent      = toCompany;
        document.getElementById('sj-to-address').textContent      = toAddress;
        document.getElementById('btn-print-sj').href              = `/surat-jalan/${sjId}/print`;

        const tbody     = document.getElementById('tbody-sj-items');
        const noAlert   = document.getElementById('no-sj-items-alert');
        tbody.innerHTML = '';

        if (items.length === 0) {
            noAlert.classList.remove('d-none');
            document.getElementById('sj-total-qty').textContent    = '0';
            document.getElementById('sj-total-weight').textContent = '0 kg';
            return;
        }

        noAlert.classList.add('d-none');
        let totalQty = 0, totalWeight = 0;

        items.forEach((item, index) => {
            const qty    = parseInt(item.qty) || 0;
            const desc   = item.description ?? '-';
            const weightKg = parseFloat(item.weight_kg) || 0;
            const totalW   = parseFloat(item.total_weight) || 0;

            totalQty    += qty;
            totalWeight += totalW;

            tbody.innerHTML += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${desc}</td>
                    <td class="text-center fw-semibold">${qty}</td>
                    <td class="text-center">${weightKg} kg</td>
                    <td class="text-center fw-semibold text-danger">${totalW} kg</td>
                </tr>
            `;
        });

        document.getElementById('sj-total-qty').textContent    = totalQty;
        document.getElementById('sj-total-weight').textContent = totalWeight.toFixed(2) + ' kg';
    });
});

// ======== FORMAT RUPIAH ========
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
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