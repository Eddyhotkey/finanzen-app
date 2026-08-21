<script setup>
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { ChevronDownIcon } from '@heroicons/vue/24/outline';
import { useMoney } from '@/Composables/useMoney';
import { useDate } from '@/Composables/useDate';

const props = defineProps({
    month: String,
    summary: Object,
    latestTransactions: Array,
    topCategories: Array,
    nextPlannedTransactions: Array,
});

const { money } = useMoney();
const { formatDate } = useDate();

const detailsOpen = ref(false);

const forecastCard = computed(() => ({
    title: 'Frei verfügbar / Prognose',
    value: money(props.summary.forecast),
    color: props.summary.forecast >= 0 ? 'text-green-600' : 'text-red-600',
}));

const statCards = computed(() => [
    { title: 'Einnahmen (diesen Monat)', value: money(props.summary.income), color: 'text-green-600' },
    { title: 'Ausgaben (diesen Monat)', value: money(props.summary.expenses), color: 'text-red-600' },
    { title: 'Aktueller Saldo (gesamt)', value: money(props.summary.balance), color: props.summary.balance >= 0 ? 'text-green-600' : 'text-red-600' },
    { title: 'Noch geplante Ausgaben', value: money(props.summary.plannedExpenses), color: 'text-red-600' },
    { title: 'Verbleibende Budgets', value: money(props.summary.budgetReserve), color: 'text-red-600' },
    { title: 'Geplante Einnahmen', value: money(props.summary.plannedIncome), color: 'text-green-600' },
]);
</script>

<template>
    <AppLayout>
        <template #title>Dashboard</template>

        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-bold text-foreground sm:text-3xl">
                    {{ month }}
                </h2>
                <p class="text-sm text-muted-foreground sm:text-base">
                    Deine Finanzprognose für diesen Monat
                </p>
            </div>

            <!-- Mobile: Prognose angepinnt, Rest im Akkordeon -->
            <div class="space-y-4 sm:hidden">
                <StatCard v-bind="forecastCard" />

                <button
                    type="button"
                    @click="detailsOpen = !detailsOpen"
                    class="flex w-full items-center justify-between rounded-xl border border-border bg-card px-4 py-3 text-sm font-medium text-foreground shadow-sm"
                >
                    <span>Details {{ detailsOpen ? 'ausblenden' : 'anzeigen' }}</span>
                    <ChevronDownIcon class="h-4 w-4 transition-transform" :class="{ 'rotate-180': detailsOpen }" />
                </button>

                <div v-if="detailsOpen" class="grid grid-cols-1 gap-4">
                    <StatCard v-for="card in statCards" :key="card.title" v-bind="card" />
                </div>
            </div>

            <!-- Desktop/Tablet: unverändertes Grid -->
            <div class="hidden gap-4 sm:grid sm:grid-cols-2 xl:grid-cols-3">
                <StatCard v-for="card in statCards" :key="card.title" v-bind="card" />
                <StatCard v-bind="forecastCard" />
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <div class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 text-base font-semibold sm:text-lg">
                        Offene geplante Buchungen diesen Monat
                    </h3>

                    <div v-if="nextPlannedTransactions.length" class="space-y-3">
                        <div
                            v-for="item in nextPlannedTransactions"
                            :key="item.id"
                            class="flex flex-col gap-3 border-b border-border pb-3 last:border-0 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                                    :style="{ backgroundColor: item.category.color + '22', color: item.category.color }"
                                >
                                    <CategoryIcon :icon="item.category.icon" />
                                </span>

                                <div class="min-w-0">
                                    <p class="truncate font-medium">
                                        {{ item.description || item.category.name }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ item.category.name }} · {{ formatDate(item.due_date) }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="text-right font-semibold sm:text-left"
                                :class="item.type === 'income' ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ item.type === 'income' ? '+' : '-' }} {{ money(item.amount) }}
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-muted-foreground">
                        Keine offenen geplanten Buchungen.
                    </p>
                </div>

                <div class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 text-base font-semibold sm:text-lg">
                        Letzte Buchungen
                    </h3>

                    <div v-if="latestTransactions.length" class="space-y-3">
                        <div
                            v-for="transaction in latestTransactions"
                            :key="transaction.id"
                            class="flex flex-col gap-3 border-b border-border pb-3 last:border-0 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                                    :style="{ backgroundColor: transaction.category.color + '22', color: transaction.category.color }"
                                >
                                    <CategoryIcon :icon="transaction.category.icon" />
                                </span>

                                <div class="min-w-0">
                                    <p class="truncate font-medium">
                                        {{ transaction.description || transaction.category.name }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ transaction.category.name }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="text-right font-semibold sm:text-left"
                                :class="transaction.type === 'income' ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ transaction.type === 'income' ? '+' : '-' }} {{ money(transaction.amount) }}
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-muted-foreground">
                        Noch keine Buchungen vorhanden.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
