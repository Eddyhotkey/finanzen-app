<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PlannedTransactionForm from '@/Components/PlannedTransactionForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    plannedTransaction: Object,
    categories: Array,
});

const form = useForm({
    type: props.plannedTransaction.type,
    amount: props.plannedTransaction.amount,
    due_date: props.plannedTransaction.due_date,
    category_id: props.plannedTransaction.category_id,
    description: props.plannedTransaction.description ?? '',
    repeat_type: props.plannedTransaction.repeat_type ?? 'none',
    repeat_day: props.plannedTransaction.repeat_day ?? '',
    repeat_until: props.plannedTransaction.repeat_until ?? '',
});

const submit = () => {
    form.put(route('planned-transactions.update', props.plannedTransaction.id));
};
</script>

<template>
    <AppLayout>
        <template #title>
            Geplante Buchung bearbeiten
        </template>

        <div class="mb-6">
            <Link
                :href="route('planned-transactions.index')"
                class="text-sm text-muted-foreground hover:underline"
            >
                ← Zurück zu geplanten Buchungen
            </Link>
        </div>

        <div class="max-w-xl rounded-xl border border-border bg-card p-6 shadow-sm">
            <PlannedTransactionForm
                :form="form"
                :categories="categories"
                submit-text="Geplante Buchung aktualisieren"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
