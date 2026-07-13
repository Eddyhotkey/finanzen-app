<script setup>
import PlannerLayout from '@/Layouts/PlannerLayout.vue';
import MiniMonth from '@/Components/Planner/MiniMonth.vue';
import SpecialPeriodsList from '@/Components/Planner/SpecialPeriodsList.vue';
import YearGoalsList from '@/Components/Planner/YearGoalsList.vue';

const props = defineProps({
    year: Number,
    months: Array,
    periods: Array,
    goals: Object,
});
</script>

<template>
    <PlannerLayout :date="`${year}-01-01`" view="year" :year="year">
        <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <MiniMonth v-for="month in months" :key="month.month" :month="month" />
        </div>

        <div class="space-y-6">
            <SpecialPeriodsList :periods="periods" :year="year" />
            <YearGoalsList :goals="goals" :year="year" />
        </div>
    </PlannerLayout>
</template>
