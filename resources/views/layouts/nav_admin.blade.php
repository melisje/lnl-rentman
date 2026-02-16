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
            <a class="dropdown-item" href="{{ route('admin.roles.index')}}">{{ __('roles')}}</a>
        </li>
        @endcan
        @can('access-accounts')
        <li class="">
            <a class="dropdown-item" href="{{ route('admin.rentman.accounts.index')}}">{{ __('accounts')}}</a>
        </li>
        @endcan
        @can('access-customfields')
        <li class="">
            <a class="dropdown-item" href="{{ route('admin.rentman.customfield.index')}}">{{ __('customfields')}}</a>
        </li>
        @endcan
    </ul>
</li>
@endcan