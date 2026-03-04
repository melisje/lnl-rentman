@extends('layouts.app')

@push('styles')
@endpush

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>Suppliers</H1>
        @include('layouts.errors')

        <div class="mt-5">
            <div class="card">
                <div class="card-header bg-primary text-white">Supplier Management</div>
                <div class="card-body">
                    <div id="supplier-table"></div>
                    <div id="table-info" class="mt-2 text-muted small text-end">
                        Laden van gegevens...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log("Start of script");

    const supplierRoutes =
    {
        show: "{{ route('purchase.suppliers.show', ':id') }}",
        edit: "{{ route('purchase.suppliers.edit', ':id') }}"
    };

    document.addEventListener('DOMContentLoaded', function () {

        var table = new Tabulator("#supplier-table", {

            layout: "fitColumns",
            // Verminder de witruimte in de cellen
            renderVertical: "basic",

            ajaxURL: "/purchase/supplier/data",
            // ajaxConfig: "GET",

            // Optioneel: als je wilt dat de URL korter blijft (v6 style)
            ajaxConfig: {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                },
            },

            // Setup remote pagination in Tabulator 5+
            pagination: true,
            paginationMode: "remote",
            paginationSize: 10,
            // Voeg deze regel toe voor het keuzemenu:
            paginationSizeSelector: [10, 25, 50, 100],

            sortMode: "remote", // Remote sorteren voor v6.x
            // columnHeaderSortMulti: true, // Gebruiker kan meerdere kolommen klikken zonder Shift

            // Je kunt ook een standaard sortering meegeven bij het laden:
            initialSort: [
                // {column: "name", dir: "desc"},
                {column: "id", dir: "asc"},
            ],

            filterMode: "remote", // <--- Activeer remote filtering

            placeholder: "Loading Data...",

            ajaxResponse: function(url, params, response)
            {
                // Update de teller direct met de 'total' uit je Laravel JSON
                if (response && response.total !== undefined) {
                    document.getElementById("table-info").innerHTML =
                        `Totaal gevonden: <strong>${response.total}</strong> leveranciers`;
                }

                // Geef de data door in het formaat dat paginationDataReceived verwacht
                return response;
            },

            paginationDataReceived:
            {
                data: "data",
                last_page: "last_page",
            },

            dataLoaded: function(data)
            {
                console.log(data);

                // Tabulator geeft ons toegang tot de volledige JSON response van de server
                // via de interne AJAX module
                var total = table.modules.ajax.lastResponse.total;

                document.getElementById("table-info").innerHTML =
                `Totaal gevonden: <strong>${total}</strong> leveranciers`;
            },

            columns: [
                {title: "ID", field: "id", width: 100, sorter: "number"}, // 'id' moet bestaan in je DB
                {title: "Name", field: "name", sorter: "string", headerFilter: "input"},
                {title: "VAT", field: "vat_number", sorter: "string", headerFilter: "input"},
                {title: "Email", field: "email", sorter: "string", headerFilter: "input"},
                {title: "Phone", field: "phone", sorter: "string", headerFilter: "input"},
                {title: "Address", field: "address", sorter: "string", headerFilter: "input"},
                {title: "Actions", field: "id", width: 150,
                    headerSort: false, // Je wilt niet sorteren op knoppen
                    hozAlign: "right",
                    formatter: function(cell, formatterParams, onRendered)
                    {
                        // Pak de ID van de huidige rij
                        const id = cell.getValue();

                        let url_detail = supplierRoutes.show.replace(':id', id);
                        let url_edit = supplierRoutes.edit.replace(':id', id);

                        // Genereer de HTML voor de knoppen (Bootstrap stijl)
                        return `
                        <div class="btn-group" role="group">
                            <a href="${url_detail}" class="btn btn-sm btn-info text-white" title="Details">
                                <i class="bi bi-search"></i>
                            </a>
                            <a href="${url_edit}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                        `;
                    }
                },
            ],
        });

    });

    // table.on("tableBuilt", function(){
    // document.querySelector(".tabulator").classList.add("table", "table-striped");
    // });
</script>
@endpush