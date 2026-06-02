<template>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Checklist Templates</h1>
            <Link :href="route('production.checklist.template.create')" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Nieuwe Template
            </Link>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Naam</th>
                            <th>Items</th>
                            <th>Aangemaakt</th>
                            <th class="text-end">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="template in templates" :key="template.id">
                            <td><strong>{{ template.name }}</strong></td>
                            <td><span class="badge bg-info">{{ template.items_count }} items</span></td>
                            <td>{{ template.created_at }}</td>
                            <td class="text-end">
                                <Link :href="route('production.checklist.template.show', template.id)" class="btn btn-sm btn-outline-secondary me-1">Bekijken</Link>
                                <Link :href="route('production.checklist.template.edit', template.id)" class="btn btn-sm btn-outline-primary me-1">Bewerken</Link>
                                <button class="btn btn-sm btn-outline-danger" @click="destroy(template)">Verwijderen</button>
                            </td>
                        </tr>
                        <tr v-if="templates.length === 0">
                            <td colspan="4" class="text-center py-4 text-muted">Geen templates gevonden.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({ templates: Array });

const destroy = (template) => {
    if (!confirm(`Template "${template.name}" en alle items verwijderen?`)) return;
    router.delete(route('production.checklist.template.destroy', template.id));
};
</script>
