<template>
  <v-card class="ma-4">
    <v-text-field
      class="ma-2"
      v-model="search"
      append-icon="mdi-magnify"
      label="Buscar jugador"
      :items="listaJugadores"
      single-line
      hide-details
    ></v-text-field>
    <v-data-table
      :headers="headers"
      :items="listaJugadores"
      :search="search"
      class="row-pointer"
    >
    <template v-slot:[`item.socio`]="{ item }">
        <v-icon :color="item.socio.socio && item.socio.activo ? 'blue' : '' "> mdi-star </v-icon>
        <v-icon :color="item.socio.socio && !item.socio.activo ? 'blue' : '' "> mdi-star-outline </v-icon>
        <v-icon :color="!item.socio.socio ? 'blue' : '' "> mdi-star-off-outline </v-icon>
    </template>
      <!-- <template v-slot:[`item.ingresos`]="{ item }">
        <p class="mt-4">${{ item.ingresos }}</p>

        <p />
      </template> -->
    </v-data-table>
  </v-card>
</template>


<script>
import { mapMutations, mapState } from "vuex";
export default {
  props: [],

  data() {
    return {
      search: "",
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Apellido", value: "apellido" },
        { text: "DNI", value: "dni" },
        { text: "Telefono", value: "telefono" },
        { text: "Puntos", value: "pivot.puntos" },
        { text: "Categoria", value: "pivot.categoria.nombre" },
        { text: "Monto Pagado", value: "montoPagado" },
        { text: "Socio", value: "socio", sortable: false, filterable: false },
      ],
    };
  },

  computed: {
    ...mapState("gestionarTorneos", [
        "torneoSeleccionado",
        "listaJugadores"
    ]),
  },

  methods: {
    ...mapMutations("gestionarTorneos", [
      "setListaJugadores",
    ]),
    
    initialice() {
      axios
        .get(`/torneos/${this.torneoSeleccionado.id}/jugadores`)
        .then((res) => {
          this.setListaJugadores(res.data)
        });
    },
  },

  watch: {
    torneoSeleccionado: function () {
      this.initialice();
    },
  },

  mounted() {
    this.initialice();
  },
};
</script>
