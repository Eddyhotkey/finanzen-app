<script setup>
import { computed } from 'vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import InputError from '@/Components/UI/InputError.vue';
import InputLabel from '@/Components/UI/InputLabel.vue';
import PrimaryButton from '@/Components/UI/PrimaryButton.vue';

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
            <InputLabel value="Typ" />

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

            <InputError :message="form.errors.type" />
        </div>

        <div>
            <InputLabel value="Betrag" />

            <Input
                v-model="form.amount"
                type="number"
                step="0.01"
                min="0.01"
                placeholder="0.00"
            />

            <InputError :message="form.errors.amount" />
        </div>

        <div>
            <InputLabel value="Fälligkeitsdatum" />

            <Input
                v-model="form.due_date"
                type="date"
            />

            <InputError :message="form.errors.due_date" />
        </div>

        <div>
            <InputLabel value="Kategorie" />

            <Select v-model="form.category_id">
                <option value="">Bitte wählen</option>

                <option
                    v-for="category in filteredCategories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </Select>

            <InputError :message="form.errors.category_id" />
        </div>

        <div>
            <InputLabel value="Beschreibung" />

            <Input
                v-model="form.description"
                type="text"
                placeholder="z.B. Netflix, Kredit, Strom..."
            />

            <InputError :message="form.errors.description" />
        </div>

        <div>
            <InputLabel value="Wiederholung" />

            <Select v-model="form.repeat_type">
                <option value="none">Keine Wiederholung</option>
                <option value="monthly">Monatlich</option>
                <option value="yearly">Jährlich</option>
            </Select>

            <InputError :message="form.errors.repeat_type" />
        </div>

        <div v-if="form.repeat_type !== 'none'">
            <InputLabel value="Tag der Fälligkeit" />

            <Input
                v-model="form.repeat_day"
                type="number"
                min="1"
                max="31"
                placeholder="z.B. 15"
            />

            <InputError :message="form.errors.repeat_day" />
        </div>

        <div v-if="form.repeat_type !== 'none'">
            <InputLabel value="Wiederholen bis optional" />

            <Input
                v-model="form.repeat_until"
                type="date"
            />

            <InputError :message="form.errors.repeat_until" />
        </div>

        <PrimaryButton
            type="submit"
            :disabled="form.processing"
        >
            {{ submitText }}
        </PrimaryButton>
    </form>
</template>
