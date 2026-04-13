import { ref } from 'vue'

const isDark = ref(
    document.documentElement.getAttribute('data-theme') === 'dark'
    || (
        !document.documentElement.getAttribute('data-theme')
        && window.matchMedia('(prefers-color-scheme: dark)').matches
    )
)

export function useTheme() {
    function toggle() {
        isDark.value = !isDark.value
        document.documentElement.setAttribute(
            'data-theme',
            isDark.value ? 'dark' : 'light'
        )
    }

    function setDark()  { isDark.value = true;  document.documentElement.setAttribute('data-theme', 'dark') }
    function setLight() { isDark.value = false; document.documentElement.setAttribute('data-theme', 'light') }

    return { isDark, toggle, setDark, setLight }
}
