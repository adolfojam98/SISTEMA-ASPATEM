
<template>
    <div>
        <v-btn @click="exportarHistorialJugador()" color="primary" >
            Exportar historial jugador
        </v-btn>
    </div>
</template>

<script>
import FileSaver from "file-saver";
import * as XLSX from 'xlsx-js-style';
export default {

    props: ['history'],

    data() {
        return {

        }
    },

    methods: {
        exportarHistorialJugador() {
    const wb = XLSX.utils.book_new();
    const estilo = {
    font: { size: 14, color: { rgb: "006100" } },
    fill: { fgColor: { rgb: "C6EFCE" } },
    alignment: { horizontal: "center" }
};


    this.history.forEach(torneo => {
        const jsonAExporter = [];
        if (torneo.fechas.length > 0) {
            torneo.fechas.forEach((fecha) => {
                const filaNombreFecha = {
                    "Nombre fecha": fecha.nombre
                };
                jsonAExporter.push(filaNombreFecha);

                if (fecha.partidos.length > 0) {
                    fecha.partidos.forEach((partido) => {
                        const filaPartido = {
                            jugador1: `${partido.jugadores[0].nombre} ${partido.jugadores[0].apellido} - ${partido.jugadores[0].dni}`,
                            sets1: partido.jugadores[0].pivot.sets,
                            sets2: partido.jugadores[1].pivot.sets,
                            jugador2: `${partido.jugadores[1].nombre} ${partido.jugadores[1].apellido} - ${partido.jugadores[1].dni}`
                        };
                        jsonAExporter.push(filaPartido);
                    });
                }
            });
        } else {
            jsonAExporter.push({ "No jugo este torneo aun": "" });
        }

        // ...


        
        const ws = XLSX.utils.json_to_sheet(jsonAExporter);

        // Aplicar estilo a la fila que contiene el campo "Nombre fecha"
        for (let row = 2; row <= jsonAExporter.length; row++) {
            const cellValue = ws[`A${row}`]?.v; // Obtener el valor de la celda A en la fila actual
            if (cellValue !== undefined) {
                ws[`A${row}`].s = estilo; // Aplicar el estilo definido anteriormente
            }
        }

        // ...

        XLSX.utils.book_append_sheet(wb, ws, torneo.nombre);
    });

    // ...

    const excelFile = XLSX.write(wb, { bookType: "xlsx", type: "array" });
    FileSaver.saveAs(new Blob([excelFile]), `historial_jugador.xlsx`);
}

    },
};
</script>
