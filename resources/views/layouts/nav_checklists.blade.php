@can('access-checklists')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('checklists' )}}
    </a>
    <ul class="dropdown-menu">
        <li class="">
            <a class="dropdown-item" href="{{ route('production.checklist.index', ['account' => 'llstageservice']) }}">{{ __('checklists')}}</a>
        </li>
        <li class="">
            <a class="dropdown-item" href="{{ route('production.checklist.template.index', ['account' => 'llstageservice']) }}">{{ __('templates')}}</a>
        </li>
    </ul>
</li>
@endcan