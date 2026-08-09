@extends('backend.partial.master')
@section('title', 'Roles')
@section('backend-content')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">
            <div class="card-header pb-0"><h4>Roles</h4></div>
            <form class="form theme-form" method="post" action="{{ route('roles.store') }}">
                @csrf
            <div class="card-body">
                @if(session('message'))
                    {!! session('message') !!}
                @endif
                <div class="form-group mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="roles" value="{{ old('roles') }}" placeholder="Super admin,manager....">
                    <span class="text-danger">@error('roles'){{ $message }} @enderror</span>
                </div>
                {{-- @if ($permissions->isNotEmpty())
                <div class="form-group mb-3">
                    <div class="row">
                        @foreach ($permissions as $key => $item)
                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="permission{{ $key }}" value="{{ $item->name }}"
                                    {{ (is_array(old('permissions')) && in_array($item->name, old('permissions'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission{{ $key }}">{{ $item->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <span class="text-danger">@error('permissions'){{ $message }} @enderror</span>
                </div>

                @endif --}}
                @if ($permissions->isNotEmpty())

                    @foreach ($permissionCategory as $category)

                        {{-- Category Title --}}
                        <h5 class="mt-3 mb-2 text-dark rounded border bg-light p-1">{{ $category->name }}</h5>
                        <div class="row">

                            {{-- Loop all permissions inside this category --}}
                            @foreach ($permissions->where('permission_category_id', $category->id) as $key => $item)
                                <div class="col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            id="permission{{ $item->id }}"
                                            name="permissions[]"
                                            value="{{ $item->name }}"
                                            {{ (is_array(old('permissions')) && in_array($item->name, old('permissions'))) ? 'checked' : '' }}>
                                        
                                        <label class="form-check-label" for="permission{{ $item->id }}">
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    @endforeach


                    {{-- Show Uncategorized Permissions --}}
                    @php
                        $uncategorized = $permissions->whereNull('permission_category_id');
                    @endphp

                    @if ($uncategorized->count())
                        <h5 class="mt-3 mb-2 text-warning">Uncategorized</h5>
                        <div class="row">
                            @foreach ($uncategorized as $item)
                                <div class="col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            id="permission{{ $item->id }}"
                                            name="permissions[]"
                                            value="{{ $item->name }}"
                                            {{ (is_array(old('permissions')) && in_array($item->name, old('permissions'))) ? 'checked' : '' }}>
                                        
                                        <label class="form-check-label" for="permission{{ $item->id }}">
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Validation error message --}}
                    <span class="text-danger">@error('permissions') {{ $message }} @enderror</span>

                @endif

            </div>
            <div class="card-footer text-end py-2">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0"><h4>Roles List</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-bordered dt-responsive nowrap"  id="responsive-datatable">
                      <thead>
                        <tr>
                          <th scope="col">Id</th>
                          <th scope="col">Name</th>
                          <th scope="col">Permission</th>
                          <th scope="col" id="no-export">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($roles as $key => $role)
                        <tr>
                          <td>{{ ++$key }}</td>
                          <td>{{ $role->name }}</td>
                          <td>
                            @if ($role->permissions->count())
                                @foreach ($role->permissions as $index => $permission)

                                    <span class="badge bg-primary me-1 mb-1">{{ $permission->name }}</span>

                                    @if (($index + 1) % 5 == 0)
                                        <br>
                                    @endif

                                @endforeach
                            @else
                                <span class="badge bg-secondary">No Permissions</span>
                            @endif

                          </td>
                          <td>
                            <a href="{{ route('roles.edit',$role->id) }}" aria-label="anchor" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                            </a>
                            <a href="{{ route('roles.destroy',$role->id) }}" aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                            </a>
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
