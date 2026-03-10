@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Title</H1>
        @include('layouts.errors')

        <body class="bg-light">
            <div class="container mt-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Sync Project Crew</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(isset($success))
                        <div class="alert alert-success">{{ $success }}</div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('test.projectcrew.run') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Rentman Account</label>
                                <input type="text" name="account" class="form-control" placeholder="bijv: mijnbedrijf" value="{{ old('account') ?? $account ?? null }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SubProject RM_ID</label>
                                <input type="number" name="rm_id" class="form-control" placeholder="bijv: 24844" required value="{{ old('rm_id') ?? $rm_id ?? null }}">
                                <div class="form-text">Dit ID moet al aanwezig zijn in je <code>sub_projects</code> tabel.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Start Crew Sync</button>
                        </form>
                    </div>
                </div>
            </div>
        </body>
    </div>
</div>
@endsection

@push('scripts')
@endpush