<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';

defineProps({
    month: String,
    summary: Object,
    latestTransactions: Array,
    topCategories: Array,
    nextPlannedTransactions: Array,
});

const money = (value) =>
    Number(value).toLocaleString('de-DE', {
        style: 'currency',
        currency: 'EUR',
    });

const formatDate = (date) => new Date(date).toLocaleDateString('de-DE');
</script>

<template>
    <AppLayout>
        <template #title>
            Dashboard
        </template>

        <div class="space-y-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">
                    {{ month }}
                </h2>
                <p class="text-gray-500">
                    Deine Finanzprognose für diesen Monat
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <StatCard title="Einnahmen" :value="money(summary.income)" color="text-green-600" />
                <StatCard title="Ausgaben" :value="money(summary.expenses)" color="text-red-600" />
                <StatCard title="Aktueller Saldo" :value="money(summary.balance)" :color="summary.balance >= 0 ? 'text-green-600' : 'text-red-600'" />
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <StatCard title="Noch geplante Ausgaben" :value="money(summary.plannedExpenses)" color="text-red-600" />
                <StatCard title="Geplante Einnahmen" :value="money(summary.plannedIncome)" color="text-green-600" />
                <StatCard title="Frei verfügbar / Prognose" :value="money(summary.forecast)" :color="summary.forecast >= 0 ? 'text-green-600' : 'text-red-600'" />
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold">Nächste geplante Buchungen</h3>

                    <div v-if="nextPlannedTransactions.length" class="space-y-3">
                        <div
                            v-for="item in nextPlannedTransactions"
                            :key="item.id"
                            class="flex items-center justify-between border-b pb-3 last:border-0"
                        >
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full"
                                    :style="{ backgroundColor: item.category.color + '22', color: item.category.color }"
                                >
                                    <CategoryIcon :icon="item.category.icon" />
                                </span>

                                <div>
                                    <p class="font-medium">{{ item.description || item.category.name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ item.category.name }} · {{ formatDate(item.due_date) }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="font-semibold"
                                :class="item.type === 'income' ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ item.type === 'income' ? '+' : '-' }} {{ money(item.amount) }}
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-gray-500">
                        Keine offenen geplanten Buchungen.
                    </p>
                </div>

                <div class="rounded-xl border bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold">Letzte Buchungen</h3>

                    <div v-if="latestTransactions.length" class="space-y-3">
                        <div
                            v-for="transaction in latestTransactions"
                            :key="transaction.id"
                            class="flex items-center justify-between border-b pb-3 last:border-0"
                        >
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full"
                                    :style="{ backgroundColor: transaction.category.color + '22', color: transaction.category.color }"
                                >
                                    <CategoryIcon :icon="transaction.category.icon" />
                                </span>

                                <div>
                                    <p class="font-medium">{{ transaction.description || transaction.category.name }}</p>
                                    <p class="text-sm text-gray-500">{{ transaction.category.name }}</p>
                                </div>
                            </div>

                            <div
                                class="font-semibold"
                                :class="transaction.type === 'income' ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ transaction.type === 'income' ? '+' : '-' }} {{ money(transaction.amount) }}
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-gray-500">
                        Noch keine Buchungen vorhanden.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
