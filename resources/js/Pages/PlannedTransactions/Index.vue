<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { Link, router } from '@inertiajs/vue3';
import { useDate } from '@/Composables/useDate';
import CategoryExpenseBarChart from '@/Components/Finance/CategoryExpenseBarChart.vue';
import { useMoney } from '@/Composables/useMoney';
import { useConfirm } from '@/Composables/useConfirm';

// Named confirmDelete (not confirm) so the native window.confirm() used
// below by pay() — a non-destructive "mark as paid" action left as-is —
// isn't shadowed by this composable's same-named export.
const { confirm: confirmDelete } = useConfirm();



defineProps({
    plannedTransactions: Array,
});

const { money } = useMoney();

const formatMoney = (amount, type) =>
    type === 'income' ? `+ ${money(amount)}` : `- ${money(amount)}`;



const { formatDate } = useDate();

const pay = (item) => {
    if (confirm(`"${item.description || item.category.name}" als bezahlt markieren?`)) {
        router.post(route('planned-transactions.pay', item.id));
    }
};

const remove = async (item) => {
    if (await confirmDelete('Diese geplante Buchung wirklich löschen?')) {
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
                <h2 class="text-lg font-semibold text-foreground">
                    Geplante Buchungen
                </h2>

                <p class="text-sm text-muted-foreground">
                    Kommende Ausgaben und Einnahmen im Blick behalten.
                </p>
            </div>

            <Link
                :href="route('planned-transactions.create')"
                class="rounded bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
            >
                Neue geplante Buchung
            </Link>
        </div>

        <CategoryExpenseBarChart
            :items="plannedTransactions"
            title="Geplante Ausgaben diesen Monat nach Kategorie"
            current-month-only
            date-field="due_date"
        />
        <!-- Mobile Cards -->
        <div class="space-y-3 md:hidden">
            <div
                v-for="item in plannedTransactions"
                :key="item.id"
                class="rounded-xl border border-border bg-card p-4 shadow-sm"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-3">
                <span
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                    :style="{ backgroundColor: item.category.color + '22', color: item.category.color }"
                >
                    <CategoryIcon :icon="item.category.icon" />
                </span>

                        <div class="min-w-0">
                            <p class="truncate font-semibold text-foreground">
                                {{ item.description || item.category.name }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ item.category.name }} · {{ formatDate(item.due_date) }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="shrink-0 text-right font-bold"
                        :class="item.type === 'income' ? 'text-green-600' : 'text-red-600'"
                    >
                        {{ formatMoney(item.amount, item.type) }}
                    </div>
                </div>

                <div class="mt-3">
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
                </div>

                <div class="mt-4 flex flex-wrap justify-end gap-4 border-t border-border pt-3 text-sm">
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
                </div>
            </div>

            <div
                v-if="plannedTransactions.length === 0"
                class="rounded-xl border border-border bg-card p-8 text-center text-muted-foreground shadow-sm"
            >
                Noch keine geplanten Buchungen vorhanden.
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="overflow-hidden rounded-lg bg-card shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted text-muted-foreground">
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
                    class="border-t border-border"
                >
                    <td class="px-6 py-4 text-muted-foreground">
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

                            <span class="font-medium text-foreground">
                                    {{ item.category.name }}
                                </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-muted-foreground">
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
                    <td colspan="6" class="px-6 py-8 text-center text-muted-foreground">
                        Noch keine geplanten Buchungen vorhanden.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
