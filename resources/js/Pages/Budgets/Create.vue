<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BudgetForm from '@/Components/BudgetForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array,
});

const form = useForm({
    category_id: '',
    amount: '',
    month: '',
    year: '',
});

const submit = () => {
    form.post(route('budgets.store'));
};
</script>

<template>
    <AppLayout>
        <template #title>Budget anlegen</template>

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
                submit-text="Budget speichern"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
