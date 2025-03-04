@extends('admin.layouts.app')

@section('content')
<div class="container my-4">
    <div class="row mb-4">
        <div class="col text-center">
            <h1 class="display-4">Total Product</h1>
        </div>
    </div>

    <div class="row">
        @foreach($totals as $total)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100" style="background-color: #f8f9fa;">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">{{ $total['brand'] }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Total Bought:</strong> <span class="text-success font-weight-bold">{{ $total['total_bought'] }}</span>
                    </p>
                    <p class="card-text">
                        <strong>Total Sold:</strong> <span class="text-danger font-weight-bold">{{ $total['total_sold'] }}</span>
                    </p>
                    <p class="card-text">
                        <strong>Remaining:</strong> <span class="text-primary font-weight-bold">{{ $total['remaining'] }}</span>
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col">
            <table class="table table-bordered table-hover">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Product</th>
                        <th>Total Bought</th>
                        <th>Total Sold</th>
                        <th>Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($totals as $total)
                    <tr>
                        <td>{{ $total['brand'] }}</td>
                        <td class="text-success font-weight-bold">{{ $total['total_bought'] }}</td>
                        <td class="text-danger font-weight-bold">{{ $total['total_sold'] }}</td>
                        <td class="text-primary font-weight-bold">{{ $total['remaining'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
