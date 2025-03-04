@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Additional</h1>
                <h5>Player List</h5>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if(Session::has('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            {{ Session::get('success')}}
        </div>
        @endif

        @include('admin.message')

        <div class="card">
            <form action="{{ route('player.listAdditional') }}" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('player.listAdditional') }}'" class="btn btn-default btn-sm">Reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group" style="width: 500px;">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
                            <select name="date" class="form-control mx-1">
                                <option value="">Select Date</option>
                                @foreach ($dates as $date)
                                <option value="{{ $date }}" {{ $date == $selectedDate ? 'selected' : '' }}>{{ $date }}</option>
                                @endforeach
                            </select>
                            <select name="status" class="form-control mx-1">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All</option>
                                <option value="in" {{ $status == 'in' ? 'selected' : '' }}>In</option>
                                <option value="out" {{ $status == 'out' ? 'selected' : '' }}>Out</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">S.N.</th>
                            <th>Name</th>
                            <th>Card Number</th>
                            <th>Number of Additional Player</th>
                            <th>Play Duration</th>
                            @if(auth('admin')->user()->role == 1)
                            <th width="100">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalPlayers = 0;
                        $totalAdditionalPlayer = 0;
                        $total = 0;
                        $totalDiscount = 0;
                        @endphp

                        @if ($categories->isNotEmpty())
                        @foreach($categories as $category)
                        <tr data-created-time="{{ $category->created_at }}" id="row-{{ $category->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->cardnumber }}</td>
                            <td>{{ $category->additional_player }}</td>
                            <td class="elapsed-time">{{ $category->elapsed_time }}</td>
                            @if(auth('admin')->user()->role == 1)
                            <td>
                                <a href="{{ route('players.editAdditional', ['id' => $category->id, 'additionalId' => $category->id]) }}">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                            </td>

                            @endif
                        </tr>
                        @php
                        $totalPlayers += $category->playernumber;
                        $totalAdditionalPlayer += (int)$category->additional_player;
                        $total += $category->total;
                        $totalDiscount += $category->discount;
                        @endphp
                        @endforeach

                        <tr>
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td><strong>{{ $totalAdditionalPlayer }}</strong></td>
                            <td><strong></strong></td>
                            <td></td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="7">Records Not Found.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="mt-2 p-2">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update elapsed time for all rows every 60 seconds
        setInterval(updateElapsedTimes, 60000);
        updateElapsedTimes();

        function updateElapsedTimes() {
            document.querySelectorAll('tr[data-created-time]').forEach(function(row) {
                var createdTime = row.getAttribute('data-created-time');
                var elapsedTimeElement = row.querySelector('.elapsed-time');
                var now = moment.utc();
                var createdMoment = moment.utc(createdTime);
                var duration = moment.duration(now.diff(createdMoment));
                var hours = String(Math.floor(duration.asHours())).padStart(2, '0');
                var minutes = String(duration.minutes()).padStart(2, '0');
                var seconds = String(duration.seconds()).padStart(2, '0');
                elapsedTimeElement.textContent = hours + ':' + minutes + ':' + seconds;
            });
        }
    });
</script>

@endsection