@extends('backend.partial.master')

@section('title', 'Fee Structure')

@section('backend-content')

<div class="row">

    <div class="col-md-12">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Fee Structure List</h4>

                <a href="{{ route('fee-structures.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Fee Structure
                </a>

            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped" id="responsive-datatable">

                    <thead>

                        <tr>

                            <th width="60">SL</th>

                            <th>Course</th>

                            <th>Level</th>

                            {{-- <th>Category</th> --}}

                            <th>Registration Fee</th>

                            <th>Admission Fee</th>

                            <th>Monthly Fee</th>

                            <th width="170">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($feeStructures as $key => $fee)

                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>{{ $fee->course->course_name }}</td>

                                <td>{{ $fee->level->name }}</td>

                                {{-- <td>{{ $fee->category->name }}</td> --}}

                                <td>₹ {{ number_format($fee->registration_fee,2) }}</td>

                                <td>₹ {{ number_format($fee->admission_fee,2) }}</td>

                                <td>₹ {{ number_format($fee->monthly_fee,2) }}</td>

                                <td>

                                    <a href="{{ route('fee-structures.edit',$fee->id) }}"
                                       class="btn btn-warning btn-sm">

                                        Edit

                                    </a>

                                    <form action="{{ route('fee-structures.destroy',$fee->id) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure want to delete this Fee Structure?')">

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center text-danger">

                                    No Fee Structure Found

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
