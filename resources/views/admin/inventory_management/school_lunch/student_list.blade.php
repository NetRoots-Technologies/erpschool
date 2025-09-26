@extends('admin.layouts.main')

@section('title')
Served Students
@stop

@section('content')
<div class="container-fluid">
    <a href="{{ route('inventory.school_lunch.view') }}" class="btn btn-primary my-3">Back</a>

    <div class="row justify-content-center my-4">
        <div class="col-12">
            <div class="card basic-form shadow-sm">
                <div class="card-body table-responsive">
                    <h2 class="text-center">List of Served Students</h2>

                    <div class="row font-weight-bold border-bottom mt-5 pb-2 text-center">
                        <div class="col-md-4 fs-5">Student Name</div>
                        <div class="col-md-4 fs-5">Lunch</div>
                        <div class="col-md-4 fs-5">Served</div>
                    </div>

                    @forelse ($student_batch_products as $batch)
                        @foreach ($batch->mealBatchDetails as $detail)
                            <div class="row py-2 border-bottom text-center">
                                <div class="col-md-4">
                                    {{ optional($detail->student)->first_name }} {{ optional($detail->student)->last_name }}
                                </div>
                                <div class="col-md-4">
                                    {{ optional($batch->product)->name }}
                                </div>
                                <div class="col-md-4 {{ ($detail->assigned ?? 0) == 1 ? 'text-success' : 'text-danger' }}">
                                    <i class="fa fa-{{ ($detail->assigned ?? 0) == 1 ? 'check' : 'times' }}"></i>
                                </div>
                            </div>
                        @endforeach
                    @empty
                        <div class="py-3 text-center text-muted">No served students found.</div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(function(){ 'use strict'; });
</script>
@endsection
