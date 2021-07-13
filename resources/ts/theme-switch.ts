const themeSwitch: HTMLElement | null = document.getElementById('theme-switch');

// Dark Mode
themeSwitch?.addEventListener('click', () => {

    if (document.documentElement.className === "") {

        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else if (document.documentElement.className === "dark") {

        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
});

