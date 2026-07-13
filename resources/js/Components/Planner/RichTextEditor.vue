<script setup>
import { watch } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import {
    BoldIcon,
    ItalicIcon,
    StrikethroughIcon,
    H2Icon,
    H3Icon,
    ListBulletIcon,
    NumberedListIcon,
    ChatBubbleLeftRightIcon,
    ArrowUturnLeftIcon,
    ArrowUturnRightIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue', 'blur']);

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            heading: { levels: [2, 3] },
            // StarterKit bundles these by default; disabled because they'd
            // produce markup outside the backend's strip_tags allowlist.
            link: false,
            underline: false,
            code: false,
            codeBlock: false,
            horizontalRule: false,
        }),
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
    ],
    editorProps: {
        attributes: {
            class: 'prose prose-sm dark:prose-invert max-w-none px-3 py-2 min-h-[6rem] focus:outline-none',
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
    onBlur: () => {
        emit('blur');
    },
});

// Parent resets modelValue when the viewed date changes, without remounting
// this component. Push that into the editor without re-triggering onUpdate
// (emitUpdate: false) — the isSame check also prevents cursor jumps while
// typing, since onUpdate -> update:modelValue would otherwise loop back here.
watch(() => props.modelValue, (value) => {
    const isSame = editor.value?.getHTML() === value;
    if (!isSame) {
        editor.value?.commands.setContent(value ?? '', { emitUpdate: false });
    }
});
</script>

<template>
    <div class="overflow-hidden rounded-lg border border-border shadow-sm focus-within:border-ring focus-within:ring-1 focus-within:ring-ring">
        <div class="flex flex-wrap items-center gap-1 border-b border-border bg-muted px-2 py-1">
            <button type="button" title="Fett" @mousedown.prevent="editor?.chain().focus().toggleBold().run()" :class="['rounded p-1.5 hover:bg-border', editor?.isActive('bold') ? 'bg-border text-foreground' : 'text-muted-foreground']">
                <BoldIcon class="h-4 w-4" />
            </button>
            <button type="button" title="Kursiv" @mousedown.prevent="editor?.chain().focus().toggleItalic().run()" :class="['rounded p-1.5 hover:bg-border', editor?.isActive('italic') ? 'bg-border text-foreground' : 'text-muted-foreground']">
                <ItalicIcon class="h-4 w-4" />
            </button>
            <button type="button" title="Durchgestrichen" @mousedown.prevent="editor?.chain().focus().toggleStrike().run()" :class="['rounded p-1.5 hover:bg-border', editor?.isActive('strike') ? 'bg-border text-foreground' : 'text-muted-foreground']">
                <StrikethroughIcon class="h-4 w-4" />
            </button>
            <span class="mx-1 h-4 w-px bg-border" />
            <button type="button" title="Überschrift 2" @mousedown.prevent="editor?.chain().focus().toggleHeading({ level: 2 }).run()" :class="['rounded p-1.5 hover:bg-border', editor?.isActive('heading', { level: 2 }) ? 'bg-border text-foreground' : 'text-muted-foreground']">
                <H2Icon class="h-4 w-4" />
            </button>
            <button type="button" title="Überschrift 3" @mousedown.prevent="editor?.chain().focus().toggleHeading({ level: 3 }).run()" :class="['rounded p-1.5 hover:bg-border', editor?.isActive('heading', { level: 3 }) ? 'bg-border text-foreground' : 'text-muted-foreground']">
                <H3Icon class="h-4 w-4" />
            </button>
            <span class="mx-1 h-4 w-px bg-border" />
            <button type="button" title="Aufzählung" @mousedown.prevent="editor?.chain().focus().toggleBulletList().run()" :class="['rounded p-1.5 hover:bg-border', editor?.isActive('bulletList') ? 'bg-border text-foreground' : 'text-muted-foreground']">
                <ListBulletIcon class="h-4 w-4" />
            </button>
            <button type="button" title="Nummerierte Liste" @mousedown.prevent="editor?.chain().focus().toggleOrderedList().run()" :class="['rounded p-1.5 hover:bg-border', editor?.isActive('orderedList') ? 'bg-border text-foreground' : 'text-muted-foreground']">
                <NumberedListIcon class="h-4 w-4" />
            </button>
            <button type="button" title="Zitat" @mousedown.prevent="editor?.chain().focus().toggleBlockquote().run()" :class="['rounded p-1.5 hover:bg-border', editor?.isActive('blockquote') ? 'bg-border text-foreground' : 'text-muted-foreground']">
                <ChatBubbleLeftRightIcon class="h-4 w-4" />
            </button>
            <span class="mx-1 h-4 w-px bg-border" />
            <button type="button" title="Rückgängig" @mousedown.prevent="editor?.chain().focus().undo().run()" class="rounded p-1.5 text-muted-foreground hover:bg-border">
                <ArrowUturnLeftIcon class="h-4 w-4" />
            </button>
            <button type="button" title="Wiederholen" @mousedown.prevent="editor?.chain().focus().redo().run()" class="rounded p-1.5 text-muted-foreground hover:bg-border">
                <ArrowUturnRightIcon class="h-4 w-4" />
            </button>
        </div>
        <EditorContent :editor="editor" />
    </div>
</template>
