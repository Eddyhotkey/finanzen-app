<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { useMoney } from '@/Composables/useMoney';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { useConfirm } from '@/Composables/useConfirm';

defineProps({
    accounts: Array,
    totalBalance: [Number, String],
});

const { money } = useMoney();
const { confirm } = useConfirm();

const typeLabel = (type) => ({
    checking: 'Girokonto',
    savings: 'Sparkonto',
    cash: 'Bargeld',
    credit_card: 'Kreditkarte',
    depot: 'Depot',
}[type] ?? type);

const remove = async (account) => {
    if (await confirm('Konto wirklich löschen?')) {
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
                class="rounded-lg bg-primary px-4 py-2 text-primary-foreground hover:bg-primary-hover"
            >
                + Neues Konto
            </Link>
        </div>
        <div class="mb-6 rounded-xl border border-border bg-card p-6 shadow-sm">
            <p class="text-sm text-muted-foreground">
                Gesamtbetrag auf aktiven Konten
            </p>

            <p class="mt-2 text-3xl font-bold text-foreground">
                {{ money(totalBalance) }}
            </p>
        </div>
        <div v-if="accounts.length" class="grid gap-6 md:grid-cols-2">
            <div
                v-for="account in accounts"
                :key="account.id"
                class="rounded-xl border bg-card p-6 shadow-sm"
                :class="account.is_active ? 'border-border' : 'border-border opacity-60'"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-full text-white"
                            :style="{ backgroundColor: account.color || '#111827' }"
                        >
                            <CategoryIcon :icon="account.icon" />
                        </span>

                        <div>
                            <h3 class="font-semibold text-foreground">
                                {{ account.name }}
                            </h3>

                            <p class="text-sm text-muted-foreground">
                                {{ typeLabel(account.type) }}
                            </p>
                        </div>
                    </div>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-medium"
                        :class="account.is_active ? 'bg-green-100 text-green-700' : 'bg-muted text-muted-foreground'"
                    >
                        {{ account.is_active ? 'Aktiv' : 'Inaktiv' }}
                    </span>
                </div>

                <div class="mt-6">
                    <p class="text-sm text-muted-foreground">Aktueller Stand</p>
                    <p class="text-2xl font-bold text-foreground">
                        {{ money(account.initial_balance) }}
                    </p>
                </div>

                <div class="mt-5 flex justify-end gap-4 border-t border-border pt-4 text-sm">
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

        <div v-else class="rounded-xl border border-border bg-card p-10 text-center text-muted-foreground shadow-sm">
            Noch keine Konten vorhanden.
        </div>
    </AppLayout>
</template>
