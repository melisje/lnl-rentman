@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>{{ __('Custom Fields') }}</h1>
            <a href="{{ route('admin.rentman.customfield.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> {{ __('Add Field') }}
            </a>
        </div>

        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body py-2">
                <form action="{{ route('admin.rentman.customfield.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label for="account" class="small fw-bold">{{ __('Filter by Account') }}:</label>
                    </div>
                    <div class="col-auto">
                        <select name="account" onchange="this.form.submit()" class="form-select form-select-sm">
                            <option value="">{{ __('All Accounts') }}</option>
                            @foreach($accounts as $acc)
                            <option value="{{ $acc->account }}" {{ request('account')==$acc->account ? 'selected' : '' }}>
                                {{ $acc->account }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover bg-white border">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;"></th>
                        <th>{{ __('Account') }}</th>
                        <th>{{ __('Name') }} (rm_id)</th>
                        <th>{{ __('Belongs To') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th class="text-center">{{ __('Hidden') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fields as $field)
                    <tr>
                        <td class="text-center">
                            <a href="{{ route('admin.rentman.customfield.edit', $field->id) }}"><i class="bi bi-pencil"></i></a>
                        </td>
                        <td><span class="badge bg-secondary">{{ $field->account }}</span></td>
                        <td><strong>{{ $field->name }}</strong> <small class="text-muted">({{ $field->rm_id }})</small></td>
                        <td><code>{{ $field->belongs_to }}</code></td>
                        <td>{{ $field->type }}</td>
                        <td class="text-center">
                            <i class="bi {{ $field->hidden ? 'bi-check-circle-fill text-danger' : 'bi-circle text-muted' }}"></i>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $fields->links() }}
    </div>
</div>
@endsection