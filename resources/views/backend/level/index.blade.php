@extends('backend.partial.master')
@section('title', 'Level Master')

@section('backend-content')

<div class="row">

    {{-- Add Level --}}
    <div class="col-md-4">

        <div class="card">
            <div class="card-header">
                <h4>Add Level</h4>
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

                <form action="{{ route('level.store') }}" method="POST">

                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            Level Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="Enter Level Name">

                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>

                    <button type="reset" class="btn btn-secondary">
                        Reset
                    </button>

                </form>

            </div>
        </div>

    </div>


    {{-- Level List --}}
    <div class="col-md-8">

        <div class="card">

            <div class="card-header">
                <h4>Level List</h4>
            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>
                            <th width="70">SL</th>
                            <th>Level Name</th>
                            <th width="180">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($levels as $key => $level)

                        <tr>

                            <td>{{ $key+1 }}</td>

                            <td>{{ $level->name }}</td>

                            <td>

                                <a href="{{ route('level.edit',$level->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('level.destroy',$level->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure want to delete this level?')">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3" class="text-center text-danger">
                                No Record Found
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
