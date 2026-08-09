@extends('backend.partial.master')

@section('title','Create Syllabus')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <form action="{{ route('syllabus.store') }}"
              method="POST">

            @csrf

            <div class="card shadow">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>

                        <h4 class="mb-0">

                            <i class="fas fa-book-open text-primary"></i>

                            Create Syllabus

                        </h4>

                        <small class="text-muted">

                            Select Course & Level then add unlimited syllabus chapters.

                        </small>

                    </div>

                    <a href="{{ route('syllabus.index') }}"
                       class="btn btn-secondary">

                        <i class="fas fa-arrow-left"></i>

                        Back

                    </a>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Course

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="course_id"
                                class="form-select select2 @error('course_id') is-invalid @enderror"
                                required>

                                <option value="">

                                    Select Course

                                </option>

                                @foreach($courses as $course)

                                    <option
                                        value="{{ $course->id }}"
                                        {{ old('course_id')==$course->id ? 'selected':'' }}>

                                        {{ $course->course_name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('course_id')

                                <small class="text-danger">

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Level

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="level_id"
                                class="form-select select2 @error('level_id') is-invalid @enderror"
                                required>

                                <option value="">

                                    Select Level

                                </option>

                                @foreach($levels as $level)

                                    <option
                                        value="{{ $level->id }}"
                                        {{ old('level_id')==$level->id ? 'selected':'' }}>

                                        {{ $level->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('level_id')

                                <small class="text-danger">

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="mb-0">

                            <i class="fas fa-list"></i>

                            Syllabus Details

                        </h5>

                        <button
                            type="button"
                            id="addRow"
                            class="btn btn-success">

                            <i class="fas fa-plus"></i>

                            Add Chapter

                        </button>

                    </div>

                    <div id="chapterContainer">

                        <div class="card border mb-3 chapter-item">

                            <div class="card-header bg-light d-flex justify-content-between align-items-center">

                                <strong>

                                    Chapter #1

                                </strong>

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm removeRow">

                                    <i class="mdi mdi-delete"></i>

                                </button>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-2 mb-3">

                                        <label class="form-label">

                                            Chapter No

                                        </label>

                                        <input
                                            type="number"
                                            name="chapter_no[]"
                                            class="form-control"
                                            value="1"
                                            readonly>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            Title

                                        </label>

                                        <input
                                            type="text"
                                            name="title[]"
                                            class="form-control"
                                            placeholder="Enter chapter title"
                                            required>

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">

                                            Duration

                                        </label>

                                        <input
                                            type="text"
                                            name="duration[]"
                                            class="form-control"
                                            placeholder="30 Minutes / 2 Hours">

                                    </div>

                                    <div class="col-md-12">

                                        <label class="form-label">

                                            Content

                                        </label>

                                        <textarea
                                            name="content[]"
                                            class="form-control ckeditor"
                                            rows="6"></textarea>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer text-end">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-save"></i>

                        Save Syllabus

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>

let chapterIndex = 1;

/*
|--------------------------------------------------------------------------
| Add Chapter
|--------------------------------------------------------------------------
*/

$('#addRow').on('click', function () {

    chapterIndex++;

    let row = `
    <div class="card border mb-3 chapter-item">

        <div class="card-header bg-light d-flex justify-content-between align-items-center">

            <strong>

                Chapter #${chapterIndex}

            </strong>

            <button
                type="button"
                class="btn btn-danger btn-sm removeRow">

                <i class="mdi mdi-delete"></i>

            </button>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-2 mb-3">

                    <label class="form-label">

                        Chapter No

                    </label>

                    <input
                        type="number"
                        name="chapter_no[]"
                        class="form-control chapter-number"
                        value="${chapterIndex}"
                        readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Title

                    </label>

                    <input
                        type="text"
                        name="title[]"
                        class="form-control"
                        placeholder="Enter chapter title"
                        required>

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">

                        Duration

                    </label>

                    <input
                        type="text"
                        name="duration[]"
                        class="form-control"
                        placeholder="30 Minutes / 2 Hours">

                </div>

                <div class="col-md-12">

                    <label class="form-label">

                        Content

                    </label>

                    <textarea
                        name="content[]"
                        rows="6"
                        class="form-control ckeditor"></textarea>

                </div>

            </div>

        </div>

    </div>
    `;

    $('#chapterContainer').append(row);

    initEditors();

});



/*
|--------------------------------------------------------------------------
| Remove Chapter
|--------------------------------------------------------------------------
*/

$(document).on('click','.removeRow',function(){

    if($('.chapter-item').length==1){

        alert('At least one chapter is required.');

        return;
    }

    $(this)
        .closest('.chapter-item')
        .remove();

    updateChapterNumber();

});



/*
|--------------------------------------------------------------------------
| Update Chapter Number
|--------------------------------------------------------------------------
*/

function updateChapterNumber(){

    chapterIndex = 0;

    $('.chapter-item').each(function(){

        chapterIndex++;

        $(this).find('.card-header strong')
            .text('Chapter #' + chapterIndex);

        $(this)
            .find('.chapter-number')
            .val(chapterIndex);

    });

}
/*
|--------------------------------------------------------------------------
| CKEditor Initialize
|--------------------------------------------------------------------------
*/

function initEditors() {

    $('.ckeditor').each(function () {

        // Skip if already initialized
        if ($(this).next('.ck-editor').length) {
            return;
        }

        ClassicEditor
            .create(this)
            .catch(error => {
                console.error(error);
            });

    });

}


/*
|--------------------------------------------------------------------------
| Initialize First Editor
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    initEditors();

});


/*
|--------------------------------------------------------------------------
| Form Validation
|--------------------------------------------------------------------------
*/

$('form').on('submit', function () {

    let valid = true;

    $('input[name="title[]"]').each(function () {

        if ($(this).val().trim() == '') {

            valid = false;

            $(this).focus();

            alert('Please enter chapter title.');

            return false;
        }

    });

    if (!valid) {

        return false;

    }

});


/*
|--------------------------------------------------------------------------
| Auto Scroll To New Chapter
|--------------------------------------------------------------------------
*/

$('#addRow').click(function () {

    $('html, body').animate({

        scrollTop: $('.chapter-item:last').offset().top - 100

    }, 400);

});

</script>

@endpush
