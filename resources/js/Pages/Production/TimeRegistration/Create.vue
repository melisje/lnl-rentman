<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="mb-3">
                    <Link :href="route('production.project.timeregistration.index')" class="text-decoration-none text-muted">
                        &larr; Terug naar overzicht
                    </Link>
                    <h1 class="h3 mt-2">Nieuwe Tijdregistratie</h1>
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
                                <select v-model="form.budget_type" class="form-select" :class="{ 'is-invalid': form.errors.budget_type }" required>
                                    <option value="" disabled>Kies een type...</option>
                                    <option v-for="t in budgetTypes" :key="t" :value="t">{{ t }}</option>
                                </select>
                                <div class="invalid-feedback">{{ form.errors.budget_type }}</div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold">Starttijd <span class="text-danger">*</span></label>
                                    <input v-model="form.start" type="datetime-local" class="form-control" :class="{ 'is-invalid': form.errors.start }" required>
                                    <div class="invalid-feedback">{{ form.errors.start }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Eindtijd (optioneel)</label>
                                    <input v-model="form.end" type="datetime-local" class="form-control" :class="{ 'is-invalid': form.errors.end }">
                                    <div class="invalid-feedback">{{ form.errors.end }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Duur (u:mm)</label>
                                <input v-model="form.duration" type="text" class="form-control" placeholder="Bijv. 2:30" :class="{ 'is-invalid': form.errors.duration }">
                                <small class="text-muted">Laat eindtijd leeg → wordt automatisch berekend vanuit duur.</small>
                                <div class="invalid-feedback">{{ form.errors.duration }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Opmerkingen</label>
                                <textarea v-model="form.remarks" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <Link :href="route('production.project.timeregistration.index')" class="btn btn-light border">Annuleren</Link>
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
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ projects: Array, crewmembers: Array, budgetTypes: Array, defaultStart: String });

const form = useForm({
    project_id: '', crewmember_id: '', budget_type: '',
    start: props.defaultStart, end: '', duration: '', remarks: '',
});

const projectSearch = ref('');
const crewSearch    = ref('');

const matchProject = () => {
    const match = props.projects.find(p => p.label === projectSearch.value);
    form.project_id = match ? match.id : '';
};

const matchCrew = () => {
    const match = props.crewmembers.find(c => c.label === crewSearch.value);
    form.crewmember_id = match ? match.id : '';
};

const submit = () => form.post(route('production.project.timeregistration.store'));
</script>
