<template>
  <div>
    <v-card outlined flat max-width="374">
        <div class="d-flex container-group_player">
          <div class="justify-space-between my-auto container-group_player_info">
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <p class="text-truncate mb-none" v-bind="attrs" v-on="on">{{ partido.jugador1?.nombre }} {{
                  partido.jugador1?.apellido }}</p>
              </template>
              <span>{{ partido.jugador1?.nombre }} {{ partido.jugador1?.apellido }} - {{ partido.jugador1?.dni }}</span>
            </v-tooltip>
          </div>

          <div class="my-auto">
            <v-text-field label="Sets" class="input-sets" v-model="partido.setsJugador1" type="number"></v-text-field>
          </div>
        </div>

        <div class="d-flex container-group_player">
          <div class="justify-space-between my-auto container-group_player_info">
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <p class="text-truncate mb-none" v-bind="attrs" v-on="on">{{ partido.jugador2?.nombre }} {{
                  partido.jugador2?.apellido }}</p>
              </template>
              <span>{{ partido.jugador2?.nombre }} {{ partido.jugador2?.apellido }} - {{ partido.jugador2?.dni }}</span>
            </v-tooltip>
          </div>

          <div cols="3" class="my-auto">
            <v-text-field label="Sets" class="input-sets" v-model="partido.setsJugador2" type="number"></v-text-field>
          </div>
        </div>
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

.my-auto {
  margin: auto 0;
}

.mb-none {
  margin-bottom: 0;
}

.container-group_player {
  display: flex;
  justify-content: space-between;
  margin-right: 16px;
  margin-left: 16px;
}

.container-group_player .container-group_player_info {
  text-overflow: ellipsis;
  max-width: 80%;
}

.container-group_player .input-sets {
  width: 36px;
}

.v-text-field__details {
  display: none;
}

.max-w-80 {
  max-width: 80%;
}
</style>
