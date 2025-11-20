@can('access-projects')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Projects
    </a>
    <ul class="dropdown-menu">
        <li class="">
            <a class="dropdown-item" href="{{ route('projects.index')}}">{{ __('Overview')}}</a>
            <a class="dropdown-item" href="{{ route('projects.fetch')}}">{{ __('Fetch')}}</a>
        </li>
    </ul>
</li>
@endcan