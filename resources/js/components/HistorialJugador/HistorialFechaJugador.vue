<template>
  <div>
    <v-dialog v-model="dialog" width="500">
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
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["fecha"],
  data() {
    return {
      dialog: false,
    }
  },
  watch: {
    fecha: function () {
      this.dialog = true;
      console.log("fecha cambiada");
    }

  }
}
</script>