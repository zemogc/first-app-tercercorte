import './bootstrap';

import Alpine from 'alpinejs';
import asistente from './asistente';

window.Alpine = Alpine;

Alpine.data('asistente', asistente);

Alpine.start();

// Chart.js solo se descarga en las páginas que tienen gráficas.
if (document.querySelector('canvas[data-chart]')) {
    import('./charts');
}
