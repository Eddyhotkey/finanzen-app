<script setup>
defineProps({
    padding: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <div
        class="rounded-xl border border-border bg-card shadow-sm"
        :class="{ 'p-6': padding }"
    >
        <slot />
    </div>
</template>
