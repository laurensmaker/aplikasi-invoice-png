@extends('backend.layouts.main')
@section('content')
    <div class="row">
        <div class="col-xxl-10">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-sm-6">
                    <div class="stats-box card bg-white border-0 rounded-10 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-1">
                                <div class="flex-grow-1 me-3">
                                    {{-- <h3 class="body-font fw-bold fs-3 mb-2">{{ $totalEksportir }}</h3> --}}
                                    <span>INVOICE</span>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="icon transition">
                                        <i class="flaticon-donut-chart"></i>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="d-flex align-items-center">
                                <div class="svg-success me-2">
                                    <i data-feather="trending-up"></i>
                                </div>
                                <p class="fw-semibold"><span class="text-success">1.3%</span> Up from past week
                                </p>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="stats-box card bg-white border-0 rounded-10 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-1">
                                <div class="flex-grow-1 me-3">
                                    {{-- <h3 class="body-font fw-bold fs-3 mb-2">{{ $totalPengajuan }}</h3> --}}
                                    <span>PACKING LIST</span>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="icon transition">
                                        <i class="flaticon-goal"></i>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="d-flex align-items-center">
                                <div class="svg-danger me-2">
                                    <i data-feather="trending-down"></i>
                                </div>
                                <p class="fw-semibold"><span class="text-danger">1.3%</span> Down from past week
                                </p>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
   
    
@endsection