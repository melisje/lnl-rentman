import './bootstrap';
import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import * as bootstrap from 'bootstrap';
import {router} from '@inertiajs/vue3'; // Importeer de router van Inertia.js

// STAP A: Maak jQuery als ALLEREERSTE globaal beschikbaar
window.$ = window.jQuery = $;

// STAP C: Andere globale variabelen
window.Tabulator = Tabulator;

// VUE Router beschikbaar maken in de globale scope, zo kunnnen deze ook in de Blade templates worden gebruikt
window.router = router;

createInertiaApp({
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue'),
    ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});

