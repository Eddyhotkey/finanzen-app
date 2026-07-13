<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { useMoney } from '@/Composables/useMoney';
import { useDate } from '@/Composables/useDate';
import CategoryIcon from '@/Components/CategoryIcon.vue';
import { useConfirm } from '@/Composables/useConfirm';

defineProps({
    goals: Array,
});

const { money } = useMoney();
const { formatDate } = useDate();
const { confirm } = useConfirm();

const progress = (goal) => {
    if (!Number(goal.target_amount)) return 0;

    return Math.min(
        100,
        Math.round((Number(goal.current_amount) / Number(goal.target_amount)) * 100)
    );
};

const remaining = (goal) => {
    return Number(goal.target_amount) - Number(goal.current_amount);
};

const remove = async (goal) => {
    if (await confirm('Sparziel wirklich löschen?')) {
        router.delete(route('savings-goals.destroy', goal.id));
    }
};
</script>

<template>
    <AppLayout>
        <template #title>Sparziele</template>

        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold">Sparziele</h2>

            <Link
                :href="route('savings-goals.create')"
                class="rounded-lg bg-primary px-4 py-2 text-primary-foreground hover:bg-primary-hover"
            >
                + Neues Sparziel
            </Link>
        </div>

        <div v-if="goals.length" class="grid gap-6 md:grid-cols-2">
            <div
                v-for="goal in goals"
                :key="goal.id"
                class="rounded-xl border bg-card p-6 shadow-sm"
                :class="goal.is_active ? 'border-border' : 'border-border opacity-60'"
            >
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-full text-white"
                            :style="{ backgroundColor: goal.color || '#111827' }"
                        >
                            <CategoryIcon :icon="goal.icon" />
                        </span>

                        <div>
                            <h3 class="font-semibold text-foreground">
                                {{ goal.name }}
                            </h3>

                            <p class="text-sm text-muted-foreground">
                                Ziel: {{ money(goal.target_amount) }}
                            </p>
                        </div>
                    </div>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-medium"
                        :class="goal.is_active ? 'bg-green-100 text-green-700' : 'bg-muted text-muted-foreground'"
                    >
                        {{ goal.is_active ? 'Aktiv' : 'Inaktiv' }}
                    </span>
                </div>

                <div class="mb-3 flex items-end justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">Aktuell gespart</p>
                        <p class="text-2xl font-bold text-foreground">
                            {{ money(goal.current_amount) }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-muted-foreground">Fortschritt</p>
                        <p class="font-semibold text-foreground">
                            {{ progress(goal) }} %
                        </p>
                    </div>
                </div>

                <div class="h-3 overflow-hidden rounded-full bg-muted">
                    <div
                        class="h-3 rounded-full transition-all"
                        :style="{
                            width: progress(goal) + '%',
                            backgroundColor: goal.color || '#111827'
                        }"
                    />
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-muted-foreground">Noch nötig</p>
                        <p class="font-semibold text-foreground">
                            {{ money(remaining(goal)) }}
                        </p>
                    </div>

                    <div v-if="goal.target_date">
                        <p class="text-muted-foreground">Zieldatum</p>
                        <p class="font-semibold text-foreground">
                            {{ formatDate(goal.target_date) }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 flex justify-end gap-4 border-t border-border pt-4 text-sm">
                    <Link
                        :href="route('savings-goals.edit', goal.id)"
                        class="text-blue-600 hover:underline"
                    >
                        Bearbeiten
                    </Link>

                    <button
                        @click="remove(goal)"
                        class="text-red-600 hover:underline"
                    >
                        Löschen
                    </button>
                </div>
            </div>
        </div>

        <div v-else class="rounded-xl border bg-card p-10 text-center text-muted-foreground shadow-sm">
            Noch keine Sparziele vorhanden.
        </div>
    </AppLayout>
</template>
