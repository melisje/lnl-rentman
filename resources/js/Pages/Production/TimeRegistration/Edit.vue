<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="mb-3">
                    <Link :href="route('production.project.timeregistration.index')" class="text-decoration-none text-muted">
                        &larr; Terug naar overzicht
                    </Link>
                    <h1 class="h3 mt-2">Registratie #{{ registration.id }} bewerken</h1>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form @submit.prevent="submit">
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold">Project</label>
                                    <input v-model="projectSearch" type="text" class="form-control" :class="{ 'is-invalid': form.errors.project_id }"
                                        placeholder="Typ projectnaam..." list="projectOptions" @input="matchProject" required>
                                    <datalist id="projectOptions">
                                        <option v-for="p in projects" :key="p.id" :value="p.label" :data-id="p.id" />
                                    </datalist>
                                    <div class="invalid-feedback">{{ form.errors.project_id }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold">Crewmember</label>
                                    <input v-model="crewSearch" type="text" class="form-control" :class="{ 'is-invalid': form.errors.crewmember_id }"
                                        placeholder="Typ naam crewlid..." list="crewOptions" @input="matchCrew" required>
                                    <datalist id="crewOptions">
                                        <option v-for="c in crewmembers" :key="c.id" :value="c.label" :data-id="c.id" />
                                    </datalist>
                                    <div class="invalid-feedback">{{ form.errors.crewmember_id }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Budget Type</label>
                                <select v-model="form.budget_type" class="form-select" required>
                                    <option v-for="t in budgetTypes" :key="t" :value="t">{{ t }}</option>
                                </select>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Starttijd</label>
                                    <input v-model="form.start" type="datetime-local" class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Eindtijd</label>
                                    <input v-model="form.end" type="datetime-local" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Directe Duur (uren)</label>
                                <input v-model="form.duration" type="number" step="0.01" class="form-control">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Opmerkingen</label>
                                <textarea v-model="form.remarks" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <Link :href="route('production.project.timeregistration.index')" class="btn btn-light border">Annuleren</Link>
                                <button type="submit" class="btn btn-warning fw-semibold" :disabled="form.processing">Opslaan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ registration: Object, projects: Array, crewmembers: Array, budgetTypes: Array });

const form = useForm({
    project_id:    props.registration.project_id,
    crewmember_id: props.registration.crewmember_id,
    budget_type:   props.registration.budget_type,
    start:         props.registration.start ?? '',
    end:           props.registration.end ?? '',
    duration:      props.registration.duration ?? '',
    remarks:       props.registration.remarks ?? '',
});

const projectSearch = ref(props.projects.find(p => p.id === props.registration.project_id)?.label ?? '');
const crewSearch    = ref(props.crewmembers.find(c => c.id === props.registration.crewmember_id)?.label ?? '');

const matchProject = () => {
    const match = props.projects.find(p => p.label === projectSearch.value);
    form.project_id = match ? match.id : '';
};

const matchCrew = () => {
    const match = props.crewmembers.find(c => c.label === crewSearch.value);
    form.crewmember_id = match ? match.id : '';
};

const submit = () => form.put(route('production.project.timeregistration.update', props.registration.id));
</script>
