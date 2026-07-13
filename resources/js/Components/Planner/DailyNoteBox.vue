<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import RichTextEditor from '@/Components/Planner/RichTextEditor.vue';

const props = defineProps({
    date: String,
    note: Object,
});

const content = ref(props.note?.content ?? '');

watch(() => props.date, () => {
    content.value = props.note?.content ?? '';
});

const save = () => {
    router.post(route('planner-daily-notes.store'), {
        date: props.date,
        content: content.value,
    }, { preserveScroll: true, preserveState: true });
};
</script>

<template>
    <RichTextEditor
        v-model="content"
        placeholder="Gedanken, Ideen, Rückblick, Beobachtungen..."
        @blur="save"
    />
</template>
