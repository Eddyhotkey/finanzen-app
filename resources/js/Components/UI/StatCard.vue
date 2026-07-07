<script setup>
defineProps({
    title: String,
    value: [String, Number],
    color: {
        type: String,
        default: 'text-slate-800',
    },
});
</script>

<template>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <p class="text-sm text-gray-500">
            {{ title }}
        </p>

        <h2 class="mt-2 text-3xl font-bold" :class="color">
            {{ value }}
        </h2>
    </div>
</template>
