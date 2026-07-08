<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { Link, router } from '@inertiajs/vue3';
import { useMoney } from '@/Composables/useMoney';

defineProps({
    budgets: Array,
});



const { money } = useMoney();

const remove = (budget) => {
    if (confirm('Budget wirklich löschen?')) {
        router.delete(route('budgets.destroy', budget.id));
    }
};
</script>

<template>
    <AppLayout>
        <template #title>Budgets</template>

        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold">Budgets</h2>

            <Link
                :href="route('budgets.create')"
                class="rounded-lg bg-gray-900 px-4 py-2 text-white hover:bg-gray-800"
            >
                + Neues Budget
            </Link>
        </div>

        <div v-if="budgets.length" class="grid gap-6 md:grid-cols-2">
            <div
                v-for="budget in budgets"
                :key="budget.id"
                class="rounded-xl border bg-white p-6 shadow-sm"
                :class="budget.is_over ? 'border-red-200' : 'border-gray-200'"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span
                            v-if="budget.category"
                            class="flex h-10 w-10 items-center justify-center rounded-full"
                            :style="{
                                backgroundColor: budget.category.color + '22',
                                color: budget.category.color
                            }"
                        >
                            <CategoryIcon :icon="budget.category.icon" />
                        </span>

                        <span
                            v-else
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-600"
                        >
                            €
                        </span>

                        <div>
                            <h3 class="font-semibold text-gray-900">
                                {{ budget.category?.name || 'Gesamtbudget' }}
                            </h3>

                            <p class="text-sm text-gray-500">
                                {{ budget.month || 'Alle Monate' }} / {{ budget.year || 'Alle Jahre' }}
                            </p>
                        </div>
                    </div>

                    <span
                        v-if="budget.is_over"
                        class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700"
                    >
                        Überschritten
                    </span>
                </div>

                <div class="mb-3 flex items-end justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Prognose</p>
                        <p
                            class="text-2xl font-bold"
                            :class="budget.is_over ? 'text-red-600' : 'text-gray-900'"
                        >
                            {{ money(budget.forecast) }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-gray-500">Budget</p>
                        <p class="font-semibold text-gray-900">
                            {{ money(budget.amount) }}
                        </p>
                    </div>
                </div>

                <div class="h-3 overflow-hidden rounded-full bg-gray-100">
                    <div
                        class="h-3 rounded-full"
                        :class="budget.is_over ? 'bg-red-500' : 'bg-green-500'"
                        :style="{ width: budget.progress + '%' }"
                    ></div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Ausgegeben</p>
                        <p class="font-semibold text-red-600">
                            {{ money(budget.spent) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Geplant</p>
                        <p class="font-semibold text-orange-600">
                            {{ money(budget.planned) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Rest</p>
                        <p
                            class="font-semibold"
                            :class="budget.remaining >= 0 ? 'text-green-600' : 'text-red-600'"
                        >
                            {{ money(budget.remaining) }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 flex justify-end gap-4 border-t pt-4 text-sm">
                    <Link
                        :href="route('budgets.edit', budget.id)"
                        class="text-blue-600 hover:underline"
                    >
                        Bearbeiten
                    </Link>

                    <button
                        @click="remove(budget)"
                        class="text-red-600 hover:underline"
                    >
                        Löschen
                    </button>
                </div>
            </div>
        </div>

        <div
            v-else
            class="rounded-xl border bg-white p-10 text-center text-gray-500 shadow-sm"
        >
            Noch keine Budgets vorhanden.
        </div>
    </AppLayout>
</template>
