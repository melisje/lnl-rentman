<template>
    <div class="container">
        <div class="mx-2 p-3 bg-light rounded-3">
            <h1>{{ checklist.name }}</h1>
            <span class="badge text-bg-primary fs-6">Project: {{ checklist.project_name ?? 'Geen project gekoppeld' }}</span>
            <div class="text-secondary mt-1">{{ checklist.remarks }}</div>

            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">Checklist Items</h5>
                    <div class="d-flex gap-2">
                        <Link :href="route('production.checklist.edit', checklist.id)" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Bewerken
                        </Link>
                        <Link :href="route('production.checklist.add-template-items.form', checklist.id)" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-plus-square"></i> Template items
                        </Link>
                        <button class="btn btn-sm btn-success" @click="addItem">
                            <i class="bi bi-plus-lg"></i> Item toevoegen
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px"></th>
                                <th style="width:50px">Ok</th>
                                <th>Omschrijving</th>
                                <th>Opmerkingen</th>
                                <th style="width:50px"></th>
                            </tr>
                        </thead>
                        <tbody ref="tbodyEl">
                            <tr v-for="item in items" :key="item.id" :data-id="item.id" class="align-middle">
                                <td class="text-muted handle" style="cursor:grab">
                                    <i class="bi bi-grip-vertical"></i>
                                </td>
                                <td>
                                    <input type="checkbox" class="form-check-input"
                                        :checked="item.is_completed"
                                        @change="toggleComplete(item, $event.target.checked)">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                                        :value="item.name"
                                        @blur="saveField(item, 'name', $event.target.value)">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                                        :value="item.remarks"
                                        placeholder="..."
                                        @blur="saveField(item, 'remarks', $event.target.value)">
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-link text-danger p-0" @click="deleteItem(item)">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';
import Sortable from 'sortablejs';
import axios from 'axios';

const props = defineProps({ checklist: Object });

const items   = ref([...props.checklist.items]);
const tbodyEl = ref(null);
let sortable  = null;

onMounted(() => {
    sortable = new Sortable(tbodyEl.value, {
        handle: '.handle',
        animation: 150,
        ghostClass: 'table-active',
        onEnd: async () => {
            const orders = [...tbodyEl.value.querySelectorAll('tr[data-id]')].map((row, index) => ({
                id: parseInt(row.dataset.id),
                sequence: index + 1,
            }));
            items.value = orders.map(o => items.value.find(i => i.id === o.id));
            await axios.post('/production/checklist-items/reorder', { orders });
        },
    });
});

onBeforeUnmount(() => sortable?.destroy());

const saveField = async (item, field, value) => {
    item[field] = value;
    await axios.patch(`/production/checklist-items/${item.id}`, {
        is_completed: item.is_completed ? 1 : 0,
        name:         item.name,
        remarks:      item.remarks,
    });
};

const toggleComplete = async (item, checked) => {
    item.is_completed = checked;
    await axios.patch(`/production/checklist-items/${item.id}`, {
        is_completed: checked ? 1 : 0,
        name:         item.name,
        remarks:      item.remarks,
    });
};

const addItem = async () => {
    const { data } = await axios.post(`/production/checklist/${props.checklist.id}/items`);
    items.value.push({ id: data.id, name: data.name ?? '', remarks: '', is_completed: false, sequence: data.sequence });
};

const deleteItem = async (item) => {
    if (!confirm('Item verwijderen?')) return;
    await axios.delete(`/production/checklist-items/${item.id}`);
    items.value = items.value.filter(i => i.id !== item.id);
};
</script>
