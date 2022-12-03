<template>
  <div>
    <v-select
      id="selc-torneo"
      :value="torneoSeleccionado"
      v-on:change="confirmarCambioTorneo"
      :items="torneos"
      item-text="nombre"
      return-object
      filled
      label="Seleccione el torneo"
      class="subheading font-weight-bold"
    ></v-select>

    <v-card elevation="4" class="rounded-sm">
      <v-text-field
        dense
        filled
        :value="nombreFecha"
        @input="setNombreFecha"
        class="subheading font-weight-bold"
        label="Nombre de la fecha"
        :rules="nombreFechaRules"
      ></v-text-field>

      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            :value="montoSociosUnaCategoria"
            @input="setMontoSociosUnaCategoria"
            :rules="montoRules"
            class="mr-2 ml-2"
            outlined
            label="Monto socios que juegan una categoria"
            prefix="$"
          ></v-text-field>

          <v-text-field
            :value="montoSociosDosCategorias"
            @input="setMontoSociosDosCategorias"
            :rules="montoRules"
            class="mr-2 ml-2"
            outlined
            label="Monto socios que juegan dos categorias"
            prefix="$"
          ></v-text-field>
        </v-col>

        <v-col cols="12" md="6">
          <v-text-field
            :value="montoNoSociosUnaCategoria"
            @input="setMontoNoSociosUnaCategoria"
            :rules="montoRules"
            class="mr-2 ml-2"
            outlined
            label="Monto no socios que juegan una categoria"
            prefix="$"
          ></v-text-field>

          <v-text-field
            :value="montoNoSociosDosCategorias"
            @input="setMontoNoSociosDosCategorias"
            :rules="montoRules"
            class="mr-2 ml-2"
            outlined
            label="Monto no socios que juegan dos categorias"
            prefix="$"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-card>

    <!-- modal confirmacion cambio torneo seleccionado -->

    <v-dialog
      persistent
      v-model="showConfirmarCambioTorneo"
      @click="showConfirmarCambioTorneo = false"
      max-width="600px"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
            ¿Seguro que desea cambiar el torneo seleccionado? Se perderan todos los cambios.
        </v-card-title>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="() => cambiarTorneo(false)">
            Cancelar
          </v-btn>
          <v-btn color="primary" text @click="() => cambiarTorneo(true)">
            Aceptar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </div>
</template>

<script>
import { mapState, mapGetters, mapMutations, mapActions } from "vuex";
export default {
  data() {
    return {
      torneoAnterior: null,
      torneoPorConfirmar: null,
      showConfirmarCambioTorneo: false,
      nombreFechaRules: [
        (v) =>
          !v || /^(([A-Za-z0-9]*([/])*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(v) ||
          "Nombre invalido",
        (v) => !v || v.length <= 30 || "Demasiado largo",
      ],
      montoRules: [
        (v) =>  !v || /^(([0-9]*)([.][0-9]([0-9])*)*)+$/.test(v) || "Monto no valido ",
      ],
    };
  },
  computed: {
    ...mapState("crearFecha", [
      "torneoSeleccionado",
      "nombreFecha",
      "torneos",
      "montoSociosUnaCategoria",

      "montoSociosDosCategorias",
      "montoNoSociosUnaCategoria",
      "montoNoSociosDosCategorias",
    ]),
    ...mapGetters("crearFecha", ["tieneAlgunCampoCompletado"])
  },
  methods: {
    ...mapMutations("crearFecha", [
      "setTorneoSeleccionado",
      "setNombreFecha",
      "setMontoSociosUnaCategoria",
      "setMontoSociosDosCategorias",
      "setMontoNoSociosUnaCategoria",
      "setMontoNoSociosDosCategorias",
      "setListaCategorias",
      "setListaJugadores",
    ]),
    ...mapActions(["callSnackbar"]),
    ...mapActions("crearFecha", ["calcularMonto", "setTorneo"]),
    traerJugadoresTorneo() {
      
        let me = this;
        axios
          .get(`/torneos/${this.torneoSeleccionado.id}/jugadores`)
          .then((res) => {
            console.log(res.data);
            this.setListaJugadores(res.data);
          })
          .catch((e) =>
            this.callSnackbar(
              "No se pudieron traer jugadores. " + error,
              "error"
            )
          );
      
    },

    confirmarCambioTorneo(data) {
        if(this.torneoSeleccionado && this.tieneAlgunCampoCompletado) {
            this.torneoPorConfirmar = data
            this.showConfirmarCambioTorneo = true
        } else {
            this.setTorneo(data)
        }
    },

    cambiarTorneo(confirm) {
        if(confirm) {
            this.setTorneo(this.torneoPorConfirmar)
        } else {
            this.torneoPorConfirmar = null
            window.location.reload()
        }
        this.showConfirmarCambioTorneo = false
    },

    traerCategorias() {
     
        console.log("Entro en buscar categoiras");
        let me = this;
        axios
          .get(`/torneos/${this.torneoSeleccionado.id}/categorias`)
          .then((res) => {
            //this.$store.commit('setListaCategorias', res.data);
            res.data.forEach((categoria) => {
              Object.defineProperty(categoria, "jugadoresAnotados", {
                value: [],
                writable: true,
                configurable: true,
                enumerable: true,
              });
              Object.defineProperty(categoria, "gruposConEliminatoria", {
                value: false,
                writable: true,
                configurable: true,
                enumerable: true,
              });
              Object.defineProperty(categoria, "cantidadGrupos", {
                writable: true,
                configurable: true,
                enumerable: true,
              });
              Object.defineProperty(categoria, "listaGrupos", {
                value: [],
                writable: true,
                configurable: true,
                enumerable: true,
              });
              Object.defineProperty(categoria, "partidosLlaves", {
                value: [],
                writable: true,
                configurable: true,
                enumerable: true,
              });
              //Object.defineProperty(categoria,'gruposGenerados', {value: false, writable:true, configurable:true, enumerable:true});
              this.$set(categoria, "gruposGenerados", false);
              this.$set(categoria, "llavesGeneradas", false);
            });
            this.setListaCategorias(res.data);
          })
          .catch((e) =>
            this.callSnackbar(["Error al traer categorias", "error"])
          );
    
    },
  },
  watch: {
    torneoSeleccionado() {
      console.log(" watcher torneoSeleccionado");
      this.traerJugadoresTorneo();
      this.traerCategorias();
    },
    listaCategorias() {
      console.log(" watcher listaCategorias");
      this.calcularMonto();
    },
    montoSociosUnaCategoria() {
      console.log(" watcher montoSociosUnaCategoria");
      this.calcularMonto();
    },
    montoSociosDosCategorias() {
      console.log(" watcher montoSociosDosCategorias");
      this.calcularMonto();
    },
    montoNoSociosUnaCategoria() {
      console.log(" watcher montoNoSociosUnaCategoria");
      this.calcularMonto();
    },
    montoNoSociosDosCategorias() {
      console.log(" watcher montoNoSociosDosCategorias");
      this.calcularMonto();
    },
  },
};
</script>
