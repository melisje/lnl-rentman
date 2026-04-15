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
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Zoek op naam of nummer..."
                                :value="search ?? ''"
                            >
                            <button class="btn btn-outline-secondary" type="submit">Zoeken</button>
                        </div>
                    </form>
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
