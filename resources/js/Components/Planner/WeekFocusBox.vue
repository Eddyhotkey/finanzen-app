<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import RichTextEditor from '@/Components/Planner/RichTextEditor.vue';

const props = defineProps({
    date: String,
    year: Number,
    weekNumber: Number,
    weekNote: Object,
});

const content = ref(props.weekNote?.content ?? '');

watch(() => props.weekNote, (note) => {
    content.value = note?.content ?? '';
});

const save = () => {
    router.post(route('planner-week-notes.store'), {
        year: props.year,
        week_number: props.weekNumber,
        content: content.value,
        redirect_date: props.date,
    }, { preserveScroll: true, preserveState: true });
};
</script>

<template>
    <div class="mb-6 rounded-xl border border-border bg-card p-6 shadow-sm">
        <h3 class="mb-1 font-semibold text-foreground">Wochenfokus</h3>
        <p class="mb-3 text-sm text-muted-foreground">Was ist diese Woche wichtig, auch wenn der genaue Tag noch offen ist?</p>

        <RichTextEditor
            v-model="content"
            placeholder="Zum Beispiel: Arzttermin vereinbaren, Unterlagen vorbereiten, Besorgungen, wichtige Gedanken..."
            @blur="save"
        />
    </div>
</template>
