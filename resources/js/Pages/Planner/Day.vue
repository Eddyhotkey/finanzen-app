<script setup>
import PlannerLayout from '@/Layouts/PlannerLayout.vue';
import CategoryTaskGroup from '@/Components/Planner/CategoryTaskGroup.vue';
import AppointmentsPanel from '@/Components/Planner/AppointmentsPanel.vue';
import DayTimeline from '@/Components/Planner/DayTimeline.vue';
import DailyNoteBox from '@/Components/Planner/DailyNoteBox.vue';
import InlineCategoryForm from '@/Components/Planner/InlineCategoryForm.vue';

defineProps({
    date: String,
    categories: Array,
    tasks: Array,
    appointments: Array,
    note: Object,
});
</script>

<template>
    <PlannerLayout :date="date">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <CategoryTaskGroup
                    v-for="category in categories"
                    :key="category.id"
                    :category="category"
                    :tasks="tasks.filter((t) => t.planner_category_id === category.id)"
                    :date="date"
                />

                <InlineCategoryForm :date="date" />

                <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
                    <h3 class="mb-3 font-semibold text-foreground">Tagesnotizen / Ideen</h3>
                    <DailyNoteBox :date="date" :note="note" />
                </div>
            </div>

            <div class="space-y-6">
                <AppointmentsPanel :appointments="appointments" :date="date" />
                <DayTimeline :appointments="appointments" />
            </div>
        </div>
    </PlannerLayout>
</template>
