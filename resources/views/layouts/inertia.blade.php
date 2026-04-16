<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @inertiaHead

    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-bg: linear-gradient(180deg, #0f1b3d 0%, #0b1631 100%);
            --sidebar-text: rgba(255, 255, 255, 0.82);
            --sidebar-muted: rgba(255, 255, 255, 0.55);

            --surface: #f5f7fb;
            --surface-2: #ffffff;
            --border: #e7ebf3;
            --text: #1a2233;
            --muted: #6f7b91;
            --accent: #f09206;
            --accent-soft: rgba(240, 146, 6, 0.12);

            --shadow: 0 6px 20px rgba(17, 29, 59, 0.06);

            --topbar-height: 60px;
            --sidebar-width: 68px;
            --submenu-width: 220px;
            --content-padding: 18px;

            --radius-lg: 18px;
            --radius-md: 12px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background: linear-gradient(180deg, #eef2f8 0%, #f7f9fc 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .wrapper {
            min-height: 100vh;
        }

        .dashboard-shell {
            min-height: 100vh;
            padding-left: calc(var(--sidebar-width) + var(--submenu-width));
            padding-top: var(--topbar-height);
        }

        .menu-top {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(231, 235, 243, 0.95);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px 0 20px;
            z-index: 50;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-toggle {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 10px;
            background: #f3f6fb;
            color: var(--text);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
        }

        .brand-title {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .brand-title .accent {
            color: var(--accent);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .topbar-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: transparent;
            color: #24304a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .topbar-icon:hover {
            background: #f5f7fb;
            border-color: var(--border);
            color: var(--accent);
        }

        .menu-left {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 60;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 0;
            box-shadow: 6px 0 20px rgba(9, 16, 35, 0.14);
        }

        .menu-left-top,
        .menu-left-bottom {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .menu-left-bottom {
            margin-top: auto;
        }

        .menu-left .item {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            color: var(--sidebar-text);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .menu-left .item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .menu-left .item.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            box-shadow: inset 3px 0 0 var(--accent);
        }

        .submenu-left {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            bottom: 0;
            width: var(--submenu-width);
            padding-top: calc(var(--topbar-height) + 14px);
            background: rgba(8, 16, 37, 0.94);
            color: white;
            z-index: 40;
            box-shadow: inset -1px 0 0 rgba(255, 255, 255, 0.06);
        }

        .submenu-inner {
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 0 12px 14px;
        }

        .submenu-section-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--sidebar-muted);
            margin: 14px 10px 8px;
        }

        .submenu-left .item {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 42px;
            padding: 0 12px;
            margin-bottom: 4px;
            border-radius: 12px;
            color: var(--sidebar-text);
            font-size: 0.95rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .submenu-left .item i {
            width: 16px;
            text-align: center;
            font-size: 0.95rem;
        }

        .submenu-left .item:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
        }

        .submenu-left .item.active {
            background: var(--accent-soft);
            color: #fff;
            box-shadow: inset 3px 0 0 var(--accent);
        }

        .submenu-spacer {
            flex: 1;
        }

        .submenu-footer {
            margin-top: 14px;
            padding: 14px 10px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            color: var(--sidebar-muted);
            font-size: 0.88rem;
        }

        .content {
            min-height: calc(100vh - var(--topbar-height));
            padding: var(--content-padding);
        }

        .content-frame {
            min-height: calc(100vh - var(--topbar-height) - (var(--content-padding) * 2));
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: var(--radius-lg);
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
            padding: 18px 20px;
            border-radius: 16px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            box-shadow: 0 6px 18px rgba(24, 37, 64, 0.04);
        }

        .page-header h1 {
            margin: 0 0 4px;
            font-size: 2rem;
            line-height: 1.05;
            letter-spacing: -0.04em;
        }

        .page-header p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .page-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-primary-modern,
        .btn-secondary-modern {
            min-height: 40px;
            padding: 0 16px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-weight: 600;
            font-size: 0.92rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary-modern {
            background: var(--accent);
            color: white;
            box-shadow: 0 8px 14px rgba(240, 146, 6, 0.18);
        }

        .btn-secondary-modern {
            background: white;
            color: var(--text);
            border-color: var(--border);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 16px;
        }

        .card-modern {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(24, 37, 64, 0.04);
        }

        .card-modern-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px 0;
        }

        .card-modern-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .card-modern-body {
            padding: 16px 18px 18px;
        }

        .placeholder-block {
            min-height: 320px;
            border-radius: 12px;
            background: linear-gradient(180deg, #f4f6fb 0%, #eef2f8 100%);
            border: 1px dashed #d8dfeb;
        }

        .summary-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eef2f7;
        }

        .summary-row:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .summary-label {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .summary-value {
            width: 90px;
            height: 22px;
            border-radius: 8px;
            background: #f3f5f9;
        }

        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quick-action {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 44px;
            padding: 0 10px;
            border-radius: 10px;
            background: #fafbfd;
            border: 1px solid #eef2f7;
        }

        .quick-action-icon {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            background: #e9edf5;
        }

        .quick-action-line {
            width: 62%;
            height: 10px;
            border-radius: 999px;
            background: #e9edf5;
        }

        .footer-bar {
            margin-top: 18px;
            padding: 14px 2px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: var(--muted);
            font-size: 0.88rem;
            border-top: 1px solid rgba(231, 235, 243, 0.9);
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .menu-top {
                left: 0;
            }

            .menu-left,
            .submenu-left {
                display: none;
            }

            .dashboard-shell {
                padding-left: 0;
            }

            .content {
                padding: 12px;
            }

            .content-frame {
                padding: 12px;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="menu-left">
            <div class="menu-left-top">
                <a href="#" class="item">
                    <i class="bi bi-list"></i>
                </a>
                <a href="#" class="item active">
                    <i class="bi bi-house-fill"></i>
                </a>
                <a href="#" class="item">
                    <i class="bi bi-cloud-check-fill"></i>
                </a>
                <a href="#" class="item">
                    <i class="bi bi-person-fill"></i>
                </a>
            </div>

            <div class="menu-left-bottom">
                <a href="#" class="item">
                    <i class="bi bi-gear-fill"></i>
                </a>
            </div>
        </div>

        <div class="submenu-left">
            <div class="submenu-inner">
                <a href="#" class="item">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Hier komt item 1</span>
                </a>

                <a href="#" class="item">
                    <i class="bi bi-person-fill"></i>
                    <span>Nog iets anders</span>
                </a>

                <div class="submenu-section-label">Menu</div>

                <a href="#" class="item active">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Veel menu</span>
                </a>

                <a href="#" class="item">
                    <i class="bi bi-folder-fill"></i>
                    <span>Bla bla</span>
                </a>

                <a href="#" class="item">
                    <i class="bi bi-people-fill"></i>
                    <span>Ja hier ook</span>
                </a>

                <div class="submenu-spacer"></div>

                <div class="submenu-footer">Instellingen</div>
            </div>
        </div>

        <div class="menu-top">
            <div class="brand">
                <button class="brand-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h2 class="brand-title">
                    <span class="accent">App</span>Name
                </h2>
            </div>

            <div class="topbar-actions">
                <button class="topbar-icon" type="button"><i class="bi bi-buildings-fill"></i></button>
                <button class="topbar-icon" type="button"><i class="bi bi-globe-europe-africa"></i></button>
                <button class="topbar-icon" type="button"><i class="bi bi-search"></i></button>
                <button class="topbar-icon" type="button"><i class="bi bi-person-fill"></i></button>
            </div>
        </div>

        <div class="dashboard-shell">
            <main class="content">
                <div class="content-frame">
                    <section class="page-header">
                        <div>
                            <h1>Paginatitel</h1>
                            <p>Subtitel of extra informatie komt hier</p>
                        </div>

                        <div class="page-actions">
                            <button class="btn-primary-modern" type="button">Primaire actie</button>
                            <button class="btn-secondary-modern" type="button">Secundaire actie</button>
                        </div>
                    </section>

                    <section class="dashboard-grid">
                        <div class="card-modern">
                            <div class="card-modern-header">
                                <h2 class="card-modern-title">Sectietitel</h2>
                                <button class="topbar-icon" type="button">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                            </div>
                            <div class="card-modern-body">
                                <div class="placeholder-block"></div>
                            </div>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:16px;">
                            <div class="card-modern">
                                <div class="card-modern-header">
                                    <h2 class="card-modern-title">Samenvatting</h2>
                                </div>
                                <div class="card-modern-body">
                                    <div class="summary-list">
                                        <div class="summary-row">
                                            <span class="summary-label">Label</span>
                                            <span class="summary-value"></span>
                                        </div>
                                        <div class="summary-row">
                                            <span class="summary-label">Label</span>
                                            <span class="summary-value"></span>
                                        </div>
                                        <div class="summary-row">
                                            <span class="summary-label">Label</span>
                                            <span class="summary-value"></span>
                                        </div>
                                        <div class="summary-row">
                                            <span class="summary-label">Label</span>
                                            <span class="summary-value"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-modern">
                                <div class="card-modern-header">
                                    <h2 class="card-modern-title">Snelle acties</h2>
                                </div>
                                <div class="card-modern-body">
                                    <div class="quick-actions">
                                        <div class="quick-action">
                                            <span class="quick-action-icon"></span>
                                            <span class="quick-action-line"></span>
                                        </div>
                                        <div class="quick-action">
                                            <span class="quick-action-icon"></span>
                                            <span class="quick-action-line"></span>
                                        </div>
                                        <div class="quick-action">
                                            <span class="quick-action-icon"></span>
                                            <span class="quick-action-line"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <footer class="footer-bar">
                        <div>© 2026 AppName. Alle rechten voorbehouden.</div>
                        <div class="footer-links">
                            <a href="#">Privacy</a>
                            <a href="#">Gebruiksvoorwaarden</a>
                            <a href="#">Help &amp; Support</a>
                        </div>
                    </footer>
                </div>
            </main>
        </div>
    </div>
</body>

</html>