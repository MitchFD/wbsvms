@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'profile'
])

@section('content')
    <div class="content">
        {{-- directory link --}}
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <a href="#" class="directory_active_linkv1">Access Denied! </a>
            </div>
        </div>
        {{-- card intro --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card_gbr shadow">
                    <div class="card-body card_intro">
                        <div class="page_intro">
                            <span class="page_intro_titlev1">Access Denied</span>
                            <span class="page_intro_subtitle">The System Role that has been assigned to your account is not allowed to access this page.</span> 
                        </div>
                        <div class="page_illustration">
                            <img class="illustration_svg" src="{{ asset('storage/svms/illustrations/access_denied_illustration.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    
@endpush