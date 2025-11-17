@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-5 mb-4 bg-body-secondary rounded-3 ">
        <div class="my-2">
            {{ $token }}
        </div>
        <div class="my-2">
            {{ $response }}
        </div>
    </div>
</div>
@endsection