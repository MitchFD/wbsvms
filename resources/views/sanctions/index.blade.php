@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'sanctions'
])

@section('content')
    <div class="content">
    @if(auth()->user()->user_status == 'active')
        @php
            $get_user_role_info = App\Models\Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
            $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        @endphp
        @if(in_array('sanctions', $get_uRole_access))
            {{-- directory link --}}
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <a href="{{ route('sanctions.index', 'sanctions') }}" class="directory_active_link">Sanctions </a>
                </div>
            </div>

            {{-- card intro --}}
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card card_gbr shadow">
                        <div class="card-body card_intro">
                            <div class="page_intro">
                                <span class="page_intro_title">Sanctions</span>
                                <span class="page_intro_subtitle">Create Sanctions as default options to ease your task for adding corresponding sanctions to recorded violations. You will be able to Add new Sanctions, Edit Existing Sanctions, and/or delete created sanctions.</span>
                            </div>
                            <div class="page_illustration">
                                <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/violation_records_illustration.svg') }}" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        @else

        @endif
    @else
        
    @endif
    </div>
@endsection

@push('scripts')

@endpush