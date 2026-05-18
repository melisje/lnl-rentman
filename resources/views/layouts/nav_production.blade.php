@can('access-production')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('production' )}}
    </a>
    <ul class="dropdown-menu">
        @include('layouts.nav_projects')
        @include('layouts.nav_projects')
        @include('layouts.nav_checklists')
        <li>
            <hr class="dropdown-divider">
        </li>

        <li><a class="dropdown-item" href="#">Action</a></li>
        <li><a class="dropdown-item" href="#">Another action</a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="#">Something else here</a></li>
    </ul>
</li>
@endcan