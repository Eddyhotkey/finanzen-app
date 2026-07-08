<script setup>
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import InputLabel from '@/Components/UI/InputLabel.vue';
import InputError from '@/Components/UI/InputError.vue';
import PrimaryButton from '@/Components/UI/PrimaryButton.vue';

defineProps({
    form: Object,
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
            <InputLabel value="Name" />
            <Input v-model="form.name" type="text" placeholder="z.B. Girokonto" />
            <InputError :message="form.errors.name" />
        </div>

        <div>
            <InputLabel value="Typ" />
            <Select v-model="form.type">
                <option value="checking">Girokonto</option>
                <option value="savings">Sparkonto</option>
                <option value="cash">Bargeld</option>
                <option value="credit_card">Kreditkarte</option>
                <option value="depot">Depot</option>
            </Select>
            <InputError :message="form.errors.type" />
        </div>

        <div>
            <InputLabel value="Startsaldo" />
            <Input v-model="form.initial_balance" type="number" step="0.01" />
            <InputError :message="form.errors.initial_balance" />
        </div>

        <div>
            <InputLabel value="Farbe" />
            <Input v-model="form.color" type="color" />
            <InputError :message="form.errors.color" />
        </div>

        <div>
            <InputLabel value="Icon" />
            <Input v-model="form.icon" type="text" placeholder="z.B. bank, cash, card" />
            <InputError :message="form.errors.icon" />
        </div>

        <label class="flex items-center gap-2">
            <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300">
            <span class="text-sm text-gray-700">Aktiv</span>
        </label>

        <PrimaryButton type="submit" :disabled="form.processing">
            {{ submitText }}
        </PrimaryButton>
    </form>
</template>
