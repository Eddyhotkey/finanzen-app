<script setup>
defineProps({
    value: {
        type: Number,
        default: 0,
    },

    color: {
        type: String,
        default: 'green',
    },
});

const colors = {
    green: 'bg-green-500',
    red: 'bg-red-500',
    blue: 'bg-blue-500',
    orange: 'bg-orange-500',
};
</script>

<template>
    <div class="h-3 overflow-hidden rounded-full bg-gray-100">
        <div
            class="h-3 rounded-full transition-all duration-300"
            :class="colors[color]"
            :style="{ width: Math.min(value, 100) + '%' }"
        />
    </div>
</template>
