<script>
    const submenus = {
        home: [

        
            { type: 'divider', label: 'Productie' },
            {
                type: 'item',
                icon: 'bi-calendar-date-fill',
                label: 'Weekoverzicht',
                url: '/weekoverzicht'
            },
            {
                type: 'item',
                icon: 'bi-clock-history',
                label: 'Tijdsregistraties',
                url: '/production/project/timeregistration'
            },
            {
                type: 'item',
                icon: 'bi-check2-all',
                label: 'Checklists',
                url: '/production/checklist'
            },
            {
                type: 'item',
                icon: 'bi-layout-text-sidebar',
                label: 'Checklist Templates',
                url: '/production/checklist/template'
            },
            
        ],

        develop: [{
                type: 'divider',
                label: 'Admin'
            },
            {
                type: 'item',
                icon: 'bi-speedometer2',
                label: 'Dashboard',
                url: '/develop/admin'
            },
            {
                type: 'item',
                icon: 'bi-shield-lock-fill',
                label: 'Rollen',
                url: '/develop/admin/roles'
            },
            {
                type: 'item',
                icon: 'bi-building',
                label: 'Accounts',
                url: '/develop/admin/rentman/accounts'
            },
            {
                type: 'item',
                icon: 'bi-sliders2',
                label: 'Custom Fields',
                url: '/develop/admin/rentman/customfield'
            },

            {
                type: 'divider',
                label: 'Productie'
            },
            {
                type: 'item',
                icon: 'bi-folder2-open',
                label: 'Projecten',
                url: '/develop/projects'
            },
            {
                type: 'item',
                icon: 'bi-clock-history',
                label: 'Tijdsregistraties',
                url: '/develop/production/project/timeregistration'
            },
            {
                type: 'item',
                icon: 'bi-check2-square',
                label: 'Checklists',
                url: '/develop/production/checklist'
            },
            {
                type: 'item',
                icon: 'bi-layout-text-sidebar',
                label: 'Checklist Templates',
                url: '/develop/production/checklist/template'
            },

            {
                type: 'divider',
                label: 'Financieel'
            },
            {
                type: 'item',
                icon: 'bi-receipt',
                label: 'Facturen',
                url: '/develop/invoices'
            },
            {
                type: 'item',
                icon: 'bi-cloud-download',
                label: 'Facturen ophalen',
                url: '/develop/invoices/fetch'
            },
            {
                type: 'item',
                icon: 'bi-truck',
                label: 'Leveranciers',
                url: '/develop/purchase/suppliers'
            },

            {
                type: 'divider',
                label: 'Testing'
            },
            {
                type: 'item',
                icon: 'bi-webhook',
                label: 'Webhook Tester',
                url: '/develop/test/webhook-tester'
            },
            {
                type: 'item',
                icon: 'bi-people',
                label: 'Crew Sync',
                url: '/develop/test/sync-crew'
            },
            {
                type: 'item',
                icon: 'bi-bug',
                label: 'Testjef 1',
                url: '/develop/testjef1'
            },
            {
                type: 'item',
                icon: 'bi-bug',
                label: 'Testjef 2',
                url: '/develop/testjef2'
            },
            {
                type: 'item',
                icon: 'bi-bug',
                label: 'Testjef 3',
                url: '/develop/testjef3'
            },
            {
                type: 'item',
                icon: 'bi-patch-question-fill',
                label: 'Test Tom',
                url: '/overview'
            },
        ],

        settings: [{
                type: 'item',
                icon: 'bi-sliders',
                label: 'Instellingen',
                url: '#'
            },
            {
                type: 'item',
                icon: 'bi-key-fill',
                label: 'Permissies',
                url: '#'
            },
            {
                type: 'item',
                icon: 'bi-bell-fill',
                label: 'Webhook Calls',
                url: '/webhookcall'
            },
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
            return `<a href="${item.url}" data-inertia-link class="item${isActive ? ' active' : ''}">
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

        if (window.innerWidth <= 900) {
            document.querySelector('.wrapper').classList.add('mobile-menu-open');
        } else {
            document.querySelector('.submenu-left').classList.remove('submenu-hidden');
            document.querySelector('.dashboard-shell').classList.remove('submenu-collapsed');
        }
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
    document.addEventListener('inertia:navigate', initNav);

    document.addEventListener('click', (e) => {
        const link = e.target.closest('[data-inertia-link]');
        if (!link || link.getAttribute('href') === '#') return;
        e.preventDefault();
        activateSubmenuItem(link);
        window.router?.visit(link.getAttribute('href'));
    });
</script>