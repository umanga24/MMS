@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Player</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('players.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="categoryForm" name="categoryForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cardnumber">Card Number</label>
                                <input type="number" name="cardnumber" id="cardnumber" class="form-control" placeholder="Card Number" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="mobilenumber">Mobile Number</label>
                                <input type="number" name="mobilenumber" id="mobilenumber" class="form-control" placeholder="Mobile Number" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="schedule">Schedule</label>
                                <select name="schedule" id="schedule" class="form-control">
                                    <option value="full-time">Full-time</option>
                                    <option value="half-time">Half-time</option>
                                </select>
                            </div>



                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="playernumber">Player Number</label>
                                <input type="number" name="playernumber" id="playernumber" class="form-control" placeholder="Player Number" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="perplayer">Per Person</label>
                                <input type="number" name="perplayer" id="perplayer" class="form-control" placeholder="Per Person" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="subtotal">Sub Total Amount</label>
                                <input type="number" name="subtotal" id="subtotal" class="form-control" placeholder="Sub Total Amount" readonly min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="discount">Discount</label>
                                <input type="number" name="discount" id="discount" class="form-control" placeholder="Discount" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="first_total">Total</label>
                                <input type="number" name="first_total" id="first_total" class="form-control" placeholder="Total" readonly min="0">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>

                <a href="{{route('players.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->


@endsection('content')

@section('customJs')

<script>
    function calculateAmounts() {
        const playerNumber = parseFloat(document.getElementById('playernumber').value) || 0;
        const perPlayer = parseFloat(document.getElementById('perplayer').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;

        const subTotal = playerNumber * perPlayer;
        document.getElementById('subtotal').value = subTotal.toFixed(2);

        const total = subTotal - discount;
        document.getElementById('first_total').value = total.toFixed(2);
    }

    document.getElementById('playernumber').addEventListener('input', calculateAmounts);
    document.getElementById('perplayer').addEventListener('input', calculateAmounts);
    document.getElementById('discount').addEventListener('input', calculateAmounts);

    $("#categoryForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);


        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("players.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);

                if (response['status'] == true) {
                    window.location.href = "{{route('players.index')}}";

                    $("#name").removeClass('is-invalid')
                        // .siblings('p')
                        .removeClass('invalid-feedback').html("name");

                    $("#cardnumber").removeClass('is-invalid')
                        // .siblings('p')
                        .removeClass('invalid-feedback').html("cardnumber");

                    $("#mobilenumber").removeClass('is-invalid')
                        // .siblings('p')
                        .removeClass('invalid-feedback').html("mobilenumber");


                    $("#playernumber").removeClass('is-invalid')
                        // .siblings('p')
                        .removeClass('invalid-feedback').html("playernumber");


                    $("#perplayer").removeClass('is-invalid')
                        // .siblings('p')
                        .removeClass('invalid-feedback').html("perplayer");


                    $("#subtotal").removeClass('is-invalid')
                        // .siblings('p')
                        .removeClass('invalid-feedback').html("subtotal");

                    $("#discount").removeClass('is-invalid')
                        // .siblings('p')
                        .removeClass('invalid-feedback').html("discount");

                    $("#first_total").removeClass('is-invalid')
                        // .siblings('p')
                        .removeClass('invalid-feedback').html("first_total");



                } else {
                    var errors = response['errors']
                    if (errors['name']) {
                        $("#name").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }


                    if (errors['cardnumber']) {
                        $("#cardnumber").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['cardnumber']);
                    } else {
                        $("#cardnumber").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }



                    if (errors['mobilenumber']) {
                        $("#mobilenumber").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['mobilenumber']);
                    } else {
                        $("#mobilenumber").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }


                    if (errors['playernumber']) {
                        $("#playernumber").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['playernumber']);
                    } else {
                        $("#playernumber").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }



                    if (errors['perplayer']) {
                        $("#perplayer").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['perplayer']);
                    } else {
                        $("#perplayer").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }



                    if (errors['subtotal']) {
                        $("#subtotal").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['subtotal']);
                    } else {
                        $("#subtotal").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }


                    if (errors['discount']) {
                        $("#discount").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['discount']);
                    } else {
                        $("#discount").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }

                    if (errors['first_total']) {
                        $("#first_total").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['first_total']);
                    } else {
                        $("#first_total").removeClass('is-invalid')
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