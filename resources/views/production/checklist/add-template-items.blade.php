@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('production.checklist.index') }}">Templates</a></li>
                    <li class="breadcrumb-item active">New Template</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header bg-white">

                    <h5 class="mb-0">Create Checklist Template</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('production.checklist.add-template-items.store', $checklist->id) }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Gebruik Template</label>
                            <select name="template_id" class="form-control">
                                <option value="">-- Kies een Template --</option>
                                @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Alle items uit deze template worden gekopieerd naar de nieuwe lijst.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('production.checklist.template.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add items</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection