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

            <hr>

            <h2>Huidige week <small>({{ thisWeekLabel }})</small></h2>
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

            <hr>

            <h2>Week +1 <small>({{ nextWeekLabel }})</small></h2>
                <div class="row">
                <div v-for="project in nextWeekProjects" :key="project.id" class="col-md-4 p-2">
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
