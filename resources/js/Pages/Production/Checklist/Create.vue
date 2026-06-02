<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('production.checklist.index')">Checklists</Link>
                        </li>
                        <li class="breadcrumb-item active">Nieuwe Checklist</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Checklist aanmaken</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label class="form-label">Gebruik Template</label>
                                <select v-model="form.template_id" class="form-control" :class="{ 'is-invalid': form.errors.template_id }">
                                    <option value="">-- Kies een Template --</option>
                                    <option v-for="t in templates" :key="t.id" :value="t.id">{{ t.name }}</option>
                                </select>
                                <div class="invalid-feedback">{{ form.errors.template_id }}</div>
                                <small class="text-muted">Alle items uit deze template worden gekopieerd.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Project</label>
                                <select v-model="form.project_id" class="form-control" :class="{ 'is-invalid': form.errors.project_id }">
                                    <option value="">-- Kies een project --</option>
                                    <option v-for="p in projects" :key="p.id" :value="p.id">
                                        {{ p.number }} - {{ p.name }}
                                    </option>
                                </select>
                                <div class="invalid-feedback">{{ form.errors.project_id }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Naam</label>
                                <input v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }" required>
                                <div class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Opmerkingen</label>
                                <textarea v-model="form.remarks" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <Link :href="route('production.checklist.index')" class="btn btn-outline-secondary">Annuleren</Link>
                                <button type="submit" class="btn btn-primary" :disabled="form.processing">Opslaan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

defineProps({ projects: Array, templates: Array });

const form = useForm({ name: '', remarks: '', project_id: '', template_id: '' });
const submit = () => form.post(route('production.checklist.store'));
</script>
