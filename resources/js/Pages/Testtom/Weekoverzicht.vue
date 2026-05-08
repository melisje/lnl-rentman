<template>

    <table class="table">
        <tbody>
            <template v-for="item in processedModels" :key="item.type === 'header' ? `week-${item.weeks}` : item.id">
                <tr v-if="item.type === 'header'" class="row-week-header">
                    <td colspan="2" class="fw-bold week">{{ weekLabel(item.weeks) }}</td>
                    <td width="100px" class="text-center">PM<br>(U)</td>
                    <td width="100px" class="text-center">Light<br>(U)</td>
                    <td width="100px" class="text-center">Sound<br>(U)</td>
                    <td width="100px" class="text-center">Rigging<br>(U)</td>
                    <td width="100px" class="text-center">Checklist<br>(tasks)</td>
                </tr>
                <tr v-else :class="item.useAltBg ? 'row-alt' : 'row-default'">
                    <td>
                        {{ formatDate(item.planperiod_start) }} <br> {{ formatDate(item.usageperiod_start) }}
                    </td>
                    <td>
                        <h4 style="margin-bottom: -2px">{{ item.full_display_name }}</h4>
                        {{ item.am_name }} {{ item.pm_name }}
                    </td>

                    <td v-for="category in ['projectmanager', 'light', 'sound', 'rigging']" :key="category"
                        class="text-center cell-toggle" :style="budgetCellStyle(item, category)">
                        <span class="cell-pct">{{ fmtPct(budgetPercent(item, category)) }}%</span>
                        <span class="cell-fraction">{{ fmt(budgetConsumption(item, category)) }} / {{ fmt(budget(item, category)) }}</span>
                    </td>

                    <td class="text-center cell-toggle" :style="checklistCellStyle(item)">
                        <span class="cell-pct">{{ fmtPct(checklistPercent(item)) }}%</span>
                        <span class="cell-fraction">{{ item.count_checklist_items_completed ?? 0 }} / {{ item.count_checklist_items ?? 0 }}</span>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>

</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    models: Array,
});

const processedModels = computed(() => {
    let lastWeeks = null;
    let useAltBg = false;
    const result = [];

    props.models.forEach(model => {
        if (lastWeeks !== model.weeks_until_start_plan) {
            useAltBg = !useAltBg;
            lastWeeks = model.weeks_until_start_plan;
            result.push({ type: 'header', weeks: model.weeks_until_start_plan });
        }
        result.push({
            ...model,
            type: 'row',
            useAltBg,
        });
    });

    return result;
});

function weekLabel(weeks) {
    if (weeks < 0) return `${Math.abs(weeks)} ${Math.abs(weeks) !== 1 ? 'weken' : 'week'} geleden`;
    if (weeks === 0) return 'Deze week';
    if (weeks === 1) return 'Volgende week';
    return `Over ${weeks} weken`;
}

function budgetConsumption(row, category) {
    return row.budget_consumption?.[category] ?? 0;
}

function budget(row, category) {
    return row.budgets?.[category] ?? 0;
}

function budgetPercent(row, category) {
    const b = budget(row, category);
    return b > 0 ? (budgetConsumption(row, category) / b) * 100 : 0;
}

function checklistPercent(row) {
    const items = row.count_checklist_items ?? 0;
    const completed = row.count_checklist_items_completed ?? 0;
    return items > 0 ? (completed / items) * 100 : 0;
}

function percentStyle(percent) {
    if (percent > 100) return { backgroundColor: '#3b5bdb', color: '#ffffff' }; // over budget: blauw
    if (percent === 100) return { backgroundColor: '#2f9e44', color: '#ffffff' }; // compleet: groen
    if (percent >= 75) return { backgroundColor: '#f59f00', color: '#ffffff' };  // bijna: amber
    if (percent >= 50) return { backgroundColor: '#e8590c', color: '#ffffff' };  // halverwege: oranje
    return { backgroundColor: '#c92a2a', color: '#ffffff' };                     // laag: rood
}

function budgetCellStyle(row, category) {
    return percentStyle(budgetPercent(row, category));
}

function checklistCellStyle(row) {
    return percentStyle(checklistPercent(row));
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const [year, month, day] = dateStr.split('-');
    return `${day}/${month}`;
}

function fmt(value) {
    return Math.round(Number(value));
}

function fmtPct(value) {
    return Math.round(Number(value));
}
</script>

<style scoped>
.row-week-header {
    --bs-table-bg: var(--submenu-bg);
    --bs-table-color: var(--submenu-text);
    color: var(--submenu-text);
}

.row-week-header .week {
    padding: 20px;
    font-size: 1.4rem;
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1;
}

.row-default {
    --bs-table-bg: var(--surface-2);
    --bs-table-color: var(--text);
}

.row-alt {
    --bs-table-bg: var(--surface);
    --bs-table-color: var(--text);
}

.cell-fraction {
    display: none;
    font-size: 18px;
}

.cell-toggle:hover .cell-pct {
    display: none;
}

.cell-toggle:hover .cell-fraction {
    display: inline;
}

.cell-pct {
    font-weight: bold;
    font-size: 25px;
}
</style>
