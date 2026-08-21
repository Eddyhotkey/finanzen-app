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

    orientation: {
        type: String,
        default: 'horizontal', // 'horizontal' | 'vertical'
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
            const color = item.category?.color ?? '#9ca3af';

            grouped[name] ??= { amount: 0, color };
            grouped[name].amount += Number(item.amount);
        });

    return Object.entries(grouped)
        .map(([category, { amount, color }]) => ({ category, amount, color }))
        .sort((a, b) => b.amount - a.amount);
});

const chartData = computed(() => ({
    labels: expensesByCategory.value.map(item => item.category),
    datasets: [
        {
            label: 'Ausgaben',
            data: expensesByCategory.value.map(item => item.amount),
            backgroundColor: expensesByCategory.value.map(item => item.color),
        },
    ],
}));

const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: props.orientation === 'vertical' ? 'x' : 'y',
    plugins: {
        tooltip: {
            callbacks: {
                label: (context) => money(context.raw),
            },
        },
    },
}));
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
