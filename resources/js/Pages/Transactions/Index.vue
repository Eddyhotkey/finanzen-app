<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    transactions: Array,
});

const formatMoney = (amount, type) => {
    const value = Number(amount).toLocaleString('de-DE', {
        style: 'currency',
        currency: 'EUR',
    });

    return type === 'income' ? `+ ${value}` : `- ${value}`;
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('de-DE');
};

const deleteTransaction = (transaction) => {
    if (confirm('Diese Buchung wirklich löschen?')) {
        router.delete(route('transactions.destroy', transaction.id));
    }
};
</script>

<template>
    <AppLayout>
        <template #title>
            Buchungen
        </template>

        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">
                Alle Buchungen
            </h2>

            <Link
                :href="route('transactions.create')"
                class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800"
            >
                Neue Buchung
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3">Datum</th>
                    <th class="px-6 py-3">Kategorie</th>
                    <th class="px-6 py-3">Beschreibung</th>
                    <th class="px-6 py-3 text-right">Betrag</th>
                    <th class="px-6 py-3 text-right">Aktionen</th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="transaction in transactions"
                    :key="transaction.id"
                    class="border-t"
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

                            <span class="font-medium text-gray-900">
                                    {{ transaction.category.name }}
                                </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ transaction.description || '—' }}
                    </td>

                    <td
                        class="px-6 py-4 text-right font-semibold"
                        :class="transaction.type === 'income' ? 'text-green-600' : 'text-red-600'"
                    >
                        {{ formatMoney(transaction.amount, transaction.type) }}
                    </td>

                    <td class="space-x-3 px-6 py-4 text-right">
                        <Link
                            :href="route('transactions.edit', transaction.id)"
                            class="text-blue-600 hover:underline"
                        >
                            Bearbeiten
                        </Link>

                        <button
                            @click="deleteTransaction(transaction)"
                            class="text-red-600 hover:underline"
                        >
                            Löschen
                        </button>
                    </td>
                </tr>

                <tr v-if="transactions.length === 0">
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        Noch keine Buchungen vorhanden.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
