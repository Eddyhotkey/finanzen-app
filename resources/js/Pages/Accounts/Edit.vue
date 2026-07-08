<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AccountForm from '@/Components/AccountForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    account: Object,
});

const form = useForm({
    name: props.account.name,
    type: props.account.type,
    initial_balance: props.account.initial_balance,
    color: props.account.color ?? '#111827',
    icon: props.account.icon ?? 'bank',
    is_active: props.account.is_active,
});

const submit = () => {
    form.put(route('accounts.update', props.account.id));
};
</script>

<template>
    <AppLayout>
        <template #title>Konto bearbeiten</template>

        <div class="mb-6">
            <Link :href="route('accounts.index')" class="text-sm text-gray-600 hover:underline">
                ← Zurück
            </Link>
        </div>

        <div class="max-w-xl rounded-xl border bg-white p-6 shadow-sm">
            <AccountForm
                :form="form"
                submit-text="Konto aktualisieren"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
