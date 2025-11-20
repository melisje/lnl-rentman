@can('access-invoices')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Invoices
    </a>
    <ul class="dropdown-menu">
        <li class="">
            <a class="dropdown-item" href="{{ route('invoices.index')}}">{{ __('Overview')}}</a>
            <a class="dropdown-item" href="{{ route('invoices.fetch')}}">{{ __('Fetch')}}</a>
        </li>
    </ul>
</li>
@endcan