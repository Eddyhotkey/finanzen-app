<script setup>
import { useDarkMode } from '@/Composables/useDarkMode';
import { SunIcon, MoonIcon } from '@heroicons/vue/24/outline';

defineProps({
    class: { type: String, default: '' },
});

const { isDark, toggle } = useDarkMode();
</script>

<template>
    <button type="button" @click="toggle" :class="$props.class" :title="isDark ? 'Helles Design' : 'Dunkles Design'">
        <SunIcon v-if="isDark" class="h-5 w-5" />
        <MoonIcon v-else class="h-5 w-5" />
    </button>
</template>
