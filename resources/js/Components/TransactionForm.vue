<script setup>
import { computed } from 'vue';
import CategoryIcon from '@/Components/CategoryIcon.vue';

const props = defineProps({
    form: Object,
    categories: Array,
    submitText: {
        type: String,
        default: 'Speichern',
    },
});

const emit = defineEmits(['submit']);

const filteredCategories = computed(() => {
    return props.categories.filter(category => category.type === props.form.type);
});
</script>

<template>
    <form @submit.prevent="emit('submit')" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Typ
            </label>

            <div class="mt-2 grid grid-cols-2 gap-2">
                <button
                    type="button"
                    @click="form.type = 'expense'"
                    class="rounded border px-4 py-2 text-sm"
                    :class="form.type === 'expense'
                        ? 'border-red-600 bg-red-50 text-red-700'
                        : 'border-gray-200 hover:bg-gray-50'"
                >
                    Ausgabe
                </button>

                <button
                    type="button"
                    @click="form.type = 'income'"
                    class="rounded border px-4 py-2 text-sm"
                    :class="form.type === 'income'
                        ? 'border-green-600 bg-green-50 text-green-700'
                        : 'border-gray-200 hover:bg-gray-50'"
                >
                    Einnahme
                </button>
            </div>

            <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">
                {{ form.errors.type }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Betrag
            </label>

            <input
                v-model="form.amount"
                type="number"
                step="0.01"
                min="0"
                class="mt-1 w-full rounded border-gray-300"
                placeholder="0.00"
            />

            <p v-if="form.errors.amount" class="mt-1 text-sm text-red-600">
                {{ form.errors.amount }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Datum
            </label>

            <input
                v-model="form.date"
                type="date"
                class="mt-1 w-full rounded border-gray-300"
            />

            <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                {{ form.errors.date }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Kategorie
            </label>

            <select
                v-model="form.category_id"
                class="mt-1 w-full rounded border-gray-300"
            >
                <option value="">Bitte wählen</option>

                <option
                    v-for="category in filteredCategories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>

            <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                {{ form.errors.category_id }}
            </p>

            <div
                v-if="form.category_id"
                class="mt-3 flex items-center gap-2 text-sm text-gray-600"
            >
                <CategoryIcon
                    :icon="categories.find(category => category.id === Number(form.category_id))?.icon"
                />
                <span>
                    {{ categories.find(category => category.id === Number(form.category_id))?.name }}
                </span>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Beschreibung
            </label>

            <input
                v-model="form.description"
                type="text"
                class="mt-1 w-full rounded border-gray-300"
                placeholder="z.B. REWE, Gehalt, Tanken..."
            />

            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                {{ form.errors.description }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="submit"
                :disabled="form.processing"
                class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 disabled:opacity-50"
            >
                {{ submitText }}
            </button>
        </div>
    </form>
</template>
