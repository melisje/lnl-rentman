@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>{{ __('Rentman Accounts') }}</h1>
            <a href="{{ route('admin.rentman.accounts.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> {{ __('Add Account') }}
            </a>
        </div>

        @include('layouts.errors')

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover table-striped bg-white">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center" style="width: 50px;">{{ __('Edit') }}</th>
                        <th scope="col" class="text-center">{{ __('Account') }}</th>
                        <th scope="col" class="text-center">{{ __('URL') }}</th>
                        <th scope="col" class="text-center">{{ __('API Token') }}</th>
                        <th scope="col" class="text-center">{{ __('Webhook Token') }}</th>
                        <th scope="col" class="text-center">{{ __('Created') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $account)
                    <tr>
                        <td class="text-center">
                            <a href="{{ route('admin.rentman.accounts.edit', $account->account) }}" class="text-primary">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </td>
                        <td class="text-center fw-bold">{{ $account->account }}</td>
                        <td class="text-center small">{{ $account->url }}</td>
                        <td class="text-center">
                            <code>{{ Str::limit($account->api_token, 12) }}</code>
                        </td>
                        <td class="text-center">
                            <code>{{ Str::limit($account->webhook_token, 12) }}</code>
                        </td>
                        <td class="text-center small">
                            {{ $account->created_at->format('d-m-Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $accounts->links() }}
        </div>
    </div>
</div>
@endsection