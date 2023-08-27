import { mapActions } from 'vuex';
<template>
  <div>
        <v-card elevation="0" class="rounded-sm">
          <div class="pa-5">
            <v-form ref="form" v-model="formularioValido" lazy-validation>

              <v-row justify="center">
                <v-col cols="12" md="8">
                  <v-select
                    v-model="torneoSeleccionado"
                    :items="torneos"
                    item-text="nombre"
                    return-object
                    label="Seleccione el torneo"
                    :rules="torneoRules"
                    required
                  ></v-select>

                  <v-text-field
                    v-model="nombreFecha"
                    label="Nombre de la fecha"
                    :rules="nombreFechaRules"
                    required
                  ></v-text-field>

                  <v-text-field
                    required
                    v-model="montoSociosUnaCategoria"
                    :rules="montoRules"
                    label="Monto socios que juegan una categoria"
                    prefix="$"
                  ></v-text-field>

                  <v-text-field
                    required
                    v-model="montoSociosDosCategorias"
                    :rules="montoRules"
                    label="Monto socios que juegan dos categorias"
                    prefix="$"
                  ></v-text-field>

                  <v-text-field
                    required
                    v-model="montoNoSociosUnaCategoria"
                    :rules="montoRules"
                    label="Monto no socios que juegan una categoria"
                    prefix="$"
                  ></v-text-field>

                  <v-text-field
                    required
                    v-model="montoNoSociosDosCategorias"
                    :rules="montoRules"
                    label="Monto no socios que juegan dos categorias"
                    prefix="$"
                  ></v-text-field>

                </v-col>
              </v-row>
              <v-card-actions>
                <v-spacer></v-spacer>
                    <v-btn @click="[cerrarDialog()]">
                      CANCELAR
                    </v-btn>
                    <v-btn
                      :disabled="!formularioValido"
                      @click="guardarFecha"
                      color="primary"
                      >Guardar</v-btn
                    >
              </v-card-actions>
            </v-form>
          </div>
        </v-card>
  </div>
</template>


<script>
import { mapActions } from "vuex";

export default {
  data() {
    return {
      formularioValido: true,
      torneos: [],
      torneoSeleccionado: null,
      nombreFecha: null,
      montoSociosUnaCategoria: null,
      montoSociosDosCategorias: null,
      montoNoSociosUnaCategoria: null,
      montoNoSociosDosCategorias: null,
      torneoRules: [(v) => !!v || "El torneo es requerido"],
      nombreFechaRules: [
        (v) => !!v || "El nombre es requerido",
        (v) =>
          !v ||
          /^(([A-Za-z0-9]*([/])*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(v) ||
          "Nombre invalido",
        (v) => !v || v.length <= 30 || "Demasiado largo",
      ],
      montoRules: [
        (v) => !!v || "El monto es requerido",
        (v) =>
          !v ||
          /^(([0-9]*)([.][0-9]([0-9])*)*)+$/.test(v) ||
          "Monto no valido ",
      ],
    };
  },
  created() {
    axios.get("/torneos").then((res) => {
      this.torneos = res.data;
    });
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    cerrarDialog() {      
      this.$emit('close');
    },
    guardarFecha() {
      if (!this.validarFormulario()) {
        return;
      }
      axios.post("/fechas", {
        nombreFecha: this.nombreFecha,
        montoSociosUnaCategoria: this.montoSociosUnaCategoria,
        montoSociosDosCategorias: this.montoSociosDosCategorias,
        montoNoSociosUnaCategoria: this.montoNoSociosUnaCategoria,
        montoNoSociosDosCategorias: this.montoNoSociosDosCategorias,
        torneoId: this.torneoSeleccionado.id,
      })
      .then(res=> {
        this.cerrarDialog()
        this.callSnackbar(["Fecha generada exitosamente", "success"]);
      })
      .catch((e) =>
        this.callSnackbar("Hubo un error al guardar la fecha. " + e, "error")
      );
      
    },

    validarFormulario() {
      return this.$refs.form.validate();
    },
    traerJugadoresTorneo() {
      let me = this;
      axios
        .get(`/torneos/${this.torneoSeleccionado.id}/jugadores`)
        .then((res) => {
          console.log(res.data);
          this.setListaJugadores(res.data);
        })
        .catch((e) =>
          this.callSnackbar("No se pudieron traer jugadores. " + e, "error")
        );
    },

    confirmarCambioTorneo(data) {
      if (this.torneoSeleccionado && this.tieneAlgunCampoCompletado) {
        this.torneoPorConfirmar = data;
        this.showConfirmarCambioTorneo = true;
      } else {
        this.setTorneo(data);
      }
    },

    cambiarTorneo(confirm) {
      if (confirm) {
        this.setTorneo(this.torneoPorConfirmar);
      } else {
        this.torneoPorConfirmar = null;
        window.location.reload();
      }
      this.showConfirmarCambioTorneo = false;
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
};
</script>