(function () {
    "use strict";

    const STORAGE_KEY = "simulation_theme";

    const setTheme = (isDark) => {
        document.body.classList.toggle("dark-theme", isDark);

        const themeIcon = document.getElementById("themeIcon");
        const themeText = document.getElementById("themeText");

        if (themeIcon) {
            themeIcon.className = isDark ? "bi bi-moon-stars-fill" : "bi bi-sun-fill";
        }

        if (themeText) {
            themeText.textContent = isDark ? "Mode sombre" : "Mode clair";
        }

        localStorage.setItem(STORAGE_KEY, isDark ? "dark" : "light");
    };

    const initTheme = () => {
        const savedTheme = localStorage.getItem(STORAGE_KEY);
        const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
        const isDark = savedTheme ? savedTheme === "dark" : prefersDark;

        setTheme(isDark);

        const themeToggle = document.getElementById("themeToggle");
        if (themeToggle) {
            themeToggle.addEventListener("click", (event) => {
                event.preventDefault();
                setTheme(!document.body.classList.contains("dark-theme"));
            });
        }
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initTheme);
    } else {
        initTheme();
    }
})();
