<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import { useConfirm } from '@/Composables/useConfirm';

const { confirm } = useConfirm();

const props = defineProps({
    goal: Object,
});

const note = ref(props.goal.note ?? '');

const statusOrder = ['offen', 'aktiv', 'erledigt'];
const statusLabel = { offen: 'Offen', aktiv: 'Aktiv', erledigt: 'Erledigt' };
const statusClass = {
    offen: 'bg-muted text-muted-foreground',
    aktiv: 'bg-blue-100 text-blue-700',
    erledigt: 'bg-green-100 text-green-700',
};

const update = (fields) => {
    router.patch(route('planner-year-goals.update', props.goal.id), {
        category_label: props.goal.category_label,
        title: props.goal.title,
        note: note.value,
        status: props.goal.status,
        progress: props.goal.progress,
        ...fields,
    }, { preserveScroll: true });
};

const cycleStatus = () => {
    const next = statusOrder[(statusOrder.indexOf(props.goal.status) + 1) % statusOrder.length];
    update({ status: next });
};

const saveNote = () => update({ note: note.value });

const adjustProgress = (delta) => {
    router.post(route('planner-year-goals.progress', props.goal.id), { delta }, { preserveScroll: true });
};

const destroy = async () => {
    if (await confirm(`Ziel "${props.goal.title}" wirklich löschen?`)) {
        router.delete(route('planner-year-goals.destroy', props.goal.id), { preserveScroll: true });
    }
};
</script>

<template>
    <div class="rounded-lg border p-4">
        <div class="mb-2 flex items-start justify-between gap-2">
            <p class="font-semibold text-foreground">{{ goal.title }}</p>
            <button type="button" @click="destroy" class="text-muted-foreground hover:text-red-600">
                <TrashIcon class="h-4 w-4" />
            </button>
        </div>

        <textarea
            v-model="note"
            @blur="saveNote"
            rows="2"
            placeholder="Kurze Notiz zum Ziel..."
            class="mb-3 block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
        />

        <div class="mb-2 flex items-center justify-between">
            <button
                type="button"
                @click="cycleStatus"
                class="rounded-full px-2 py-0.5 text-xs font-medium"
                :class="statusClass[goal.status]"
            >
                {{ statusLabel[goal.status] }}
            </button>
            <span class="text-sm font-semibold text-foreground">Fortschritt: {{ goal.progress }}%</span>
        </div>

        <div class="mb-3 h-2 overflow-hidden rounded-full bg-muted">
            <div class="h-2 rounded-full bg-primary transition-all" :style="{ width: goal.progress + '%' }" />
        </div>

        <div class="flex gap-2">
            <button
                type="button"
                @click="adjustProgress(-10)"
                class="rounded-lg border border-border px-2 py-1 text-xs font-medium text-foreground hover:bg-muted"
            >
                -10%
            </button>
            <button
                type="button"
                @click="adjustProgress(10)"
                class="rounded-lg border border-border px-2 py-1 text-xs font-medium text-foreground hover:bg-muted"
            >
                +10%
            </button>
        </div>
    </div>
</template>
