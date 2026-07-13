<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    category: Object,
    weekStart: String,
    weekEnd: String,
});

const title = ref('');
const date = ref(props.weekStart);

const add = () => {
    if (!title.value.trim() || !date.value) return;

    router.post(route('planner-tasks.store'), {
        planner_category_id: props.category.id,
        title: title.value,
        date: date.value,
        priority: 'mittel',
        is_done: false,
        redirect_view: 'week',
        redirect_date: props.weekStart,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            title.value = '';
        },
    });
};
</script>

<template>
    <div class="rounded-xl border border-border bg-card p-4 shadow-sm">
        <div class="mb-2 flex items-center gap-2">
            <span class="h-2 w-2 rounded-full" :style="{ backgroundColor: category.color || '#111827' }" />
            <h4 class="font-semibold text-foreground">{{ category.name }}</h4>
        </div>

        <div class="flex flex-wrap gap-2">
            <input
                v-model="title"
                type="text"
                :placeholder="`${category.name}-Aufgabe hinzufügen`"
                class="min-w-[10rem] flex-1 rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
                @keyup.enter="add"
            />
            <input
                v-model="date"
                type="date"
                :min="weekStart"
                :max="weekEnd"
                class="rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
            />
            <button
                type="button"
                @click="add"
                class="rounded-lg bg-primary px-3 py-1 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
            >
                +
            </button>
        </div>
    </div>
</template>
