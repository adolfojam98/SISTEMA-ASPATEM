<template>
  <div>
    <center>
      <h2 class="ma-3 mt-1">Crear nuevo torneo</h2>
    </center>
    <v-stepper v-model="e6" vertical>
      <v-stepper-step :complete="e6 > 1" editable step="1" @click="setStep(1)" :rules="[() => isvalid.stepNombreTorneo]">
        Nombre del torneo
      </v-stepper-step>
      <v-stepper-content step="1">
        <step-nombre-torneo></step-nombre-torneo>
        <v-btn color="primary" @click="setStep(2)"> Continuar </v-btn>
      </v-stepper-content>
      <v-stepper-step :complete="e6 > 2" editable step="2" @click="setStep(2)" :rules="[() => isvalid.stepCategoria]">
        Categorías
      </v-stepper-step>
      <v-stepper-content step="2">
        <step-categorias-torneo-component></step-categorias-torneo-component>
        <v-btn color="primary" @click="setStep(3)"> Continuar </v-btn>
      </v-stepper-content>

      <v-stepper-step :complete="e6 > 3" editable step="3" @click="setStep(3)" :rules="[() => isvalid.stepListaJugadores]">
        <!-- TODO ver de crear un excel de ejemplo para carga masiva de jugadores -->
        Lista de Jugadores
      </v-stepper-step>
      <v-stepper-content step="3">
        <step-lista-jugadores-torneo></step-lista-jugadores-torneo>
        <v-btn color="primary" @click="setStep(4)"> Continuar </v-btn>
      </v-stepper-content>

      <v-stepper-step step="4" editable :rules="[() => isvalid.stepConfiguracionPuntos]">
        Configuración de puntos
      </v-stepper-step>
      <v-stepper-content step="4">
        <step-configuracion-puntos-torneo></step-configuracion-puntos-torneo>
        <v-btn color="primary" @click="generarTorneo()" :disabled="!valid">
          Guardar
        </v-btn>
      </v-stepper-content>
    </v-stepper>
  </div>
</template>

<!--como wea hacer para ver el progreso de un socio en los torneos si son independientes (relacionamos los terneos?)
podria ser sino una nueva fecha de un torneo anterior entonces aqui aplicamos una relacion-->

<script>
import { mapActions, mapGetters, mapMutations, mapState } from "vuex";
import StepCategoriasTorneoComponent from "./CrearTorneo/StepCategoriasTorneoComponent.vue";
import StepListaJugadoresTorneoComponent from "./CrearTorneo/StepListaJugadoresTorneoComponent.vue";
import StepNombreTorneoComponent from "./CrearTorneo/StepNombreTorneoComponent.vue";
export default {
  components: {
    StepNombreTorneoComponent,
    StepCategoriasTorneoComponent,
    StepListaJugadoresTorneoComponent,
  },
  data: () => ({
    isvalid: {
      stepConfiguracionPuntos: true,
      stepListaJugadores: true,
      stepCategoria: true,
      stepNombreTorneo: true
    }
    ,
    e6: 1,
    valid: true,
    nombreTorneoRules: [
      (v) => !!v || "El nombre del torneo es requerido.",
      (v) =>
        /^([A-Za-z]([A-Za-z0-9]*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(v) ||
        "El nombre del torneo tiene caracteres no permitidos.",
      (v) => v.length <= 30 || "El nombre del torneo es demasiado largo.",
    ],
  }),
  computed: {
    store() {
      return this.$store.state;
    },
    ...mapState("CrearTorneo", [
      "nombreTorneo",
      "gestionPuntos",
      "listaJugadores",
      "arrayCategorias",
    ]),
    ...mapGetters("CrearTorneo", ["existeNombreTorneo"]),
  },

  methods: {
    validarStep(res) {
      console.log(res);
      return true;
    },
    ...mapMutations("CrearTorneo", ["getTorneos"]),
    ...mapActions(["callSnackbar"]),
    setStep(n) {
      this.e6 = n
    },
    async generarTorneo() {
      if (!this.datosCargadosCorrectamente()) {
        return;
      }

      try {
        const nuevoTorneo = await axios.post("/torneo", {
          nombreTorneo: this.nombreTorneo,
          gestionPuntos: this.gestionPuntos,
        });
        const jugadores = await axios.post("/jugadores", {
          id_torneo: nuevoTorneo.data,
          jugadores: this.listaJugadores,
        });
        const categorias = await axios.post("/categorias", {
          id_torneo: nuevoTorneo.data,
          categorias: this.arrayCategorias,
        });
        this.callSnackbar(["Torneo agregado satisfactoriamente", "success"]);
      } catch (e) {
        this.callSnackbar([
          "No se ha podido generar el torneo,verifique los datos ingresados. " +
          e,
          "error",
        ]);
      }
    },
    verificarNombreTorneo() {
      this.isvalid.stepNombreTorneo = false;
      if (this.existeNombreTorneo(this.nombreTorneo)) {
        throw "El nombre del torneo ya esta en uso";
      }

      if (this.nombreTorneo == null || this.nombreTorneo.trim() == "") {
        throw "El nombre del torneo es requerido.";
      }
      if (
        !/^([A-Za-z]([A-Za-z0-9]*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(
          this.nombreTorneo
        )
      ) {
        throw "El nombre del torneo tiene caracteres no permitidos.";
      }
      if (this.nombreTorneo.length >= 30) {
        throw "El nombre del torneo es demasiado largo."
      }
      this.isvalid.stepNombreTorneo = true;
      return true;
    },
    verificarCategorias() {
      this.isvalid.stepCategoria = false;
      if (this.arrayCategorias.length < 1) {
        throw "Debe cargar al menos una categoria";
      }
      this.isvalid.stepCategoria = true;
      return true;
    },
    verificarJugadores() {
      this.isvalid.stepListaJugadores = false;
      if (this.listaJugadores.length < 5) {
        throw "Debe cargar al menos 5 jugadores";
      }
      this.isvalid.stepListaJugadores = true;
      return true;
    },
    verificarConfiguracionPuntos() {
      this.isvalid.stepConfiguracionPuntos = false;
      if (
        this.gestionPuntos.mismaCat_MayorNivel_Ganador == null ||
        this.gestionPuntos.mismaCat_MayorNivel_Perdedor == null ||
        this.gestionPuntos.mismaCat_MenorNivel_Ganador == null ||
        this.gestionPuntos.mismaCat_MenorNivel_Perdedor == null ||
        this.gestionPuntos.diferenteCat_MayorNivel_Ganador == null ||
        this.gestionPuntos.diferenteCat_MayorNivel_Perdedor == null ||
        this.gestionPuntos.diferenteCat_MenorNivel_Ganador == null ||
        this.gestionPuntos.diferenteCat_MenorNivel_Perdedor == null
      ) {
        throw "Debe cargar la configuracion de los puntos de los torneos";
      }
      this.isvalid.stepConfiguracionPuntos = true;
      return true;
    },
    datosCargadosCorrectamente() {
      this.reiniciarValidaciones();
      try {
          this.verificarNombreTorneo() ;
          this.verificarCategorias() ;
          this.verificarJugadores() ;
          this.verificarConfiguracionPuntos();
          return true;
      } catch (error) {
        this.callSnackbar([error, "error"]);
        return false;
      }
    },
    reiniciarValidaciones(){
      this.isvalid.stepCategoria = true;
      this.isvalid.stepConfiguracionPuntos = true;
      this.isvalid.stepListaJugadores = true;
      this.isvalid.stepNombreTorneo = true;
    }
  },
  created() {
    this.getTorneos();
  },
};
</script>

<style></style>
