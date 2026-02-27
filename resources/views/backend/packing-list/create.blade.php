@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Form Invoice</h3>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">

     
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

                    <div class="col-lg-6 mb-3">
                        <label>From Company</label>
                        <input type="text" name="from_company" class="form-control" required>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label>From Address</label>
                        <input type="text" name="from_address" class="form-control" required>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label>To Company</label>
                        <input type="text" name="to_company" class="form-control" required>
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
        
        



    </div>
</div>

@endsection