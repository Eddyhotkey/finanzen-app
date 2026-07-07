<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },

    disabled: Boolean,
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50"
    >
        <slot />
    </button>
</template>
