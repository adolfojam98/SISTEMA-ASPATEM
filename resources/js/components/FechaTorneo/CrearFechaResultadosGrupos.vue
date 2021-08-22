<template>
  <div class="body">
    <div class="container">
      <v-row class="fill-height" align="center" justify="center">
        <v-card
          flat
          class="lista-grupos rounded-0"
          v-for="grupo in categoria.listaGrupos"
          :key="grupo.nombre"
        >
          <v-col>
            <v-card>
              <div>
                <center>
                  <h4 class="title">{{ grupo.nombre }}</h4>
                  <hr />
                </center>

                <ol>
                  <div
                    class="lista-jugadores"
                    v-for="jugador in grupo.jugadoresDelGrupo"
                    :key="jugador.id"
                  >
                    <li>
                      {{ jugador.nombre }}
                      {{ jugador.apellido }}

                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon
                            v-if="jugador.dni"
                            class="mb-1"
                            v-bind="attrs"
                            v-on="on"
                            >mdi-account-question</v-icon
                          >
                        </template>
                        <span v-if="jugador.dni">{{ jugador.dni }}</span>
                      </v-tooltip>
                    </li>
                  </div>
                </ol>
              </div>
            </v-card>
          </v-col>
        </v-card>
      </v-row>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  props: ["categoria"],
  computed: {
    ...mapState("crearFecha", ["listaCategorias"]),
  },

  beforeMount() {
    this.ordenar();
  },

  methods: {
    ordenar() {

      let self = this;
      self.categoria.listaGrupos.forEach((grupo) => {

        grupo.jugadoresDelGrupo.sort(function (jugador1, jugador2) {

          if (self.partidosGanados(grupo, jugador1) > self.partidosGanados(grupo, jugador2)) {
            return -1;
          } 
          else if (self.partidosGanados(grupo, jugador1) < self.partidosGanados(grupo, jugador2)) {
            return 1;
          } 
          else if(self.calcularGanadorEntre(grupo, jugador1, jugador2)) {
              return -1;
            } 
            else { return 1; }
        })
      })
    },

    partidosGanados(grupo, jugador){
        let count = 0;
        grupo.partidos.forEach((partido) => {

            if(partido.jugador1 == jugador && partido.setsJugador1 > partido.setsJugador2) { count++; }
            else if(partido.jugador2 == jugador && partido.setsJugador2 > partido.setsJugador1) { count++; }
        })
        return count;
    },

    calcularGanadorEntre(grupo, jugador1,jugador2){ //TODO true si gana el primero pasado por parametro
        grupo.partidos.forEach((partido) => {

            if(partido.jugador1 == jugador1 && partido.jugador2 == jugador2){ 
                if(partido.setsJugador1 > partido.setsJugador2) { return true }
                else { return false }
            } 
            if(partido.jugador2 == jugador1 && partido.jugador1 == jugador2){
                if(partido.setsJugador1 < partido.setsJugador2) { return true }
                else { return false }
            }
        })
    }


  },
};
</script>