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
        <v-icon :color="elegirColorIcono(item)">
          {{ elegirIcono(item) }}
        </v-icon>
      </template>
      <template v-slot:[`header.socio`]="{ header }">
        {{ header.text }}
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-icon style="margin-top: 0px" v-bind="attrs" v-on="on"
              >mdi-help-circle-outline</v-icon
            >
          </template>
          <span>
            <v-icon color="blue"> mdi-star </v-icon> Socio
            <v-icon color="blue"> mdi-star-outline </v-icon> Socio Inactivo
            <v-icon> mdi-star-off-outline </v-icon> No Socio
          </span>
        </v-tooltip>
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
        {
          text: "Socio",
          value: "socio",
          sortable: false,
          filterable: false,
        },
      ],
    };
  },

  computed: {
    ...mapState("gestionarTorneos", ["torneoSeleccionado", "listaJugadores"]),
  },

  methods: {
    ...mapMutations("gestionarTorneos", ["setListaJugadores"]),
    elegirIcono(item) {
      if (item.socio.socio && item.socio.activo) return "mdi-star";
      if (item.socio.socio && !item.socio.activo) return "mdi-star-outline";
      return "mdi-star-off-outline";
    },
    elegirColorIcono(item) {
      if (item.socio.socio && item.socio.activo) return "blue";
      if (item.socio.socio && !item.socio.activo) return "blue";
      return "black";
    },
    initialice() {
      axios
        .get(`/torneos/${this.torneoSeleccionado.id}/jugadores`)
        .then((res) => {
          this.setListaJugadores(res.data);
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
