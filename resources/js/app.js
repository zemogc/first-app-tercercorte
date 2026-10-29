import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Chart.js solo se descarga en las páginas que tienen gráficas.
if (document.querySelector('canvas[data-chart]')) {
    import('./charts');
}
