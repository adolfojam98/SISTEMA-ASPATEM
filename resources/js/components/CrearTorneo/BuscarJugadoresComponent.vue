<template>
  <div>
    <v-row>
      <v-col cols="6">
        <v-autocomplete :loading="isLoading" v-model="jugadorSeleccionado"
        :item-text="nombreCompleto" :items="jugadores" :search-input.sync="search" return-object
        label="Buscar jugador" class="subheading font-weight-bold"> <template v-slot:no-data>
          <v-list-item>
            <v-list-item-title>
              Busque por nombre/apellido o DNI
            </v-list-item-title>
          </v-list-item>
        </template>

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
      isLoading: false,
      search: null,
    };
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
    },
    async getJugadores() {
      this.isLoading = true
      const res = await axios.get("/usuario", { params: { search: this.search } });

      this.jugadores = res.data.usuarios.data;
      this.isLoading = false;
    },
  },
   watch: {
    search(val) {

      if (!val || val.length < 1) return;
      console.log(val);
      // Items have already been requested
      if (this.isLoading) return
      this.getJugadores();

    },
  },

  computed: {
    ...mapState("CrearTorneo", ["listaJugadores"])
  },
  
};
</script>