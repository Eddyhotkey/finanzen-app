<script setup>
import PlannerLayout from '@/Layouts/PlannerLayout.vue';
import WeekDayCard from '@/Components/Planner/WeekDayCard.vue';
import WeekFocusBox from '@/Components/Planner/WeekFocusBox.vue';
import WeekQuickAddRow from '@/Components/Planner/WeekQuickAddRow.vue';

defineProps({
    date: String,
    weekStart: String,
    weekEnd: String,
    weekNumber: Number,
    year: Number,
    weekNote: Object,
    days: Array,
    categories: Array,
});
</script>

<template>
    <PlannerLayout
        :date="date"
        view="week"
        :week-start="weekStart"
        :week-end="weekEnd"
        :week-number="weekNumber"
    >
        <WeekFocusBox :date="date" :year="year" :week-number="weekNumber" :week-note="weekNote" />

        <div class="grid items-start gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7">
            <WeekDayCard v-for="day in days" :key="day.date" :day="day" />
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <WeekQuickAddRow
                v-for="category in categories"
                :key="category.id"
                :category="category"
                :week-start="weekStart"
                :week-end="weekEnd"
            />
        </div>
    </PlannerLayout>
</template>
