<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AccountForm from '@/Components/AccountForm.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    type: 'checking',
    initial_balance: 0,
    color: '#111827',
    icon: 'bank',
    is_active: true,
});

const submit = () => {
    form.post(route('accounts.store'));
};
</script>

<template>
    <AppLayout>
        <template #title>Konto anlegen</template>

        <div class="mb-6">
            <Link :href="route('accounts.index')" class="text-sm text-gray-600 hover:underline">
                ← Zurück
            </Link>
        </div>

        <div class="max-w-xl rounded-xl border bg-white p-6 shadow-sm">
            <AccountForm
                :form="form"
                submit-text="Konto speichern"
                @submit="submit"
            />
        </div>
    </AppLayout>
</template>
