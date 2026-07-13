<script setup>
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';
import { useMoney } from '@/Composables/useMoney';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },

    title: {
        type: String,
        default: 'Ausgaben nach Kategorie',
    },

    currentMonthOnly: {
        type: Boolean,
        default: false,
    },
    dateField: {
        type: String,
        default: 'due_date',
    },
});

const { money } = useMoney();

const expensesByCategory = computed(() => {
    const grouped = {};

    const now = new Date();
    const currentMonth = now.getMonth();
    const currentYear = now.getFullYear();

    props.items
        .filter(item => item.type === 'expense')
        .filter(item => {
            if (!props.currentMonthOnly) return true;

            const date = new Date(item[props.dateField]);

            return (
                date.getMonth() === currentMonth &&
                date.getFullYear() === currentYear
            );
        })
        .forEach(item => {
            const name = item.category?.name ?? 'Ohne Kategorie';
            grouped[name] = (grouped[name] ?? 0) + Number(item.amount);
        });

    return Object.entries(grouped)
        .map(([category, amount]) => ({ category, amount }))
        .sort((a, b) => b.amount - a.amount);
});

const chartData = computed(() => ({
    labels: expensesByCategory.value.map(item => item.category),
    datasets: [
        {
            label: 'Ausgaben',
            data: expensesByCategory.value.map(item => item.amount),
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: 'y',
    plugins: {
        tooltip: {
            callbacks: {
                label: (context) => money(context.raw),
            },
        },
    },
};
</script>

<template>
    <div
        v-if="expensesByCategory.length"
        class="rounded-lg bg-card p-6 shadow mb-3"
    >
        <h3 class="mb-4 text-lg font-semibold text-foreground">
            {{ title }}
        </h3>

        <div class="h-80">
            <Bar
                :data="chartData"
                :options="chartOptions"
            />
        </div>
    </div>
</template>
