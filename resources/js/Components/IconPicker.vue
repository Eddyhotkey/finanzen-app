<script setup>
import CategoryIcon from '@/Components/CategoryIcon.vue';

const model = defineModel();

const icons = [
    { value: 'shopping-cart', label: 'Lebensmittel' },
    { value: 'home', label: 'Haus' },
    { value: 'car', label: 'Auto' },
    { value: 'banknotes', label: 'Geld' },
    { value: 'gift', label: 'Geschenke' },
    { value: 'health', label: 'Gesundheit' },
    { value: 'phone', label: 'Handy' },
    { value: 'education', label: 'Bildung' },
    { value: 'repair', label: 'Reparatur' },
    { value: 'food', label: 'Restaurant' },
    { value: 'vacation', label: 'Urlaub' },
    { value: 'electricity', label: 'Strom' },
    { value: 'family', label: 'Familie/Kinder' },
    { value: 'computer', label: 'Computer' },
    { value: 'entertainment', label: 'Freizeit' },
    { value: 'work', label: 'Arbeit' },
];
</script>

<template>
    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
        <button
            v-for="icon in icons"
            :key="icon.value"
            type="button"
            @click="model = icon.value"
            class="flex items-center gap-2 rounded border px-3 py-2 text-sm hover:bg-gray-50"
            :class="model === icon.value ? 'border-gray-900 bg-gray-100' : 'border-gray-200'"
        >
            <CategoryIcon :icon="icon.value" />
            <span>{{ icon.label }}</span>
        </button>
    </div>
</template>
