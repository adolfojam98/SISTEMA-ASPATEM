<template>
  <div>
    <v-container>
        <v-row>

            <v-flex v-if="cantidadPartidos != 1 && cantidadPartidos != 3 && cantidadPartidos != 7 && cantidadPartidos != 15 && cantidadPartidos != 31 && cantidadPartidos != 6">
      <h1>Partidos de ajuste</h1>
      <v-row v-for="partido in partidos" :key="partido.id">
        <v-col cols="12" md="6" v-if="partido.fase == 'ajuste'">
          <v-card>
              qwer
            <v-divider></v-divider>
            qwer
          </v-card>
        </v-col>
      </v-row>
        </v-flex>

        <v-flex v-if="cantidadPartidos >= 125" align-self="center">
      <h1>64vo de final</h1>
      <v-row v-for="partido in partidos" :key="partido.id">
        <v-col cols="12" md="6" v-if="partido.fase == '64vo de final'">
          <v-card>
              qwer
            <v-divider></v-divider>
            qwer
          </v-card>
        </v-col>
      </v-row>
      </v-flex>

        <v-flex v-if="cantidadPartidos >= 63" align-self="center">
      <h1>32vo de final'</h1>
      <v-row v-for="partido in partidos" :key="partido.id">
        <v-col cols="12" md="6" v-if="partido.fase == '32vo de final'">
          <v-card>
              qwer
            <v-divider></v-divider>
            qwer
          </v-card>
        </v-col>
      </v-row>
      </v-flex>

        <v-flex v-if="cantidadPartidos >= 31" align-self="center">
      <h1>16vo de final</h1>
      <v-row v-for="partido in partidos" :key="partido.id">
        <v-col cols="12" md="6" v-if="partido.fase == '16vo de final'">
          <v-card>
              qwer
            <v-divider></v-divider>
            qwer
          </v-card>
        </v-col>
      </v-row>
      </v-flex>

        <v-flex v-if="cantidadPartidos >= 15" align-self="center">
      <h1>8vo de final</h1>
      <v-row v-for="partido in partidos" :key="partido.id">
        <v-col cols="12" md="6" v-if="partido.fase == '8vo de final'">
          <v-card>
              qwer
            <v-divider></v-divider>
            qwer
          </v-card>
        </v-col>
      </v-row>
      </v-flex>

        <v-flex v-if="cantidadPartidos >= 7" align-self="center">
      <h1>4tos de final</h1>
      <v-row v-for="partido in partidos" :key="partido.id">
        <v-col cols="12" md="6" v-if="partido.fase == '4tos de final'">
          <v-card>
              qwer
            <v-divider></v-divider>
            qwer
          </v-card>
        </v-col>
      </v-row>
      </v-flex>


        <v-flex v-if="cantidadPartidos >= 3" align-self="center">
      <h1>Semifinal</h1>
      <v-row v-for="partido in partidos" :key="partido.id">
        <v-col cols="12" md="6" v-if="partido.fase == 'semifinal'">
          <v-card>
              qwer
            <v-divider></v-divider>
            qwer
          </v-card>
        </v-col>
      </v-row>
      </v-flex>
      
      <v-flex v-if="cantidadPartidos >= 1" align-self="center">
      <h1>Final</h1>
      <v-row v-for="partido in partidos" :key="partido.id">
        <v-col cols="12" md="6" v-if="partido.fase == 'final'">
          <v-card>
              qwerasdfasdf
            <v-divider></v-divider>
            qwer
          </v-card>
        </v-col>
      </v-row>
      </v-flex>

      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  props: ["partidos"],
  data: () => ({
        cantidadPartidos : 0,
    }),
    created(){
        this.cantidadPartidos = this.partidos.length;
    }
};
</script>