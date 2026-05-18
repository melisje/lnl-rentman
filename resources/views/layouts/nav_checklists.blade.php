@can('access-checklists')
<li>
    <hr class="dropdown-divider">
</li>
<li><a class="dropdown-item" href="{{ route('production.checklist.index', ['account' => 'llstageservice']) }}">{{ __('checklists')}}</a></li>
<li><a class="dropdown-item" href="{{ route('production.checklist.template.index', ['account' => 'llstageservice']) }}">{{ __('checklist') }} {{ __('templates') }}</a></li>
@endcan