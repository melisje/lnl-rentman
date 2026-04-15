<template>
    <div class="container-fluid">
        <div>
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

            <table class="table table-bordered table-hover table-sm">
                <thead>
                <tr>
                    <th>Week {{ thisWeekLabel }}</th>
                    <th width="150px">Sound</th>
                    <th width="150px">Light</th>
                    <th width="150px">Rigging</th>
                    <th width="150px">Checklist</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="project in thisWeekProjects" :key="project.id">
                    <td><a :href="project.url" class="text-decoration-none">{{ project.name }}</a></td>
                    <td :class="severityClass(project.sound)">{{ project.sound }}%</td>
                    <td :class="severityClass(project.light)">{{ project.light }}%</td>
                    <td :class="severityClass(project.rigging)">{{ project.rigging }}%</td>
                    <td :class="severityClass(project.checklist)">{{ project.checklist }}%</td>
                </tr>
                </tbody>
                <br>
                <thead>
                <tr>
                    <th>Week {{ nextWeekLabel }}</th>
                    <th>Sound</th>
                    <th>Light</th>
                    <th>Rigging</th>
                    <th>Checklist</th>
                </tr>
                </thead>    
                <tbody>
                <tr v-for="project in nextWeekProjects" :key="project.id">
                    <td><a :href="project.url" class="text-decoration-none">{{ project.name }}</a></td>
                    <td :class="severityClass(project.sound)">{{ project.sound }}%</td>
                    <td :class="severityClass(project.light)">{{ project.light }}%</td>
                    <td :class="severityClass(project.rigging)">{{ project.rigging }}%</td>
                    <td :class="severityClass(project.checklist)">{{ project.checklist }}%</td>
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

const severityClass = (value) => {
    if (value === null || value === undefined) return '';
    if (value === 100) return 'bg-danger fw-bold text-white';
    if (value >= 75)   return 'bg-danger text-white';
    if (value >= 50)   return 'bg-warning text-black';
    return 'bg-success text-white';
};


const searchUrl = '/testtom/search';
</script>
