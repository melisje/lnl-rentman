@extends('layouts.app')

@push('styles')
<link href="https://somwhere/style.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Title</H1>
        @include('layouts.errors')
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">{{__('edit')}}</th>
                        <th scope="col" class="text-center">{{__('col1')}}</th>
                        <th scope="col" class="text-center">{{__('col2')}}</th>
                        <th scope="col" class="text-center">{{__('col3')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $key => $item)
                    <tr>
                        <td class="text-center">
                            <a href="{{ route('home') }}">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </td>
                        <td class="text-center">{{ $item->id }} </td>
                        <td>{{ $item }} </td>
                        <td>{{ $item }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/example.js"></script>
@endpush