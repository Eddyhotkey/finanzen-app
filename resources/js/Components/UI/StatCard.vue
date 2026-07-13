<script setup>
defineProps({
    title: String,
    value: [String, Number],
    color: {
        type: String,
        default: 'text-foreground',
    },
});
</script>

<template>
    <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
        <p class="text-sm text-muted-foreground">
            {{ title }}
        </p>

        <h2 class="mt-2 text-3xl font-bold" :class="color">
            {{ value }}
        </h2>
    </div>
</template>
