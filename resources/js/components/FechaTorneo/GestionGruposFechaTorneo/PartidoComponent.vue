<template>
  <div>
    <v-card outlined flat max-width="374">
      <v-card-text>
        <v-row>
          <v-col cols="6">
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <p class="text-truncate" v-bind="attrs" v-on="on">{{ partido.jugador1?.nombre }} {{
                  partido.jugador1?.apellido }}</p>
              </template>
              <span>{{ partido.jugador1?.nombre }} {{ partido.jugador1?.apellido }} - {{ partido.jugador1?.dni }}</span>
            </v-tooltip>
            <v-text-field label="Sets" v-model="partido.setsJugador1" type="number"></v-text-field>
          </v-col>
          <v-col cols="6">

            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <p class="text-truncate" v-bind="attrs" v-on="on">{{ partido.jugador2?.nombre }} {{
                  partido.jugador2?.apellido }}</p>
              </template>
              <span>{{ partido.jugador2?.nombre }} {{ partido.jugador2?.apellido }} - {{ partido.jugador2?.dni }}</span>
            </v-tooltip>
            <v-text-field label="Sets" v-model="partido.setsJugador2" type="number"></v-text-field>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ["partido"]
};
</script>

<style scoped>
.text-truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.align-center {
  align-items: center;
}
</style>
