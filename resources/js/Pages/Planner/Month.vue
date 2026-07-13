<script setup>
import PlannerLayout from '@/Layouts/PlannerLayout.vue';
import MonthGrid from '@/Components/Planner/MonthGrid.vue';
import MonthQuickAddRow from '@/Components/Planner/MonthQuickAddRow.vue';
import MonthFocusBox from '@/Components/Planner/MonthFocusBox.vue';

defineProps({
    date: String,
    year: Number,
    month: Number,
    days: Array,
    categories: Array,
    monthNote: Object,
});
</script>

<template>
    <PlannerLayout :date="date" view="month">
        <MonthFocusBox :date="date" :year="year" :month="month" :month-note="monthNote" />

        <MonthGrid :days="days" />

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <MonthQuickAddRow
                v-for="category in categories"
                :key="category.id"
                :category="category"
                :month-date="date"
            />
        </div>
    </PlannerLayout>
</template>
