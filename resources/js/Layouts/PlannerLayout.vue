<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useDate } from '@/Composables/useDate';

const props = defineProps({
    date: String,
    view: {
        type: String,
        default: 'day',
    },
    weekStart: String,
    weekEnd: String,
    weekNumber: Number,
    year: Number,
});

const { formatDayHeader, formatDate, formatMonthHeader } = useDate();

const monthParams = (dateStr) => {
    const d = new Date(dateStr);
    return [d.getFullYear(), d.getMonth() + 1];
};

const currentYear = computed(() => props.year ?? new Date(props.date).getFullYear());

const addDays = (dateStr, amount) => {
    const d = new Date(dateStr);
    d.setDate(d.getDate() + amount);
    return d.toISOString().slice(0, 10);
};

const addMonths = (dateStr, amount) => {
    const d = new Date(dateStr);
    d.setMonth(d.getMonth() + amount);
    return d.toISOString().slice(0, 10);
};

const goToDate = (dateStr) => {
    if (props.view === 'year') {
        router.visit(route('planner.year', new Date(dateStr).getFullYear()));
        return;
    }

    if (props.view === 'month') {
        router.visit(route('planner.month', monthParams(dateStr)));
        return;
    }

    const routeName = props.view === 'week' ? 'planner.week' : 'planner.day';
    router.visit(route(routeName, dateStr));
};

const stepSize = computed(() => (props.view === 'week' ? 7 : 1));

const previous = () => {
    if (props.view === 'year') {
        goToDate(`${currentYear.value - 1}-01-01`);
        return;
    }

    if (props.view === 'month') {
        goToDate(addMonths(props.date, -1));
        return;
    }

    goToDate(addDays(props.date, -stepSize.value));
};

const next = () => {
    if (props.view === 'year') {
        goToDate(`${currentYear.value + 1}-01-01`);
        return;
    }

    if (props.view === 'month') {
        goToDate(addMonths(props.date, 1));
        return;
    }

    goToDate(addDays(props.date, stepSize.value));
};

const today = () => goToDate(new Date().toISOString().slice(0, 10));

const headerLabel = computed(() => {
    if (props.view === 'year') {
        return `${currentYear.value}`;
    }

    if (props.view === 'week') {
        return `KW ${props.weekNumber} · ${formatDate(props.weekStart)} – ${formatDate(props.weekEnd)}`;
    }

    if (props.view === 'month') {
        return formatMonthHeader(props.date);
    }

    return formatDayHeader(props.date);
});
</script>

<template>
    <AppLayout>
        <template #title>
            Planner
        </template>

        <div v-if="view !== 'ideas'" class="mb-6 flex items-center gap-2 text-sm">
            <button
                type="button"
                @click="previous"
                class="rounded border bg-white px-2 py-1.5 hover:bg-gray-50"
            >
                ←
            </button>

            <span class="min-w-[9rem] text-center font-semibold text-gray-900">
                {{ headerLabel }}
            </span>

            <button
                type="button"
                @click="next"
                class="rounded border bg-white px-2 py-1.5 hover:bg-gray-50"
            >
                →
            </button>

            <button
                type="button"
                @click="today"
                class="rounded border bg-white px-3 py-1.5 hover:bg-gray-50"
            >
                Heute
            </button>
        </div>

        <slot />
    </AppLayout>
</template>
