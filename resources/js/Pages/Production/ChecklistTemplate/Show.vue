<template>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <Link :href="route('production.checklist.template.index')">Templates</Link>
                    </li>
                    <li class="breadcrumb-item active">{{ template.name }}</li>
                </ol>
            </nav>
            <Link :href="route('production.checklist.template.edit', template.id)" class="btn btn-sm btn-outline-primary">
                Naam / Opmerkingen bewerken
            </Link>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Items</h5>
                <button class="btn btn-sm btn-success" @click="showForm = !showForm">
                    <i class="bi bi-plus-lg"></i> Item toevoegen
                </button>
            </div>

            <div v-if="showForm" class="card-body border-bottom bg-light">
                <form @submit.prevent="storeItem" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label mb-1">Naam</label>
                        <input v-model="newItem.name" type="text" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1">Opmerkingen</label>
                        <input v-model="newItem.remarks" type="text" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1">Volgorde</label>
                        <input v-model.number="newItem.sequence" type="number" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-success" :disabled="saving">Toevoegen</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" @click="showForm = false">Annuleren</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Naam</th>
                            <th>Opmerkingen</th>
                            <th width="100" class="text-end">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items" :key="item.id">
                            <td class="text-muted">{{ item.sequence }}</td>
                            <td><strong>{{ item.name }}</strong></td>
                            <td><small class="text-muted">{{ item.remarks }}</small></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-link text-danger p-0" @click="deleteItem(item)">
                                    Verwijderen
                                </button>
                            </td>
                        </tr>
                        <tr v-if="items.length === 0">
                            <td colspan="4" class="text-center py-4 text-muted">Geen items. Klik 'Item toevoegen' om te beginnen.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({ template: Object });

const items    = ref([...props.template.items]);
const showForm = ref(false);
const saving   = ref(false);
const newItem  = ref({ name: '', remarks: '', sequence: (props.template.items.at(-1)?.sequence ?? 0) + 1 });

const storeItem = async () => {
    saving.value = true;
    try {
        const { data } = await axios.post(route('production.checklist.template.items.store', props.template.id), newItem.value);
        items.value.push(data);
        newItem.value = { name: '', remarks: '', sequence: (items.value.at(-1)?.sequence ?? 0) + 1 };
        showForm.value = false;
    } finally {
        saving.value = false;
    }
};

const deleteItem = (item) => {
    if (!confirm('Item verwijderen?')) return;
    router.delete(route('production.checklist.template-items.destroy', item.id), {
        preserveScroll: true,
        onSuccess: () => { items.value = items.value.filter(i => i.id !== item.id); },
    });
};
</script>
