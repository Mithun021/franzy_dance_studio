@extends('backend.partial.master')

@section('title', 'Salary Management')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="card-title mb-0">
                    Salary Management
                </h4>

                <a href="{{ route('salary-management.create') }}"
                   class="btn btn-primary">

                    <i class="fa fa-plus"></i> Add Salary

                </a>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped nowrap w-100"
                           id="responsive-datatable">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Salary ID</th>

                                <th>Employee ID</th>

                                <th>Employee Name</th>

                                <th>Salary Month</th>

                                <th>Salary Amount</th>

                                <th>Paid Amount</th>

                                <th>Due Amount</th>

                                <th>Payment Method</th>

                                <th>Created By</th>

                                <th width="120">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($salaries as $key => $salary)

                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $salary->salary_id }}</td>

                                    <td>{{ $salary->employee->user_id ?? '-' }}</td>

                                    <td>{{ $salary->employee->name ?? '-' }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($salary->salary_month)->format('M Y') }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($salary->salary_amount,2) }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($salary->paid_amount,2) }}
                                    </td>

                                    <td>

                                        @if($salary->due_amount > 0)

                                            <span class="badge bg-danger">
                                                ₹ {{ number_format($salary->due_amount,2) }}
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                Paid
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        {{ $salary->payment_method ?? '-' }}

                                    </td>

                                    <td>

                                        {{ $salary->creator->name ?? '-' }}

                                    </td>

                                    <td>

                                        <a href="{{ route('salary-management.show',$salary->id) }}"
                                        class="btn btn-sm btn-info">

                                            <i class="mdi mdi-eye"></i>

                                        </a>

                                        <a href="{{ route('salary-management.edit',$salary->id) }}"
                                           class="btn btn-sm btn-primary">

                                            <i class="mdi mdi-pencil-outline"></i>

                                        </a>

                                        <form action="{{ route('salary-management.destroy',$salary->id) }}"
                                              method="POST"
                                              class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this salary record?')">

                                                <i class="mdi mdi-delete fs-14"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="11" class="text-center">

                                        No Salary Record Found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
