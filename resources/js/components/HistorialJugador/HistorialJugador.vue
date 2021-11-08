<template>
    <v-card>
        <v-select
        @input="setjugadorSeleccionado"
        :value="jugadorSeleccionado"

        item-text="jugador"
        return-object
        filled
        label="Seleccione un jugador"
        class="subheading font-weight-bold"
        v-on:change='[]'
      ></v-select>

       <v-data-table
        :headers="headers"
        :search="search"
      >
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
  data() {
    return {
      search: "",
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Apellido", value: "apellido" },
        { text: "Cantidad de Participantes", value: "participantes" },
        { text: "Partidos Perdidos", value: "ganados" },
        { text: "Partidos Ganados", value: "perdidos" },
        { text: "Pago", value: "Pago" },
        { text: "Cerrada", value: "date" },
      ],
    };
  },

  computed: {
    ...mapState("historialJugador", [
      "jugadorSeleccionado",
    ]),
  },
  methods: {
    ...mapMutations("historialJugador", [
      "setjugadorSeleccionado"
    ]),
  },

  created() {
    
  },
};
</script>