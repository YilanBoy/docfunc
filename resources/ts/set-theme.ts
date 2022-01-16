// Load local storage or OS preferences
if (localStorage.mode === 'light' || (!('mode' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
    document.documentElement.classList.remove('dark')
} else {
    document.documentElement.classList.add('dark')
}
