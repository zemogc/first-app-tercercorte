const ETIQUETAS_HERRAMIENTAS = {
    buscar_lugares: 'Buscó lugares',
    detalle_pais: 'Consultó un país',
    detalle_departamento: 'Consultó un departamento',
    detalle_municipio: 'Consultó un municipio',
    ranking_poblacion: 'Calculó un ranking',
    resumen_general: 'Revisó los totales',
};

function escapar(texto) {
    return texto.replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]);
}

/**
 * Convierte el Markdown básico de las respuestas (negritas, listas y
 * párrafos) a HTML. El texto se escapa antes de aplicar el formato.
 */
export function formatear(texto) {
    const lineas = escapar(texto).replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>').split('\n');
    let html = '';
    let lista = null;

    for (const linea of lineas) {
        const item = linea.match(/^\s*(?:[-*•]|(\d+)\.)\s+(.*)$/);
        if (item) {
            const tipo = item[1] ? 'ol' : 'ul';
            if (lista !== tipo) {
                if (lista) html += `</${lista}>`;
                html += `<${tipo}>`;
                lista = tipo;
            }
            html += `<li>${item[2]}</li>`;
            continue;
        }
        if (lista) {
            html += `</${lista}>`;
            lista = null;
        }
        if (linea.trim()) html += `<p>${linea.replace(/^#+\s*/, '')}</p>`;
    }

    return lista ? `${html}</${lista}>` : html;
}

export default (preguntaInicial = '') => ({
    mensajes: [],
    entrada: preguntaInicial,
    cargando: false,
    error: null,

    init() {
        if (this.entrada.trim()) this.enviar();
    },

    etiqueta(herramienta) {
        return ETIQUETAS_HERRAMIENTAS[herramienta] ?? herramienta;
    },

    formatear,

    async enviar(texto = null) {
        const pregunta = (texto ?? this.entrada).trim();
        if (!pregunta || this.cargando) return;

        this.error = null;
        this.entrada = '';
        this.mensajes.push({ rol: 'user', texto: pregunta });
        this.cargando = true;
        this.bajar();

        try {
            const { data } = await window.axios.post(this.$root.dataset.url, {
                mensajes: this.mensajes.map(({ rol, texto }) => ({ rol, texto })),
            });
            this.mensajes.push({ rol: 'assistant', texto: data.respuesta, herramientas: data.herramientas ?? [] });
        } catch (e) {
            this.mensajes.pop();
            this.entrada = pregunta;
            this.error = e.response?.data?.message ?? 'No se pudo contactar al asistente. Inténtalo de nuevo.';
        } finally {
            this.cargando = false;
            this.bajar();
        }
    },

    reiniciar() {
        this.mensajes = [];
        this.error = null;
    },

    bajar() {
        this.$nextTick(() => this.$refs.fin?.scrollIntoView({ behavior: 'smooth', block: 'end' }));
    },
});
