<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-building"></i> {{ $current_account ? __($current_account) : __('Select Account') }}
  </a>

  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
    @foreach($globalAccounts as $key => $account)
    <li class="">
      <a class="dropdown-item @if($account->account == $current_account) active @endif" href="{{ route('account.switch', ['account' => $account->account]) }}">{{ __($account->account) }}</a>
    </li>
    @endforeach
  </ul>
</li>