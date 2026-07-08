<script setup>

// Die benutzen wir inzwischen im Dashboard, Monatsübersicht und später auch bei den Konten.
defineProps({
    title: String,
    value: [String, Number],
    color: {
        type: String,
        default: 'text-gray-900',
    },
});
</script>

<template>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">
            {{ title }}
        </p>

        <p
            class="mt-2 text-2xl font-bold"
            :class="color"
        >
            {{ value }}
        </p>

        <slot />
    </div>
</template>
