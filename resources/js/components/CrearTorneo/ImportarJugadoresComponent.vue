<template>
  <div>
    <div>
      <input
        id="file-excel"
        type="file"
        ref="input"
        @change="excelExport"
        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        style="display: none"
      />

      <label
        for="file-excel"
        class="
          subir
          text-button
          v-btn v-btn--block v-btn--contained
          theme--dark
          v-size--default
        "
        style="background-color: rgb(33, 33, 33); border-color: rgb(33, 33, 33)"
      >
        Importar jugadores
      </label>
    </div>

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
import { mapActions, mapMutations } from "vuex";
import XLSX from "xlsx";
export default {
  data() {
    return {
      excelData: [],
      prueba: [],
      json: [],
    };
  },
  methods: {
    ...mapMutations("CrearTorneo", ["pushJugadorTorneo"]),
    ...mapActions(["callSnackbar"]),

    focusInput() {
      this.$refs.input.focus();
    },

    async excelExport(e) {
      if (e.target.files.length > 0) {
        try {
          let file = e.target.files[0];
          let reader = new FileReader();

          reader.onload = async function (e) {
            let data = new Uint8Array(e.target.result);
            let workbook = XLSX.read(data, { type: "array" });
            let worksheet = workbook.Sheets[workbook.SheetNames[0]];
            this.excelData = XLSX.utils.sheet_to_json(worksheet, {
              header: 1,
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
      this.$refs.input.value = "";
      this.$refs.input.files = null;
    },

    armarJson(data) {
      try {
        let cabecera = data.shift();

        if (
          cabecera[0].toLowerCase() == "apellido" &&
          cabecera[1].toLowerCase() == "nombre" &&
          cabecera[2].toLowerCase() == "dni" &&
          cabecera[3].toLowerCase() == "puntos"
        ) {
          let json = [];
          data.forEach((participante, i) => {
            console.log(`participante nro ${i}`);
            if (this.validarFilaParticipante(participante)) {
              let participanteJson = {
                apellido: participante[0],
                nombre: participante[1],
                dni: participante[2],
                puntos: participante[3],
              };
              json.push(participanteJson);
            }
          });
          console.log("Emitiendo");
          this.validarJugadoresImportados(json);

          this.callSnackbar(["Jugadores agregados correctamente", "success"]);
        } else {
          this.callSnackbar([
            "Excel subido no cumple con las especificaciones",
            "error",
          ]);
        }
      } catch (error) {
        this.callSnackbar(["Error al leer Archivo. " + error, "error"]);
      }

      this.excelData = [];
      this.prueba = [];
      this.json = [];
    },
    validarFilaParticipante(participante) {
      return !(
        participante[0] === undefined ||
        participante[1] === undefined ||
        participante[2] === undefined ||
        participante[3] ===undefined
      );
    },
    async validarJugadoresImportados(json) {
      for (const jugador of json) {
        const resp = await axios.get("/usuario", {
          params: {
            dni: jugador.dni,
          },
        });
        if (Array.isArray(resp.data) && resp.data.length) {
          jugador = { ...resp.data[0], puntos: jugador.puntos };
        }
        this.pushJugadorTorneo(jugador);
      }
    },
  },

  mounted() {
    this.focusInput();
  },
};
</script>

<style>
.subir:hover {
  cursor: pointer;
}
</style>
