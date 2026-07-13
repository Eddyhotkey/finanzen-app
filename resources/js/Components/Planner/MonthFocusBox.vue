<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import RichTextEditor from '@/Components/Planner/RichTextEditor.vue';

const props = defineProps({
    date: String,
    year: Number,
    month: Number,
    monthNote: Object,
});

const content = ref(props.monthNote?.content ?? '');

watch(() => props.monthNote, (note) => {
    content.value = note?.content ?? '';
});

const save = () => {
    router.post(route('planner-month-notes.store'), {
        year: props.year,
        month: props.month,
        content: content.value,
        redirect_date: props.date,
    }, { preserveScroll: true, preserveState: true });
};
</script>

<template>
    <div class="mb-6 rounded-xl border border-border bg-card p-6 shadow-sm">
        <h3 class="mb-1 font-semibold text-foreground">Monatsfokus</h3>
        <p class="mb-3 text-sm text-muted-foreground">Was ist diesen Monat wichtig, auch wenn der genaue Tag noch offen ist?</p>

        <RichTextEditor
            v-model="content"
            placeholder="Zum Beispiel: Größere Termine, Ziele für den Monat, wichtige Erledigungen..."
            @blur="save"
        />
    </div>
</template>
