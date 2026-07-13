<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import IconPicker from '@/Components/IconPicker.vue';
import ColorPicker from '@/Components/ColorPicker.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
    type: props.category.type,
    color: props.category.color,
    icon: props.category.icon,
});

const submit = () => {
    form.put(route('categories.update', props.category.id));
};
</script>

<template>
    <AppLayout>
        <template #title>
            Kategorie bearbeiten
        </template>

        <div class="max-w-xl rounded-lg bg-card p-6 shadow">
            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-foreground">
                        Name
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="mt-1 w-full rounded border-border"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                        {{ form.errors.name }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-foreground">
                        Typ
                    </label>
                    <select
                        v-model="form.type"
                        class="mt-1 w-full rounded border-border"
                    >
                        <option value="expense">Ausgabe</option>
                        <option value="income">Einnahme</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-foreground">
                        Farbe
                    </label>
                    <div class="mt-2">
                        <ColorPicker v-model="form.color" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-foreground">
                        Icon
                    </label>
                    <div class="mt-2">
                        <IconPicker v-model="form.icon" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary-hover disabled:opacity-50"
                    >
                        Aktualisieren
                    </button>

                    <Link
                        href="/categories"
                        class="text-sm text-muted-foreground hover:underline"
                    >
                        Abbrechen
                    </Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
