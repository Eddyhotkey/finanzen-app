<script setup>
const model = defineModel();
</script>

<template>
    <select
        v-model="model"
        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"
    >
        <slot />
    </select>
</template>
