@extends('backend.partial.master')

@section('title','Edit Holiday')

@section('backend-content')

<div class="container-fluid">

    <form
        action="{{ route('holidays.update',$holiday->id) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="card shadow">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    Edit Holiday

                </h5>

                <a href="{{ route('holidays.index') }}"
                   class="btn btn-secondary">

                    <i class="fa fa-arrow-left"></i>

                    Back

                </a>

            </div>

            <div class="card-body">

                @if($errors->any())

                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <div class="row">

                    {{-- Holiday Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Holiday Name

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="holiday_name"
                            class="form-control @error('holiday_name') is-invalid @enderror"
                            value="{{ old('holiday_name',$holiday->holiday_name) }}"
                            placeholder="Enter Holiday Name">

                        @error('holiday_name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Holiday Date --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Holiday Date

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="holiday_date"
                            class="form-control @error('holiday_date') is-invalid @enderror"
                            value="{{ old('holiday_date',$holiday->holiday_date->format('Y-m-d')) }}">

                        @error('holiday_date')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Holiday Type --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Holiday Type

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="holiday_type"
                            class="form-select @error('holiday_type') is-invalid @enderror">

                            <option value="">

                                Select Holiday Type

                            </option>

                            <option
                                value="Festival"
                                {{ old('holiday_type',$holiday->holiday_type)=='Festival' ? 'selected' : '' }}>

                                Festival

                            </option>

                            <option
                                value="Weekly Off"
                                {{ old('holiday_type',$holiday->holiday_type)=='Weekly Off' ? 'selected' : '' }}>

                                Weekly Off

                            </option>

                        </select>

                        @error('holiday_type')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            <div class="card-footer d-flex justify-content-between">

                <a href="{{ route('holidays.index') }}"
                   class="btn btn-secondary">

                    <i class="fa fa-times"></i>

                    Cancel

                </a>

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="fa fa-save"></i>

                    Update Holiday

                </button>

            </div>

        </div>

    </form>

</div>

@endsection
