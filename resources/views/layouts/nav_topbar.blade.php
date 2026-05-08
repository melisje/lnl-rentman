<div class="menu-top">
    <div class="brand">
        <button class="brand-toggle" type="button" onclick="toggleSubmenu()"></button>
        <h2 class="brand-title">
            <span class="accent">L&L</span> Stage Service
            <!--<span class="accent">LED</span> Visions-->
        </h2>
    </div>

    <div class="topbar-actions">
        <div class="dropdown">
            <button class="topbar-icon" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-buildings-fill"></i></button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <h6 class="dropdown-header">Selecteer Firma</h6>
                @foreach($globalAccounts as $account)
                    <a class="dropdown-item @if($account->account == $current_account) active @endif" href="{{ route('account.switch', ['account' => $account->account]) }}">{{ $account->account }}</a>
                @endforeach
            </div>
        </div>
        <div class="dropdown">
            <button class="topbar-icon" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-globe-europe-africa"></i></button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <h6 class="dropdown-header">Wijzig taal</h6>
                @foreach($supportedLocales as $locale => $label)
                    <a class="dropdown-item @if($currentLocale === $locale) active @endif" href="{{ route('language.switch', $locale) }}">{{ $label }} ({{ strtoupper($locale) }})</a>
                @endforeach
            </div>
        </div>
        <button class="topbar-icon" type="button"><i class="bi bi-search"></i></button>
        <div class="dropdown">
            <button class="topbar-icon" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-person-fill"></i></button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <h6 class="dropdown-header">Mijn account</h6>
                <a class="dropdown-item" href="#">Instellingen</a>
                <a class="dropdown-item text-danger" href="#">Afmelden</a>
            </div>
        </div>
    </div>
</div>
