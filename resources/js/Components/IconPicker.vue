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
    { value: 'insurance', label: 'Versicherung' },
    { value: 'savings', label: 'Sparen' },
    { value: 'clothing', label: 'Kleidung' },
    { value: 'subscriptions', label: 'Abos' },
    { value: 'sport', label: 'Sport & Fitness' },
    { value: 'travel', label: 'Reisen' },
    { value: 'music', label: 'Musik' },
    { value: 'taxes', label: 'Steuern' },
    { value: 'credit-card', label: 'Kreditkarte' },
    { value: 'charity', label: 'Spende' },
    { value: 'tickets', label: 'Veranstaltungen' },
    { value: 'books', label: 'Bücher' },
    { value: 'rent', label: 'Miete' },
    { value: 'bank', label: 'Bank/Finanzen' },
    { value: 'investment', label: 'Investitionen' },
    { value: 'care', label: 'Pflege/Beauty' },
    { value: 'goal', label: 'Ziel' },
];
</script>

<template>
    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
        <button
            v-for="icon in icons"
            :key="icon.value"
            type="button"
            @click="model = icon.value"
            class="flex items-center gap-2 rounded border px-3 py-2 text-sm hover:bg-muted"
            :class="model === icon.value ? 'border-foreground bg-muted' : 'border-border'"
        >
            <CategoryIcon :icon="icon.value" />
            <span>{{ icon.label }}</span>
        </button>
    </div>
</template>
