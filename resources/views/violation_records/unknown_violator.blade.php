@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'violation_records'
])

@section('content')
    <div class="content">

    {{-- directory link --}}
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <a href="{{ route('violation_records.index', 'violation_records') }}" class="directory_link">Violation Records</a> 
                <span class="directory_divider"> / </span> 
                <a href="{{ route('violation_records.violator', $violator_id , 'violation_records') }}" class="directory_active_link">Violator <span class="directory_divider"> ~ </span> Unknown Student</a>
            </div>
        </div>
    {{-- directory link end --}}

    {{-- unknown violator illustration --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr card_ofh shadow-none cb_p25 card_body_bg_gray">
                    <div class="no_data_div3 d-flex justify-content-center align-items-center text-center flex-column">
                        <img class="no_data_svg" src="{{asset('storage/svms/illustrations/unknown_violator.svg')}}" alt="no offenses found">
                        <span class="font-italic font-weight-bold">Unknown Student. </span>
                    </div>
                </div>
            </div>
        </div>
    {{-- unknown violator illustration end --}}
    </div>

@endsection

@push('scripts')

@endpush