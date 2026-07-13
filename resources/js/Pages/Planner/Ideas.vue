<script setup>
import PlannerLayout from '@/Layouts/PlannerLayout.vue';
import { useDate } from '@/Composables/useDate';

defineProps({
    date: String,
    notes: Array,
});

const { formatDate, formatDateTime } = useDate();
</script>

<template>
    <PlannerLayout :date="date" view="ideas">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-foreground">Ideen / Rückblick</h2>
            <p class="text-sm text-muted-foreground">Hier siehst du alle gespeicherten Tagesnotizen.</p>
        </div>

        <div v-if="notes.length" class="space-y-4">
            <div v-for="note in notes" :key="note.id" class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <h3 class="font-semibold text-foreground">{{ formatDate(note.date) }}</h3>
                <p class="mb-3 text-xs text-muted-foreground">Aktualisiert: {{ formatDateTime(note.updated_at) }}</p>
                <p class="prose prose-sm max-w-none text-foreground dark:prose-invert" v-html="note.content"></p>
            </div>
        </div>

        <div v-else class="rounded-xl border border-border bg-card p-10 text-center text-muted-foreground shadow-sm">
            Noch keine Tagesnotizen vorhanden.
        </div>
    </PlannerLayout>
</template>
