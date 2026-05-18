@can('access-projects')
<li>
    <hr class="dropdown-divider">
</li>
<li><a class="dropdown-item" href="{{ route('rentman.projects.index', ['account' => 'llstageservice']) }}">{{ __('projects')}}</a></li>
<li><a class="dropdown-item" href="{{ route('testtom.weekoverzicht', ['account' => 'llstageservice']) }}">{{ __('weekoverview')}}</a></li>
@endcan