<template>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="mb-3">
                    <Link :href="route('production.project.timeregistration.index')" class="text-decoration-none text-muted">
                        &larr; Terug naar overzicht
                    </Link>
                    <h1 class="h3 mt-2">Details Registratie #{{ registration.id }}</h1>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="row g-3 border-bottom mb-3 pb-3">
                            <div class="col-6">
                                <small class="text-muted text-uppercase fw-semibold d-block">Project</small>
                                <span class="fs-5">{{ registration.project_name }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted text-uppercase fw-semibold d-block">Crewmember</small>
                                <span class="fs-5">{{ registration.crew_name }}</span>
                            </div>
                        </div>

                        <div class="row g-3 border-bottom mb-3 pb-3">
                            <div class="col-6">
                                <small class="text-muted text-uppercase fw-semibold d-block mb-1">Budget Type</small>
                                <span class="badge bg-primary fs-7">{{ registration.budget_type }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted text-uppercase fw-semibold d-block">Duur</small>
                                <span class="fs-5 fw-bold">{{ registration.duration ?? 'Onbekend' }} uur</span>
                            </div>
                        </div>

                        <div class="row g-3 border-bottom mb-3 pb-3">
                            <div class="col-6">
                                <small class="text-muted text-uppercase fw-semibold d-block">Starttijd</small>
                                <span>{{ registration.start ?? 'Nvt' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted text-uppercase fw-semibold d-block">Eindtijd</small>
                                <span>{{ registration.end ?? 'Nvt' }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <small class="text-muted text-uppercase fw-semibold d-block">Opmerkingen</small>
                            <p class="bg-light p-3 rounded mt-1" style="white-space:pre-line">
                                {{ registration.remarks ?? 'Geen opmerkingen.' }}
                            </p>
                        </div>

                        <div class="text-muted" style="font-size:0.75rem">
                            Aangemaakt: {{ registration.created_at }}
                        </div>
                    </div>

                    <div class="card-footer bg-light px-4 py-3 d-flex justify-content-end">
                        <Link :href="route('production.project.timeregistration.edit', registration.id)" class="btn btn-warning fw-semibold">
                            Bewerken
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({ registration: Object });
</script>
