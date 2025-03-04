@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Player</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('players.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->

@if(Session::has('message'))
<div id="flash-message" class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Errors!</h5> {{ Session::get('message')}}
</div>
@endif
@include('admin.message')
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">

        <!-- Main Player Form -->
        <form action="{{ route('players.update', $category->id) }}" method="post" id="mainForm" name="mainForm">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Original Player Fields -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cardnumber">Card Number</label>
                                <input type="number" name="cardnumber" id="cardnumber" class="form-control" placeholder="Card Number" value="{{ $category->cardnumber }}" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $category->name }}">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="mobilenumber">Mobile Number</label>
                                <input type="number" name="mobilenumber" id="mobilenumber" class="form-control" placeholder="Mobile Number" value="{{ $category->mobilenumber }}">
                                <p></p>
                            </div>


                            <div class="mb-3">
                                <label for="schedule">Schedule</label>
                                <select name="schedule" id="schedule" class="form-control">
                                    <option value="full-time" {{ $category->schedule == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="half-time" {{ $category->schedule == 'half-time' ? 'selected' : '' }}>Half-time</option>
                                </select>
                            </div>

                        </div>

                        <!-- Pricing Fields -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="playernumber">Player Number</label>
                                <input type="number" name="playernumber" id="playernumber" class="form-control" placeholder="Player Number" value="{{ $category->playernumber }}" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="perplayer">Per Person</label>
                                <input type="number" name="perplayer" id="perplayer" class="form-control" placeholder="Per Person" value="{{ $category->perplayer }}" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="subtotal">Sub Total Amount</label>
                                <input type="number" name="subtotal" id="subtotal" class="form-control" placeholder="Sub Total Amount" readonly value="{{ $category->subtotal }}" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="discount">Discount</label>
                                <input type="number" name="discount" id="discount" class="form-control" placeholder="Discount" value="{{ $category->discount }}" min="0">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="first_total">Total</label>
                                <input type="number" name="first_total" id="first_total" class="form-control" placeholder="Total" readonly value="{{ $category->first_total }}" min="0">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('players.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>

        <!-- Additional Time Form -->
        <form action="{{ route('players.updateAdditional', $category->id) }}" method="post" id="additionalForm" name="additionalForm">
            @csrf

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Additional Time Fields -->
                        <div class="col-md-6">
                            <h3 style="background-color: yellow; text-align: center; align-items: center; justify-content: center;">Additional Time</h3>

                            <div class="mb-3">
                                <label for="additional_player">Additional Player Number</label>
                                <input type="number" name="additional_player" id="additional_player" class="form-control" placeholder="Additional Player Number" min="0" value="">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="additional_per_player">Additional Per Player</label>
                                <input type="number" name="additional_per_player" id="additional_per_player" class="form-control" placeholder="Additional Per Player" readonly min="0" value=150>
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="additional_sub_total">Additional Sub Total Amount</label>
                                <input type="number" name="additional_sub_total" id="additional_sub_total" class="form-control" placeholder="Additional Sub Total Amount" readonly value="" min="0">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Add Additional Time</button>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    // Main form calculations
    function calculateMainAmounts() {
        const playerNumber = parseFloat(document.getElementById('playernumber').value) || 0;
        const perPlayer = parseFloat(document.getElementById('perplayer').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;

        const subTotal = playerNumber * perPlayer;
        document.getElementById('subtotal').value = subTotal.toFixed(2);

        const total = subTotal - discount;
        document.getElementById('first_total').value = total.toFixed(2);
    }

    document.getElementById('playernumber').addEventListener('input', calculateMainAmounts);
    document.getElementById('perplayer').addEventListener('input', calculateMainAmounts);
    document.getElementById('discount').addEventListener('input', calculateMainAmounts);

    // Additional form calculations
    function calculateAdditionalAmounts() {
        const additionalPlayer = parseFloat(document.getElementById('additional_player').value) || 0;
        const additionalPerPlayer = parseFloat(document.getElementById('additional_per_player').value) || 0;

        const additionalSubTotal = additionalPlayer * additionalPerPlayer;
        document.getElementById('additional_sub_total').value = additionalSubTotal.toFixed(2);
    }

    document.getElementById('additional_player').addEventListener('input', calculateAdditionalAmounts);
    document.getElementById('additional_per_player').addEventListener('input', calculateAdditionalAmounts);

    // Main form submission
    $("#mainForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);

        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("players.update", $category->id) }}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);

                if (response['status'] == true) {
                    window.location.href = "{{ route('players.index') }}";
                } else {
                    var errors = response['errors'];
                    $.each(errors, function(key, value) {
                        $("#" + key).addClass('is-invalid');
                        $("#" + key).siblings('p').addClass('invalid-feedback').html(value);
                    });
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
            }
        });
    });

    // Additional form submission
    $("#additionalForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);

        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("players.updateAdditional", $category->id) }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);




                if (response['status'] == true) {
                    // Reset additional fields
                    document.getElementById('additional_player').value = '';
                    document.getElementById('additional_per_player').value = '';
                    document.getElementById('additional_sub_total').value = '';

                    alert(response['message']);
                } else {
                    // Handle validation errors
                    var errors = response['errors'];
                    $.each(errors, function(key, value) {
                        $("#" + key).addClass('is-invalid');
                        $("#" + key).siblings('p').addClass('invalid-feedback').html(value);
                    });
                }


                if (response['status'] == true) {
                    window.location.href = "{{ route('players.index') }}";
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
            }
        });
    });
</script>
@endsection