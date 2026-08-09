@extends('backend.partial.master')
@section('title', 'Permission Category')
@section('backend-content')

<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="card">
            <div class="card-header pb-0"><h4>Permission Category</h4></div>
            <form class="form theme-form" method="post" action="{{ route('permission-categories.store') }}">
                @csrf
            <div class="card-body">
                @if(session('message'))
                    {!! session('message') !!}
                @endif
                <div class="form-group mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="name" placeholder="Users, Products, Category, Customer...." value="{{ old('name') }}">
                    <span class="text-danger">@error('name'){{ $message }} @enderror</span>
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
            <div class="card-header pb-0"><h4>Permission Category List</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-bordered dt-responsive nowrap"  id="responsive-datatable">
                      <thead>
                        <tr>
                          <th scope="col">Id</th>
                          <th scope="col">Name</th>
                          <th scope="col" id="no-export">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($permissions as $key => $item)
                        <tr>
                          <td>{{ ++$key }}</td>
                          <td>{{ $item->name }}</td>
                          <td>
                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                            </a>
                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle" data-bs-toggle="tooltip" data-bs-original-title="Delete">
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
