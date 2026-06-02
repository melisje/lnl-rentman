<template>
    <div class="container-fluid">
        <div class="mx-2 p-3 bg-light rounded-3">
            <h1>Webhook Calls</h1>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Webhook Log</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table ref="tableEl" class="table table-hover table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Account</th>
                                    <th>IP</th>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Item Type</th>
                                    <th>Items</th>
                                    <th>EventDate</th>
                                    <th width="80px">Actie</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const tableEl = ref(null);
let dt = null;

onMounted(() => {
    dt = window.$(tableEl.value).DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        ajax: '/webhookcall',
        order: [[0, 'desc']],
        columns: [
            { data: 'id',        name: 'rm_webhook_calls.id' },
            { data: 'account',   name: 'account' },
            { data: 'ip',        name: 'rm_webhook_calls.ip' },
            { data: 'user_name', name: 'rm_crew.displayname' },
            { data: 'eventType', name: 'rm_webhook_calls.eventType' },
            { data: 'itemType',  name: 'rm_webhook_calls.itemType' },
            { data: 'items',     name: 'rm_webhook_calls.items' },
            { data: 'eventDate', name: 'rm_webhook_calls.eventDate' },
            { data: 'action',    name: 'action', orderable: false, searchable: false },
        ],
        language: { url: '/vendor/datatables/nl-NL.json' },
    });
});

onBeforeUnmount(() => {
    dt?.destroy();
});
</script>
