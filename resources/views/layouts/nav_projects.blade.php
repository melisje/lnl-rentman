@can('access-projects')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('projects' )}}
    </a>
    <ul class="dropdown-menu">
        <li class="">
            <a class="dropdown-item" href="{{ route('rentman.projects.index', ['account' => 'llstageservice']) }}">{{ __('overview')}}</a>
        </li>
    </ul>
</li>
@endcan