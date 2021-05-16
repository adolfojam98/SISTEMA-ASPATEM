<template>
    <div>
        <div>
            <input
                id="file-excel"
                type="file"
                @change="excelExport"
                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                style="display: none;"
            />

            <label
                for="file-excel"
                class="subir text-button v-btn v-btn--block v-btn--contained theme--dark v-size--default"
                style="background-color: rgb(33, 33, 33); border-color: rgb(33, 33, 33);"
            >
                Importar jugadores
            </label>
        </div>
<v-snackbar v-model="snackbar" timeout="3000">
            <div
            v-text="message">
            </div>

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="error"
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                >
                    Cerrar
                </v-btn>
            </template>
        </v-snackbar>
        <div>
            <!-- <v-simple-table
    
    >
      <template v-slot:default>
        
        <tbody>
          <tr v-for="fila in excelData" :key="fila">
            <td v-for="celda in fila" :key="celda">{{celda}}</td>
           
          </tr>
        </tbody>
      </template>
    </v-simple-table> -->
        </div>
    </div>
</template>

<script>
import { mapMutations } from "vuex";
import XLSX from "xlsx";
export default {
    data() {
        return {
            excelData: [],
            prueba: [],
            json: [],
            snackbar: false,
            message: ''
        };
    },
    methods: {
        ...mapMutations("CrearTorneo", ["pushJugadorTorneo"]),
        async excelExport(e) {
            if (e.target.files.length > 0) {
                try {
                    let file = e.target.files[0];
                    let reader = new FileReader();

                    reader.onload = async function(e) {
                        let data = new Uint8Array(e.target.result);
                        let workbook = XLSX.read(data, { type: "array" });
                        let worksheet = workbook.Sheets[workbook.SheetNames[0]];
                        this.excelData = XLSX.utils.sheet_to_json(worksheet, {
                            header: 1
                        });

                        this.armarJson(this.excelData);
                    }.bind(this);
                    reader.readAsArrayBuffer(file);
                } catch (exception) {
                    console.log(exception);
                }
            } else {
                toast("No files found", { type: "error" });
            }
        },

        armarJson(data) {
            try{
let cabecera = data.shift();
            console.log(cabecera[0].toLowerCase() == "apellido");
            console.log(cabecera[1].toLowerCase() == "nombre");
            console.log(cabecera[2].toLowerCase() == "dni");
            console.log(cabecera[3].toLowerCase() == "puntos");

            if (
                cabecera[0].toLowerCase() == "apellido" &&
                cabecera[1].toLowerCase() == "nombre" &&
                cabecera[2].toLowerCase() == "dni" &&
                cabecera[3].toLowerCase() == "puntos"
            ) {
                let json = [];
                data.forEach(participante => {
                    let participanteJson = {
                        apellido: participante[0],
                        nombre: participante[1],
                        dni: participante[2],
                        puntos: participante[3]
                    };
                    json.push(participanteJson);
                });
                console.log("Emitiendo");
                json.forEach(e => {
                    this.pushJugadorTorneo(e);
                });
            } else {
                this.message = 'Excel subido no cumple con las especificaciones';
                this.snackbar = true;
            }
            }catch(error){
                this.message = 'Error al leer Excel: ' + error ;
                this.snackbar = true;
            }
            
        }
    }
};
</script>

<style>
.subir:hover {
    cursor: pointer;
}
</style>
