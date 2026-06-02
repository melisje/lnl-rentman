<template>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Checklists</h1>
            <Link :href="route('production.checklist.create')" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Nieuwe Checklist
            </Link>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Project</th>
                            <th>Naam</th>
                            <th>Voortgang</th>
                            <th class="text-end">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="checklist in checklists" :key="checklist.id">
                            <td><strong>{{ checklist.project_name ?? '—' }}</strong></td>
                            <td>{{ checklist.name }}</td>
                            <td>
                                <span v-if="checklist.items_count > 0" :class="`badge bg-${checklist.status_color}`">
                                    {{ checklist.completed_count }} / {{ checklist.items_count }}
                                    <small class="ms-1 fw-bold">{{ checklist.status_percentage }}%</small>
                                </span>
                                <span v-else class="badge bg-secondary">Geen items</span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-gear-fill me-1"></i> Acties
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            <Link :href="route('production.checklist.show', checklist.id)" class="dropdown-item">
                                                <i class="bi bi-eye text-secondary me-2"></i> Bekijken
                                            </Link>
                                        </li>
                                        <li>
                                            <Link :href="route('production.checklist.edit', checklist.id)" class="dropdown-item">
                                                <i class="bi bi-pencil text-primary me-2"></i> Bewerken
                                            </Link>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item text-danger" @click="destroy(checklist)">
                                                <i class="bi bi-trash me-2"></i> Verwijderen
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="checklists.length === 0">
                            <td colspan="4" class="text-center py-4 text-muted">Geen checklists gevonden.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({ checklists: Array });

const destroy = (checklist) => {
    if (!confirm(`Checklist "${checklist.name}" verwijderen?`)) return;
    router.delete(route('production.checklist.destroy', checklist.id));
};
</script>
