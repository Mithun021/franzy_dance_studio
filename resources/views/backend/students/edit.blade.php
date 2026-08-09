@extends('backend.partial.master')

@section('title','Edit Student')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <form action="{{ route('students.update',$student->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="card">

                <div class="card-header bg-warning text-dark">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h3 class="mb-1">

                                Edit Student Profile

                            </h3>

                            <small>

                                Update student personal information

                            </small>

                        </div>

                        <a href="{{ route('students.view',$student->id) }}"
                            class="btn btn-dark">

                            <i class="fa fa-arrow-left"></i>

                            Back

                        </a>

                    </div>

                </div>

                <div class="card-body">
                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                        @endif


                        @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                        @endif

                        <h4 class="mb-4">

                            Documents

                        </h4>

                        <div class="row">

                            <div class="col-md-6">

                                <div class="card">

                                    <div class="card-header">

                                        Profile Image

                                    </div>

                                    <div class="card-body text-center">

                                        @if($student->profile_image)

                                            <img src="{{ asset('storage/'.$student->profile_image) }}"
                                                class="img-thumbnail mb-3"
                                                style="height:180px;object-fit:cover;">

                                        @else

                                            <img src="{{ asset('backend/images/user.png') }}"
                                                class="img-thumbnail mb-3"
                                                style="height:180px;object-fit:cover;">

                                        @endif

                                        <input type="file"
                                            name="profile_image"
                                            class="form-control">

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="card">

                                    <div class="card-header">

                                        Signature

                                    </div>

                                    <div class="card-body text-center">

                                        @if($student->signature)

                                            <img src="{{ asset('storage/'.$student->signature) }}"
                                                class="img-thumbnail mb-3"
                                                style="height:180px;object-fit:contain;">

                                        @else

                                            <p class="text-muted mt-5">

                                                No Signature Uploaded

                                            </p>

                                        @endif

                                        <input type="file"
                                            name="signature"
                                            class="form-control">

                                    </div>

                                </div>

                            </div>

                        </div>

                        <hr class="my-4">

                        <h4 class="mb-4">

                            Basic Information

                        </h4>

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Name

                                </label>

                                <input type="text"
                                    name="name"
                                    value="{{ old('name',$student->name) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input type="email"
                                    name="email"
                                    value="{{ old('email',$student->email) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Phone

                                </label>

                                <input type="text"
                                    name="phone"
                                    value="{{ old('phone',$student->phone) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    WhatsApp

                                </label>

                                <input type="text"
                                    name="whatsapp_no"
                                    value="{{ old('whatsapp_no',$student->whatsapp_no) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- ==========================================
                                Personal Information
                        =========================================== -->

                        <h4 class="mb-4">

                            Personal Information

                        </h4>

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Date of Birth

                                </label>

                                <input type="date"
                                    name="date_of_birth"
                                    value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Gender

                                </label>

                                <select name="gender"
                                        class="form-control">

                                    <option value="">Select Gender</option>

                                    <option value="Male"
                                        {{ old('gender',$student->gender)=='Male' ? 'selected' : '' }}>

                                        Male

                                    </option>

                                    <option value="Female"
                                        {{ old('gender',$student->gender)=='Female' ? 'selected' : '' }}>

                                        Female

                                    </option>

                                    <option value="Other"
                                        {{ old('gender',$student->gender)=='Other' ? 'selected' : '' }}>

                                        Other

                                    </option>

                                </select>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Religion

                                </label>

                                <input type="text"
                                    name="religion"
                                    value="{{ old('religion',$student->religion) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Mother Tongue

                                </label>

                                <input type="text"
                                    name="mother_tongue"
                                    value="{{ old('mother_tongue',$student->mother_tongue) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Occupation

                                </label>

                                <input type="text"
                                    name="occupation"
                                    value="{{ old('occupation',$student->occupation) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Qualification

                                </label>

                                <input type="text"
                                    name="qualification"
                                    value="{{ old('qualification',$student->qualification) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- ==========================================
                                Guardian Details
                        =========================================== -->

                        <h4 class="mb-4">

                            Guardian Details

                        </h4>

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Guardian Name

                                </label>

                                <input type="text"
                                    name="guardian_name"
                                    value="{{ old('guardian_name',$student->guardian_name) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Guardian Contact

                                </label>

                                <input type="text"
                                    name="guardian_contact"
                                    value="{{ old('guardian_contact',$student->guardian_contact) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Guardian Occupation

                                </label>

                                <input type="text"
                                    name="guardian_occupation"
                                    value="{{ old('guardian_occupation',$student->guardian_occupation) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- ==========================================
                                Local Guardian
                        =========================================== -->

                        <h4 class="mb-4">

                            Local Guardian

                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Local Guardian Name

                                </label>

                                <input type="text"
                                    name="local_guardian_name"
                                    value="{{ old('local_guardian_name',$student->local_guardian_name) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Relation

                                </label>

                                <input type="text"
                                    name="local_guardian_relation"
                                    value="{{ old('local_guardian_relation',$student->local_guardian_relation) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- ==========================================
                                Address Information
                        =========================================== -->

                        <h4 class="mb-4">

                            Address Information

                        </h4>

                        <div class="row">

                            <div class="col-md-12 mb-3">

                                <label class="form-label">

                                    Address

                                </label>

                                <textarea name="address"
                                        rows="3"
                                        class="form-control">{{ old('address',$student->address) }}</textarea>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="form-label">

                                    City

                                </label>

                                <input type="text"
                                    name="city"
                                    value="{{ old('city',$student->city) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">

                                    State

                                </label>

                                <input type="text"
                                    name="state"
                                    value="{{ old('state',$student->state) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">

                                    Country

                                </label>

                                <input type="text"
                                    name="country"
                                    value="{{ old('country',$student->country) }}"
                                    class="form-control">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">

                                    Pincode

                                </label>

                                <input type="text"
                                    name="pincode"
                                    value="{{ old('pincode',$student->pincode) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- ==========================================
                                Account Information
                        =========================================== -->

                        <h4 class="mb-4">

                            Account Settings

                        </h4>

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    User Type

                                </label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ ucfirst($student->user_type) }}"
                                    readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Account Status

                                </label>

                                <select name="is_active"
                                        class="form-control">

                                    <option value="yes"
                                        {{ old('is_active',$student->is_active)=="yes" ? 'selected' : '' }}>

                                        Active

                                    </option>

                                    <option value="no"
                                        {{ old('is_active',$student->is_active)=="no" ? 'selected' : '' }}>

                                        Inactive

                                    </option>

                                </select>

                            </div>



                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Change Password (Optional)

                                </label>

                                <input type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Leave blank if you don't want to change">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Confirm Password

                                </label>

                                <input type="password"
                                    name="password_confirmation"
                                    class="form-control">

                            </div>

                        </div>

                        <hr>

                        <hr>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('students.view',$student->id) }}"
                            class="btn btn-secondary">

                                <i class="fa fa-arrow-left"></i>

                                Back

                            </a>

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Update Student

                            </button>

                        </div>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        @endsection
