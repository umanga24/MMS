@extends('admin.layouts.app')
@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Income</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('dailyincomes.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="expensesForm" name="expensesForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Particular">

                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="Amount">
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary">Create</button>
                <a href="{{route('dailyincomes.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
@endsection('content')

@section('customJs')

<script>
    $("#expensesForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);

        // Unable to click the submit button multiple times , while submiting the new record in category form
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("dailyincomes.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);

                if (response['status'] == true) {
                    window.location.href = "{{route('dailyincomes.index')}}";

                    $("#title").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    $("#amount").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");


                } else {
                    var errors = response['errors']

                    if (errors['title']) {
                        $("#title").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['title']);
                    } else {
                        $("#title").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }


                    if (errors['amount']) {
                        $("#amount").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['amount']);
                    } else {
                        $("#amount").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }




                }


            },
            error: function(jqXHR, exception) {
                console.log("something went wrong");
            }
        })

    });
</script>
@endsection('customJs')