<script>
    const submenus = {
        home: [
            { type: 'item', icon: 'bi-house-door-fill', label: 'Dashboard', url: '#' },
            { type: 'item', icon: 'bi-bell-fill',       label: 'Meldingen', url: '#' },
        ],
        rentman: [
            { type: 'divider', label: 'Projecten' },
            { type: 'item',    icon: 'bi-folder-fill',   label: 'Archief',        url: '#' },
            { type: 'item',    icon: 'bi-people-fill',   label: 'Klanten',        url: '#' },
        ],
        testtom: [
            { type: 'item', icon: 'bi-patch-question-fill',       label: 'Test Tom', url: '/testtom' },
            { type: 'item', icon: 'bi-calendar-date-fill',      label: 'Weekoverzicht',     url: '/testtom/weekoverzicht' },
        ],
        settings: [
            { type: 'item', icon: 'bi-sliders',    label: 'Instellingen', url: '#' },
            { type: 'item', icon: 'bi-key-fill',   label: 'Permissies',   url: '#' },
        ],
    };

    function renderSubmenu(section) {
        const inner = document.getElementById('submenu-inner');
        if (!inner) return;

        const items = submenus[section] ?? [];
        const currentPath = window.location.pathname;

        const html = items.map(item => {
            if (item.type === 'divider') {
                return `<div class="submenu-section-label">${item.label}</div>`;
            }
            const isActive = item.url !== '#' && currentPath === item.url;
            return `<a href="${item.url}" class="item${isActive ? ' active' : ''}" onclick="activateSubmenuItem(this)">
                        <i class="bi ${item.icon}"></i>
                        <span>${item.label}</span>
                    </a>`;
        }).join('');

        inner.innerHTML = html + '<div class="submenu-spacer"></div>';
    }

    function setActiveSection(section) {
        document.querySelectorAll('.menu-left .item[data-section]').forEach(el => {
            el.classList.toggle('active', el.dataset.section === section);
        });

        renderSubmenu(section);
        localStorage.setItem('activeSection', section);
    }

    function initNav() {
        const saved = localStorage.getItem('activeSection') ?? 'home';
        setActiveSection(saved);
    }

    function activateSubmenuItem(el) {
        document.querySelectorAll('#submenu-inner .item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
    }

    function toggleSubmenu() {
        if (window.innerWidth <= 900) {
            document.querySelector('.wrapper').classList.toggle('mobile-menu-open');
        } else {
            document.querySelector('.submenu-left').classList.toggle('submenu-hidden');
            document.querySelector('.dashboard-shell').classList.toggle('submenu-collapsed');
        }
    }

    document.addEventListener('DOMContentLoaded', initNav);
    document.addEventListener('inertia:navigate',  initNav);
</script>
