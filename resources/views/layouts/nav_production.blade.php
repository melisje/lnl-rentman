@can('access-production')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('production' )}}
    </a>
    <ul class="dropdown-menu">
        @include('layouts.nav_projects')
        @include('layouts.nav_checklists')
    </ul>
</li>
@endcan