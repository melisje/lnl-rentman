<template>
    <div class="container-fluid">
        <div class="mx-2 p-3 bg-light rounded-3">
            <div class="row">
                <div class="col-8">
                    <h1>Weekoverzicht</h1>
                </div>
                <div class="col-4 mt-1">
                    <form method="GET" :action="searchUrl" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Zoek op naam of nummer..." :value="search ?? ''">
                            <button class="btn btn-outline-secondary" type="submit">Zoeken</button>
                        </div>
                    </form>
                </div>
            </div>

            <h2>Huidige week</h2>
            <div class="row">
                <div v-for="project in thisWeekProjects" :key="project.id" class="col-md-4 p-2">
                    <a :href="project.url" class="text-decoration-none">
                        <div class="card">
                            <div class="card-header">
                                <h4 v-if="project.name.length < 40">{{ project.name }}</h4>
                                <h4 v-else>{{ project.name.substring(0, 40) + ".." }}</h4>
                            </div>
                            <div class="row">
                                <div class="col-4 text-center p-4">
                                    <h1><i class="bi bi-speaker"></i></h1>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 25%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-4 text-center p-4">
                                    <h1><i class="bi bi-lightbulb"></i></h1>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 25%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-4 text-center p-4">
                                    <h1><i class="bi bi-puzzle"></i></h1>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 25%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <span class="text-black">{{ project.planperiod_start }} - {{ project.planperiod_end
                                }}</span>
                            </div>
                        </div>

                    </a>
                </div>
            </div>

            <table class="table table-sm table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th colspan="4">Huidige week ({{ thisWeekLabel }})</th>
                    </tr>
                    <tr>
                        <th>Project</th>
                        <th width="50px">Van</th>
                        <th width="50px">Tot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="thisWeekProjects.length === 0">
                        <td colspan="4" class="text-muted">Geen projecten deze week</td>
                    </tr>
                    <tr v-for="project in thisWeekProjects" :key="project.id">
                        <td>
                            [<a class="text-decoration-none" :href="project.url">{{ project.number }}</a>]
                            <a class="text-decoration-none" :href="project.url">{{ project.name }}</a>
                        </td>
                        <td>{{ project.planperiod_start }}</td>
                        <td>{{ project.planperiod_end }}</td>
                    </tr>
                </tbody>
                <thead class="table-secondary">
                    <tr>
                        <th colspan="4">Week +1 ({{ nextWeekLabel }})</th>
                    </tr>
                    <tr>
                        <th>Project</th>
                        <th>Van</th>
                        <th>Tot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="nextWeekProjects.length === 0">
                        <td colspan="4" class="text-muted">Geen projecten volgende week</td>
                    </tr>

                    <tr v-for="project in nextWeekProjects" :key="project.id">
                        <td>
                            [<a class="text-decoration-none" :href="project.url">{{ project.number }}</a>]
                            <a class="text-decoration-none" :href="project.url">{{ project.name }}</a>
                        </td>
                        <td>{{ project.planperiod_start }}</td>
                        <td>{{ project.planperiod_end }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    thisWeekLabel: String,
    nextWeekLabel: String,
    thisWeekProjects: Array,
    nextWeekProjects: Array,
    search: String,
});

const searchUrl = '/testtom/search';
</script>
