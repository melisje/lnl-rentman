@extends('layouts.app')

@push('styles')
<link href="https://somwhere/style.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Title</H1>
        @include('layouts.errors')

        <div class="card">
            <div class="card-body bg-light">
                <pre><code class="text-dark">
        {{ json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                </code></pre>
            </div>
        </div>


    </div>
</div>
@endsection

@push('scripts')
<script src="/example.js"></script>
@endpush