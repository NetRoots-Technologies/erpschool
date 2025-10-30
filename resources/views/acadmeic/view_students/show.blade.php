@extends('admin.layouts.main')

@section('title')
    Student — Show
@stop

@section('content')
<style>
    /* Soft card look */
    .card-soft {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,.06);
        overflow: hidden;
        background: #fff;
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: #fff;
        padding: 18px 20px;
    }
    .kv {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 8px 16px;
        padding: 10px 0;
        border-bottom: 1px dashed #e9ecef;
    }
    .kv:last-child { border-bottom: 0; }
    .kv .k { color: #6b7280; font-weight: 600; }
    .badge-soft {
        background: #eef2ff;
        color: #3730a3;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .section-title {
        font-size: 14px;
        letter-spacing: .08em;
        color: #64748b;
        text-transform: uppercase;
        margin: 0 0 10px;
    }
    .gallery img {
        width: 18%;
        /* height: 220px;
        object-fit: cover; */
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,.08);
        float: right
    }
    .gallery .thumb {
        position: relative;
        background: #f8fafc;
        border-radius: 14px;
        padding: 12px;
        height: 100%;

    }
    .thumb h6 {
        font-size: 13px;
        margin: 8px 0 0;
        color: #334155;
        text-align: center;
    }
    .divider {
        height: 1px;
        background: linear-gradient(90deg, rgba(0,0,0,0), rgba(148,163,184,.35), rgba(0,0,0,0));
        margin: 28px 0 10px;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Show Student</h3>
        <a href="{{ route('academic.student_view.index') }}" class="btn btn-primary">
            Back
        </a>
    </div>

    {{-- Top identity card --}}
    <div class="card card-soft mb-4">
        <div class="card-header-gradient d-flex flex-wrap align-items-center justify-content-between">
            <div>
                <div class="h5 mb-1">{{ $student->first_name }} {{ $student->last_name }}</div>
                <div class="small opacity-75">
                    {{ optional($student->AcademicClass)->name ?? '—' }}
                    @if($student->section_id)
                        <span class="mx-1">•</span> Section: {{ $student->section_id }}
                    @endif
                </div>
            </div>
            <div>

                <div class="thumb gallery">
                                   @php
                        $photo = optional($student->studentPictures)->passport_photos;
                    @endphp

                    @if($photo)
                        <img src="{{ Str::startsWith($photo, ['http://','https://']) ? $photo : asset($photo) }}" alt="Passport photo">
                    @else
                        <img src="{{ asset('images/placeholders/student.png') }}" alt="">
             @endif
                </div>
               
            </div>
        </div>

    
            


        <div class="card-body p-4">
            {{-- SECTION A: Personal Info --}}
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="section-title">Section A — Personal Information</div>
                    <div class="kv">
                        <div class="k">Admission Required (Class / Grade)</div>
                        <div>{{ $student->admission_class ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Campus</div>
                        <div>
                            {{ optional($branches->firstWhere('id', $student->branch_id))->name ?? '—' }}
                        </div>
                    </div>
                    <div class="kv">
                        <div class="k">Date of Admission</div>
                        <div>{{ $student->admission_date ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Date of Birth</div>
                        <div>{{ $student->student_dob ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Father’s Name</div>
                        <div>{{ $student->father_name ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Guardian’s Name</div>
                        <div>{{ $student->guardian_name ?? '—' }}</div>
                    </div>

                     <div class="kv">
                        <div class="k">Gender</div>
                        <div>{{ $student->gender ?? '—' }}</div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="section-title">&nbsp;</div>
                    <div class="kv">
                        <div class="k">Current Address</div>
                        <div>{{ $student->student_current_address ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Permanent Address</div>
                        <div>{{ $student->student_permanent_address ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">City</div>
                        <div>{{ $student->city ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Country of Origin</div>
                        <div>{{ $student->country ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Cell No</div>
                        <div>{{ $student->cell_no ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Landline</div>
                        <div>{{ $student->landline ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Email</div>
                        <div>{{ $student->student_email ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            {{-- Languages --}}
            <div class="row">
                <div class="col-lg-4">
                    <div class="section-title">Languages</div>
                    <div class="kv">
                        <div class="k">Native</div>
                        <div>{{ $student->native_language ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">First</div>
                        <div>{{ $student->first_language ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Second</div>
                        <div>{{ $student->second_language ?? '—' }}</div>
                    </div>
                </div>

                {{-- Previous School (Section B) --}}
                <div class="col-lg-8">
                    <div class="section-title">Section B — Previous School Information</div>
                    <div class="kv">
                        <div class="k">Name of School</div>
                        <div>{{ optional($student->student_schools)->school_name ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Reasons of Leaving</div>
                        <div>{{ optional($student->student_schools)->leaving_reason ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Local School (if overseas)</div>
                        <div>{{ optional($student->student_schools)->local_school_name ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Local School Address</div>
                        <div>{{ optional($student->student_schools)->local_school_address ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            {{-- Transport (Section D) --}}
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-title">Section D — Travel Arrangements</div>
                    <div class="kv">
                        <div class="k">Mode of Transport</div>
                        <div>{{ optional($student->student_transports)->pickup_dropoff ?? '—' }}</div>
                    </div>
                    <div class="kv">
                        <div class="k">Need School Transport?</div>
                        <div>
                            @php $tf = optional($student->student_transports)->transport_facility; @endphp
                            {{ $tf ? Str::ucfirst($tf) : '—' }}
                        </div>
                    </div>
                    <div class="kv">
                        <div class="k">Pick / Drop-off Point</div>
                        <div>{{ optional($student->student_transports)->pick_address ?? '—' }}</div>
                    </div>
                </div>

                {{-- Section E --}}
                <div class="col-lg-6">
                    <div class="section-title">Section E</div>
                    <div class="kv">
                        <div class="k">Photo / Media Permission</div>
                        <div>
                            @php
                                $perm = $student->picture_permission ?? null; // 'yes' / 'no' / null
                            @endphp
                            {{ $perm ? ($perm === 'yes' ? 'Allowed' : 'Not Allowed') : '—' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            {{-- Emergency Contacts --}}
            <div class="row">
                <div class="col-12">
                    <div class="section-title">Emergency Contacts</div>
                </div>

                @if($student->student_emergency_contacts->isNotEmpty())
                    @foreach($student->student_emergency_contacts as $c)
                        <div class="col-lg-6 mb-3">
                            <div class="card card-soft h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>{{ $c->name }}</strong>
                                        @if($c->parent_responsibility)
                                            <span class="badge-soft">Parental: {{ Str::ucfirst($c->parent_responsibility) }}</span>
                                        @endif
                                    </div>
                                    <div class="kv"><div class="k">Relation</div><div>{{ $c->relation ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">Home Address</div><div>{{ $c->home_address ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">City</div><div>{{ $c->city ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">Landline</div><div>{{ $c->landline ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">Cell</div><div>{{ $c->cell_no ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">Email</div><div>{{ $c->email_address ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">Work Address</div><div>{{ $c->work_address ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">Work Landline</div><div>{{ $c->work_landline ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">Work Cell</div><div>{{ $c->work_cell_no ?? '—' }}</div></div>
                                    <div class="kv"><div class="k">Work Email</div><div>{{ $c->work_email ?? '—' }}</div></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-muted">No emergency contacts added.</div>
                @endif
            </div>

            <div class="divider"></div>

            {{-- Siblings --}}
            <div class="row">
                <div class="col-12">
                    <div class="section-title">Siblings</div>
                </div>
                <div class="col-12">
                    <div class="table-responsive card card-soft">
                        <table class="table mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>Sr.</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th>Class</th>
                                <th>Gender</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($siblings as $idx => $sibling)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $sibling->student_id ?? 'N/A' }}</td>
                                    <td>{{ trim(($sibling->first_name ?? '').' '.($sibling->last_name ?? '')) }}</td>
                                    <td>{{ $sibling->cell_no ?? 'N/A' }}</td>
                                    <td>{{ optional($sibling->AcademicClass)->name ?? 'N/A' }}</td>
                                    <td class="text-uppercase">{{ $sibling->gender ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">No siblings found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
