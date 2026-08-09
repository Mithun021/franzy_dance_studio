@extends('backend.partial.master')

@section('title','Add Student')

@section('backend-content')

<div class="container-fluid">

    <form action="{{ route('students.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">

                    Add New Student

                </h4>

            </div>

            <div class="card-body">
                @if(session('success'))

                    <div class="alert alert-success">

                        {{ session('success') }}

                    </div>

                    @endif


                    @if(session('error'))

                    <div class="alert alert-danger">

                        {{ session('error') }}

                    </div>

                    @endif


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

                        <div class="col-12">

                            <h5 class="border-bottom pb-2 mb-4">

                                Basic Information

                            </h5>

                        </div>

                        {{--=============================
Basic Information
==============================--}}

<div class="col-md-4 mb-3">
    <label class="form-label">Full Name <span class="text-danger">*</span></label>
    <input type="text"
           name="name"
           value="{{ old('name') }}"
           class="form-control @error('name') is-invalid @enderror">

    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-md-4 mb-3">
    <label class="form-label">Email</label>

    <input type="email"
           name="email"
           value="{{ old('email') }}"
           class="form-control">
</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Mobile Number <span class="text-danger">*</span>

    </label>

    <input type="text"
           name="phone"
           value="{{ old('phone') }}"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Password

    </label>

    <input type="password"
           name="password"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Confirm Password

    </label>

    <input type="password"
           name="password_confirmation"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Date Of Birth

    </label>

    <input type="date"
           name="date_of_birth"
           value="{{ old('date_of_birth') }}"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Gender

    </label>

    <select name="gender"
            class="form-control">

        <option value="">Select</option>

        <option value="Male">Male</option>

        <option value="Female">Female</option>

        <option value="Other">Other</option>

    </select>

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Religion

    </label>

    <input type="text"
           name="religion"
           value="{{ old('religion') }}"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Mother Tongue

    </label>

    <input type="text"
           name="mother_tongue"
           value="{{ old('mother_tongue') }}"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Qualification

    </label>

    <input type="text"
           name="qualification"
           value="{{ old('qualification') }}"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Occupation

    </label>

    <input type="text"
           name="occupation"
           value="{{ old('occupation') }}"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        WhatsApp

    </label>

    <input type="text"
           name="whatsapp_no"
           value="{{ old('whatsapp_no') }}"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Profile Image

    </label>

    <input type="file"
           name="profile_image"
           class="form-control">

</div>

<div class="col-md-4 mb-3">

    <label class="form-label">

        Signature

    </label>

    <input type="file"
           name="signature"
           class="form-control">

</div>

</div>

<hr>

<h5 class="mb-3">

Guardian Information

</h5>

<div class="row">

<div class="col-md-4 mb-3">

<input type="text"
       name="guardian_name"
       value="{{ old('guardian_name') }}"
       class="form-control"
       placeholder="Guardian Name">

</div>

<div class="col-md-4 mb-3">

<input type="text"
       name="guardian_contact"
       value="{{ old('guardian_contact') }}"
       class="form-control"
       placeholder="Guardian Contact">

</div>

<div class="col-md-4 mb-3">

<input type="text"
       name="guardian_occupation"
       value="{{ old('guardian_occupation') }}"
       class="form-control"
       placeholder="Guardian Occupation">

</div>

</div>

<hr>

<h5 class="mb-3">

Local Guardian

</h5>

<div class="row">

<div class="col-md-6 mb-3">

<input type="text"
       name="local_guardian_name"
       value="{{ old('local_guardian_name') }}"
       class="form-control"
       placeholder="Local Guardian Name">

</div>

<div class="col-md-6 mb-3">

<input type="text"
       name="local_guardian_relation"
       value="{{ old('local_guardian_relation') }}"
       class="form-control"
       placeholder="Relation">

</div>

</div>

<hr>

<h5 class="mb-3">

Address

</h5>

<div class="row">

<div class="col-md-12 mb-3">

<textarea name="address"
          rows="3"
          class="form-control"
          placeholder="Address">{{ old('address') }}</textarea>

</div>

<div class="col-md-3 mb-3">

<input type="text"
       name="city"
       value="{{ old('city') }}"
       class="form-control"
       placeholder="City">

</div>

<div class="col-md-3 mb-3">

<input type="text"
       name="state"
       value="{{ old('state') }}"
       class="form-control"
       placeholder="State">

</div>

<div class="col-md-3 mb-3">

<input type="text"
       name="country"
       value="{{ old('country') }}"
       class="form-control"
       placeholder="Country">

</div>

<div class="col-md-3 mb-3">

<input type="text"
       name="pincode"
       value="{{ old('pincode') }}"
       class="form-control"
       placeholder="Pincode">

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

Status

</label>

<select name="is_active"
        class="form-control">

    <option value="Yes">Active</option>

    <option value="No">Inactive</option>

</select>

</div>

</div>

<hr>

<div class="text-end">

    <a href="{{ route('student.list') }}"
       class="btn btn-secondary">

        Cancel

    </a>

    <button class="btn btn-primary">

        <i class="fa fa-save"></i>

        Save Student

    </button>

</div>

</div>

</div>

</form>

</div>

@endsection
