<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('production.checklist.template.index')">Templates</Link>
                        </li>
                        <li class="breadcrumb-item">
                            <Link :href="route('production.checklist.template.show', template.id)">{{ template.name }}</Link>
                        </li>
                        <li class="breadcrumb-item active">Bewerken</li>
                    </ol>
                </nav>

                <div class="card shadow-sm border-warning">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-warning">Template bewerken: {{ template.name }}</h5>
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
                                <Link :href="route('production.checklist.template.show', template.id)" class="btn btn-outline-secondary">Annuleren</Link>
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

const props = defineProps({ template: Object });

const form = useForm({ name: props.template.name, remarks: props.template.remarks ?? '' });
const submit = () => form.put(route('production.checklist.template.update', props.template.id));

const destroy = () => {
    if (!confirm('Template en alle items verwijderen?')) return;
    router.delete(route('production.checklist.template.destroy', props.template.id));
};
</script>
