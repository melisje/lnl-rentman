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
                        <li class="breadcrumb-item active">Template items toevoegen</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Template items toevoegen aan: {{ checklist.name }}</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label class="form-label">Kies Template</label>
                                <select v-model="form.template_id" class="form-control" :class="{ 'is-invalid': form.errors.template_id }">
                                    <option value="">-- Kies een Template --</option>
                                    <option v-for="t in templates" :key="t.id" :value="t.id">{{ t.name }}</option>
                                </select>
                                <div class="invalid-feedback">{{ form.errors.template_id }}</div>
                                <small class="text-muted">Alle items worden achteraan de checklist toegevoegd.</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <Link :href="route('production.checklist.show', checklist.id)" class="btn btn-outline-secondary">Annuleren</Link>
                                <button type="submit" class="btn btn-primary" :disabled="form.processing">Items toevoegen</button>
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

const props = defineProps({ checklist: Object, templates: Array });

const form = useForm({ template_id: '' });
const submit = () => form.post(route('production.checklist.add-template-items.store', props.checklist.id));
</script>
