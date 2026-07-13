<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import ColorPicker from '@/Components/ColorPicker.vue';

const props = defineProps({
    date: String,
});

const showForm = ref(false);
const name = ref('');
const color = ref('#6b7280');

const addCategory = () => {
    if (!name.value.trim()) return;

    router.post(route('planner-categories.store'), {
        name: name.value,
        color: color.value,
        redirect_date: props.date,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            name.value = '';
            showForm.value = false;
        },
    });
};
</script>

<template>
    <div class="rounded-xl border border-dashed border-border bg-card p-4">
        <button
            v-if="!showForm"
            type="button"
            @click="showForm = true"
            class="text-sm font-medium text-muted-foreground hover:text-foreground"
        >
            + Kategorie
        </button>

        <div v-else class="space-y-3">
            <input
                v-model="name"
                type="text"
                placeholder="z.B. Familie"
                class="block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
            />

            <ColorPicker v-model="color" />

            <div class="flex gap-2">
                <button
                    type="button"
                    @click="addCategory"
                    class="rounded-lg bg-primary px-3 py-1 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
                >
                    Speichern
                </button>
                <button
                    type="button"
                    @click="showForm = false"
                    class="rounded-lg border border-border px-3 py-1 text-sm text-foreground hover:bg-muted"
                >
                    Abbrechen
                </button>
            </div>
        </div>
    </div>
</template>
