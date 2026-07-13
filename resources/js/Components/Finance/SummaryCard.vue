<script setup>

// Die benutzen wir inzwischen im Dashboard, Monatsübersicht und später auch bei den Konten.
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
    <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
        <p class="text-sm text-muted-foreground">
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
