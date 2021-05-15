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

            
        @else

        @endif
    @else
        
    @endif
    </div>
@endsection

@push('scripts')

@endpush