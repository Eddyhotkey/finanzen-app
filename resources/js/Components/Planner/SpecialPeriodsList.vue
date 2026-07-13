<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useDate } from '@/Composables/useDate';
import ColorPicker from '@/Components/ColorPicker.vue';
import { useConfirm } from '@/Composables/useConfirm';

const { confirm } = useConfirm();

const props = defineProps({
    periods: Array,
    year: Number,
});

const { formatDate } = useDate();

const tagSuggestions = ['Urlaub', 'Ferien', 'Hausprojekt', 'Krank / Feiertag'];

const showAdd = ref(false);
const editingId = ref(null);

const emptyForm = () => ({
    label: '',
    tag: 'Urlaub',
    start_date: '',
    end_date: '',
    color: '#10b981',
});

const form = ref(emptyForm());

const startAdd = () => {
    form.value = emptyForm();
    showAdd.value = true;
    editingId.value = null;
};

const startEdit = (period) => {
    form.value = {
        label: period.label,
        tag: period.tag || '',
        start_date: period.start_date,
        end_date: period.end_date,
        color: period.color || '#10b981',
    };
    editingId.value = period.id;
    showAdd.value = false;
};

const cancel = () => {
    showAdd.value = false;
    editingId.value = null;
};

const submitAdd = () => {
    if (!form.value.label.trim() || !form.value.start_date || !form.value.end_date) return;

    router.post(route('planner-special-periods.store'), {
        ...form.value,
        redirect_year: props.year,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showAdd.value = false;
        },
    });
};

const submitEdit = (id) => {
    if (!form.value.label.trim() || !form.value.start_date || !form.value.end_date) return;

    router.patch(route('planner-special-periods.update', id), {
        ...form.value,
        redirect_year: props.year,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null;
        },
    });
};

const destroy = async (period) => {
    if (await confirm(`"${period.label}" wirklich löschen?`)) {
        router.delete(route('planner-special-periods.destroy', period.id), { preserveScroll: true });
    }
};
</script>

<template>
    <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
        <div class="mb-1 flex items-center justify-between">
            <h3 class="font-semibold text-foreground">Besondere Zeiträume</h3>
            <button
                type="button"
                @click="startAdd"
                class="rounded-lg border border-border px-3 py-1 text-sm font-medium text-foreground hover:bg-muted"
            >
                Zeitraum hinzufügen
            </button>
        </div>
        <p class="mb-4 text-sm text-muted-foreground">Urlaub, Ferien, Hausprojekt oder Krank im Jahreskalender markieren.</p>

        <div v-if="showAdd" class="mb-4 space-y-3 rounded-lg border border-dashed p-4">
            <input
                v-model="form.label"
                type="text"
                placeholder="Bezeichnung, z.B. Sommerurlaub"
                class="block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
            />
            <input
                v-model="form.tag"
                type="text"
                list="period-tag-suggestions"
                placeholder="Typ, z.B. Urlaub"
                class="block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
            />
            <div class="flex gap-2">
                <input v-model="form.start_date" type="date" class="w-full rounded-lg border-border text-sm" />
                <input v-model="form.end_date" type="date" class="w-full rounded-lg border-border text-sm" />
            </div>
            <ColorPicker v-model="form.color" />
            <div class="flex gap-2">
                <button
                    type="button"
                    @click="submitAdd"
                    class="rounded-lg bg-primary px-3 py-1 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
                >
                    Speichern
                </button>
                <button
                    type="button"
                    @click="cancel"
                    class="rounded-lg border border-border px-3 py-1 text-sm text-foreground hover:bg-muted"
                >
                    Abbrechen
                </button>
            </div>
        </div>

        <datalist id="period-tag-suggestions">
            <option v-for="tag in tagSuggestions" :key="tag" :value="tag" />
        </datalist>

        <p v-if="!periods.length && !showAdd" class="text-sm text-muted-foreground">Noch keine Zeiträume erfasst.</p>

        <div v-else class="grid gap-3 sm:grid-cols-2">
            <div v-for="period in periods" :key="period.id" class="rounded-lg border p-4">
                <template v-if="editingId === period.id">
                    <div class="space-y-3">
                        <input
                            v-model="form.label"
                            type="text"
                            class="block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
                        />
                        <input
                            v-model="form.tag"
                            type="text"
                            list="period-tag-suggestions"
                            class="block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
                        />
                        <div class="flex gap-2">
                            <input v-model="form.start_date" type="date" class="w-full rounded-lg border-border text-sm" />
                            <input v-model="form.end_date" type="date" class="w-full rounded-lg border-border text-sm" />
                        </div>
                        <ColorPicker v-model="form.color" />
                        <div class="flex gap-2">
                            <button
                                type="button"
                                @click="submitEdit(period.id)"
                                class="rounded-lg bg-primary px-3 py-1 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
                            >
                                Speichern
                            </button>
                            <button
                                type="button"
                                @click="cancel"
                                class="rounded-lg border border-border px-3 py-1 text-sm text-foreground hover:bg-muted"
                            >
                                Abbrechen
                            </button>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-semibold text-foreground">{{ period.label }}</p>
                            <p class="text-sm text-muted-foreground">
                                {{ formatDate(period.start_date) }} – {{ formatDate(period.end_date) }}
                            </p>
                        </div>
                        <div class="flex gap-3 text-sm">
                            <button type="button" @click="startEdit(period)" class="text-blue-600 hover:underline">
                                Bearbeiten
                            </button>
                            <button type="button" @click="destroy(period)" class="text-red-600 hover:underline">
                                Löschen
                            </button>
                        </div>
                    </div>
                    <span
                        v-if="period.tag"
                        class="mt-2 inline-block rounded-full px-2 py-0.5 text-xs font-medium"
                        :style="{ backgroundColor: (period.color || '#10b981') + '33', color: period.color || '#10b981' }"
                    >
                        {{ period.tag }}
                    </span>
                </template>
            </div>
        </div>
    </div>
</template>
