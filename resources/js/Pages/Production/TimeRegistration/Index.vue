<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Project Tijdregistraties</h1>
            <Link :href="route('production.project.timeregistration.create')" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg"></i> Nieuwe Registratie
            </Link>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Project</th>
                            <th>Crewmember</th>
                            <th>Budget Type</th>
                            <th>Duur (uur)</th>
                            <th class="text-end pe-4">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in registrations" :key="r.id">
                            <td class="ps-4">{{ r.id }}</td>
                            <td class="fw-bold">{{ r.project_name }}</td>
                            <td>{{ r.crew_name }}</td>
                            <td>
                                <span class="badge" :class="budgetClass(r.budget_type)">{{ r.budget_type }}</span>
                            </td>
                            <td>{{ r.duration ?? 'Niet berekend' }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <Link :href="route('production.project.timeregistration.show', r.id)" class="btn btn-outline-primary">Bekijk</Link>
                                    <Link :href="route('production.project.timeregistration.edit', r.id)" class="btn btn-outline-warning">Bewerk</Link>
                                    <button class="btn btn-outline-danger" @click="destroy(r)">Verwijder</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="registrations.length === 0">
                            <td colspan="6" class="text-center py-5 text-muted">Geen tijdregistraties gevonden.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({ registrations: Array });

const budgetClass = (type) => ({
    'bg-dark':    type === 'pm',
    'bg-warning text-dark': type === 'light',
    'bg-info text-dark':    type === 'sound',
    'bg-danger':            type === 'rigging',
});

const destroy = (r) => {
    if (!confirm(`Registratie #${r.id} verwijderen?`)) return;
    router.delete(route('production.project.timeregistration.destroy', r.id));
};
</script>
