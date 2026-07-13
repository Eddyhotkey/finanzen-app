<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    days: Array,
});

const weekdayLabels = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

const weekdayShort = (dateStr) =>
    new Date(`${dateStr}T00:00:00`).toLocaleDateString('de-DE', { weekday: 'short' });
</script>

<template>
    <!-- Below sm: the 7-column calendar grid is too cramped to read (each
         cell shrinks to ~50px), so days in the current month are shown as a
         compact vertical agenda list instead. From sm up, the calendar grid
         below takes over unchanged. -->
    <div class="space-y-1 sm:hidden">
        <Link
            v-for="day in days.filter((d) => d.inMonth)"
            :key="day.date"
            :href="route('planner.day', day.date)"
            class="flex items-center gap-3 rounded-lg border border-border bg-card px-3 py-2 hover:bg-muted"
            :class="day.isToday ? 'ring-2 ring-inset ring-primary' : ''"
        >
            <div class="w-10 flex-none text-center">
                <div class="text-xs uppercase text-muted-foreground">{{ weekdayShort(day.date) }}</div>
                <div class="text-sm font-semibold text-foreground">{{ day.day }}</div>
            </div>

            <div class="min-w-0 flex-1 space-y-0.5">
                <p v-if="!day.categories.length" class="text-xs text-muted-foreground">Keine Einträge.</p>
                <div
                    v-for="cat in day.categories"
                    :key="cat.name"
                    class="flex items-center gap-1 truncate text-xs text-muted-foreground"
                >
                    <span class="h-1.5 w-1.5 flex-none rounded-full" :style="{ backgroundColor: cat.color || '#111827' }" />
                    <span class="truncate">{{ cat.name }}: {{ cat.count }}</span>
                </div>
            </div>
        </Link>
    </div>

    <div class="hidden overflow-hidden rounded-xl border border-border bg-card shadow-sm sm:block">
        <div class="grid grid-cols-7 border-b border-border bg-muted text-center text-xs font-semibold uppercase text-muted-foreground">
            <div v-for="label in weekdayLabels" :key="label" class="py-2">{{ label }}</div>
        </div>

        <div class="grid grid-cols-7">
            <Link
                v-for="day in days"
                :key="day.date"
                :href="route('planner.day', day.date)"
                class="flex min-h-[6.5rem] flex-col gap-1 border-b border-r border-border p-2 text-left last:border-r-0 hover:bg-muted"
                :class="[
                    day.inMonth ? 'bg-card' : 'bg-muted text-muted-foreground/50',
                    day.isToday ? 'ring-2 ring-inset ring-primary' : '',
                ]"
            >
                <span class="text-sm font-semibold" :class="day.inMonth ? 'text-foreground' : 'text-muted-foreground/50'">
                    {{ day.day }}
                </span>

                <div class="space-y-0.5">
                    <div
                        v-for="cat in day.categories"
                        :key="cat.name"
                        class="flex items-center gap-1 truncate text-[11px] text-muted-foreground"
                    >
                        <span class="h-1.5 w-1.5 flex-none rounded-full" :style="{ backgroundColor: cat.color || '#111827' }" />
                        <span class="truncate">{{ cat.name }}: {{ cat.count }}</span>
                    </div>
                </div>
            </Link>
        </div>
    </div>
</template>
