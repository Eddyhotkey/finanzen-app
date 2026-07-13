<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TransactionForm from '@/Components/TransactionForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    transaction: Object,
    categories: Array,
});

const form = useForm({
    type: props.transaction.type,
    amount: props.transaction.amount,
    date: props.transaction.date,
    category_id: props.transaction.category_id,
    description: props.transaction.description ?? '',
});

const submit = () => {
    form.put(route('transactions.update', props.transaction.id));
};
</script>

<template>
    <AppLayout>
        <template #title>
            Buchung bearbeiten
        </template>

        <div class="mb-6">
            <Link
                :href="route('transactions.index')"
                class="text-sm text-muted-foreground hover:underline"
            >
                ← Zurück zu Buchungen
            </Link>
        </div>

        <div class="max-w-xl rounded-lg bg-card p-6 shadow">
            <TransactionForm
                :form="form"
                :categories="categories"
                submit-text="Buchung aktualisieren"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
