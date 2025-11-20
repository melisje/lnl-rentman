<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-globe"></i> {{ strtoupper(App::getLocale()) }}
  </a>

  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
    <li>
      <a class="dropdown-item @if(App::isLocale('en')) active @endif" href="{{ route('language.switch', 'en') }}">
        English (EN)
      </a>
    </li>
    <li>
      <a class="dropdown-item @if(App::isLocale('nl')) active @endif" href="{{ route('language.switch', 'nl') }}">
        Nederlands (NL)
      </a>
    </li>
  </ul>
</li>