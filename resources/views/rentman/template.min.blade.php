@extends('layouts.app')

@push('styles')
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Title</H1>
        @include('layouts.errors')
    </div>
</div>
@endsection

@push('scripts')
@endpush