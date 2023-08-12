
<template>
    <div>
        <v-btn @click="exportarHistorialJugador()">
            Exportar historial jugador
        </v-btn>
    </div>
</template>

<script>
import XLSX from 'xlsx';
import FileSaver from "file-saver";
export default {

    props: ['history'],

    data() {
        return {

        }
    },

    methods: {
        exportarHistorialJugador() {
            const wb = XLSX.utils.book_new();

            this.history.forEach(torneo => {
                const jsonAExporter = [];
                if(torneo.fechas.length > 0) {
                torneo.fechas.forEach((fecha)=>{
                    const filaNombreFecha = {
                         "Nombre fecha": fecha.nombre
                    };
                    jsonAExporter.push(filaNombreFecha);

                    if(fecha.partidos.length > 0 ){
                        fecha.partidos.forEach((partido)=>{
                            const filaPartido = {
                                jugador1 : `${partido.jugadores[0].nombre} ${partido.jugadores[0].apellido} - ${partido.jugadores[0].dni}`,
                                sets1: partido.jugadores[0].pivot.sets,                                
                                sets2: partido.jugadores[1].pivot.sets,
                                jugador2 : `${partido.jugadores[1].nombre} ${partido.jugadores[1].apellido} - ${partido.jugadores[1].dni}`,

                            }
                            jsonAExporter.push(filaPartido);
                        })


                    }
                })
            }else{
                jsonAExporter.push({"No jugo este torneo aun": ""})
            }
                console.log(jsonAExporter);
                        const ws = XLSX.utils.json_to_sheet(jsonAExporter);   
                        console.log(torneo.nombre)         
                        XLSX.utils.book_append_sheet(wb, ws, torneo.nombre);    

            });



            // Convertimos el JSON a una hoja de cálculo de Excel


            // Creamos un libro de Excel y le agregamos la hoja de cálculo



            // Creamos un archivo de Excel en formato binario y lo guardamos en el disco
            const excelFile = XLSX.write(wb, { bookType: "xlsx", type: "array" });
            FileSaver.saveAs(new Blob([excelFile]), `historial_jugador.xlsx`)
        }
    },



}
</script>