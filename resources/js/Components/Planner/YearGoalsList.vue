<script setup>
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import YearGoalCard from '@/Components/Planner/YearGoalCard.vue';

const props = defineProps({
    goals: Object,
    year: Number,
});

const allGoals = computed(() => Object.values(props.goals).flat());
const totalCount = computed(() => allGoals.value.length);
const doneCount = computed(() => allGoals.value.filter((g) => g.status === 'erledigt').length);

const title = ref('');
const categoryLabel = ref('');

const addGoal = () => {
    if (!title.value.trim() || !categoryLabel.value.trim()) return;

    router.post(route('planner-year-goals.store'), {
        title: title.value,
        category_label: categoryLabel.value.toUpperCase(),
        note: '',
        status: 'offen',
        progress: 0,
        year: props.year,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            title.value = '';
            categoryLabel.value = '';
        },
    });
};
</script>

<template>
    <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
        <div class="mb-1 flex items-center justify-between">
            <h3 class="font-semibold text-foreground">Jahresziele</h3>
            <span class="text-sm text-muted-foreground">{{ totalCount }} Ziele, {{ doneCount }} erledigt</span>
        </div>
        <p class="mb-4 text-sm text-muted-foreground">Große Themen ohne festes Tagesdatum.</p>

        <div class="mb-6 flex flex-wrap gap-2">
            <input
                v-model="title"
                type="text"
                placeholder="Neues Jahresziel..."
                class="min-w-[12rem] flex-1 rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
                @keyup.enter="addGoal"
            />
            <input
                v-model="categoryLabel"
                type="text"
                placeholder="Kategorie, z.B. Persönlich"
                class="w-48 rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
                @keyup.enter="addGoal"
            />
            <button
                type="button"
                @click="addGoal"
                class="rounded-lg bg-primary px-3 py-1 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
            >
                Ziel hinzufügen
            </button>
        </div>

        <p v-if="!totalCount" class="text-sm text-muted-foreground">Noch keine Jahresziele erfasst.</p>

        <div v-for="(items, category) in goals" :key="category" class="mb-6 last:mb-0">
            <div class="mb-2 flex items-center justify-between">
                <h4 class="text-xs font-semibold uppercase text-muted-foreground">{{ category }}</h4>
                <span class="text-xs text-muted-foreground">{{ items.length }} Ziele</span>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <YearGoalCard v-for="goal in items" :key="goal.id" :goal="goal" />
            </div>
        </div>
    </div>
</template>
