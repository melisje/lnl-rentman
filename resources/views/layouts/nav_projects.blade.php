@can('access-projects')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('projects' )}}
    </a>
    <ul class="dropdown-menu">
        <li class="">
            <a class="dropdown-item" href="{{ route('projects.index')}}">{{ __('overview')}}</a>
            <a class="dropdown-item" href="{{ route('projects.fetch')}}">{{ __('fetch')}}</a>
        </li>
    </ul>
</li>
@endcan