import { ref } from 'vue';

const isDark = ref(document.documentElement.classList.contains('dark'));

function apply(value) {
    isDark.value = value;
    document.documentElement.classList.toggle('dark', value);
    localStorage.setItem('theme', value ? 'dark' : 'light');
}

export function useDarkMode() {
    const toggle = () => apply(!isDark.value);

    return { isDark, toggle };
}
