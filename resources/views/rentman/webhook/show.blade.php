@extends('layouts.app')

@push('styles')
<link href="https://somwhere/style.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>{{ __('webhookcall') }}: {{ $webhookcall->id }}</H1>
        @include('layouts.errors')

        <div class="row">
            <div class="col">
                <div class="input-group mb-3">
                    <span class="input-group-text">{{ __('account')}}</span>
                    <div class="form-control">
                        {{ $webhookcall->account}}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="input-group mb-3">
                    <span class="input-group-text">{{ __('From')}}</span>
                    <div class="form-control">
                        {{ $webhookcall->ip}}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="input-group mb-3">
                    <span class="input-group-text">{{ __('user')}}</span>
                    <div class="form-control">
                        {{ $webhookcall->user}}
                    </div>
                </div>
            </div>

        </div>
        <div class="row">


            <div class="col">
                <div class="input-group mb-3">
                    <span class="input-group-text">{{ __('eventType')}}</span>
                    <div class="form-control">
                        {{ $webhookcall->eventType}}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="input-group mb-3">
                    <span class="input-group-text">{{ __('itemType')}}</span>
                    <div class="form-control">
                        {{ $webhookcall->itemType}}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="input-group mb-3">
                    <span class="input-group-text">{{ __('eventdate')}}</span>
                    <div class="form-control">
                        {{ $webhookcall->eventDate->diffForHumans()}}
                    </div>
                </div>
            </div>

        </div>


        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">{{ __('payload')}}</span>
            <div class="form-control">
                @json($webhookcall->payload)
            </div>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">{{ __('headers')}}</span>
            <div class="form-control">
                @if($webhookcall->headers)
                @foreach($webhookcall->headers as $key => $header)
                <div class="row">
                    <div class="col-2">{{ $key}} </div>
                    <div class="col"> {{ $header[0] }}
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">{{ __('items')}}</span>
            <div class="form-control">
                @if($webhookcall->items)
                @foreach($webhookcall->items as $key => $item)
                <div>{{ json_encode($item) }}</div>
                @endforeach
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="/example.js"></script>
@endpush