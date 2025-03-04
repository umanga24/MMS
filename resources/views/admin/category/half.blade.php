@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Half-Time Playing</h1>
                <h1>Player List</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('players.create')}}" class="btn btn-primary">New Player</a>
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
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route("players.index") }}'" class="btn btn-default btn-sm">Reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group" style="width: 250px;">
                            <input value="{{Request::get('keyword')}}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
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
                            <th width="60">S.N</th>
                            <th>Name</th>
                            <th>Entry Time</th>
                            <th>Time</th>
                            <th>Card Number</th>
                            <th>Number of Player</th>

                            <th>Additional Player</th>
                            <th>Discount</th>
                            <th>Total Amount</th>

                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($categories->isNotEmpty())
                        @foreach($categories as $category)
                        <tr data-created-time="{{ $category->created_at }}" id="row-{{ $category->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td class="created-at" data-timestamp="{{ $category->created_at }}"></td>

                            <td class="elapsed-time"></td>
                            <td>{{ $category->cardnumber}}</td>
                            <td>{{ $category->playernumber}}</td>

                            <td>{{ $category->additional_player}}</td>
                            <td>{{ $category->discount}}</td>
                            <td>{{ $category->total}}</td>
                            <td>
                                <a href="{{route('players.edit',$category->id)}}">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <a href="#" onclick="removeRow({{ $category->id }})" class="text-danger w-4 h-4 mr-1">
    <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M16.707 7.293a1 1 0 010 1.414L14.414 11H21a1 1 0 110 2h-6.586l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0zM10 4a1 1 0 011 1v3a1 1 0 11-2 0V6H6v12h3v-2a1 1 0 112 0v3a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1h5z" clip-rule="evenodd"></path>
    </svg>
</a>


                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">Records Not Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $categories->links()}}
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    function removeRow(id) {
        var row = document.getElementById('row-' + id);
        var elapsedTime = row.querySelector('.elapsed-time').textContent;

        var url = '{{ route("players.hide", ":id") }}';
        url = url.replace(':id', id);

        if (confirm("Are you sure you want to checkout this player")) {
            $.ajax({
                url: url,
                type: 'PATCH',
                data: {
                    elapsed_time: elapsedTime,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Player marked as hidden.');
                    window.location.href = "{{ route('players.index') }}";
                },
                error: function(xhr) {
                    console.error('Error marking player as hidden.');
                }
            });
        }
    }

    function updateElapsedTimes() {
        document.querySelectorAll('tr[data-created-time]').forEach(function(row) {
            var createdTime = row.getAttribute('data-created-time');
            var elapsedTimeElement = row.querySelector('.elapsed-time');
            var now = new Date();
            var createdMoment = new Date(createdTime);

            // Calculate elapsed time
            var duration = new Date(now - createdMoment);
            var hours = String(duration.getUTCHours()).padStart(2, '0');
            var minutes = String(duration.getUTCMinutes()).padStart(2, '0');
            var seconds = String(duration.getUTCSeconds()).padStart(2, '0');

            // Format elapsed time in 12-hour format with AM/PM
            var elapsedTime = `${hours}:${minutes}:${seconds}`;
            elapsedTimeElement.textContent = elapsedTime;
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
            }, 1000); // 1000 milliseconds = 1 seconds
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('td.created-at').forEach(cell => {
            const timestamp = cell.getAttribute('data-timestamp');
            const localDate = new Date(timestamp);

            // Format the time to 'hh:mm:ss a' in Asia/Kathmandu timezone
            const options = {
                timeZone: 'Asia/Kathmandu',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true // Use 12-hour format with AM/PM
            };

            const localTime = localDate.toLocaleTimeString('en-US', options);
            cell.textContent = localTime;
        });
    });
</script>

@endsection