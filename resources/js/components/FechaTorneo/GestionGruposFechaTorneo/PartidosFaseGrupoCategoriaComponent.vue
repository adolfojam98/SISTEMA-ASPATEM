<template>
  <div>
    <v-card elevation="0">
      <center>
        <h2>Fase de grupos</h2>
      </center>
      <v-card-text>
          <v-row v-for="grupo in categoria.listaGrupos" :key="grupo.nombre">
            <v-col>
              <h3>Grupo {{ grupo.nombre }}</h3>
              <v-row cols="12" class="d-flex">
                <v-col v-for="partido in grupo.partidos" :key="partido.id" md="auto">
                  <div style="min-width: 200px">
                    <partido :partido="partido"></partido>
                  </div>
                </v-col>
              </v-row>
            </v-col>

          </v-row>
        <v-switch v-model="categoria.gruposConEliminatoria" label="Pasan todos a las llaves" class="ml-2 mt-0"></v-switch>
        <v-btn class="ml-2 mr-4" @click="[generarLlaves()]" color="primary">Generar
          llaves</v-btn>
      </v-card-text>
    </v-card>
  </div>
</template>


<script>
export default {
  props: ["categoria"],
  methods: {
    generarLlaves() {
      this.$emit('generar-llaves', null);
    }

  },

}
</script>