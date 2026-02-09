@can('access-webhookcalls')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('webhookcalls' )}}
    </a>
    <ul class="dropdown-menu">
        <li class="">
            <a class="dropdown-item" href="{{ route('webhookcalls.index')}}">{{ __('overview')}}</a>
        </li>
    </ul>
</li>
@endcan