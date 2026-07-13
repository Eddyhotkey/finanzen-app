<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BudgetForm from '@/Components/BudgetForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    budget: Object,
    categories: Array,
});

const form = useForm({
    category_id: props.budget.category_id,
    amount: props.budget.amount,
    month: props.budget.month ?? '',
    year: props.budget.year ?? '',
});

const submit = () => {
    form.put(route('budgets.update', props.budget.id));
};
</script>

<template>
    <AppLayout>
        <template #title>Budget bearbeiten</template>

        <div class="mb-6">
            <Link
                :href="route('budgets.index')"
                class="text-sm text-muted-foreground hover:underline"
            >
                ← Zurück
            </Link>
        </div>

        <div class="max-w-xl rounded-xl border border-border bg-card p-6 shadow-sm">
            <BudgetForm
                :form="form"
                :categories="categories"
                submit-text="Budget aktualisieren"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
