<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block text-sm font-medium text-gray-700">
        {{ value }}
    </label>
</template>
