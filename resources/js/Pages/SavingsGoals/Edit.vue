<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SavingsGoalForm from '@/Components/SavingsGoalForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    goal: Object,
});

const form = useForm({
    name: props.goal.name,
    target_amount: props.goal.target_amount,
    current_amount: props.goal.current_amount,
    target_date: props.goal.target_date ?? '',
    color: props.goal.color ?? '#111827',
    icon: props.goal.icon ?? 'goal',
    is_active: props.goal.is_active,
});

const submit = () => {
    form.put(route('savings-goals.update', props.goal.id));
};
</script>

<template>
    <AppLayout>
        <template #title>Sparziel bearbeiten</template>

        <div class="mb-6">
            <Link :href="route('savings-goals.index')" class="text-sm text-muted-foreground hover:underline">
                ← Zurück
            </Link>
        </div>

        <div class="max-w-xl rounded-xl border border-border bg-card p-6 shadow-sm">
            <SavingsGoalForm
                :form="form"
                submit-text="Sparziel aktualisieren"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
