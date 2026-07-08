<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    month: Object,
    summary: Object,
    transactions: Array,
    plannedTransactions: Array,
});

const money = (value) =>
    Number(value).toLocaleString('de-DE', {
        style: 'currency',
        currency: 'EUR',
    });

const formatDate = (date) => new Date(date).toLocaleDateString('de-DE');

const formatMoney = (amount, type) => {
    return `${type === 'income' ? '+' : '-'} ${money(amount)}`;
};
</script>

<template>
    <AppLayout>
        <template #title>
            Monatsübersicht
        </template>

        <div class="space-y-8">
            <div class="flex items-center justify-between">
                <Link
                    :href="route('reports.month', [month.previous.year, month.previous.month])"
                    class="rounded bg-white px-4 py-2 text-sm shadow hover:bg-gray-50"
                >
                    ← Vorheriger Monat
                </Link>

                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900">
                        {{ month.label }}
                    </h2>
                    <p class="text-gray-500">
                        Monatliche Finanzübersicht
                    </p>
                </div>

                <Link
                    :href="route('reports.month', [month.next.year, month.next.month])"
                    class="rounded bg-white px-4 py-2 text-sm shadow hover:bg-gray-50"
                >
                    Nächster Monat →
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <StatCard title="Einnahmen" :value="money(summary.income)" color="text-green-600" />
                <StatCard title="Ausgaben" :value="money(summary.expenses)" color="text-red-600" />
                <StatCard title="Saldo" :value="money(summary.balance)" :color="summary.balance >= 0 ? 'text-green-600' : 'text-red-600'" />
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <StatCard title="Geplante Einnahmen" :value="money(summary.plannedIncome)" color="text-green-600" />
                <StatCard title="Geplante Ausgaben" :value="money(summary.plannedExpenses)" color="text-red-600" />
                <StatCard title="Prognose" :value="money(summary.forecast)" :color="summary.forecast >= 0 ? 'text-green-600' : 'text-red-600'" />
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="border-b px-6 py-4">
                        <h3 class="font-semibold text-gray-800">
                            Alle Buchungen
                        </h3>
                    </div>

                    <table class="w-full text-left text-sm">
                        <tbody>
                        <tr
                            v-for="transaction in transactions"
                            :key="transaction.id"
                            class="border-b last:border-0"
                        >
                            <td class="px-6 py-4 text-gray-600">
                                {{ formatDate(transaction.date) }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                        <span
                                            class="flex h-8 w-8 items-center justify-center rounded-full"
                                            :style="{ backgroundColor: transaction.category.color + '22', color: transaction.category.color }"
                                        >
                                            <CategoryIcon :icon="transaction.category.icon" />
                                        </span>

                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ transaction.description || transaction.category.name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ transaction.category.name }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td
                                class="px-6 py-4 text-right font-semibold"
                                :class="transaction.type === 'income' ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ formatMoney(transaction.amount, transaction.type) }}
                            </td>
                        </tr>

                        <tr v-if="transactions.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                Keine Buchungen in diesem Monat.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="border-b px-6 py-4">
                        <h3 class="font-semibold text-gray-800">
                            Offene geplante Buchungen
                        </h3>
                    </div>

                    <table class="w-full text-left text-sm">
                        <tbody>
                        <tr
                            v-for="item in plannedTransactions"
                            :key="item.id"
                            class="border-b last:border-0"
                        >
                            <td class="px-6 py-4 text-gray-600">
                                {{ formatDate(item.due_date) }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                        <span
                                            class="flex h-8 w-8 items-center justify-center rounded-full"
                                            :style="{ backgroundColor: item.category.color + '22', color: item.category.color }"
                                        >
                                            <CategoryIcon :icon="item.category.icon" />
                                        </span>

                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ item.description || item.category.name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ item.category.name }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td
                                class="px-6 py-4 text-right font-semibold"
                                :class="item.type === 'income' ? 'text-green-600' : 'text-red-600'"
                            >
                                {{ formatMoney(item.amount, item.type) }}
                            </td>
                        </tr>

                        <tr v-if="plannedTransactions.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                Keine offenen geplanten Buchungen in diesem Monat.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
