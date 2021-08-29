<template>
  <div>
    <v-card>
      <v-container>
        <datos-fecha></datos-fecha>
        <v-card elevation="4" class="rounded-sm">
          <jugadores-fecha></jugadores-fecha>
        </v-card>
      </v-container>
    </v-card>
    <v-card v-if="listaCategorias.length > 0" dark>
      <grupos-fecha></grupos-fecha>
    </v-card>
    <v-btn @click="guardarFechaComponent()">Guardar</v-btn>

    <v-btn @click="guardarLocalStorage()">SAVE STORAGE</v-btn>
    <v-btn @click="cargarLocalStorage()">CARGAR STORAGE</v-btn>
    <v-btn @click="backupBase()">BackupBase</v-btn>
   


    <v-alert
      v-for="(validacion, index) in validaciones"
      :key="index"
      dense
      outlined
      type="error"
    >
      {{ validacion.mensaje }}
    </v-alert>
  </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  data() {
    return {
      validaciones: [],
    };
  },
  computed: {
    ...mapState("crearFecha", ["listaCategorias","listaJugadores"]),

    store() {
      return this.$store.state;
    },
  },
  methods: {
    ...mapMutations("crearFecha", ["setTorneos"]),
    ...mapActions(["callSnackbar"]),

    async guardarFechaComponent() {
      this.validaciones = [];

      if (this.verificarDatosCargados()) {
        try {
          await axios.post("/torneo/fecha/guardar", {
            categorias: this.store.crearFecha.listaCategorias,
            montos: {
              montoSociosUnaCategoria:
                this.store.crearFecha.montoSociosUnaCategoria,
              montoSociosDosCategorias:
                this.store.crearFecha.montoSociosDosCategorias,
              montoNoSociosUnaCategoria:
                this.store.crearFecha.montoNoSociosUnaCategoria,
              montoNoSociosDosCategorias:
                this.store.crearFecha.montoNoSociosDosCategorias,
            },
            nombreFecha: this.store.crearFecha.nombreFecha,
            jugadores: this.store.crearFecha.listaJugadores,
          });
        } catch (e) {
          this.callSnackbar(["No se ha podido guardar. " + e, "error"]);
        }
      }
    },
    verificarDatosCargados() {
      let valido = true;
      if (
        !this.verificarCargaDeCategorias(this.store.crearFecha.listaCategorias)
      ) {
        valido = false;
      }
      if (!this.verificarDatosBasicos(this.store.crearFecha)) {
        valido = false;
      }

      return valido;
    },

    verificarCargaDeCategorias(categorias) {
      let valido = true;
      categorias.forEach((categoria) => {
        if (!categoria.gruposGenerados) {
          valido = false;
          this.validaciones.push({
            mensaje:
              "No se han generado los grupos para la categoria " +
              categoria.nombre,
          });
        }
        if (!categoria.llavesGeneradas) {
          valido = false;
          this.validaciones.push({
            mensaje:
              "No se han generado las llaves para la categoría" +
              categoria.nombre,
          });
        } else {
          if (!this.verificarPartidosLlavesCategorias(categoria)) {
            valido = false;
            this.validaciones.push({
              mensaje:
                "Al menos un partido de las llaves de la categoria" +
                categoria.nombre +
                " no ha sido completado",
            });
          }
        }
      });

      return valido;
    },
    verificarPartidosLlavesCategorias(categoria) {
      let valido = true;
      categoria.partidosLlaves.forEach((partido) => {
        partido.jugador1 == null ||
        partido.jugador2 == null ||
        partido.set1 == null ||
        partido.set2 == null
          ? (valido = false)
          : true;
      });

      return valido;
    },

    verificarDatosBasicos(crearFecha) {
      let valido = true;

      if (crearFecha.nombreFecha == "") {
        valido = false;
        this.validaciones.push({ mensaje: "No se especifico nombre de fecha" });
      }
      if (
        crearFecha.montoSociosUnaCategoria == null ||
        crearFecha.montoSociosDosCategorias == null ||
        crearFecha.montoNoSociosUnaCategoria == null ||
        crearFecha.montoNoSociosDosCategorias == null
      ) {
        valido = false;
        this.validaciones.push({
          mensaje: "Al menos un monto no fue especificado",
        });
      }

      return valido;
    },

    guardarLocalStorage() {
      localStorage.crearFecha = JSON.stringify(this.store.crearFecha);
    },
    cargarLocalStorage() {
      this.store.crearFecha = JSON.parse(localStorage.crearFecha);
      console.log(this.store.crearFecha);
    },
    backupBase(){
      axios.get('/base/descargar').then(res => res.download());
    }
  },

  created() {
    axios.get("/torneos").then((res) => {
      this.setTorneos(res.data);
    });
  },
};
</script>

<style></style>
