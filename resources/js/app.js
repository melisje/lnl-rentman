import './bootstrap';
import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import { TabulatorFull as Tabulator } from 'tabulator-tables';

// STAP A: Maak jQuery als ALLEREERSTE globaal beschikbaar
window.$ = window.jQuery = $;
// De meest robuuste manier om jQuery globaal te maken
// Object.assign(window, { $: $, jQuery: $ });

// STAP B: Koppel de plugins handmatig als de automatische koppeling faalt
// Registreer de plugin expliciet
// DataTable(window, $);

// STAP C: Andere globale variabelen
window.Tabulator = Tabulator;

