<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <template #title>
            Dashboard
        </template>

        <div class="rounded-lg bg-white p-6 shadow">
            <h2 class="text-lg font-semibold text-gray-800">
                Willkommen in deiner Finanz-App
            </h2>

            <p class="mt-2 text-gray-600">
                Als Nächstes bauen wir Kategorien und Buchungen.
            </p>
        </div>
    </AppLayout>
</template>
