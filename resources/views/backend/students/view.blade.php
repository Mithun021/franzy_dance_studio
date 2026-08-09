@extends('backend.partial.master')

@section('title','Student Details')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <div class="card-header bg-primary text-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h3 class="mb-1">

                            Student Profile

                        </h3>

                        <small>

                            Complete Student Information

                        </small>

                    </div>

                    <a href="{{ route('student.list') }}"
                        class="btn btn-light">

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="row">

                    <!-- Profile -->

                    <div class="col-lg-3">

                        <div class="text-center">

                            @if($student->profile_image)

                                <img src="{{ asset('storage/'.$student->profile_image) }}"
                                    class="img-fluid rounded-circle border shadow"
                                    style="width:180px;height:180px;object-fit:cover;">

                            @else

                                <img src="{{ asset('backend/images/user.png') }}"
                                    class="img-fluid rounded-circle border shadow"
                                    style="width:180px;height:180px;object-fit:cover;">

                            @endif

                            <h4 class="mt-3">

                                {{ $student->name }}

                            </h4>

                            <p class="text-muted">

                                Student

                            </p>

                            @if($student->is_active == 'yes')

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            @endif

                        </div>

                    </div>

                    <!-- Basic Information -->

                    <div class="col-lg-9">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="fw-bold">

                                    Mobile

                                </label>

                                <p>

                                    {{ $student->phone }}

                                </p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="fw-bold">

                                    WhatsApp

                                </label>

                                <p>

                                    {{ $student->whatsapp_no }}

                                </p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="fw-bold">

                                    Email

                                </label>

                                <p>

                                    {{ $student->email ?: 'N/A' }}

                                </p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="fw-bold">

                                    Gender

                                </label>

                                <p>

                                    {{ $student->gender }}

                                </p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="fw-bold">

                                    Date Of Birth

                                </label>

                                <p>

                                    {{ optional($student->date_of_birth)->format('d M Y') }}

                                </p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="fw-bold">

                                    Religion

                                </label>

                                <p>

                                    {{ $student->religion }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <hr class="my-4">
                <!-- ==========================
                        Personal Details
                =========================== -->

                <h4 class="mb-4">

                    Personal Information

                </h4>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">

                            Occupation

                        </label>

                        <p>

                            {{ $student->occupation ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">

                            Qualification

                        </label>

                        <p>

                            {{ $student->qualification ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">

                            Mother Tongue

                        </label>

                        <p>

                            {{ $student->mother_tongue ?: 'N/A' }}

                        </p>

                    </div>

                </div>

                <hr>

                <!-- ==========================
                        Guardian Details
                =========================== -->

                <h4 class="mb-4">

                    Guardian Details

                </h4>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">

                            Guardian Name

                        </label>

                        <p>

                            {{ $student->guardian_name ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">

                            Contact Number

                        </label>

                        <p>

                            {{ $student->guardian_contact ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">

                            Occupation

                        </label>

                        <p>

                            {{ $student->guardian_occupation ?: 'N/A' }}

                        </p>

                    </div>

                </div>

                <hr>

                <!-- ==========================
                    Local Guardian
                =========================== -->

                <h4 class="mb-4">

                    Local Guardian

                </h4>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="fw-bold">

                            Local Guardian Name

                        </label>

                        <p>

                            {{ $student->local_guardian_name ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="fw-bold">

                            Relation

                        </label>

                        <p>

                            {{ $student->local_guardian_relation ?: 'N/A' }}

                        </p>

                    </div>

                </div>

                <hr>

                <!-- ==========================
                        Address
                =========================== -->

                <h4 class="mb-4">

                    Address Information

                </h4>

                <div class="row">

                    <div class="col-md-12 mb-3">

                        <label class="fw-bold">

                            Address

                        </label>

                        <p>

                            {{ $student->address ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="fw-bold">

                            City

                        </label>

                        <p>

                            {{ $student->city ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="fw-bold">

                            State

                        </label>

                        <p>

                            {{ $student->state ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="fw-bold">

                            Country

                        </label>

                        <p>

                            {{ $student->country ?: 'N/A' }}

                        </p>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="fw-bold">

                            Pincode

                        </label>

                        <p>

                            {{ $student->pincode ?: 'N/A' }}

                        </p>

                    </div>

                </div>

                <hr>

                <!-- ==========================
                    Documents
                =========================== -->

                <h4 class="mb-4">

                    Documents

                </h4>

                <div class="row">

                    <div class="col-md-6">

                        <div class="card border">

                            <div class="card-header">

                                Profile Photo

                            </div>

                            <div class="card-body text-center">

                                @if($student->profile_image)

                                    <img src="{{ asset('storage/'.$student->profile_image) }}"
                                        class="img-thumbnail"
                                        style="max-height:220px;">

                                @else

                                    <p class="text-muted">

                                        No Profile Uploaded

                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="card border">

                            <div class="card-header">

                                Signature

                            </div>

                            <div class="card-body text-center">

                                @if($student->signature)

                                    <img src="{{ asset('storage/'.$student->signature) }}"
                                        class="img-thumbnail"
                                        style="max-height:220px;">

                                @else

                                    <p class="text-muted">

                                        No Signature Uploaded

                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
