@extends('layouts.app')

@section('content')

<div class="offcanvas offcanvas-start show" data-bs-scroll="true" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasLabel">Fetch invoices</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="my-2">
            @include('layouts.errors')
            <form method="POST" action="{{ route('invoices.fetch') }}">
                @csrf

                <div class="row">
                    <div class="col ">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="endpoint" name="endpoint" placeholder="Enter limit" value="{{ old('endpoint', $validatedData['endpoint'] ?? 'invoices') }}">
                            <label for="endpoint">Endpoint</label>
                            @error('limit')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="limit" name="limit" placeholder="Enter limit" value="{{ old('limit', $validatedData['limit'] ?? '300') }}">
                            <label for="limit">Limit</label>
                            @error('limit')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="offset" name="offset" placeholder="Enter offset" value="{{ old('offset', $validatedData['offset'] ?? '0') }}">
                            <label for="offset">Offset</label>
                            @error('offset')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="filter" name="filter" placeholder="Enter filter" value="{{ old('filter', $validatedData['filter'] ?? 'modified[gte]=2025-10-20') }}">
                            <label for="filter">Filter</label>
                            @error('filter')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
                <div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="fields" name="fields" placeholder="Enter fields" value="displayname">
                            <label for="fields">Fields</label>
                            @error('fields')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{__('fetch')}}</button>
            </form>

        </div>

    </div>
</div>

<div class="container-fluid">
    <div class="p-5 mb-4 bg-body-secondary rounded-3 ">
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasExample">
            Fetch data
        </button>

        <div class="my-2">
            @isset($data)
            {{-- {{ $data }} --}}
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Key</th>
                            @foreach($data[0] as $key => $value)
                            <th>{{ $key }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $item)
                        <tr>
                            <td>{{ $key + 1}}</td>
                            @foreach($item as $key => $value)
                            <td>{{ $value }}</td>
                            @endforeach
                            {{-- <td>{{ $item['id'] }}</td>
                            <td>{{ $item['displayname'] }}</td>
                            <td>{{ json_encode($item) }}</td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endisset


        </div>
    </div>
</div>
@endsection