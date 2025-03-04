@extends('admin.layouts.app')

@section('content')

<section class="content-header">
	<div class="container-fluid my-2">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Buy Products</h1>
			</div>
			@if(auth('admin')->user()->role == 1 || auth('admin')->user()->role == 3)
			<div class="col-sm-6 text-right">
				<a href="{{ route('products.create') }}" class="btn btn-primary">Add Record</a>
			</div>
			@endif
		</div>
	</div>
</section>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		@if(Session::has('success'))
		<div id="flash-message" class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h5><i class="icon fas fa-check"></i> Success!</h5> {{ Session::get('success') }}
		</div>
		@endif
		@include('admin.message')

		<!-- Filter Form -->
		<form action="{{ route('products.index') }}" method="GET" class="form-inline mb-3">
			<div class="form-group">
				<label for="date" class="mr-2">Filter by Date:</label>
				<input type="date" name="date" id="date" class="form-control mr-2" value="{{ $date === 'all' ? '' : $date }}">
				<button type="submit" class="btn btn-primary mr-2">Filter</button>
				<a href="{{ route('products.index', ['date' => 'all']) }}" class="btn btn-secondary">Show All</a>
			</div>
		</form>

		<div class="card">
			<div class="card-body table-responsive p-0">
				<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>S.N</th>
							<th>Product</th>
							<th>Price</th>
							<th>Qty</th>
							<th>Total</th>
							<th>Remarks</th>
							<th>Date</th>
							@if(auth('admin')->user()->role == 1 || auth('admin')->user()->role == 3)
							<th width="100">Action</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@if($products->isNotEmpty())
						@foreach($products as $product)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $product->brand->name }}</td>
							<td>{{ $product->price }}</td>
							<td>{{ $product->qty }}</td>
							<td>{{ $product->total }}</td>
							<td>{{ $product->title }}</td>
							<td>{{ $product->created_at->format('Y-m-d') }}</td>
							<td>
								@if(auth('admin')->user()->role == 1 || auth('admin')->user()->role == 3)
								<a href="{{ route('products.edit', $product->id) }}">
									<svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
									</svg>
								</a>
								@endif
								@if(auth('admin')->user()->role == 1)
								<a href="#" onclick="deleteproduct({{ $product->id }})" class="text-danger w-4 h-4 mr-1">
									<svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										<path ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
									</svg>
								</a>
								@endif
							</td>
						</tr>
						@endforeach
						<tr>
							<td></td>
							<td><strong>Total:</strong></td>
							<td><strong>{{ $totalQuantity }}</strong></td>

							<td><strong></strong></td>
							<td><strong>{{ $totalPurchases }}</strong></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						@else
						<tr>
							<td colspan="8">Records Not Found</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
			<div class="card-footer clearfix">
				{{ $products->links() }}
			</div>
		</div>
	</div>
</section>
@endsection

@section('customJs')
<script>
	function deleteproduct(id) {
		var url = '{{ route("products.delete", "ID") }}';
		var newUrl = url.replace("ID", id);

		if (confirm("Are you sure you want to delete")) {
			$.ajax({
				url: newUrl,
				type: 'delete',
				dataType: 'json',
				success: function(response) {
					window.location.href = "{{ route('products.index') }}";
				}
			});
		}
	}

	$(document).ready(function() {
		setTimeout(function() {
			$('#flash-message').fadeOut('slow');
		}, 1000); // 1000ms = 1 second
	});
</script>
@section('customJs')