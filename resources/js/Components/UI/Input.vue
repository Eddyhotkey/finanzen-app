<script setup>
defineProps({
    modelValue: [String, Number, null],
    type: {
        type: String,
        default: 'text',
    },
    placeholder: String,
    step: String,
    min: String,
});

defineEmits(['update:modelValue']);
</script>

<template>
    <input
        :value="modelValue"
        :type="type"
        :placeholder="placeholder"
        :step="step"
        :min="min"
        @input="$emit('update:modelValue', $event.target.value)"
        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"
    />
</template>
