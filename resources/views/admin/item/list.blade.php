@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Item List</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('players.create')}}" class="btn btn-primary">New Item</a>
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
                        <button type="button" onclick="window.location.href='{{ route("items.index") }}'" class="btn btn-default btn-sm">Reset</button>
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
                            <th width="60">ID</th>
                            <th>Name</th>
                           
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($items->isNotEmpty())
                        @foreach($items as $item)
                        <tr data-created-time="{{ $category->created_at }}" id="row-{{ $category->id }}">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                           
                            <td>
                                <a href="{{route('players.edit',$category->id)}}">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <a href="#" onclick="deleteItem({{ $category->id }})" class="text-danger w-4 h-4 mr-1">
                                    <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
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
                {{ $items->links()}}
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
function deleteItem(id) {
        var url = '{{ route("items.delete","ID") }}';
        var newUrl = url.replace("ID", id);
        if (confirm("Are you sure you want to delete")) {
            $.ajax({
                url: newUrl,
                type: 'delete',
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ route('items.list') }}";
                }
            });
        }
    }

</script>
@endsection