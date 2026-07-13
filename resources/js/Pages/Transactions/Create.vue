<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TransactionForm from '@/Components/TransactionForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    categories: Array,
});

const today = new Date().toISOString().slice(0, 10);

const form = useForm({
    type: 'expense',
    amount: '',
    date: today,
    category_id: '',
    description: '',
});

const submit = () => {
    form.post(route('transactions.store'));
};
</script>

<template>
    <AppLayout>
        <template #title>
            Neue Buchung
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
                submit-text="Buchung speichern"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
