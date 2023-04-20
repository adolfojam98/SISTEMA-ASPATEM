<template>
  <div>
  <v-card outlined flat>
    <v-card-text>
      <v-row>
        <v-col>
          <p>{{ partido.jugador1.nombre }} {{ partido.jugador1.apellido }}
          </p>
          <v-text-field label="Sets" v-model="partido.setsJugador1" type="number"></v-text-field>
        </v-col>
        <v-col>
          <p>{{ partido.jugador2.nombre }} {{ partido.jugador2.apellido }}
          </p>
          <v-text-field label="Sets" v-model="partido.setsJugador2" type="number"></v-text-field>
        </v-col>
      </v-row>
    </v-card-text>

  </v-card>

  </div>
</template>

<script>
export default {
    props : ["partido"]
}
</script>

 