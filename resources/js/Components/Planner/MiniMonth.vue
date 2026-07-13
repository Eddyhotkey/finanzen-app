<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useDate } from '@/Composables/useDate';

const props = defineProps({
    month: Object,
});

const { formatMonthName } = useDate();

const monthParams = computed(() => {
    const d = new Date(props.month.date);
    return [d.getFullYear(), d.getMonth() + 1];
});

const hasToday = computed(() => props.month.days.some((d) => d.isToday && d.inMonth));

const cellStyle = (day) => {
    if (day.isHoliday) {
        return { backgroundColor: '#fce7f3' };
    }

    if (day.periodTag) {
        return { backgroundColor: day.periodColor ? `${day.periodColor}33` : '#d1fae5' };
    }

    return {};
};
</script>

<template>
    <div class="rounded-xl border border-border bg-card p-4 shadow-sm">
        <div class="mb-1 flex items-center justify-between">
            <h3 class="font-semibold capitalize text-foreground">{{ formatMonthName(month.date) }}</h3>
            <Link :href="route('planner.month', monthParams)" class="text-xs font-medium text-blue-600 hover:underline">
                Monat
            </Link>
        </div>

        <p class="mb-2 text-xs text-muted-foreground">{{ month.holidayCount }} Feiertage</p>

        <div class="grid grid-cols-7 gap-0.5 text-center text-[11px]">
            <div
                v-for="day in month.days"
                :key="day.date"
                class="flex h-6 items-center justify-center rounded"
                :class="[
                    day.inMonth ? 'text-foreground' : 'text-muted-foreground/50',
                    day.isToday ? 'font-bold ring-1 ring-primary' : '',
                ]"
                :style="cellStyle(day)"
                :title="day.holidayLabel || day.periodTag || ''"
            >
                {{ day.day }}
            </div>
        </div>

        <Link
            v-if="hasToday"
            :href="route('planner.day')"
            class="mt-2 inline-block rounded-full bg-primary px-2 py-0.5 text-[11px] font-medium text-primary-foreground"
        >
            Heute
        </Link>
    </div>
</template>
