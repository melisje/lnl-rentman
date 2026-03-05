@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mx-2 p-3 bg-light rounded-3">
        <H1>{{ __('webhookcalls')}}</H1>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Webhook Log</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="webhook-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Account</th>
                                <th>IP</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Item Type</th>
                                <th>Created at</th>
                                <th width="80px">Actie</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <hr>


    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    // import nlLocale from 'datatables.net-plugins/i18n/nl-NL.json' assert { type: 'json' };
    $(document).ready(function() {
    $('#webhook-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25, // Zet de standaard op 25
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]], // Opties in de dropdown
        ajax: "{{ route('webhookcall.index') }}",
        order: [[0, "desc"]], // Nieuwste webhooks bovenaan
        columns: [
            { data: 'id', name: 'id' },
            { data: 'account', name: 'account' },
            { data: 'ip', name: 'ip' },
            { data: 'user', name: 'user' },
            { data: 'eventType', name: 'eventType' },
            { data: 'itemType', name: 'itemType' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language:
        {
            url: '/vendor/datatables/nl-NL.json'
        }
    });
});
</script>
@endpush