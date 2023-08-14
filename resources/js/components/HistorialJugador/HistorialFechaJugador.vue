
<template>
  <div>
    <v-dialog v-model="dialog" width="500">
      <v-card>
        <h2 class="pa-3"> <center>Fecha: {{ fecha?.nombre }}</center></h2>

      <div v-if="fecha">
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">
                  Jugador 1
                </th>
                <th class="text-left">
                  sets
                </th>
                <th class="text-left">
                  sets
                </th>
                <th class="text-left">
                  Jugador 2
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="partido in fecha.partidos" :key="partido.id">
                <td>{{ partido.jugadores[0].nombre }} {{
                  partido.jugadores[0].apellido }}</td>
                <td>{{ partido.jugadores[0].pivot.sets }}</td>
                <td>
                  {{ partido.jugadores[1].pivot.sets }}
                </td>
                <td>{{ partido.jugadores[1].nombre }} {{
                  partido.jugadores[1].apellido }}</td>

              </tr>
            </tbody>
          </template>
        </v-simple-table>

      </div>
    </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions } from 'vuex';
export default {
  props: ["fecha"],
  data() {
    return {
      dialog: false,
    }
  },
  methods: {
    ...mapActions(["callSnackbar"]),
  },
  watch: {
    fecha: function () {
      if(this.fecha?.partidos?.length < 1) {
        this.dialog = false;
        this.callSnackbar(["El jugador no participó de la fecha","warning"]);
        return;
      }
      this.dialog = true;
      console.log("fecha cambiada");
    }

  }
}
</script>