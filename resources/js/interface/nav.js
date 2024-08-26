export default (function () {

    if (typeof window.isListenerAttached === 'undefined') {
        window.isListenerAttached = false;
    }

    function applyThemeFromLocalStorage() {
        const theme = localStorage.getItem('theme');
        if (theme === 'dark') {
            document.body.classList.add('dark');
        } else {
            document.body.classList.remove('dark');
        }
    }

    function attachLivewireNavigationListener() {
        if (!window.isListenerAttached) {
            document.addEventListener('livewire:navigated', applyThemeFromLocalStorage);
            window.isListenerAttached = true; // Set the flag to true to run once only
        }
    }

    document.addEventListener("DOMContentLoaded", (event) => {
        // Handle theme selection (localStorage)
        const theme = localStorage.getItem('theme');

        if (theme === 'dark') {
            document.body.classList.add('dark');
        } else if (theme === 'light') {
            document.body.classList.remove('dark');
        } else {
            document.body.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }

        applyThemeFromLocalStorage();
        attachLivewireNavigationListener();

        // Handle the active state on click because of wire:navigate
        const els = document.querySelectorAll('.nav-el');

        function setElActive(el) {
            document.querySelector('.active').classList.remove("active");
            el.classList.add("active");
        }

        for (let i = 0; i < els.length; i++) {
            els[i].addEventListener('click', (e) => {
                setElActive(els[i]);
            });
        }
    });
}());
