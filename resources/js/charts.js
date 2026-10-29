import Chart from 'chart.js/auto';

const PALETA = ['#10b981', '#0ea5e9', '#f59e0b', '#d946ef', '#8b5cf6', '#f43f5e', '#14b8a6', '#6366f1'];

const graficas = [];

function colores() {
    const oscuro = document.documentElement.classList.contains('dark');

    return {
        texto: oscuro ? '#9ca3af' : '#6b7280',
        rejilla: oscuro ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)',
        borde: oscuro ? '#111827' : '#ffffff',
    };
}

const formato = new Intl.NumberFormat('es-CO');

function configuracion(tipo, { etiquetas, valores, serie }) {
    const c = colores();

    if (tipo === 'doughnut') {
        return {
            type: 'doughnut',
            data: {
                labels: etiquetas,
                datasets: [{ data: valores, backgroundColor: PALETA, borderColor: c.borde, borderWidth: 3, hoverOffset: 6 }],
            },
            options: {
                cutout: '68%',
                plugins: { legend: { position: 'right', labels: { color: c.texto, usePointStyle: true, boxWidth: 8 } } },
            },
        };
    }

    return {
        type: 'bar',
        data: {
            labels: etiquetas,
            datasets: [{ label: serie, data: valores, backgroundColor: PALETA[0], borderRadius: 8, maxBarThickness: 36 }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => ` ${formato.format(ctx.parsed.y)} habitantes` } },
            },
            scales: {
                x: { ticks: { color: c.texto }, grid: { display: false } },
                y: {
                    ticks: { color: c.texto, callback: (v) => formato.format(v) },
                    grid: { color: c.rejilla },
                    border: { display: false },
                },
            },
        },
    };
}

function dibujar() {
    graficas.splice(0).forEach((g) => g.destroy());

    document.querySelectorAll('canvas[data-chart]').forEach((canvas) => {
        const datos = JSON.parse(canvas.dataset.values);
        graficas.push(new Chart(canvas, configuracion(canvas.dataset.chart, datos)));
    });
}

if (document.querySelector('canvas[data-chart]')) {
    dibujar();
    window.addEventListener('theme-changed', dibujar);
}
