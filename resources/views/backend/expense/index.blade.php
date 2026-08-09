@extends('backend.partial.master')
@section('title', 'Expense Management')

@section('backend-content')

<div class="row">

    <div class="col-12">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Expense Management</h4>

                <a href="{{ route('expense.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Expense
                </a>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped" id="responsive-datatable">

                        <thead>

                            <tr>

                                <th width="60">#</th>

                                <th>Expense ID</th>

                                <th>Date</th>

                                <th>Title</th>

                                <th>Description</th>

                                <th>Amount</th>

                                <th>Payment Method</th>

                                <th>Created By</th>

                                <th width="120">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($expenses as $key => $expense)

                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <strong>{{ $expense->expense_id }}</strong>
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
                                    </td>

                                    <td>{{ $expense->title }}</td>

                                    <td>

                                        @if($expense->description)

                                            {{ \Illuminate\Support\Str::limit($expense->description,50) }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                    <td>

                                        ₹ {{ number_format($expense->amount,2) }}

                                    </td>

                                    <td>

                                        {{ $expense->payment_method ?? '-' }}

                                    </td>

                                    <td>

                                        {{ $expense->user->name ?? '-' }}

                                    </td>

                                    <td>

                                        <a href="{{ route('expense.edit',$expense->id) }}"
                                           class="btn btn-sm btn-primary">

                                            <i class="mdi mdi-pencil-outline fs-14"></i>

                                        </a>

                                        <form action="{{ route('expense.destroy',$expense->id) }}"
                                              method="POST"
                                              class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this expense?')">

                                                <i class="mdi mdi-delete fs-14"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center">

                                        No Expense Found.

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
