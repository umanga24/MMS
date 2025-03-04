@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Day Summary<h1><h5> Player List</h5>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if(Session::has('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5> {{ Session::get('success')}}
        </div>
        @endif

        @include('admin.message')
        <div class="card">
            <form action="{{ route('summary.list') }}" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route("summary.list") }}'" class="btn btn-default btn-sm">Reset</button>
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
                            <th>Schedule</th>

                            <th>Number of Player</th>
                            <!-- <th>Number of Additional Player</th> -->
                            <!-- <th>Play Duration</th> -->
                            <!-- <th>Amount</th> -->
                            <!-- <th width="100">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPlayers = 0;
                            $total = 0;
                            $totalAdditionalPlayer = 0;
                        @endphp

                        @if ($categories->isNotEmpty())
                        @foreach($categories as $category)
                        <tr data-created-time="{{ $category->created_at }}" id="row-{{ $category->id }}">
                        <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->cardnumber }}</td>
                            <td>{{ $category->schedule }}</td>

                            <td>{{ $category->playernumber }}</td>
                            <!-- <td>{{ $category->additional_player }}</td> -->
                            <!-- <td>{{ $category->elapsed_time }}</td> -->
                            <!-- <td>{{ $category->total }}</td> -->
                            <!-- <td>
                                <a href="{{ route('players.edit', $category->id) }}">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a> -->
                                <!-- <a href="#" onclick="deleteCategory({{ $category->id }})" class="text-danger w-4 h-4 mr-1">
                                    <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </a> -->
                            <!-- </td> -->
                        </tr>
                        @php
                            $totalPlayers += $category->playernumber;
                            $totalAdditionalPlayer += (int)$category->additional_player;
                            $total += $category->total;
                        @endphp
                        @endforeach

                        <tr>
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td><strong>{{ $totalPlayers }}</strong></td>
                            <!-- <td><strong>{{ $totalAdditionalPlayer }}</strong></td> -->
                            <td></td>
                            <!-- <td><strong>Rs. {{ $total }}</strong></td> -->
                            <td></td>
                        </tr>
                        <!-- <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                            <td><strong>{{ $totalPlayers + $totalAdditionalPlayer }}</strong></td>
                            <td></td>
                            <td></td>
                            <td><strong>Rs. {{ $total }}</strong></td>
                            <td></td>
                        <tr>

                        </tr> -->
                        @else
                            <!-- <tr>
                                <td colspan="7">Records Not Found</td>
                            </tr> -->
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    function deleteCategory(id) {
        var url = '{{ route("players.delete","ID") }}';
        var newUrl = url.replace("ID", id);
        if (confirm("Are you sure you want to delete")) {
            $.ajax({
                url: newUrl,
                type: 'delete',
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ route('report.list') }}";
                }
            });
        }
    }

    function removeRow(id) {
        var url = '{{ route("players.hide", ":id") }}';
        url = url.replace(':id', id);

        if (confirm("Are you sure you want to delete")) {
            $.ajax({
                url: url,
                type: 'PATCH',
                dataType: 'json',
                success: function(response) {
                    console.log('Category marked as hidden.');
                    window.location.href = "{{ route('players.index') }}";
                },
                error: function(xhr) {
                    console.error('Error marking category as hidden.');
                }
            });
        }
    }

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

    document.addEventListener('DOMContentLoaded', function() {
        updateElapsedTimes();
        setInterval(updateElapsedTimes, 1000);
    });

    document.addEventListener('DOMContentLoaded', function() {
        var flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            setTimeout(function() {
                flashMessage.style.display = 'none';
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    });

</script>
@endsection
