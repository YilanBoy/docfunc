const themeSwitch = document.getElementById('theme-switch') as HTMLInputElement | null;
const themeSelect = document.getElementById('theme-select') as HTMLSelectElement | null;

// Dark Mode Switch
themeSwitch?.addEventListener('click', () => {

    if (document.documentElement.className === "") {

        document.documentElement.classList.add('dark');
        // Store in local storage
        localStorage.setItem('theme', 'dark');
        syncThemeSelection()
    } else if (document.documentElement.className === "dark") {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        syncThemeSelection()
    }
});

function syncThemeSelection(): void {
    if (themeSelect !== null) {
        if (localStorage.theme === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            themeSelect.selectedIndex = 0;
        } else {
            themeSelect.selectedIndex = 1;
        }
    }
}

// Initialize Dark Mode Selection
syncThemeSelection()

// Dark Mode Selection
themeSelect?.addEventListener('change', (event) => {

    let optionValue: string = (<HTMLSelectElement>event.target).value;

    if (optionValue === 'dark') {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
});

