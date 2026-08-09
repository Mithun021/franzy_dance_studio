@extends('backend.partial.master')

@section('title','View Syllabus')

@section('backend-content')

<div id="printArea">

    <!-- Header -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h3 class="fw-bold mb-1">

                        <i class="fas fa-book-open text-primary"></i>

                        Course Syllabus

                    </h3>

                    <p class="text-muted mb-0">

                        Complete syllabus details of selected Course & Level

                    </p>

                </div>

                <div class="mt-2 mt-md-0">

                    <a href="{{ route('syllabus.index') }}"
                       class="btn btn-secondary">

                        <i class="fas fa-arrow-left"></i>

                        Back

                    </a>

                    <a href="{{ route('syllabus.edit',$syllabus->id) }}"
                       class="btn btn-warning">

                        <i class="fas fa-edit"></i>

                        Edit

                    </a>



                </div>

            </div>

        </div>

    </div>



    <!-- Course Information -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="fas fa-info-circle"></i>

                Course Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-lg-3 col-md-6 mb-3">

                    <label class="text-muted d-block">

                        Course Name

                    </label>

                    <h5 class="fw-bold">

                        {{ optional($syllabus->course)->course_name }}

                    </h5>

                </div>

                <div class="col-lg-3 col-md-6 mb-3">

                    <label class="text-muted d-block">

                        Level

                    </label>

                    <span class="badge bg-info fs-6">

                        {{ optional($syllabus->level)->name }}

                    </span>

                </div>

                <div class="col-lg-3 col-md-6 mb-3">

                    <label class="text-muted d-block">

                        Total Chapters

                    </label>

                    <h5 class="text-success fw-bold">

                        {{ $syllabus->details->count() }}

                    </h5>

                </div>

                <div class="col-lg-3 col-md-6 mb-3">

                    <label class="text-muted d-block">

                        Created On

                    </label>

                    <h6>

                        {{ $syllabus->created_at->format('d M Y') }}

                    </h6>

                </div>

            </div>

        </div>

    </div>


    <!-- Chapter List Starts -->

    <div class="card shadow-sm border-0">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">

                <i class="fas fa-list"></i>

                Chapter Details

            </h5>

        </div>

        <div class="card-body">

            <div class="accordion"
                 id="chapterAccordion">
                 @foreach($syllabus->details as $detail)

<div class="accordion-item border rounded mb-3 shadow-sm">

    <h2 class="accordion-header"
        id="heading{{ $detail->id }}">

        <button
            class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse{{ $detail->id }}"
            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
            aria-controls="collapse{{ $detail->id }}">

            <div class="w-100 d-flex justify-content-between align-items-center">

                <div>

                    <span class="badge bg-primary me-2">

                        Chapter {{ $detail->chapter_no }}

                    </span>

                    <strong>

                        {{ $detail->title }}

                    </strong>

                </div>

                <div class="me-3">

                    @if($detail->duration)

                        <span class="badge bg-success">

                            <i class="fas fa-clock"></i>

                            {{ $detail->duration }}

                        </span>

                    @else

                        <span class="badge bg-secondary">

                            Duration N/A

                        </span>

                    @endif

                </div>

            </div>

        </button>

    </h2>

    <div id="collapse{{ $detail->id }}"
         class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
         data-bs-parent="#chapterAccordion">

        <div class="accordion-body">

            <div class="card border-0 bg-light">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h5 class="fw-bold text-primary mb-0">

                                    {{ $detail->title }}

                                </h5>

                                <span class="badge bg-dark">

                                    Chapter {{ $detail->chapter_no }}

                                </span>

                            </div>

                        </div>

                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-12">

                            <div class="syllabus-content">

                                {!! $detail->content !!}

                            </div>

                        </div>

                    </div>

                    <hr>

                    <div class="row text-center">

                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <small class="text-muted">

                                    Chapter No

                                </small>

                                <h5 class="mb-0 fw-bold">

                                    {{ $detail->chapter_no }}

                                </h5>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <small class="text-muted">

                                    Duration

                                </small>

                                <h5 class="mb-0 text-success">

                                    {{ $detail->duration ?: 'N/A' }}

                                </h5>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <small class="text-muted">

                                    Course

                                </small>

                                <h6 class="mb-0">

                                    {{ optional($syllabus->course)->course_name }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endforeach

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<style>

.syllabus-content{

    line-height:1.9;

    font-size:15px;

}

.syllabus-content img{

    max-width:100%;

    height:auto;

}

.syllabus-content table{

    width:100% !important;

}

.syllabus-content p{

    margin-bottom:10px;

}

.syllabus-content ul{

    padding-left:20px;

}

.syllabus-content ol{

    padding-left:20px;

}

</style>

<script>

/*
|--------------------------------------------------------------------------
| Open All Accordion Before Print
|--------------------------------------------------------------------------
*/

function openAllAccordion(){

    $('.accordion-collapse').addClass('show');

    $('.accordion-button').removeClass('collapsed');

    $('.accordion-button').attr('aria-expanded','true');

}

/*
|--------------------------------------------------------------------------
| Restore Accordion
|--------------------------------------------------------------------------
*/

function restoreAccordion(){

    $('.accordion-collapse').each(function(index){

        if(index!==0){

            $(this).removeClass('show');

        }

    });

    $('.accordion-button').each(function(index){

        if(index!==0){

            $(this).addClass('collapsed');

            $(this).attr('aria-expanded','false');

        }

    });

}


</script>

@endpush
