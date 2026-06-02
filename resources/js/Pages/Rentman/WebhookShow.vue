<template>
    <div class="container">
        <div class="mx-2 p-3 bg-light rounded-3">
            <h1>Webhook Call: {{ webhookcall.id }}</h1>

            <div class="row">
                <div class="col">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Account</span>
                        <div class="form-control">{{ webhookcall.account }}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-3">
                        <span class="input-group-text">From</span>
                        <div class="form-control">{{ webhookcall.ip }}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-3">
                        <span class="input-group-text">User</span>
                        <div class="form-control">{{ webhookcall.user }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Event Type</span>
                        <div class="form-control">{{ webhookcall.eventType }}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Item Type</span>
                        <div class="form-control">{{ webhookcall.itemType }}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Event Date</span>
                        <div class="form-control">{{ webhookcall.eventDate }}</div>
                    </div>
                </div>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Payload</span>
                <div class="form-control">{{ webhookcall.payload }}</div>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Headers</span>
                <div class="form-control">
                    <div v-for="(value, key) in parsedHeaders" :key="key" class="row">
                        <div class="col-2">{{ key }}</div>
                        <div class="col">{{ Array.isArray(value) ? value[0] : value }}</div>
                    </div>
                </div>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Items</span>
                <div class="form-control">
                    <div v-for="(item, index) in parsedItems" :key="index">
                        {{ JSON.stringify(item) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    webhookcall: Object,
});

const parsedHeaders = computed(() => {
    try { return JSON.parse(props.webhookcall.headers ?? '{}'); }
    catch { return {}; }
});

const parsedItems = computed(() => {
    try { return JSON.parse(props.webhookcall.items ?? '[]'); }
    catch { return []; }
});
</script>
