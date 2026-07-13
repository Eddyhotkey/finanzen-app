<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block text-sm font-medium text-foreground">
        {{ value }}
    </label>
</template>
