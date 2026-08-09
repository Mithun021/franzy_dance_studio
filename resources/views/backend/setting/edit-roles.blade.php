@extends('backend.partial.master')
@section('title', 'Edit Roles')
@section('backend-content')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">
            <div class="card-header pb-0"><h4>Edit Roles</h4></div>
            <form class="form theme-form" method="post" action="{{ route('roles.update',$role->id) }}">
                @csrf
            <div class="card-body">
                @if(session('message'))
                    {!! session('message') !!}
                @endif
                <div class="form-group mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="roles" value="{{ old('roles',$role->name) }}" placeholder="Super admin,manager....">
                    <span class="text-danger">@error('roles'){{ $message }} @enderror</span>
                </div>
                {{-- @if ($permissions->isNotEmpty())
                <div class="form-group mb-3">
                    <div class="row">
                        @foreach ($permissions as $key => $item)
                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                <div class="form-check">
                                    <input {{ $hasPermission->contains($item->name) ? 'checked' : '' }} type="checkbox" name="permissions[]" id="permission{{ $key }}" value="{{ $item->name }}"
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

                            @foreach ($permissions->where('permission_category_id', $category->id) as $item)

                                @php
                                    $isChecked =
                                        (is_array(old('permissions')) && in_array($item->name, old('permissions')))
                                        || $hasPermission->contains($item->name);
                                @endphp

                                <div class="col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            id="permission{{ $item->id }}"
                                            name="permissions[]"
                                            value="{{ $item->name }}"
                                            {{ $isChecked ? 'checked' : '' }}>

                                        <label class="form-check-label" for="permission{{ $item->id }}">
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                </div>

                            @endforeach

                        </div>

                    @endforeach


                    {{-- Uncategorized Permissions --}}
                    @php
                        $uncategorized = $permissions->whereNull('permission_category_id');
                    @endphp

                    @if ($uncategorized->count())
                        <h5 class="mt-3 mb-2 text-warning">Uncategorized</h5>
                        <div class="row">

                            @foreach ($uncategorized as $item)

                                @php
                                    $isChecked =
                                        (is_array(old('permissions')) && in_array($item->name, old('permissions')))
                                        || $hasPermission->contains($item->name);
                                @endphp

                                <div class="col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            id="permission{{ $item->id }}"
                                            name="permissions[]"
                                            value="{{ $item->name }}"
                                            {{ $isChecked ? 'checked' : '' }}>

                                        <label class="form-check-label" for="permission{{ $item->id }}">
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    @endif


                    <span class="text-danger">@error('permissions'){{ $message }}@enderror</span>

                @endif

            </div>
            <div class="card-footer text-end py-2">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection
