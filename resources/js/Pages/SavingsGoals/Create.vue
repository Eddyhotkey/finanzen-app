<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SavingsGoalForm from '@/Components/SavingsGoalForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    target_amount: '',
    current_amount: 0,
    target_date: '',
    color: '#111827',
    icon: 'goal',
    is_active: true,
});

const submit = () => {
    form.post(route('savings-goals.store'));
};
</script>

<template>
    <AppLayout>
        <template #title>Sparziel anlegen</template>

        <div class="mb-6">
            <Link :href="route('savings-goals.index')" class="text-sm text-muted-foreground hover:underline">
                ← Zurück
            </Link>
        </div>

        <div class="max-w-xl rounded-xl border border-border bg-card p-6 shadow-sm">
            <SavingsGoalForm
                :form="form"
                submit-text="Sparziel speichern"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
