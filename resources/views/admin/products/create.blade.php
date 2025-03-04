@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Buy Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('products.store') }}" method="post" id="productsForm" name="productsForm">
            @csrf

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
                                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="float" name="price" min="0" id="price" class="form-control" placeholder="Price">
                                        <p class="error"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="qty">Quantity</label>
                                <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">
                                <p class="error"></p>
                            </div>

                            <!-- <div class="mb-3">
                                <label for="total">Total Amount</label>
                                <input type="number" min="0" name="total" id="total" class="form-control" placeholder="Total Amount" readonly>
                                <p class="error"></p>
                            </div> -->

                            <div class="mb-3">
                                <label for="total">Total Amount</label>
                                <input type="float" min="0" name="total" id="total" class="form-control" placeholder="Total Amount" >
                                <p class="error"></p>
                            </div>

                            <div class="mb-3">
                                <label for="title">Remarks</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Remarks">
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
<script>
    // function calculateTotal() {
    //     var price = parseFloat(document.getElementById('price').value) || 0;
    //     var qty = parseInt(document.getElementById('qty').value) || 0;
    //     var total = price * qty;
    //     document.getElementById('total').value = total;
    // }

    // document.getElementById('price').addEventListener('input', calculateTotal);
    // document.getElementById('qty').addEventListener('input', calculateTotal);

    $("#productsForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);

        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{ route("products.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    window.location.href = "{{ route('products.index') }}";
                } else {
                    var errors = response.errors;
                    $(".error").html('');
                    $("input, select").removeClass('is-invalid');

                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid').siblings('p.error').html(value);
                    });

                    $("button[type=submit]").prop('disabled', false);
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
                $("button[type=submit]").prop('disabled', false);
            }
        });
    });
</script>
@endsection