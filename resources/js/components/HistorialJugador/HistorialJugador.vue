<template>
  <div>
    <v-card elevation="0">
      <center>
        <h2 cols="12">Historial de jugadores</h2>
      </center>
      <div class="d-inline-flex ml-4" v-if="!jugadorTorneo">

        <v-autocomplete :loading="isLoading" @input="setjugadorSeleccionado" :value="jugadorSeleccionado"
          :item-text="nombreCompleto" :items="jugadores" @change="getHistory" :search-input.sync="search" return-object
          label="Seleccione un jugador" class="subheading font-weight-bold mr-4"> <template v-slot:no-data>
            <v-list-item>
              <v-list-item-title>
                Busque por nombre/apellido o DNI
              </v-list-item-title>
            </v-list-item>
          </template>

        </v-autocomplete>

        <div class="align-self-center" v-if="jugadorSeleccionado">
          <exportar-historial-jugador :history="history"></exportar-historial-jugador>
        </div>

      </div>
      <div v-if="!history?.length && jugadorSeleccionado">
        <h4 class="mt-5">
          <center>Esta persona aun no ha jugado ningun torneo </center>
        </h4>
      </div>
      <div v-if="history?.length">
        <v-toolbar>
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
                <v-simple-table v-if="torneo.fechas?.length">
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
                      <tr v-for="fecha in torneo.fechas" :key="fecha.created_at" @click="mostrarFecha(fecha)"
                        style="cursor: pointer;">
                        <th class="text-left">{{ fecha.nombre }}</th>
                        <th class="text-left">
                          {{ formatDate(fecha.created_at) }}
                        </th>
                        <th class="text-left" v-if="fecha.monto_pagado !== '-'">
                          ${{ fecha.monto_pagado }}
                        </th>
                        <th class="text-left" v-else>-</th>
                        <th class="text-left">
                          {{ fecha.puntos_totales }}({{ fecha.nuevos_puntos }})
                        </th>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
                <center v-else>
                  <h4 class="mt-5">
                    Aún no has jugado ninguna fecha: Puntos actuales
                    {{ torneo.puntos_base }}
                  </h4>
                </center>
              </v-container>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </div>


    </v-card>
    <historial-fecha-jugador :fecha="fechaSeleccionada"></historial-fecha-jugador>
  </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
  props: ["jugadorTorneo"],
  data() {
    return {
      isLoading: false,
      tab: null,
      search: "",
      fechaSeleccionada: null,
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

    nombreCompleto: (item) => item.apellido + " " + item.nombre + " " + item.dni,

    async getJugadores() {
      this.isLoading = true
      const res = await axios.get("/usuario", { params: { search: this.search } });

      this.setjugadores(res.data.usuarios.data);
      this.isLoading = false;
    },

    getHistory() {
      axios
        .get(`/usuario/${this.jugadorSeleccionado.id}/history`)
        .then((res) => {
          this.setHistory(res.data);
        });
    },

    formatDate(fecha) {
      if (!fecha || typeof fecha === "undefined") {
        return null;
      }

      const date = new Date(fecha);
      const day = date.getDate().toString().padStart(2, '0');
      const month = (date.getMonth() + 1).toString().padStart(2, '0');
      const year = date.getFullYear().toString();
      const outputDateString = `${day}/${month}/${year}`;

      return outputDateString;
    },
    mostrarFecha(fecha) {
      this.fechaSeleccionada = null;
      setTimeout(() => {
        this.fechaSeleccionada = fecha;
      }, 0);

      console.log("mostrando fecha", fecha);
    }
  },

  watch: {
    async search(val) {

      if (!val || val.length < 1) return;
      console.log(val);
      // Items have already been requested
      if (this.isLoading) return
      this.getJugadores();

    },
    jugadorTorneo: function () {
      console.log('cambia el jugador');
      this.setjugadorSeleccionado(this.jugadorTorneo);
      this.getHistory();

    }
  },


};
</script>