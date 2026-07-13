<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    day: Object,
});

const weekdayLabels = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];

const label = (dateStr) => {
    const d = new Date(dateStr);
    return {
        weekday: weekdayLabels[(d.getDay() + 6) % 7],
        short: d.toLocaleDateString('de-DE', { day: '2-digit', month: 'short' }),
    };
};
</script>

<template>
    <div class="flex flex-col rounded-xl border border-border bg-card p-4 shadow-sm">
        <p class="text-xs uppercase text-muted-foreground">{{ label(day.date).weekday.slice(0, 2) }}</p>
        <p class="mb-3 font-semibold text-foreground">{{ label(day.date).short }}</p>

        <div class="mb-3 grid grid-cols-3 gap-1 text-center text-xs">
            <div class="rounded bg-muted py-1">
                <p class="font-semibold text-foreground">{{ day.taskCount }}</p>
                <p class="text-muted-foreground">Tasks</p>
            </div>
            <div class="rounded bg-muted py-1">
                <p class="font-semibold text-foreground">{{ day.doneCount }}</p>
                <p class="text-muted-foreground">Erledigt</p>
            </div>
            <div class="rounded bg-muted py-1">
                <p class="font-semibold text-foreground">{{ day.appointmentCount }}</p>
                <p class="text-muted-foreground">Termine</p>
            </div>
        </div>

        <div v-if="day.categories.length" class="mb-3">
            <div class="flex flex-wrap gap-1">
                <span
                    v-for="category in day.categories"
                    :key="category.id"
                    class="flex max-w-full items-center gap-1 rounded-full bg-muted px-2 py-0.5 text-[11px] text-muted-foreground"
                >
                    <span
                        class="h-1.5 w-1.5 flex-none rounded-full"
                        :style="{ backgroundColor: category.color || '#111827' }"
                    />
                    <span class="truncate">{{ category.name }}</span>
                    <span class="flex-none text-muted-foreground">{{ category.count }}</span>
                </span>
            </div>

            <ul class="mt-2 space-y-0.5 text-xs text-muted-foreground">
                <li v-for="t in day.titles" :key="t" class="truncate">· {{ t }}</li>
                <li v-if="day.moreTitles" class="text-muted-foreground">+ {{ day.moreTitles }} weitere</li>
            </ul>
        </div>
        <p v-else class="mb-3 flex-1 text-xs text-muted-foreground">Keine Einträge.</p>

        <Link
            :href="route('planner.day', day.date)"
            class="mt-auto rounded-lg border border-border py-1.5 text-center text-sm font-medium text-foreground hover:bg-muted"
        >
            Zum Tag
        </Link>
    </div>
</template>
