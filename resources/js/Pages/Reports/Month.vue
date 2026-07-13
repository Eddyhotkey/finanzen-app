<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { Link } from '@inertiajs/vue3';
import { useMoney } from '@/Composables/useMoney';
import { useDate } from '@/Composables/useDate';

defineProps({
    month: Object,
    summary: Object,
    transactions: Array,
    plannedTransactions: Array,
});

const { money } = useMoney();
const { formatDate } = useDate();

const formatMoney = (amount, type) =>
    `${type === 'income' ? '+' : '-'} ${money(amount)}`;
</script>

<template>
    <AppLayout>
        <template #title>Monatsübersicht</template>

        <div class="space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <Link
                    :href="route('reports.month', [month.previous.year, month.previous.month])"
                    class="rounded bg-card px-4 py-2 text-center text-sm shadow hover:bg-muted"
                >
                    ← Vorheriger Monat
                </Link>

                <div class="text-center">
                    <h2 class="text-2xl font-bold text-foreground sm:text-3xl">
                        {{ month.label }}
                    </h2>
                    <p class="text-sm text-muted-foreground sm:text-base">
                        Monatliche Finanzübersicht
                    </p>
                </div>

                <Link
                    :href="route('reports.month', [month.next.year, month.next.month])"
                    class="rounded bg-card px-4 py-2 text-center text-sm shadow hover:bg-muted"
                >
                    Nächster Monat →
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <StatCard title="Einnahmen" :value="money(summary.income)" color="text-green-600" />
                <StatCard title="Ausgaben" :value="money(summary.expenses)" color="text-red-600" />
                <StatCard title="Saldo" :value="money(summary.balance)" :color="summary.balance >= 0 ? 'text-green-600' : 'text-red-600'" />
                <StatCard title="Geplante Einnahmen" :value="money(summary.plannedIncome)" color="text-green-600" />
                <StatCard title="Geplante Ausgaben" :value="money(summary.plannedExpenses)" color="text-red-600" />
                <StatCard title="Prognose" :value="money(summary.forecast)" :color="summary.forecast >= 0 ? 'text-green-600' : 'text-red-600'" />
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <div class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-semibold text-foreground">
                        Alle Buchungen
                    </h3>

                    <div v-if="transactions.length" class="space-y-3">
                        <div
                            v-for="transaction in transactions"
                            :key="transaction.id"
                            class="flex flex-col gap-3 border-b border-border pb-3 last:border-0 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div class="flex min-w-0 items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                                    :style="{ backgroundColor: transaction.category.color + '22', color: transaction.category.color }"
                                >
                                    <CategoryIcon :icon="transaction.category.icon" />
                                </span>

                                <div class="min-w-0">
                                    <p class="truncate font-medium text-foreground">
                                        {{ transaction.description || transaction.category.name }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ transaction.category.name }} · {{ formatDate(transaction.date) }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="text-right font-semibold"
                                :class="transaction.type === 'income' ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ formatMoney(transaction.amount, transaction.type) }}
                            </div>
                        </div>
                    </div>

                    <p v-else class="py-6 text-center text-muted-foreground">
                        Keine Buchungen in diesem Monat.
                    </p>
                </div>

                <div class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-semibold text-foreground">
                        Offene geplante Buchungen
                    </h3>

                    <div v-if="plannedTransactions.length" class="space-y-3">
                        <div
                            v-for="item in plannedTransactions"
                            :key="item.id"
                            class="flex flex-col gap-3 border-b border-border pb-3 last:border-0 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div class="flex min-w-0 items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                                    :style="{ backgroundColor: item.category.color + '22', color: item.category.color }"
                                >
                                    <CategoryIcon :icon="item.category.icon" />
                                </span>

                                <div class="min-w-0">
                                    <p class="truncate font-medium text-foreground">
                                        {{ item.description || item.category.name }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ item.category.name }} · {{ formatDate(item.due_date) }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="text-right font-semibold"
                                :class="item.type === 'income' ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ formatMoney(item.amount, item.type) }}
                            </div>
                        </div>
                    </div>

                    <p v-else class="py-6 text-center text-muted-foreground">
                        Keine offenen geplanten Buchungen in diesem Monat.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
