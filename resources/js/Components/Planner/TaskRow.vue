<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { useConfirm } from '@/Composables/useConfirm';

const { confirm } = useConfirm();

const props = defineProps({
    task: Object,
    date: String,
});

const editing = ref(false);
const title = ref(props.task.title);
const editingDescription = ref(false);
const description = ref(props.task.description ?? '');

const priorityOrder = ['niedrig', 'mittel', 'hoch'];

const priorityLabel = {
    niedrig: 'Niedrig',
    mittel: 'Mittel',
    hoch: 'Hoch',
};

const update = (fields) => {
    router.patch(route('planner-tasks.update', props.task.id), {
        planner_category_id: props.task.planner_category_id,
        title: props.task.title,
        description: props.task.description,
        date: props.task.date,
        priority: props.task.priority,
        is_done: props.task.is_done,
        ...fields,
    }, { preserveScroll: true });
};

const toggleDone = () => update({ is_done: !props.task.is_done });

const cyclePriority = () => {
    const next = priorityOrder[(priorityOrder.indexOf(props.task.priority) + 1) % priorityOrder.length];
    update({ priority: next });
};

const saveTitle = () => {
    if (title.value.trim() && title.value !== props.task.title) {
        update({ title: title.value });
    }
    editing.value = false;
};

const saveDescription = () => {
    const value = description.value.trim() || null;
    if (value !== (props.task.description ?? null)) {
        update({ description: value });
    }
    editingDescription.value = false;
};

const defer = () => {
    router.post(route('planner-tasks.defer', props.task.id), {}, { preserveScroll: true });
};

const destroy = async () => {
    if (await confirm('Aufgabe wirklich löschen?')) {
        router.delete(route('planner-tasks.destroy', props.task.id), { preserveScroll: true });
    }
};
</script>

<template>
    <div class="flex items-center gap-3 rounded-lg border border-border px-3 py-2">
        <input
            type="checkbox"
            :checked="task.is_done"
            @change="toggleDone"
            class="rounded border-border"
        />

        <div class="min-w-0 flex-1">
            <input
                v-if="editing"
                v-model="title"
                type="text"
                class="w-full rounded border-border text-sm"
                @keyup.enter="saveTitle"
                @blur="saveTitle"
            />
            <p
                v-else
                class="truncate text-sm"
                :class="task.is_done ? 'text-muted-foreground line-through' : 'text-foreground'"
            >
                {{ task.title }}
            </p>

            <div class="mt-1 flex items-center gap-2 text-xs text-muted-foreground">
                <button
                    type="button"
                    @click="cyclePriority"
                    class="rounded-full bg-amber-100 px-2 py-0.5 font-medium text-amber-700 hover:bg-amber-200"
                >
                    {{ priorityLabel[task.priority] }}
                </button>
                <button type="button" @click="defer" class="hover:underline">Morgen</button>
                <button
                    v-if="!editingDescription && !task.description"
                    type="button"
                    @click="editingDescription = true"
                    class="hover:underline"
                >
                    + Beschreibung
                </button>
            </div>

            <textarea
                v-if="editingDescription"
                v-model="description"
                rows="2"
                placeholder="Beschreibung (optional)..."
                class="mt-2 block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
                @blur="saveDescription"
            />
            <p
                v-else-if="task.description"
                @click="editingDescription = true"
                class="mt-1 cursor-pointer truncate text-xs text-muted-foreground hover:text-foreground"
            >
                {{ task.description }}
            </p>
        </div>

        <button type="button" @click="editing = !editing" class="text-muted-foreground hover:text-foreground">
            <PencilSquareIcon class="h-4 w-4" />
        </button>
        <button type="button" @click="destroy" class="text-muted-foreground hover:text-red-600">
            <TrashIcon class="h-4 w-4" />
        </button>
    </div>
</template>
