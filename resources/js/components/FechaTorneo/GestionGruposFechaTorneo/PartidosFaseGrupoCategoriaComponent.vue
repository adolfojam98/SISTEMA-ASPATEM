<template>
    <div>
        <v-card elevation="0">
          <v-card-title>Fase de grupos</v-card-title>
          <v-card-text>
            <v-container>
              <v-row v-for="grupo in categoria.listaGrupos" :key="grupo.nombre">
                <v-col>
                  <h3>Grupo {{ grupo.nombre }}</h3>
                  <v-row cols="12">
                    <v-col v-for="partido in grupo.partidos" :key="partido.id" md="3" sm="4" >
                      <partido :partido="partido"></partido>
                    </v-col>
                  </v-row>
                </v-col>

              </v-row>
            </v-container>
            <v-btn class="ml-2 mr-4"  @click="[generarLlaves()]" color="primary">Generar
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
             this.$emit('generar-llaves', null);
        }
       
    },

}
</script>