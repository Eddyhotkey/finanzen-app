<script setup>
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import InputLabel from '@/Components/UI/InputLabel.vue';
import InputError from '@/Components/UI/InputError.vue';
import PrimaryButton from '@/Components/UI/PrimaryButton.vue';

defineProps({
    form: Object,
    categories: Array,
    submitText: {
        type: String,
        default: 'Speichern',
    },
});

const emit = defineEmits(['submit']);
</script>

<template>
    <form @submit.prevent="emit('submit')" class="space-y-5">
        <div>
            <InputLabel value="Kategorie" />

            <Select v-model="form.category_id">
                <option value="">Gesamtbudget</option>

                <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </Select>

            <InputError :message="form.errors.category_id" />
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

        <div class="grid grid-cols-2 gap-4">
            <div>
                <InputLabel value="Monat optional" />

                <Input
                    v-model="form.month"
                    type="number"
                    min="1"
                    max="12"
                    placeholder="z.B. 7"
                />

                <InputError :message="form.errors.month" />
            </div>

            <div>
                <InputLabel value="Jahr optional" />

                <Input
                    v-model="form.year"
                    type="number"
                    min="2000"
                    max="2100"
                    placeholder="z.B. 2026"
                />

                <InputError :message="form.errors.year" />
            </div>
        </div>

        <PrimaryButton
            type="submit"
            :disabled="form.processing"
        >
            {{ submitText }}
        </PrimaryButton>
    </form>
</template>
