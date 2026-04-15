<template>
    <div class="container-fluid">
        <div class="mx-2 p-3 bg-light rounded-3">
            <div class="row">
                <div class="col-8">
                    <h1>{{ project.name }}</h1>
                    <small>{{ project.planperiod_start }} - {{ project.planperiod_end }}</small>
                </div>
                <div class="col-4 mt-1">

                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <h4>Functies</h4>
                    <div v-for="functie in functions" :key="functie.id">
                        <div class="row">
                            <div class="col-6">
                                {{ functie.name }}
                            </div>
                            <div class="col-6">
                                {{ functie.duration }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    project: Object,
    functions: Array,
    indexUrl: String,
});
</script>
