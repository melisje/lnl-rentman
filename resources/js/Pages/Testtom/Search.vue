<template>
    <div class="container-fluid">
        <div class="mx-2 p-3 bg-light rounded-3">
            <div class="row">
                <div class="col-8">
                    <h1>Zoekresultaten</h1>
                </div>
                <div class="col-4 mt-1">
                    <form method="GET" :action="searchUrl" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" v-model="searchInput"
                                placeholder="Zoek op naam of nummer...">
                            <button class="btn btn-outline-secondary" type="submit">Zoeken</button>

                            <a v-if="searchInput" :href="indexUrl" class="btn btn-outline-danger">X</a>
                        </div>
                    </form>

                </div>
            </div>
            <table class="table table-bordered">
                <tr v-for="project in projects.data" :key="project.id">
                    <td width="50px">[<a :href="project.url">{{ project.number }}</a>]</td>
                    <td><a class="text-decoration-none" :href="project.url">{{ project.name }}</a></td>
                    <td width="100px">{{ project.account }}</td>
                </tr>
            </table>

            <br>

            <nav v-if="projects.links.length > 3">
                <ul class="pagination">
                    <li v-for="link in projects.links" :key="link.label" class="page-item"
                        :class="{ active: link.active, disabled: !link.url }">
                        <a class="page-link" href="#" v-html="link.label"
                            @click.prevent="link.url && goToPage(link.url)"></a>
                    </li>
                </ul>
            </nav>


        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    projects: Object,
    search: String,
    indexUrl: String,
});

const searchInput = ref(props.search ?? '');

const goToPage = (url) => {
    router.get(url, {}, {
        preserveState: true,
        replace: true,
    });
};

let timeout = null;

watch(searchInput, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/testtom/search', { search: value }, {
            preserveState: true,
            replace: true,
        });
    }, 300)
});

</script>
