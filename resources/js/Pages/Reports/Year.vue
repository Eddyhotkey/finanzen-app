<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
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
import { useMoney } from '@/Composables/useMoney';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

const props = defineProps({
    year: Number,
    previousYear: Number,
    nextYear: Number,
    months: Array,
});

const { money } = useMoney();

const chartData = computed(() => ({
    labels: props.months.map(month => month.month),
    datasets: [
        { label: 'Einnahmen', data: props.months.map(month => Number(month.income)) },
        { label: 'Ausgaben', data: props.months.map(month => Number(month.expenses)) },
        { label: 'Saldo', data: props.months.map(month => Number(month.balance)) },
        { label: 'Prognose', data: props.months.map(month => Number(month.forecast)) },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};
</script>

<template>
    <AppLayout>
        <template #title>Jahresübersicht</template>

        <div class="space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <Link
                    :href="route('reports.year', previousYear)"
                    class="rounded bg-card px-4 py-2 text-center text-sm shadow hover:bg-muted"
                >
                    ← {{ previousYear }}
                </Link>

                <h2 class="text-center text-3xl font-bold">
                    {{ year }}
                </h2>

                <Link
                    :href="route('reports.year', nextYear)"
                    class="rounded bg-card px-4 py-2 text-center text-sm shadow hover:bg-muted"
                >
                    {{ nextYear }} →
                </Link>
            </div>

            <div class="rounded-xl bg-card p-4 shadow sm:p-6">
                <h3 class="mb-4 text-lg font-semibold text-foreground">
                    Jahresverlauf
                </h3>

                <div class="h-80">
                    <Line :data="chartData" :options="chartOptions" />
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="space-y-3 md:hidden">
                <div
                    v-for="month in months"
                    :key="month.month"
                    class="rounded-xl border border-border bg-card p-4 shadow-sm"
                >
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="font-semibold text-foreground">
                            {{ month.month }}
                        </h3>

                        <p
                            class="font-bold"
                            :class="month.forecast >= 0 ? 'text-green-600' : 'text-red-600'"
                        >
                            {{ money(month.forecast) }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-muted-foreground">Einnahmen</p>
                            <p class="font-semibold text-green-600">{{ money(month.income) }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground">Ausgaben</p>
                            <p class="font-semibold text-red-600">{{ money(month.expenses) }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground">Saldo</p>
                            <p
                                class="font-semibold"
                                :class="month.balance >= 0 ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ money(month.balance) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-muted-foreground">Geplant</p>
                            <p class="font-semibold text-red-600">
                                {{ money(month.plannedExpenses) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden overflow-hidden rounded-xl bg-card shadow md:block">
                <table class="w-full text-sm">
                    <thead class="bg-muted text-left">
                    <tr>
                        <th class="px-6 py-4">Monat</th>
                        <th class="px-6 py-4 text-right">Einnahmen</th>
                        <th class="px-6 py-4 text-right">Ausgaben</th>
                        <th class="px-6 py-4 text-right">Saldo</th>
                        <th class="px-6 py-4 text-right">Geplante Einnahmen</th>
                        <th class="px-6 py-4 text-right">Geplante Ausgaben</th>
                        <th class="px-6 py-4 text-right">Prognose</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr
                        v-for="month in months"
                        :key="month.month"
                        class="border-t border-border"
                    >
                        <td class="px-6 py-4 font-medium">{{ month.month }}</td>
                        <td class="px-6 py-4 text-right text-green-600">{{ money(month.income) }}</td>
                        <td class="px-6 py-4 text-right text-red-600">{{ money(month.expenses) }}</td>
                        <td class="px-6 py-4 text-right font-semibold" :class="month.balance >= 0 ? 'text-green-600' : 'text-red-600'">{{ money(month.balance) }}</td>
                        <td class="px-6 py-4 text-right text-green-600">{{ money(month.plannedIncome) }}</td>
                        <td class="px-6 py-4 text-right text-red-600">{{ money(month.plannedExpenses) }}</td>
                        <td class="px-6 py-4 text-right font-bold" :class="month.forecast >= 0 ? 'text-green-600' : 'text-red-600'">{{ money(month.forecast) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
