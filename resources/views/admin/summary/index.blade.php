<!-- resources/views/summary/index.blade.php -->

@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Summary</h1>

    <!-- Filter Form -->
    <!-- <form action="{{ route('summary.index') }}" method="GET" class="form-inline mb-3">
        <div class="form-group">
            <label for="date" class="mr-2">Filter by Date:</label>
            <input type="date" name="date" id="date" class="form-control mr-2" value="{{ $date }}">
            <button type="submit" class="btn btn-primary mr-2">Filter</button>
            <a href="{{ route('summary.index', ['date' => 'all']) }}" class="btn btn-secondary">Show All</a>
        </div>
    </form> -->

    <form action="{{ route('summary.index') }}" method="GET" class="form-inline mb-3">
        <div class="form-group">
            <label for="date" class="mr-2">Filter by Date:</label>
            <input type="date" name="date" id="date" class="form-control mr-2" value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
            <button type="submit" class="btn btn-primary mr-2">Filter</button>
            <a href="{{ route('summary.index', ['date' => 'all']) }}" class="btn btn-secondary">Show All</a>
        </div>
    </form>

    <!-- Summary Cards with Different Background Colors -->
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Skate Sells</h5>
                    <p class="card-text">Rs. {{ $totalCategories }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Income</h5>
                    <p class="card-text">Rs. {{ $totalIncomes }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-red text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Expenses</h5>
                    <p class="card-text">Rs. {{ $totalExpenses }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Beverages Purchase</h5>
                    <p class="card-text">Rs. {{ $totalBuyProducts }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Beverages Sells</h5>
                    <p class="card-text">Rs. {{ $totalProductsSold }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection