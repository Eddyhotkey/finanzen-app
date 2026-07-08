<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { useMoney } from '@/Composables/useMoney';

defineProps({
    accounts: Array,
    totalBalance: [Number, String],
});

const { money } = useMoney();

const typeLabel = (type) => ({
    checking: 'Girokonto',
    savings: 'Sparkonto',
    cash: 'Bargeld',
    credit_card: 'Kreditkarte',
    depot: 'Depot',
}[type] ?? type);

const remove = (account) => {
    if (confirm('Konto wirklich löschen?')) {
        router.delete(route('accounts.destroy', account.id));
    }
};
</script>

<template>
    <AppLayout>
        <template #title>Konten</template>

        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold">Konten</h2>

            <Link
                :href="route('accounts.create')"
                class="rounded-lg bg-gray-900 px-4 py-2 text-white hover:bg-gray-800"
            >
                + Neues Konto
            </Link>
        </div>
        <div class="mb-6 rounded-xl border bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">
                Gesamtbetrag auf aktiven Konten
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ money(totalBalance) }}
            </p>
        </div>
        <div v-if="accounts.length" class="grid gap-6 md:grid-cols-2">
            <div
                v-for="account in accounts"
                :key="account.id"
                class="rounded-xl border bg-white p-6 shadow-sm"
                :class="account.is_active ? 'border-gray-200' : 'border-gray-100 opacity-60'"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-full text-white"
                            :style="{ backgroundColor: account.color || '#111827' }"
                        >
                            {{ account.icon || '€' }}
                        </span>

                        <div>
                            <h3 class="font-semibold text-gray-900">
                                {{ account.name }}
                            </h3>

                            <p class="text-sm text-gray-500">
                                {{ typeLabel(account.type) }}
                            </p>
                        </div>
                    </div>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-medium"
                        :class="account.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'"
                    >
                        {{ account.is_active ? 'Aktiv' : 'Inaktiv' }}
                    </span>
                </div>

                <div class="mt-6">
                    <p class="text-sm text-gray-500">Aktueller Stand</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ money(account.initial_balance) }}
                    </p>
                </div>

                <div class="mt-5 flex justify-end gap-4 border-t pt-4 text-sm">
                    <Link
                        :href="route('accounts.edit', account.id)"
                        class="text-blue-600 hover:underline"
                    >
                        Bearbeiten
                    </Link>

                    <button
                        @click="remove(account)"
                        class="text-red-600 hover:underline"
                    >
                        Löschen
                    </button>
                </div>
            </div>
        </div>

        <div v-else class="rounded-xl border bg-white p-10 text-center text-gray-500 shadow-sm">
            Noch keine Konten vorhanden.
        </div>
    </AppLayout>
</template>
