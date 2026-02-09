@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Projects</H1>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">{{__('edit')}}</th>
                        <th scope="col" class="text-center">{{__('id')}}</th>
                        <th scope="col" class="text-center">{{__('account')}}</th>
                        <th scope="col" class="text-center">{{__('ip')}}</th>
                        <th scope="col" class="text-center">{{__('user')}}</th>
                        <th scope="col" class="text-center">{{__('eventtype')}}</th>
                        <th scope="col" class="text-center">{{__('itemtype')}}</th>
                        <th scope="col" class="text-center">{{__('eventdate')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $key => $whc)
                    <tr>
                        <td class="text-center">
                            <a href="{{ route('webhookcalls.show', $whc->id) }}">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </td>
                        <td class="text-center">{{ $whc->id }} </td>
                        <td>{{ $whc->account }} </td>
                        <td>{{ $whc->ip }} </td>
                        <td>{{ $whc->user }} </td>
                        <td>{{ $whc->eventType }} </td>
                        <td>{{ $whc->itemType }} </td>
                        <td>{{ $whc->eventDate }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection