@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Additional Player</h1>
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
    <h5><i class="icon fas fa-check"></i> Success!</h5> {{ Session::get('message')}}
</div>
@endif
@include('admin.message')
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">

        <!-- Additional Time Form -->
        <form action="{{ route('players.updateAdditionalList', ['id' => $category->id, 'additionalId' => $additionalPlayer->id]) }}" method="post" id="additionalForm" name="additionalForm">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Additional Time Fields -->
                        <div class="col-md-6">
                            <h3 style="background-color: yellow; text-align: center; align-items: center; justify-content: center;">Edit Additional Time</h3>

                            <div class="mb-3">
                                <label for="additional_player">Additional Player Number</label>
                                <input type="number" name="additional_player" id="additional_player" class="form-control" placeholder="Additional Player Number" min="0" value="{{ $additionalPlayer->additional_player }}">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="additional_per_player">Additional Per Player</label>
                                <input type="number" name="additional_per_player" id="additional_per_player" class="form-control" placeholder="Additional Per Player" min="0" readonly value=100>
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="additional_sub_total">Additional Sub Total Amount</label>
                                <input type="number" name="additional_sub_total" id="additional_sub_total" class="form-control" placeholder="Additional Sub Total Amount" readonly value="{{ $additionalPlayer->additional_sub_total }}" min="0">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update Additional Time</button>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    // Additional form calculations
    function calculateAdditionalAmounts() {
        const additionalPlayer = parseFloat(document.getElementById('additional_player').value) || 0;
        const additionalPerPlayer = parseFloat(document.getElementById('additional_per_player').value) || 0;

        const additionalSubTotal = additionalPlayer * additionalPerPlayer;
        document.getElementById('additional_sub_total').value = additionalSubTotal.toFixed(2);
    }

    document.getElementById('additional_player').addEventListener('input', calculateAdditionalAmounts);
    document.getElementById('additional_per_player').addEventListener('input', calculateAdditionalAmounts);
</script>
@endsection
