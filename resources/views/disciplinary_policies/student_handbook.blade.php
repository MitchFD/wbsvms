@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'student_handbook'
])

@section('content')
    <div class="content">
    @if(auth()->user()->user_status == 'active')
        @php
            $get_user_role_info = App\Models\Userroles::select('uRole_id', 'uRole', 'uRole_access')->where('uRole', auth()->user()->user_role)->first();
            $get_uRole_access   = json_decode(json_encode($get_user_role_info->uRole_access));
        @endphp
        @if(in_array('disciplinary policies', $get_uRole_access))
            {{-- directory link --}}
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <a href="{{ route('disciplinary_policies.student_handbook', 'student_handbook') }}" class="directory_link">Disciplinary Policies </a> <span class="directory_divider"> / </span> <a href="{{ route('disciplinary_policies.student_handbook', 'student_handbook') }}" class="directory_active_link">SDCA Student Handbook </a>
                </div>
            </div>

            {{-- pdf display --}}
            <iframe src="{{ asset('storage/svms/pdfs/Student_Handbook_2018_Revised.pdf') }}" style="width:100%; height:83vh;" frameborder="0"></iframe>
        @else

        @endif
    @else
        
    @endif
    </div>
@endsection

@push('scripts')

@endpush