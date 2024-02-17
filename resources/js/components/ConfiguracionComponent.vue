<template>
  <div>
    <center><h2>Ajustes generales</h2></center>
    <v-container class="mt-5">
      <div>
      <h3>Base de datos</h3>
      <div class="d-flex mb-7" style="justify-content: space-evenly;">
        <div>
        <div class="d-block" style="width: 380px">
          <v-file-input
            label="Seleccione un archivo para realizar copia de seguridad"
            accept=".sqlite"
            @change="archivoElegido"
          ></v-file-input>
          <v-btn
          width="100%"
          @click="guardarBD()"
          color="primary"
          >cargar Copia de seguridad
        </v-btn>
        </div>
        
        </div>
        

        

        <div class="d-flex align-center">
          <v-btn class="primary" href="/base/descargar"
            >DESCARGAR copia de seguridad</v-btn
          >
        </div>
      </div>
      </div>

  <v-divider></v-divider>

    <div class="mt-7">
      <h3>Gestor usuario</h3>
      <div class="d-flex" style="justify-content: space-evenly;">
        <v-btn
          class="mt-5 primary"
          @click="showModalCambioContrasena = !showModalCambioContrasena"
          >Cambiar contraseña</v-btn
        >
      </div>
      </div>

      <modal-cambio-contrasena
        :isOpen="showModalCambioContrasena"
        @cerrado="cerrarModal"
      ></modal-cambio-contrasena>
      </v-container>
    </div>
</template>





<script>
import { mapActions } from "vuex";
export default {
  data: () => ({
    valid: false,
    showModalCambioContrasena: false,
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
    cerrarModal(event) {
      this.showModalCambioContrasena = event;
    },
    archivoElegido(e) {
      this.archivoDB = e;
    },
    async guardarBD() {
      let formData = new FormData();

      formData.append("file", this.archivoDB);
      console.log(formData);
      try {
        await axios.post(`/base/cargar`, formData, {
          headers: { "content-type": "multipart/form-data" },
        });

        this.callSnackbar(["Backup realizado correctamente ", "success"]);
      } catch (e) {
        this.callSnackbar(["Error al guardar base: " + e, "error"]);
      }
    },
  },

  mounted() {
    this.cargarConfiguracion();
  },
};
</script>