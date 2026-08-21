@extends('backend.partial.master')

@section('title','New Billing')

@section('backend-content')

<div class="container-fluid">


    {{-- ========================================================= --}}
    {{-- Alerts --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

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



    {{-- ========================================================= --}}
    {{-- Billing Form --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('billing.store') }}"
        method="POST"
        id="billingForm">

        @csrf


        <div class="card shadow">


            {{-- ================================================= --}}
            {{-- Header --}}
            {{-- ================================================= --}}

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-0">
                        Student Billing
                    </h4>

                    <small>
                        Collect Student Fees
                    </small>

                </div>


                <a
                    href="{{ route('billing.index') }}"
                    class="btn btn-light">

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>

            </div>



            {{-- ================================================= --}}
            {{-- Body --}}
            {{-- ================================================= --}}

            <div class="card-body">


                {{-- ================================================= --}}
                {{-- 1. STUDENT & COURSE --}}
                {{-- ================================================= --}}

                @include('backend.billing.partials.student-course')



                <hr class="my-4">



                {{-- ================================================= --}}
                {{-- 2. COURSE INFORMATION --}}
                {{-- ================================================= --}}

                @include('backend.billing.partials.course-summary')



                <hr class="my-4">



                {{-- ================================================= --}}
                {{-- 3. FEE SUMMARY --}}
                {{-- ================================================= --}}

                @include('backend.billing.partials.late-course-fee')
                @include('backend.billing.partials.fee-summary')
                {{-- @include('backend.billing.partials.late-course-fee') --}}



                <hr class="my-4">



                {{-- ================================================= --}}
                {{-- 4. PAYMENT ENTRIES --}}
                {{-- ================================================= --}}

                @include('backend.billing.partials.payments-entries')



                {{-- ================================================= --}}
                {{-- FORM ACTIONS --}}
                {{-- ================================================= --}}

                <div class="d-flex justify-content-between mt-4">


                    <a
                        href="{{ route('billing.index') }}"
                        class="btn btn-secondary">

                        <i class="fa fa-arrow-left me-1"></i>

                        Back

                    </a>


                    <button
                        type="submit"
                        class="btn btn-success"
                        id="saveBillingBtn">

                        <i class="fa fa-save me-1"></i>

                        Save Billing

                    </button>

                </div>


            </div>

        </div>

    </form>

</div>

@endsection
@push('scripts')
@include('backend.billing.js.billing-js')
@endpush
