@can('access-admin')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Admin
    </a>
    <ul class="dropdown-menu">
        <li class="">
            <a class="dropdown-item" href="{{ route('admin.dashboard')}}">{{ __('dashboard')}}</a>
        </li>
        @can('access-roles')
        <li class="">
            <a class="dropdown-item" href="{{ route('admin.roles')}}">{{ __('roles')}}</a>
        </li>
        @endcan
    </ul>
</li>
@endcan