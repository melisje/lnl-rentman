<template>

    <table class="table">
        <tbody>
            <template v-for="item in processedModels" :key="item.type === 'header' ? `week-${item.weeks}` : item.id">
                <tr v-if="item.type === 'header'" class="row-week-header">
                    <td colspan="2" class="fw-bold week" v-html="weekLabelHtml(item.weeks)"></td>
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
                        <span v-if="item.am_name"><span class="badge rounded-pill bg-orange">AM</span> {{ item.am_name }}</span> 
                        <span v-else><span class="badge rounded-pill bg-orange">AM</span> <span class="text-danger">Niet toegewezen</span></span>
                         - 
                        <span v-if="item.pm_name"><span class="badge rounded-pill bg-orange">PM</span> {{ item.pm_name }}</span>
                        <span v-else><span class="badge rounded-pill bg-orange">PM</span> <span class="text-danger">Niet toegewezen</span></span>
                    </td>

                    <td v-for="category in ['projectmanager', 'light', 'sound', 'rigging']" :key="category"
                        class="text-center cell-toggle" :style="budgetCellStyle(item, category)"
                        :data-label="{ projectmanager: 'PM', light: 'Light', sound: 'Sound', rigging: 'Rigging' }[category]">
                        <span class="cell-pct">{{ fmtPct(budgetPercent(item, category)) }}%</span>
                        <span class="cell-fraction">{{ fmt(budgetConsumption(item, category)) }} / {{ fmt(budget(item,
                            category)) }}</span>
                    </td>

                    <td class="text-center cell-toggle" data-label="Checklist" :style="checklistCellStyle(item)">
                        <span class="cell-pct">{{ fmtPct(checklistPercent(item)) }}%</span>
                        <span class="cell-fraction">{{ item.count_checklist_items_completed ?? 0 }} / {{
                            item.count_checklist_items ?? 0 }}</span>
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

function weekLabelHtml(weeks) {
    const label = weekLabel(weeks);
    const space = label.indexOf(' ');
    if (space === -1) return `<span class="accent">${label}</span>`;
    return `<span class="accent">${label.slice(0, space)}</span>${label.slice(space)}`;
}

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

.row-week-header .week :deep(.accent) {
    color: var(--accent);
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

@media (max-width: 768px) {
    .row-week-header {
        display: flex;
        text-align: center;
    }

    .row-week-header td:first-child {
        width: 100%;
    }

    .row-week-header td:not(:first-child) {
        display: none;
    }

    .row-default,
    .row-alt {
        display: flex;
        flex-wrap: wrap;
    }

    /* Datums: vaste smalle kolom */
    .row-default td:nth-child(1),
    .row-alt td:nth-child(1) {
        flex: 0 0 25%;
        width: 25%;
    }

    /* Projectnaam + namen: rest van eerste rij */
    .row-default td:nth-child(2),
    .row-alt td:nth-child(2) {
        flex: 0 0 75%;
        width: 75%;
    }

    /* 5 budget-cellen: elk 20%, wrappen naar tweede rij */
    .row-default td:nth-child(n+3),
    .row-alt td:nth-child(n+3) {
        flex: 0 0 20%;
        width: 20%;
    }

    /* Legende boven elk cijfer */
    .row-default td[data-label]::before,
    .row-alt td[data-label]::before {
        content: attr(data-label);
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        opacity: 0.75;
        letter-spacing: 0.04em;
        margin-bottom: 2px;
    }
}
</style>
