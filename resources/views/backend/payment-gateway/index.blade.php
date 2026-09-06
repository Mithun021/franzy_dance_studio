@extends('backend.partial.master')

@section('title', 'Payment Gateway')

@section('backend-content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Payment Gateway Settings
                    </h4>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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

                    <form action="{{ route('payment-gateway.razorpay.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-12 mb-4">
                                <h5 class="border-bottom pb-2">
                                    Razorpay
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Razorpay Key ID
                                </label>

                                <input
                                    type="text"
                                    name="key_id"
                                    class="form-control"
                                    value="{{ old('key_id', $razorpay['key_id']) }}"
                                    placeholder="rzp_live_xxxxxxxxxx"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Razorpay Key Secret
                                </label>

                                <input
                                    type="password"
                                    name="key_secret"
                                    class="form-control"
                                    value="{{ old('key_secret', $razorpay['key_secret']) }}"
                                    placeholder="Enter Razorpay secret"
                                    required
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Mode
                                </label>

                                <select name="mode" class="form-select">
                                    <option
                                        value="sandbox"
                                        {{ old('mode', $razorpay['mode']) == 'sandbox' ? 'selected' : '' }}
                                    >
                                        Sandbox
                                    </option>

                                    <option
                                        value="live"
                                        {{ old('mode', $razorpay['mode']) == 'live' ? 'selected' : '' }}
                                    >
                                        Live
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">
                                    Payment Gateway Status
                                </label>

                                <div class="form-check form-switch mt-2">
                                    <input
                                        type="hidden"
                                        name="enabled"
                                        value="0"
                                    >

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="enabled"
                                        value="1"
                                        id="razorpay_enabled"
                                        {{ old('enabled', $razorpay['enabled']) ? 'checked' : '' }}
                                    >

                                    <label
                                        class="form-check-label"
                                        for="razorpay_enabled"
                                    >
                                        Enable Razorpay
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="ri-save-line me-1"></i>
                                    Update Razorpay Settings
                                </button>

                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection
