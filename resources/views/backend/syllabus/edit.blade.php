@extends('backend.partial.master')

@section('title','Edit Syllabus')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <form action="{{ route('syllabus.update',$syllabus->id) }}"
              method="POST">

            @csrf

            @method('PUT')

            <div class="card shadow">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>

                        <h4 class="mb-0">

                            <i class="fas fa-edit text-warning"></i>

                            Edit Syllabus

                        </h4>

                        <small class="text-muted">

                            Update Course, Level & Chapters

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
                                class="form-select select2"
                                required>

                                <option value="">

                                    Select Course

                                </option>

                                @foreach($courses as $course)

                                    <option
                                        value="{{ $course->id }}"
                                        {{ old('course_id',$syllabus->course_id)==$course->id ? 'selected' : '' }}>

                                        {{ $course->course_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Level

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="level_id"
                                class="form-select select2"
                                required>

                                <option value="">

                                    Select Level

                                </option>

                                @foreach($levels as $level)

                                    <option
                                        value="{{ $level->id }}"
                                        {{ old('level_id',$syllabus->level_id)==$level->id ? 'selected' : '' }}>

                                        {{ $level->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="mb-0">

                            <i class="fas fa-list"></i>

                            Chapter Details

                        </h5>

                        <button
                            type="button"
                            id="addRow"
                            class="btn btn-success">

                            <i class="fas fa-plus-circle"></i>

                            Add Chapter

                        </button>

                    </div>

                    <div id="chapterContainer">

                        @foreach($syllabus->details as $detail)

                            <div class="card border mb-3 chapter-item">

                                <div class="card-header bg-light d-flex justify-content-between align-items-center">

                                    <strong>

                                        Chapter #{{ $loop->iteration }}

                                    </strong>

                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm removeRow">

                                        <span class="material-symbols-outlined">
                                            delete
                                        </span>

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
                                                value="{{ old('chapter_no.'.$loop->index,$detail->chapter_no) }}"
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
                                                value="{{ old('title.'.$loop->index,$detail->title) }}"
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
                                                value="{{ old('duration.'.$loop->index,$detail->duration) }}">

                                        </div>

                                        <div class="col-md-12">

                                            <label class="form-label">

                                                Content

                                            </label>

                                            <textarea
                                                name="content[]"
                                                rows="6"
                                                class="form-control ckeditor">{{ old('content.'.$loop->index,$detail->content) }}</textarea>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

                <div class="card-footer text-end">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-save"></i>

                        Update Syllabus

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>

let chapterIndex = {{ $syllabus->details->count() }};

/*
|--------------------------------------------------------------------------
| Add New Chapter
|--------------------------------------------------------------------------
*/

$('#addRow').on('click', function () {

    chapterIndex++;

    let editorId = 'editor_' + Date.now();

    let html = `

    <div class="card border mb-3 chapter-item">

        <div class="card-header bg-light d-flex justify-content-between align-items-center">

            <strong>

                Chapter #${chapterIndex}

            </strong>

            <button
                type="button"
                class="btn btn-danger btn-sm removeRow">

                <span class="material-symbols-outlined">

                    delete

                </span>

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
                        id="${editorId}"
                        name="content[]"
                        rows="6"
                        class="form-control"></textarea>

                </div>

            </div>

        </div>

    </div>

    `;

    $('#chapterContainer').append(html);

    ClassicEditor
        .create(document.querySelector('#' + editorId))
        .catch(error => {
            console.error(error);
        });

});


/*
|--------------------------------------------------------------------------
| Remove Chapter
|--------------------------------------------------------------------------
*/

$(document).on('click', '.removeRow', function () {

    if ($('.chapter-item').length == 1) {

        alert('At least one chapter is required.');

        return;

    }

    $(this)
        .closest('.chapter-item')
        .remove();

    updateChapterNumbers();

});


/*
|--------------------------------------------------------------------------
| Update Chapter Number
|--------------------------------------------------------------------------
*/

function updateChapterNumbers() {

    chapterIndex = 0;

    $('.chapter-item').each(function () {

        chapterIndex++;

        $(this)
            .find('.card-header strong')
            .text('Chapter #' + chapterIndex);

        $(this)
            .find('.chapter-number')
            .val(chapterIndex);

    });

}
/*
|--------------------------------------------------------------------------
| CKEditor Initialize (Existing Editors)
|--------------------------------------------------------------------------
*/

document.querySelectorAll('.ckeditor').forEach(function (element) {

    ClassicEditor
        .create(element)
        .catch(error => {

            console.error(error);

        });

});


/*
|--------------------------------------------------------------------------
| Auto Scroll To Newly Added Chapter
|--------------------------------------------------------------------------
*/

$('#addRow').click(function () {

    setTimeout(function () {

        $('html, body').animate({

            scrollTop: $('.chapter-item:last').offset().top - 120

        }, 500);

    }, 150);

});


/*
|--------------------------------------------------------------------------
| Form Validation
|--------------------------------------------------------------------------
*/

$('form').submit(function () {

    let valid = true;

    $('input[name="title[]"]').each(function () {

        if ($(this).val().trim() == '') {

            alert('Chapter title is required.');

            $(this).focus();

            valid = false;

            return false;

        }

    });

    if (!valid) {

        return false;

    }

    return true;

});


/*
|--------------------------------------------------------------------------
| Select2
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    $('.select2').select2({

        width: '100%'

    });

});

</script>

@endpush
