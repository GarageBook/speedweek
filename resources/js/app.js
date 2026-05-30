import Alpine from 'alpinejs';

const storedTheme = localStorage.getItem('theme');
const theme = storedTheme === 'light' ? 'light' : 'dark';

document.documentElement.classList.toggle('theme-light', theme === 'light');
document.documentElement.classList.toggle('theme-dark', theme === 'dark');

window.toggleTheme = function toggleTheme() {
    const isLight = document.documentElement.classList.toggle('theme-light');
    document.documentElement.classList.toggle('theme-dark', !isLight);
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
};

window.Alpine = Alpine;
Alpine.start();
