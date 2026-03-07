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
                                    <td>{{ $sj->tanggal_peb ? \Carbon\Carbon::parse($sj->tanggal_peb)->format('d M Y') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $sj->invoice ? $sj->invoice->items->count() : 0 }} item
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
                                                data-nomor-peb="{{ $sj->nomor_peb ?? '-' }}"
                                                data-tanggal-peb="{{ $sj->tanggal_peb ? \Carbon\Carbon::parse($sj->tanggal_peb)->format('d M Y') : '-' }}"
                                                data-from-company="{{ $sj->invoice->from_company ?? '-' }}"
                                                data-from-address="{{ $sj->invoice->from_address ?? '-' }}"
                                                data-to-company="{{ $sj->invoice->to_company ?? '-' }}"
                                                data-to-address="{{ $sj->invoice->to_address ?? '-' }}"
                                                data-items='@json($sj->invoice ? $sj->invoice->items : [])'
                                                data-kendaraan='@json($sj->pengangkutan)'>
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
                                    <td colspan="6" class="text-center text-muted py-4">
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

                {{-- PENGIRIM & PENERIMA --}}
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

                {{-- INFO NO SJ, PEB --}}
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

                {{-- KENDARAAN PENGANGKUT --}}
                <h6 class="fw-semibold mb-3">
                    <i class="ri-roadster-line me-1 text-dark"></i> Kendaraan Pengangkut
                </h6>
                <table class="table table-bordered align-middle mb-4">
                    <thead class="bg-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nomor Polisi</th>
                            <th>Nama Supir</th>
                            <th>Jenis Kendaraan</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-kendaraan"></tbody>
                </table>

                <hr>

                {{-- DAFTAR BARANG --}}
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
                                {{-- <th width="15%">Berat/unit (kg)</th>
                                <th width="15%">Total Berat (kg)</th> --}}
                            </tr>
                        </thead>
                        <tbody id="tbody-sj-items"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total</td>
                                <td class="text-center fw-bold" id="sj-total-qty">0</td>
                                {{-- <td></td>
                                <td class="text-center fw-bold text-danger" id="sj-total-weight">0 kg</td> --}}
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
// ======== MODAL DETAIL SURAT JALAN ========
document.querySelectorAll('.btn-surat-jalan').forEach(function(button) {
    button.addEventListener('click', function () {
        const sjId        = this.dataset.sjId        || '';
        const sjNumber    = this.dataset.sjNumber    || '-';
        const nomorPeb    = this.dataset.nomorPeb    || '-';
        const tanggalPeb  = this.dataset.tanggalPeb  || '-';
        const fromCompany = this.dataset.fromCompany || '-';
        const fromAddress = this.dataset.fromAddress || '-';
        const toCompany   = this.dataset.toCompany   || '-';
        const toAddress   = this.dataset.toAddress   || '-';

        let items     = [];
        let kendaraan = [];

        try { items     = JSON.parse(this.dataset.items     || '[]'); } catch(e) { items = []; }
        try { kendaraan = JSON.parse(this.dataset.kendaraan || '[]'); } catch(e) { kendaraan = []; }

        // ── Header modal ──
        document.getElementById('sj-modal-number').textContent = sjNumber;
        document.getElementById('sj-number-info').textContent  = sjNumber;
        document.getElementById('sj-nomor-peb').textContent    = nomorPeb;
        document.getElementById('sj-tanggal-peb').textContent  = tanggalPeb;

        // ── Pengirim & Penerima ──
        document.getElementById('sj-from-company').textContent = fromCompany;
        document.getElementById('sj-from-address').textContent = fromAddress;
        document.getElementById('sj-to-company').textContent   = toCompany;
        document.getElementById('sj-to-address').textContent   = toAddress;

        // ── Link cetak ──
        document.getElementById('btn-print-sj').href = '/surat-jalan/' + sjId + '/print';

        // ── Tabel Kendaraan ──
        const tbodyKendaraan = document.getElementById('tbody-kendaraan');
        tbodyKendaraan.innerHTML = '';

        if (!kendaraan || kendaraan.length === 0) {
            tbodyKendaraan.innerHTML =
                '<tr><td colspan="4" class="text-center text-muted py-2">Tidak ada data kendaraan</td></tr>';
        } else {
            kendaraan.forEach(function(k, i) {
                tbodyKendaraan.innerHTML +=
                    '<tr class="text-center">' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td><span class="badge bg-dark">' + (k.nomor_polisi   || '-') + '</span></td>' +
                        '<td>' + (k.nama_supir      || '-') + '</td>' +
                        '<td>' + (k.jenis_kendaraan || '-') + '</td>' +
                    '</tr>';
            });
        }

        // ── Tabel Barang ──
        const tbody   = document.getElementById('tbody-sj-items');
        const noAlert = document.getElementById('no-sj-items-alert');
        tbody.innerHTML = '';

        if (!items || items.length === 0) {
            noAlert.classList.remove('d-none');
            document.getElementById('sj-total-qty').textContent    = '0';
            // document.getElementById('sj-total-weight').textContent = '0 kg';
            return;
        }

        noAlert.classList.add('d-none');

        let totalQty = 0, totalWeight = 0;

        items.forEach(function(item, index) {
            const qty      = parseInt(item.qty)              || 0;
            const desc     = item.description               || '-';
            const weightKg = parseFloat(item.weight_kg)     || 0;
            const totalW   = parseFloat(item.total_weight)  || 0;

            totalQty    += qty;
            totalWeight += totalW;

            tbody.innerHTML +=
                '<tr>' +
                    '<td class="text-center">' + (index + 1) + '</td>' +
                    '<td>' + desc + '</td>' +
                    '<td class="text-center fw-semibold">' + qty + '</td>' +
                    // '<td class="text-center">' + weightKg.toLocaleString('id-ID') + ' kg</td>' +
                    // '<td class="text-center fw-semibold text-danger">' + totalW.toLocaleString('id-ID') + ' kg</td>' +
                '</tr>';
        });

        document.getElementById('sj-total-qty').textContent    = totalQty;
        // document.getElementById('sj-total-weight').textContent = totalWeight.toFixed(0) + ' kg';
    });
});

// ======== HAPUS DATA ========
document.querySelectorAll('.btn-delete').forEach(function(button) {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const id   = this.dataset.id;
        const nama = this.dataset.nama;

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Surat Jalan: ' + nama,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        });
    });
});
</script>
@endsection