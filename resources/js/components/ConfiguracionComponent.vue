<template>
  <div>
    <v-card>
      <v-container grid-list-xs>
        <v-form v-model="valid" ref="form" lazy-validation>
          <v-container>
            <v-text-field
              v-model="montoCuota"
              :rules="montoRules"
              prefix="$"
              label="Monto de la cuota"
              required
            ></v-text-field>

            <v-text-field
              v-model="montoCuotaDescuento"
              :rules="montoRules"
              prefix="$"
              label="Monto de la cuota con descuento"
              required
            ></v-text-field>

            <v-btn
              depressed
              color="primary"
              :disabled="!valid"
              @click.prevent="guardarConfiguracion"
              >Guardar cambios</v-btn
            >
          </v-container>
        </v-form>

        <v-form>
          <v-layout justify-center align-center>
            <v-switch
              center="true"
              v-model="automatizar"
              :click="automatizarBajasDeSocios()"
              label="Dar de baja de forma automatica a los socios"
              class="mx-4"
            ></v-switch>
          </v-layout>
        </v-form>
      </v-container>
      
      <v-row>
        <v-col cols = "10">
          <v-file-input
            label="Seleccione un archivo para realizar backup de base"
            accept=".sqlite"
            @change="archivoElegido"
          ></v-file-input>
        </v-col>
        <v-col>
          <v-btn
            @click="guardarBD()"
            :disabled="archivoDB == null"
            color="primary"
            >guardar</v-btn
          >
        </v-col>
      </v-row>
      <v-col>
        <v-btn class = "primary" href="/base/descargar">DESCARGAR backup base</v-btn>
      </v-col>
    </v-card>
  </div>
</template>





<script>
import { mapActions } from "vuex";
export default {
  data: () => ({
    valid: false,

    automatizar: true,
    montoCuota: 0,
    montoCuotaDescuento: 0,
    archivoDB: null,

    montoRules: [
      (v) => !!v || "Monto requerido",
      (v) => /^([0-9]*[.])?[0-9]+$/.test(v) || "Debe ingresar un monto valido",
    ],
  }),

  methods: {
    ...mapActions(["callSnackbar"]),
    cargarConfiguracion() {
      axios.get("/configuraciones").then((res) => {
        this.montoCuota = res.data.montoCuota;
        this.montoCuotaDescuento = res.data.montoCuotaDescuento;
        this.automatizar = res.data.automatizarBajasSocios;
      });
    },
    archivoElegido(e) {
      this.archivoDB = e;
    },
   async guardarBD() {
     
      let formData = new FormData();

      formData.append("file", this.archivoDB);
      console.log(formData);
     try{
await axios.post(`/base/cargar`, formData, {
        headers: { "content-type": "multipart/form-data" },
      });

             this.callSnackbar(["Backup realizado correctamente ","success"])

     }catch(e){
       this.callSnackbar(["Error al guardar base: " + e,"error"])
     }
     
    },

    guardarConfiguracion() {
      axios
        .put("/configuraciones", {
          montoCuota: this.montoCuota,
          montoCuotaDescuento: this.montoCuotaDescuento,
        })
        .then(
          this.cargarConfiguracion(),
          this.callSnackbar(["Configuraciones guardadas", "primary"])
        )
        .catch((e) => {
          this.callSnackbar(
            "No se pudo guardar las configuraciones. " + e,
            "error"
          );
        });
    },

    automatizarBajasDeSocios() {
      axios.put("/configuraciones/automatizacion", {
        automatizarBajasSocios: this.automatizar,
      });
    },
  },

  mounted() {
    this.cargarConfiguracion();
  },
};
</script>