<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('production.checklist.index')">Checklists</Link>
                        </li>
                        <li class="breadcrumb-item">
                            <Link :href="route('production.checklist.show', checklist.id)">{{ checklist.name }}</Link>
                        </li>
                        <li class="breadcrumb-item active">Bewerken</li>
                    </ol>
                </nav>

                <div class="card shadow-sm border-warning">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-warning">Checklist bewerken: {{ checklist.name }}</h5>
                        <button class="btn btn-sm btn-outline-danger" @click="destroy">Verwijderen</button>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label class="form-label">Naam</label>
                                <input v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }" required>
                                <div class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Opmerkingen</label>
                                <textarea v-model="form.remarks" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <Link :href="route('production.checklist.show', checklist.id)" class="btn btn-outline-secondary">Annuleren</Link>
                                <button type="submit" class="btn btn-warning" :disabled="form.processing">Opslaan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({ checklist: Object });

const form = useForm({ name: props.checklist.name, remarks: props.checklist.remarks ?? '' });
const submit = () => form.put(route('production.checklist.update', props.checklist.id));

const destroy = () => {
    if (!confirm('Checklist en alle items verwijderen?')) return;
    router.delete(route('production.checklist.destroy', props.checklist.id));
};
</script>
