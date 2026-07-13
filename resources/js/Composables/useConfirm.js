import { ref } from 'vue';

const show = ref(false);
const message = ref('');
let resolvePromise = null;

export function useConfirm() {
    const confirm = (msg) => {
        message.value = msg;
        show.value = true;

        return new Promise((resolve) => {
            resolvePromise = resolve;
        });
    };

    const respond = (value) => {
        show.value = false;
        resolvePromise?.(value);
        resolvePromise = null;
    };

    return { show, message, confirm, respond };
}
