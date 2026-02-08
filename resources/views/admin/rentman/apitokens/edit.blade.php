@extends('layouts.app')

@section('content')
<div class="container">
    <div class="bg-light rounded-2 p-3">
        @include('layouts.errors')
        <h1>{{ __('apitoken')}} </h1>
        <form action="{{ route('admin.apitoken.update', $token->id)}}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-3">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fc_account" name="account" placeholder="account name" value="{{ old('account',$token->account) }}">
                        <label for="fc_account" class="form-label">Account</label>
                        @error('account')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fc_url" name="url" placeholder="The url for the api calls" value="{{ old('url', $token->url) }}">
                        <label for="fc_url" class="form-label">Url</label>
                        @error('url')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-floating mb-3">
                <textarea class="form-control" id="fc_token" name="token" style="height: 100px">{{ old('token', $token->token) }}</textarea>
                <label for=" fc_token" class="form-label">API token</label>
            </div>

            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                </div>
                <div class="col">
                    <div class="py-1 rounded-2  gap-2 text-end">
                        <div class="badge text-bg-secondary">Created: {{ $token->created_at}}</div>
                        <div class="badge text-bg-secondary">Updated: {{ $token->updated_at}}</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection