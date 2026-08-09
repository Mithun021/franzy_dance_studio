@extends('backend.partial.master')
@section('title', 'Permission')
@section('backend-content')

<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="card">
            <div class="card-header pb-0"><h4>Permission</h4></div>
            <form class="form theme-form" method="post" action="{{ route('permission.store') }}">
                @csrf
            <div class="card-body">
                @if(session('message'))
                    {!! session('message') !!}
                @endif
                <div class="form-group">
                    <label for="">Permission Category</label>
                    <select name="permission_category_id" class="form-control">
                        <option value="">--Select--</option>
                    @foreach ($permissionCategory as $item)
                        <option value="{{ $item->id }}"
                        {{ old('permission_category_id', $permission->permission_category_id ?? '') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                    </select>
                    <span class="text-danger">@error('permission_category_id'){{ $message }} @enderror</span>
                </div>
                <div class="form-group mb-3 mt-2">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="permission_name" placeholder="add user,show user, edit user...." value="{{ old('permission_name') }}">
                    <span class="text-danger">@error('permission_name'){{ $message }} @enderror</span>
                </div>
            </div>
            <div class="card-footer text-end py-2">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
    <div class="col-sm-12 col-md-8">
        <div class="card">
            <div class="card-header pb-0"><h4>Permission List</h4></div>
            <div class="card-body">
                <div class="dt-ext">
                    <table class="table table-bordered table-bordered dt-responsive nowrap"  id="responsive-datatable">
                      <thead>
                        <tr>
                          <th scope="col">Id</th>
                          <th scope="col">Name</th>
                          <th scope="col" id="no-export">Action</th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach ($permissionCategory as $category)

                            {{-- Category Header Row --}}
                            <tr class="table-warning">
                                <td colspan="3" class="fw-bold">{{ $category->name }}</td>
                            </tr>

                            {{-- List Permissions Under This Category --}}
                            @foreach ($permissions->where('permission_category_id', $category->id) as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <a class="btn btn-icon btn-sm bg-primary-subtle me-1">
                                            <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                        </a>
                                        <a class="btn btn-icon btn-sm bg-danger-subtle">
                                            <i class="mdi mdi-delete fs-14 text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                        @endforeach


                        {{-- Show Uncategorized Permissions --}}
                        @php
                        $uncategorized = $permissions->whereNull('permission_category_id');
                        @endphp

                        @if ($uncategorized->count())
                            <tr class="table-secondary">
                                <td colspan="3" class="fw-bold">Uncategorized</td>
                            </tr>

                            @foreach ($uncategorized as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <a class="btn btn-icon btn-sm bg-primary-subtle me-1">
                                            <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                        </a>
                                        <a class="btn btn-icon btn-sm bg-danger-subtle">
                                            <i class="mdi mdi-delete fs-14 text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
