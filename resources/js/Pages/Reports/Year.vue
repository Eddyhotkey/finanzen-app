<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useMoney } from '@/Composables/useMoney';

import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement,
} from 'chart.js';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement
);

const props = defineProps({
    year: Number,
    previousYear: Number,
    nextYear: Number,
    months: Array,
});

const chartData = computed(() => ({
    labels: props.months.map(month => month.month),
    datasets: [
        {
            label: 'Einnahmen',
            data: props.months.map(month => Number(month.income)),
        },
        {
            label: 'Ausgaben',
            data: props.months.map(month => Number(month.expenses)),
        },
        {
            label: 'Saldo',
            data: props.months.map(month => Number(month.balance)),
        },
        {
            label: 'Prognose',
            data: props.months.map(month => Number(month.forecast)),
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};
const { money } = useMoney();
</script>

<template>
    <AppLayout>
        <template #title>
            Jahresübersicht
        </template>

        <div class="space-y-6">

            <div class="flex items-center justify-between">

                <Link
                    :href="route('reports.year', previousYear)"
                    class="rounded bg-white px-4 py-2 shadow hover:bg-gray-50"
                >
                    ← {{ previousYear }}
                </Link>

                <h2 class="text-3xl font-bold">
                    {{ year }}
                </h2>

                <Link
                    :href="route('reports.year', nextYear)"
                    class="rounded bg-white px-4 py-2 shadow hover:bg-gray-50"
                >
                    {{ nextYear }} →
                </Link>

            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">
                    Jahresverlauf
                </h3>

                <div class="h-80">
                    <Line
                        :data="chartData"
                        :options="chartOptions"
                    />
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow">

                <table class="w-full">

                    <thead class="bg-gray-50">

                    <tr class="text-left">

                        <th class="px-6 py-4">
                            Monat
                        </th>

                        <th class="px-6 py-4 text-right">
                            Einnahmen
                        </th>

                        <th class="px-6 py-4 text-right">
                            Ausgaben
                        </th>

                        <th class="px-6 py-4 text-right">
                            Saldo
                        </th>

                        <th class="px-6 py-4 text-right">
                            Geplante Einnahmen
                        </th>

                        <th class="px-6 py-4 text-right">
                            Geplante Ausgaben
                        </th>

                        <th class="px-6 py-4 text-right">
                            Prognose
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    <tr
                        v-for="month in months"
                        :key="month.month"
                        class="border-t"
                    >

                        <td class="px-6 py-4 font-medium">
                            {{ month.month }}
                        </td>

                        <td class="px-6 py-4 text-right text-green-600">
                            {{ money(month.income) }}
                        </td>

                        <td class="px-6 py-4 text-right text-red-600">
                            {{ money(month.expenses) }}
                        </td>

                        <td
                            class="px-6 py-4 text-right font-semibold"
                            :class="month.balance >= 0 ? 'text-green-600' : 'text-red-600'"
                        >
                            {{ money(month.balance) }}
                        </td>

                        <td class="px-6 py-4 text-right text-green-600">
                            {{ money(month.plannedIncome) }}
                        </td>

                        <td class="px-6 py-4 text-right text-red-600">
                            {{ money(month.plannedExpenses) }}
                        </td>

                        <td
                            class="px-6 py-4 text-right font-bold"
                            :class="month.forecast >= 0 ? 'text-green-600' : 'text-red-600'"
                        >
                            {{ money(month.forecast) }}
                        </td>

                    </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </AppLayout>
</template>
