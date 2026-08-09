@extends('backend.partial.master')

@section('title', 'Add Holidays')

@section('backend-content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Holidays</h5>

            <button type="button" class="btn btn-success btn-sm" id="addRow">
                <i class="fa fa-plus"></i> Add Row
            </button>
        </div>

        <div class="card-body">

            <form id="holidayForm"  action="{{ route('holidays.store') }}" method="POST">

                @csrf


                <div class="table-responsive">

                    <table class="table table-bordered" id="holidayTable">

                        <thead class="table-dark">

                        <tr>

                            <th width="35%">Holiday Name</th>

                            <th width="25%">Date</th>

                            <th width="25%">Type</th>

                            <th width="15%">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        <tr>

                            <td>
                                <input type="text"
                                       name="holiday_name[]"
                                       class="form-control"
                                       required>
                            </td>

                            <td>
                                <input type="date"
                                       name="holiday_date[]"
                                       class="form-control"
                                       required>
                            </td>

                            <td>

                                <select name="holiday_type[]" class="form-control">

                                    <option value="Festival">Festival</option>

                                    <option value="Weekly Off">
                                        Weekly Off
                                    </option>

                                </select>

                            </td>

                            <td class="text-center">

                                <button type="button"
                                        class="btn btn-danger removeRow">

                                    <i class="mdi mdi-delete"></i>

                                </button>

                            </td>

                        </tr>

                        </tbody>

                    </table>

                </div>

                <div class="text-end">

                    <button type="submit" class="btn btn-primary">

                        Save Holidays

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection



@push('scripts')

<script>

$(function(){


    $("#addRow").click(function(){

        let row = `

        <tr>

            <td>

                <input type="text"
                       name="holiday_name[]"
                       class="form-control"
                       required>

            </td>

            <td>

                <input type="date"
                       name="holiday_date[]"
                       class="form-control"
                       required>

            </td>

            <td>

                <select name="holiday_type[]"
                        class="form-control">

                    <option value="Festival">
                        Festival
                    </option>

                    <option value="Weekly Off">
                        Weekly Off
                    </option>

                </select>

            </td>

            <td class="text-center">

                <button type="button"
                        class="btn btn-danger removeRow">

                    <i class="mdi mdi-delete"></i>

                </button>

            </td>

        </tr>

        `;

        $("#holidayTable tbody").append(row);

    });


    $(document).on("click",".removeRow",function(){

        if($("#holidayTable tbody tr").length>1){

            $(this).closest("tr").remove();

        }

    });

});

$("#holidayForm").submit(function(e){

    e.preventDefault();

    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: $(this).serialize(),
        success: function(response){

            if(response.status){
                alert(response.message);
                location.reload();
            }else{
                alert(response.message);
            }

        },
        error: function(xhr){
            console.log(xhr.responseJSON);
        }
    });

});

</script>
@endpush
