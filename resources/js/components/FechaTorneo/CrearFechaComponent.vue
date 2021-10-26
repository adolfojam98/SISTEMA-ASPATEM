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
    <v-btn class="primary mt-3 mb-3" @click="guardarFechaComponent()">Guardar Fecha</v-btn>

   


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
    ...mapState("crearFecha", ["listaCategorias","listaJugadores","cargandoStorage"]),

    store() {
      return this.$store.state;
    },
  },
  methods: {
    ...mapMutations("crearFecha", ["setTorneos","setCargandoStorage"]),
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
          localStorage.crearFecha = null;
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
        if (categoria.gruposGenerados && !categoria.llavesGeneradas) {
          valido = false;
          this.validaciones.push({
            mensaje:
              "Se han generado los grupos para" + categoria.nombre + "per no se han generado las llaves"
          });
        }
        else {
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
      if(localStorage.crearFecha){

        this.setCargandoStorage(true)
        const crearFecha = JSON.parse(localStorage.crearFecha);
        this.store.crearFecha.torneoSeleccionado = crearFecha.torneoSeleccionado 
      
        this.store.crearFecha.nombreFecha = crearFecha.nombreFecha 
        this.store.crearFecha.listaJugadores = crearFecha.listaJugadores
        this.store.crearFecha.montoSociosUnaCategoria = crearFecha.montoSociosUnaCategoria
        this.store.crearFecha.montoSociosDosCategorias = crearFecha.montoSociosDosCategorias
        this.store.crearFecha.montoNoSociosUnaCategoria = crearFecha.montoNoSociosUnaCategoria
        this.store.crearFecha.montoNoSociosDosCategorias = crearFecha.montoNoSociosDosCategorias
        
        this.store.crearFecha.listaCategorias = crearFecha.listaCategorias
        this.setCargandoStorage(false)
      }


      

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
    this.cargarLocalStorage()
    setInterval(() => this.guardarLocalStorage(), 2000);
  },

};
</script>

<style></style>



