@extends('backend.partial.master')
@section('title', 'Edit Employee')
@section('backend-content')

<div class="row">
    <div class="col-sm-12 col-md-12">
        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Session Error --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="card">
            <div class="card-header pb-0"><h4>Edit Employee</h4></div>
            <form class="form theme-form" method="post" action="{{ route('employee.update',$user->id) }}">
                @csrf
            <div class="card-body">
                @if(session('message'))
                    {!! session('message') !!}
                @endif
                <div class="row">
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Name</label>
                        <input class="form-control" type="text" name="name" value="{{ old('name',$user->name) }}" >
                        <span class="text-danger">@error('name'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" value="{{ old('email',$user->email) }}" >
                        <span class="text-danger">@error('email'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Phone</label>
                        <input class="form-control" type="text" name="phone" value="{{ old('phone',$user->phone) }}" >
                        <span class="text-danger">@error('phone'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">City</label>
                        <input class="form-control" type="text" name="city" value="{{ old('city',$user->city) }}" >
                        <span class="text-danger">@error('city'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">State</label>
                        <input class="form-control" type="text" name="state" value="{{ old('state',$user->state) }}" >
                        <span class="text-danger">@error('state'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Monthly Salary</label>
                        <input class="form-control" type="number" name="salary" value="{{ old('salary',$user->salary) }}" step="0.01">
                        <span class="text-danger">@error('salary'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Account Type</label>

                        <select class="form-control" name="user_type">

                            <option value="faculty"
                                {{ old('user_type', $user->user_type) == 'faculty' ? 'selected' : '' }}>
                                Faculty
                            </option>

                            <option value="staff"
                                {{ old('user_type', $user->user_type) == 'staff' ? 'selected' : '' }}>
                                Staff
                            </option>

                        </select>

                        <span class="text-danger">
                            @error('user_type') {{ $message }} @enderror
                        </span>
                    </div>

                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Account Status</label>

                        <select class="form-control" name="is_active">

                            <option value="yes"
                                {{ old('is_active', $user->is_active) == 'yes' ? 'selected' : '' }}>
                                Yes
                            </option>

                            <option value="no"
                                {{ old('is_active', $user->is_active) == 'no' ? 'selected' : '' }}>
                                No
                            </option>

                        </select>

                        <span class="text-danger">
                            @error('is_active') {{ $message }} @enderror
                        </span>
                    </div>

                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control" type="password" name="password" value="{{ old('password') }}" >
                        <span class="text-danger">@error('password'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-12 form-group">
                        <div class="row">
                        @foreach ($roles as $key => $role)
                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        id="role{{ $key }}"
                                        value="{{ $role->name }}"
                                        {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) || in_array($role->id, $hasRole) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role{{ $key }}">{{ $role->name }}</label>
                                </div>
                            </div>
                        @endforeach
                        </div>
                        <span class="text-danger">@error('roles'){{ $message }} @enderror</span>
                    </div>
                </div>

            </div>
            <div class="card-footer text-end py-2">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection
