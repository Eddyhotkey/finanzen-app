<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { Link, router } from '@inertiajs/vue3';
import { useDate } from '@/Composables/useDate';
import { useMoney } from '@/Composables/useMoney';
import { useConfirm } from '@/Composables/useConfirm';

defineProps({
    transactions: Array,
});

const { formatDate } = useDate();
const { money } = useMoney();
const { confirm } = useConfirm();

const formatMoney = (amount, type) =>
    type === 'income' ? `+ ${money(amount)}` : `- ${money(amount)}`;

const deleteTransaction = async (transaction) => {
    if (await confirm('Diese Buchung wirklich löschen?')) {
        router.delete(route('transactions.destroy', transaction.id));
    }
};
</script>

<template>
    <AppLayout>
        <template #title>Buchungen</template>

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-semibold text-foreground">
                Alle Buchungen
            </h2>

            <Link
                :href="route('transactions.create')"
                class="rounded bg-primary px-4 py-2 text-center text-sm font-medium text-primary-foreground hover:bg-primary-hover"
            >
                Neue Buchung
            </Link>
        </div>

        <!-- Mobile Cards -->
        <div class="space-y-3 md:hidden">
            <div
                v-for="transaction in transactions"
                :key="transaction.id"
                class="rounded-xl border border-border bg-card p-4 shadow-sm"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                            :style="{ backgroundColor: transaction.category.color + '22', color: transaction.category.color }"
                        >
                            <CategoryIcon :icon="transaction.category.icon" />
                        </span>

                        <div class="min-w-0">
                            <p class="truncate font-semibold text-foreground">
                                {{ transaction.description || transaction.category.name }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ transaction.category.name }} · {{ formatDate(transaction.date) }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="shrink-0 text-right font-bold"
                        :class="transaction.type === 'income' ? 'text-green-600' : 'text-red-600'"
                    >
                        {{ formatMoney(transaction.amount, transaction.type) }}
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-4 border-t border-border pt-3 text-sm">
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
                </div>
            </div>

            <div
                v-if="transactions.length === 0"
                class="rounded-xl border border-border bg-card p-8 text-center text-muted-foreground shadow-sm"
            >
                Noch keine Buchungen vorhanden.
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden overflow-hidden rounded-lg bg-card shadow md:block">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted text-muted-foreground">
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
                    class="border-t border-border"
                >
                    <td class="px-6 py-4 text-muted-foreground">
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

                            <span class="font-medium text-foreground">
                                    {{ transaction.category.name }}
                                </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-muted-foreground">
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
                    <td colspan="5" class="px-6 py-8 text-center text-muted-foreground">
                        Noch keine Buchungen vorhanden.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
