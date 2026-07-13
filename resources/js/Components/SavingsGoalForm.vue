<script setup>
import Input from '@/Components/UI/Input.vue';
import InputLabel from '@/Components/UI/InputLabel.vue';
import InputError from '@/Components/UI/InputError.vue';
import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
import ColorPicker from '@/Components/ColorPicker.vue';
import IconPicker from '@/Components/IconPicker.vue';

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
            <Input v-model="form.name" type="text" placeholder="z.B. Urlaub Sardinien" />
            <InputError :message="form.errors.name" />
        </div>

        <div>
            <InputLabel value="Zielbetrag" />
            <Input v-model="form.target_amount" type="number" step="0.01" min="0.01" />
            <InputError :message="form.errors.target_amount" />
        </div>

        <div>
            <InputLabel value="Aktuell gespart" />
            <Input v-model="form.current_amount" type="number" step="0.01" min="0" />
            <InputError :message="form.errors.current_amount" />
        </div>

        <div>
            <InputLabel value="Zieldatum optional" />
            <Input v-model="form.target_date" type="date" />
            <InputError :message="form.errors.target_date" />
        </div>

        <div>
            <InputLabel value="Farbe" />
            <ColorPicker v-model="form.color" />
            <InputError :message="form.errors.color" />
        </div>

        <div>
            <InputLabel value="Icon" />
            <IconPicker v-model="form.icon" />
            <InputError :message="form.errors.icon" />
        </div>

        <label class="flex items-center gap-2">
            <input v-model="form.is_active" type="checkbox" class="rounded border-border">
            <span class="text-sm text-foreground">Aktiv</span>
        </label>

        <PrimaryButton type="submit" :disabled="form.processing">
            {{ submitText }}
        </PrimaryButton>
    </form>
</template>
