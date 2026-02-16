@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-5 mb-4 bg-body-secondary rounded-3 ">
        <h1>{{ __('apitokens') }}</h1>
        @include('layouts.errors')
        <div class="my-2 table-responsive">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th role="column">edit</th>
                            <th role="column">account</th>
                            <th role="column">url</th>
                            <th role="column">api token</th>
                            <th role="column">webhook token</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tokens as $token)
                        <tr>
                            <td class="text-center">
                                <a href="{{ route('admin.apitoken.edit', $token->id) }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                            </td>
                            <td>{{ $token->account }}</td>
                            <td>{{ $token->url }}</td>
                            <td class="text-wrap">{{ $token->api_token }}</td>
                            <td class="text-wrap">{{ $token->webhook_token }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $tokens->links() }}
                </div>
            </div>

        </div>
        @endsection

        @push('scripts')
        <script>

        </script>
        @endpush