const themeSwitch = document.getElementsByClassName('theme-switch');

// Dark Mode Switch
Array.prototype.forEach.call(themeSwitch, (element) => {
    element.addEventListener('click', () => {

        if (document.documentElement.className === "") {

            document.documentElement.classList.add('dark');
            // Store in local storage
            localStorage.setItem('mode', 'dark');
        } else if (document.documentElement.className === "dark") {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('mode', 'light');
        }
    });
})
