<script setup>
const model = defineModel();
</script>

<template>
    <select
        v-model="model"
        class="mt-1 w-full rounded-lg border-border shadow-sm focus:border-ring focus:ring-ring"
    >
        <slot />
    </select>
</template>
