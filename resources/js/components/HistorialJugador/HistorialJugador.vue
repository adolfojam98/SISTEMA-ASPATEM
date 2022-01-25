<template>
  <div>
    <v-card>
      <v-select
        @input="setjugadorSeleccionado"
        :value="jugadorSeleccionado"
        :item-text="nombreCompleto"
        :items="jugadores"
        @change="getHistory"
        return-object
        filled
        label="Seleccione un jugador"
        class="subheading font-weight-bold"
        v-on:change="[]"
      ></v-select>
    </v-card>

    <v-toolbar v-if="history">
      <v-tabs v-model="tab" align-with-title>
        <v-tabs-slider color="yellow"></v-tabs-slider>
        <v-tab v-for="torneo in history" :key="torneo.id">
            {{ torneo.nombre }}
        </v-tab>
      </v-tabs>
    </v-toolbar>
    <v-tabs-items v-model="tab">
      <v-tab-item v-for="torneo in history" :key="torneo.id">
        <v-card flat class="rounded-0">
          <v-container>
            <v-simple-table v-if="torneo.fechas.length">
              <template v-slot:default>
                <thead>
                  <tr>
                    <th class="text-left">Nombre de la fecha</th>
                    <th class="text-left">Fecha jugada</th>
                    <th class="text-left">Monto pagado</th>
                    <th class="text-left">Puntos</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="fecha in torneo.fechas" :key="fecha.created_at">
                    <th class="text-left">{{ fecha.nombre }}</th>
                    <th class="text-left">{{ formatDate(fecha.created_at) }}</th>
                    <th class="text-left" v-if="fecha.monto_pagado !== '-' ">${{ fecha.monto_pagado }}</th>
                    <th class="text-left" v-else> - </th>
                    <th class="text-left">
                      {{ fecha.puntos_totales }}({{ fecha.nuevos_puntos }})
                    </th>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
            <center v-else><h1 class="mt-5">Aún no haz jugado ninguna fecha: Puntos actuales {{torneo.puntos_base}}</h1></center>
          </v-container>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      tab: null,
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
      "jugadores",
      "history",
    ]),
  },
  methods: {
    ...mapMutations("historialJugador", [
      "setjugadorSeleccionado",
      "setjugadores",
      "setHistory",
    ]),

    nombreCompleto: (item) => item.apellido + " " + item.nombre,

    getJugadores() {
      axios.get("/usuario").then((res) => {
        this.setjugadores(res.data);
      });
    },

    getHistory() {
      axios
        .get(`/usuario/${this.jugadorSeleccionado.id}/history`)
        .then((res) => {
          this.setHistory(res.data);
        });
    },

    formatDate(date) {
        let format_date = new Date(date)
        let date_string = format_date.getFullYear() +'-'+ format_date.getMonth()+1 +'-'+ format_date.getDate() +' '+ format_date.getHours() +':'+ format_date.getMinutes() +':'+ format_date.getSeconds();
        return date_string
    }
  },

  created() {
    this.getJugadores();
  },
};
</script>