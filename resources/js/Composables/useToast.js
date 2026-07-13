import { ref } from 'vue';

const toasts = ref([]);
let nextId = 1;

export function useToast() {
    const push = (message, type = 'success') => {
        const id = nextId++;
        toasts.value.push({ id, message, type });

        setTimeout(() => remove(id), 3000);
    };

    const remove = (id) => {
        toasts.value = toasts.value.filter((toast) => toast.id !== id);
    };

    return { toasts, push, remove };
}
