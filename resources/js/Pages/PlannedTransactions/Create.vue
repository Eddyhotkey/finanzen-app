<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PlannedTransactionForm from '@/Components/PlannedTransactionForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    categories: Array,
});

const today = new Date().toISOString().slice(0, 10);

const form = useForm({
    type: 'expense',
    amount: '',
    due_date: today,
    category_id: '',
    description: '',
    repeat_type: 'none',
    repeat_day: '',
    repeat_until: '',
});

const submit = () => {
    form.post(route('planned-transactions.store'));
};
</script>

<template>
    <AppLayout>
        <template #title>
            Neue geplante Ausgabe
        </template>

        <div class="mb-6">
            <Link
                :href="route('planned-transactions.index')"
                class="text-sm text-muted-foreground hover:underline"
            >
                ← Zurück zu geplanten Ausgaben
            </Link>
        </div>

        <div class="max-w-xl rounded-xl border border-border bg-card p-6 shadow-sm">
            <PlannedTransactionForm
                :form="form"
                :categories="categories"
                submit-text="Geplante Buchung speichern"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
