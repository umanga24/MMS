@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product Sells</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('productsells.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="" method="post" id="productsForm" name="productsForm">

            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="brand_id">Brand</label>
                                <select name="brand_id" id="brand_id" class="form-control">
                                    @if($brands->isNotEmpty())
                                    <option value="">Select a Brand</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="float" name="price" min="0" id="price" class="form-control" placeholder="Price" value="{{ $product->price }}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="qty">Quantity</label>
                                <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{ $product->qty }}">
                                <p class="error"></p>
                            </div>

                            <div class="mb-3">
                                <label for="total">Total Amount</label>
                                <input type="number" min="0" name="total" id="total" class="form-control" placeholder="Total Amount" readonly value="{{ $product->total }}">
                                <p class="error"></p>
                            </div>

                            <div class="mb-3">
                                <label for="title">Remarks</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Remarks" value="{{ $product->title }}">
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('productsells.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
<script>
    function calculateTotal() {
        var price = parseFloat(document.getElementById('price').value) || 0;
        var qty = parseInt(document.getElementById('qty').value) || 0;
        var total = price * qty;
        document.getElementById('total').value = total;
    }

    document.getElementById('price').addEventListener('input', calculateTotal);
    document.getElementById('qty').addEventListener('input', calculateTotal);

    $("#productsForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);

        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{ route("productsells.update", $product->id) }}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);
                if (response['status'] == true) {
                    window.location.href = "{{ route('productsells.index') }}";

                    $("#brand_id").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    $("#price").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    $("#qty").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    $("#total").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");


                } else {

                    var errors = response['errors']

                    if (errors['brand_id']) {
                        $("#brand_id").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['brand_id']);
                    } else {
                        $("#brand_id").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }

                    if (errors['price']) {
                        $("#price").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['price']);
                    } else {
                        $("#price").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }


                    if (errors['qty']) {
                        $("#qty").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['qty']);
                    } else {
                        $("#qty").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }


                    if (errors['total']) {
                        $("#total").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['total']);
                    } else {
                        $("#total").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }




                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");

            }
        });
    });
</script>
@endsection