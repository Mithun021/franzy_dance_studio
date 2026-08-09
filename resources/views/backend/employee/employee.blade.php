@extends('backend.partial.master')
@section('title', 'Employee')
@section('backend-content')

<div class="row">
    @can('Add Employee')
    <div class="col-sm-12 col-md-12">
        <div class="card">
            <div class="card-header pb-0"><h4>Employee</h4></div>
            <form class="form theme-form" method="post" action="{{ route('employee.store') }}">
                @csrf
            <div class="card-body">
                @if(session('message'))
                    {!! session('message') !!}
                @endif
                <div class="row">
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Name<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name" value="{{ old('name') }}" >
                        <span class="text-danger">@error('name'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Email<span class="text-danger">*</span></label>
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" >
                        <span class="text-danger">@error('email'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Phone<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="phone" value="{{ old('phone') }}" >
                        <span class="text-danger">@error('phone'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">City</label>
                        <input class="form-control" type="text" name="city" value="{{ old('city') }}" >
                        <span class="text-danger">@error('city'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">State</label>
                        <input class="form-control" type="text" name="state" value="{{ old('state') }}" >
                        <span class="text-danger">@error('state'){{ $message }} @enderror</span>
                    </div>

                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Monthly Salary<span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="salary" value="{{ old('salary') }}" step="0.01">
                        <span class="text-danger">@error('salary'){{ $message }} @enderror</span>
                    </div>

                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Account Type</label>
                        <select class="form-control" name="user_type">
                            <option value="faculty" {{ old('user_type') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="staff" {{ old('user_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>

                        <span class="text-danger">@error('user_type'){{ $message }} @enderror</span>
                    </div>

                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Account Status</label>
                        <select class="form-control" name="is_active">
                            <option value="yes" {{ old('is_active') == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ old('is_active') == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                        <span class="text-danger">@error('is_active'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label class="form-label">Password<span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="password" value="{{ old('password') }}" >
                        <span class="text-danger">@error('password'){{ $message }} @enderror</span>
                    </div>
                    <div class="col-12 form-group">
                                <p>Select Required Roles(multiple)<span class="text-danger">*</span></p>
                        <div class="row">
                        @foreach ($roles as $key => $role)
                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" id="role{{ $key }}" value="{{ $role->name }}"
                                    {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) ? 'checked' : '' }}>
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
    @endcan
    @can('Show Employee')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0"><h4>Employee List</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-bordered dt-responsive nowrap"  id="responsive-datatable">
                      <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Roles</th>
                            <th>Status</th>
                            @canany(['Edit Employee','Delete Employee'])
                            <th scope="col" id="no-export">Action</th>
                            @endcanany
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>{{ $user->city ?? '-' }}</td>
                                <td>{{ $user->state ?? '-' }}</td>
                                <td>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-success">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No Roles</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active == "yes")
                                        <span class="badge bg-primary">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                @canany(['Edit Employee','Delete Employee'])
                                <td>
                                    @if(!$user->hasRole('Super Admin'))
                                        @can('Edit Employee')
                                        <a href="{{ route('employee.edit',$user->id) }}" aria-label="anchor" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                            <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                        </a>
                                        @endcan
                                        @can('Delete Employee')
                                        <a href="{{ route('employee.destroy',$user->id) }}" aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                            <i class="mdi mdi-delete fs-14 text-danger"></i>
                                        </a>
                                        @endcan
                                    @endif
                                </td>
                                @endcanany
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No employees found.</td>
                            </tr>
                        @endforelse
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>

@endsection
