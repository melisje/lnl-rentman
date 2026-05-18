<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('testing' )}}
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('testtom.projects_with_account_scope', ['account' => 'llstageservice']) }}">testjef1</a></li>
        <li><a class="dropdown-item" href="{{ route('testtom.projecttypes', ['account' => 'llstageservice']) }}" target="_blank">testjef2</a></li>
        <li><a class="dropdown-item" href="{{ route('testtom.warehouse_dashboard', ['account' => 'llstageservice']) }}" target="_blank">testjef3</a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="{{ route('testtom.weekoverzicht', ['account' => 'llstageservice']) }}">{{ __('weekoverview')}}</a></li>
    </ul>
</li>