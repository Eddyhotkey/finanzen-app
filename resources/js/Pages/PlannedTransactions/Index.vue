<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    plannedTransactions: Array,
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

const pay = (item) => {
    if (confirm(`"${item.description || item.category.name}" als bezahlt markieren?`)) {
        router.post(route('planned-transactions.pay', item.id));
    }
};

const remove = (item) => {
    if (confirm('Diese geplante Buchung wirklich löschen?')) {
        router.delete(route('planned-transactions.destroy', item.id));
    }
};
</script>

<template>
    <AppLayout>
        <template #title>
            Geplante Ausgaben
        </template>

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Geplante Buchungen
                </h2>

                <p class="text-sm text-gray-500">
                    Kommende Ausgaben und Einnahmen im Blick behalten.
                </p>
            </div>

            <Link
                :href="route('planned-transactions.create')"
                class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800"
            >
                Neue geplante Buchung
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3">Fällig am</th>
                    <th class="px-6 py-3">Kategorie</th>
                    <th class="px-6 py-3">Beschreibung</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Betrag</th>
                    <th class="px-6 py-3 text-right">Aktionen</th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="item in plannedTransactions"
                    :key="item.id"
                    class="border-t"
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

                            <span class="font-medium text-gray-900">
                                    {{ item.category.name }}
                                </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ item.description || '—' }}
                    </td>

                    <td class="px-6 py-4">
                            <span
                                v-if="item.created_transaction_id"
                                class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700"
                            >
                                Bezahlt
                            </span>

                        <span
                            v-else
                            class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700"
                        >
                                Offen
                            </span>
                    </td>

                    <td
                        class="px-6 py-4 text-right font-semibold"
                        :class="item.type === 'income' ? 'text-green-600' : 'text-red-600'"
                    >
                        {{ formatMoney(item.amount, item.type) }}
                    </td>

                    <td class="space-x-3 px-6 py-4 text-right">
                        <button
                            v-if="!item.created_transaction_id"
                            @click="pay(item)"
                            class="text-green-700 hover:underline"
                        >
                            Bezahlen
                        </button>

                        <Link
                            v-if="!item.created_transaction_id"
                            :href="route('planned-transactions.edit', item.id)"
                            class="text-blue-600 hover:underline"
                        >
                            Bearbeiten
                        </Link>

                        <button
                            @click="remove(item)"
                            class="text-red-600 hover:underline"
                        >
                            Löschen
                        </button>
                    </td>
                </tr>

                <tr v-if="plannedTransactions.length === 0">
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Noch keine geplanten Buchungen vorhanden.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
