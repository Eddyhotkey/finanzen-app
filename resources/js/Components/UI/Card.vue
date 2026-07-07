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
        class="rounded-xl border border-gray-200 bg-white shadow-sm"
        :class="{ 'p-6': padding }"
    >
        <slot />
    </div>
</template>
