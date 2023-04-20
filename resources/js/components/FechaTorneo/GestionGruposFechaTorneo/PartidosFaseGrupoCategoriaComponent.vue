<template>
    <div>
        <v-card>
          <v-card-title>Fase de grupos</v-card-title>
          <v-card-text>
            <v-container>
              <v-row v-for="grupo in categoria.listaGrupos" :key="grupo.nombre">
                <v-col>
                  <h3>Grupo {{ grupo.nombre }}</h3>
                  <v-row cols="12">
                    <v-col v-for="partido in grupo.partidos" :key="partido.id" md="4" sm="6">
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


                    </v-col>
                  </v-row>
                </v-col>

              </v-row>
            </v-container>
            <v-btn class="ml-2 mr-4" dark @click="[generarLlaves()]" color="blue">Generar
              llaves</v-btn>
            </v-card-text>
        </v-card>
    </div>
</template>


<script>
export default {
    props : ["categoria"],
    methods: {
        generarLlaves(){
          console.log('generando llaves..');
             this.$emit('generar-llaves', null);
        }
       
    },

}
</script>