@extends('layouts.app')

@section('content')
<div class="container">
    <div class="bg-light p-2 rounded-3">
        <h1>Project</h1>


        <div class="text-bg-grey-100 my-3">
            Projects:
            <div>
                {{-- {{ json_encode($test['projects']) }}i --}}
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @foreach($project as $key => $value)
                                <th>{{ $key }}</th>
                                @endforeach
                            </tr>

                        </thead>
                        <tbody>
                            <tr>
                                @foreach($project as $key => $value)
                                <td>{{ $value }}</td>
                                @endforeach
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            Invoices
        </div>

        @include('layouts.errors')
        <form method="POST" action="{{ route('projects.fetch') }}">
            @csrf

            <div class="row">
                <div class="col-sm ">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="number" name="number" placeholder="Enter project number" value="{{ old('number', $validatedData['number'] ?? '') }}">
                        <label for="endpoint">Project number</label>
                        @error('limit')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{__('fetch')}}</button>
        </form>


        @isset($project)
        <div class="my-2">
            <h4>Project</h4>
            {{ $project->number}}
        </div>
        @endisset
        @isset($subprojects)
        <div class="my-2">
            <h4>subprojects</h4>
            <table class="table table-bordered table-striped">
                @foreach($subprojects as $key => $subproject)
                <tr>
                    <td>{{ $subproject }}</td>
                    <td>{{ json_encode($subproject) }}</td>
                </tr>
                @endforeach
            </table>

        </div>
        @endisset
    </div>


</div>

@endsection