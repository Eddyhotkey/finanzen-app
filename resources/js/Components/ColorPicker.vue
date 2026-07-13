<script setup>
const model = defineModel();

const colors = [
    '#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16',
    '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9',
    '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#d946ef',
    '#ec4899', '#f43f5e', '#6b7280', '#334155', '#111827',
];
</script>

<template>
    <div class="grid grid-cols-5 gap-2 sm:grid-cols-10">
        <button
            v-for="color in colors"
            :key="color"
            type="button"
            @click="model = color"
            class="h-8 w-8 rounded-full ring-offset-2 focus:outline-none"
            :class="model === color ? 'ring-2 ring-gray-900' : 'ring-1 ring-gray-200'"
            :style="{ backgroundColor: color }"
            :aria-label="color"
        />
    </div>
</template>
