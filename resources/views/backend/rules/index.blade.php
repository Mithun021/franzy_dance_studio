@extends('backend.partial.master')

@section('title','Rules & Terms')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="fas fa-gavel"></i>

                    Rules & Terms Management

                </h5>

            </div>

            <form action="{{ route('rules.store') }}"
                method="POST">

                @csrf

                <div class="card-body">

                    <div class="mb-4">

                        <label class="form-label fw-bold">

                            Studio Rules

                        </label>

                        <textarea
                            name="rules"
                            id="rules"
                            class="form-control ckeditor"
                            rows="12">{{ old('rules',$rules->rules ?? '') }}</textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-bold">

                            Terms & Conditions

                        </label>

                        <textarea
                            name="terms_condition"
                            id="terms_condition"
                            class="form-control ckeditor"
                            rows="12">{{ old('terms_condition',$rules->terms_condition ?? '') }}</textarea>

                    </div>

                </div>

                <div class="card-footer text-end">

                    <button class="btn btn-success">

                        <i class="fas fa-save"></i>

                        {{ $rules ? 'Update' : 'Save' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
