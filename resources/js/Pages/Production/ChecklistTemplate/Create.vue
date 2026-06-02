<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('production.checklist.template.index')">Templates</Link>
                        </li>
                        <li class="breadcrumb-item active">Nieuwe Template</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Checklist Template aanmaken</h5>
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
                                <Link :href="route('production.checklist.template.index')" class="btn btn-outline-secondary">Annuleren</Link>
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

const form = useForm({ name: '', remarks: '' });
const submit = () => form.post(route('production.checklist.template.store'));
</script>
