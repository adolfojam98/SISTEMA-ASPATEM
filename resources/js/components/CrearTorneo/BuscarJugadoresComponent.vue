<template>
  <div>
    <v-row>
      <v-col cols="6">
        <v-autocomplete
          return-object
          v-model="jugadorSeleccionado"
          :items="jugadores"
          label="Buscar jugador"
          :item-text="nombreCompleto"
        >
        </v-autocomplete>
      </v-col>
      <v-col cols="2">
        <v-text-field v-model='puntos' type="number" label="Puntos"></v-text-field>
      </v-col>
      <v-col class="d-flex" style="align-items: center;">
        <v-btn
          class="mb-2"
          color="primary"
          @click="agregarJugador()"
          >Agregar</v-btn>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      jugadorSeleccionado: null,
      jugadores: [],
      puntos : null,
    };
  },
  created() {
    axios.get("/usuario").then((response) => {
      console.log(response);
      this.jugadores = response.data;
    });
  },
  methods: {
    ...mapMutations("CrearTorneo", ["pushJugadorTorneo"]),
    ...mapActions(["callSnackbar"]),
    nombreCompleto: (item) => `${item.apellido} ${item.nombre} (${item.dni})`,
    agregarJugador() {
      if (
        this.listaJugadores.find((j) => j.dni == this.jugadorSeleccionado.dni)
      ) {
        this.callSnackbar(['El jugador ya se encuentra en la lista','error']);
        return;
      }
        this.jugadorSeleccionado.puntos = this.puntos;
        this.pushJugadorTorneo(this.jugadorSeleccionado);
        this.resetearFormulario();
        this.callSnackbar(['Jugador agregado','success']);
      
    },
    resetearFormulario(){
      this.jugadorSeleccionado = null;
      this.puntos = null;
    }
  },

  computed: {
    ...mapState("CrearTorneo", ["listaJugadores"])
  }
};
</script>