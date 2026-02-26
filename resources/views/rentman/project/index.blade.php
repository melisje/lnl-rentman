-- Active: 1762257673818@@127.0.0.1@3306@rentman
@extends('layouts.app')

@push('styles')
{{--
<link href="https://unpkg.com/tabulator-tables@6.0.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet"> --}}
@endpush

@section('content')
<div class="container-fluid mt-2">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Projectenbeheer</h5>
            <div class="input-group w-25">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="search-input" class="form-control form-control-sm" placeholder="Zoeken...">
            </div>
        </div>
        <div class="card-body p-0">
            <div id="projects-table"></div>
        </div>
    </div>
</div>

<script type="module">
    document.addEventListener("DOMContentLoaded", function() {
        const tableData = @json($projects);

        const table = new window.Tabulator("#projects-table", {
            data: tableData,
            // maxHeight:"100%", //do not let table get bigger than the height of its parent element
            // height: "500px", // Voeg dit toe! Zonder hoogte kan de layout soms breken
            // rowHeight:30,

            dataTree: true,
            dataTreeChildField: "subprojects",
            dataTreeChildIndent: 20,

            // Bootstrap-specifieke instellingen
            // layout: "fitColumns", // Zorgt dat kolommen de breedte vullen
            // layout: "fitDataFill", // Zorgt dat kolommen de breedte vullen

            responsiveLayout: "collapse",

            columns: [
                {title: "Account", field: "account", widthGrow: 3},
                {title: "Nummer", field: "number", hozAlign: "center"},
                {title: "Project / Sub", field: "displayname", widthGrow: 3},
                {title: "Subprojecten #", field: "nr_of_subprojects", widthGrow: 3},
                {title: "Status", field: "status_name", formatter: function(cell) {
                    const val = cell.getValue();
                    // Gebruik Bootstrap Badge classes
                    let badgeClass = "bg-secondary";
                    if(val === "Aanvraag") badgeClass = "bg-primary";
                    if(val === "Optie") badgeClass = "bg-info text-dark";
                    if(val === "Bevestigd") badgeClass = "bg-success text-light";
                    if(val === "Gevarieerd") badgeClass = "bg-warning text-dark";
                    if(val === "Geannuleerd") badgeClass = "bg-danger text-light";
                    if(val === "Op locatie") badgeClass = "bg-location text-light";

                    return `<span class="badge ${badgeClass}">${val || 'N/B'}</span>`;
                }},
                {title: "AM", field: "am_name", hozAlign: "center"},
                {title: "PM", field: "pm_name", hozAlign: "center"},

                {title: "Start", field: "usageperiod_start", hozAlign: "center",
                    formatter: function(cell)
                    {
                        const value = cell.getValue();
                        if (!value) return "-";

                        const date = new Date(value);

                        // Formateer naar Nederlands/Belgisch formaat: DD/MM/YYYY
                        return date.toLocaleDateString('nl-BE',
                        {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                    }
                },

                {title: "Einde", field: "usageperiod_end", hozAlign: "center",
                    formatter: function(cell)
                    {
                        const value = cell.getValue();
                        if (!value) return "-";

                        const date = new Date(value);

                        // Formateer naar Nederlands/Belgisch formaat: DD/MM/YYYY
                        return date.toLocaleDateString('nl-BE',
                        {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                    }
                },

                {title: "Updated", field: "updated_at", hozAlign: "center",
                    formatter: function(cell)
                    {
                        const value = cell.getValue();
                        if (!value) return "-";

                        const date = new Date(value);

                        // Formateer naar Nederlands/Belgisch formaat: DD/MM/YYYY
                        const datum = date.toLocaleDateString('nl-BE', { day: '2-digit', month: '2-digit', year: 'numeric' });
                        const tijd = date.toLocaleTimeString('nl-BE', { hour: '2-digit', minute: '2-digit' });

                        return `<div>${datum}</div>
                        <div class="text-muted small" style="font-size: 0.8em;">${tijd} uur</div>`;
                    }
                },

                // {title: "Totaal", field: "project_total_price", formatter: "money", formatterParams: {
                //     symbol: "€ ", thousand: ".", decimal: ","
                // }},
                {
                    title: "Acties",
                    headerSort: false,
                    formatter: function(cell) {
                        // Voeg een Bootstrap button toe in de tabel
                        return `<button class="btn btn-xs btn-outline-primary py-0 px-2">Details</button>`;
                    }
                }
            ],
        });

        // Filter koppelen aan het Bootstrap input veld
        document.getElementById("search-input").addEventListener("keyup", function(e){
            table.setFilter("displayname", "like", e.target.value);
        });
    });
</script>
@endsection

@push('scripts')
@endpush