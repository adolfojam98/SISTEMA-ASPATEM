<template>
  <div class="body">
    <div class="container mt-2">
      <v-row class="fill-height justify-content-space-evenly" align="center">
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
                    </li>

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
                        <span v-if="jugador.dni"
                          >{{ jugador.dni }} - Partidos Ganados:
                          {{ partidosGanados(grupo, jugador) }}</span
                        >
                      </v-tooltip>
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
<style>
  .justify-content-space-evenly {
    justify-content: space-evenly;
  }

  .lista-jugadores {
    display: flex;
    min-width: 80px;
    justify-content: space-between;
  }

</style>

<script>
import { mapState } from "vuex";
export default {
  props: ["categoria"],
  computed: {
    ...mapState("crearFecha", ["listaCategorias"]),
  },

  mounted() {
    this.ordenar();
  },

  methods: {
    ordenar() {
      let self = this;
      self.categoria.listaGrupos.forEach((grupo) => { //TODO se usan los mismos criterios que en PartidosFaseLlaveCategoriaComponent
        let jugadores = grupo.jugadoresDelGrupo.map(jugador => {
                return { ...jugador, partidosGanados: 0 };
            });

            grupo.partidos.forEach(partido => {
                const idGanador = this.getIdGanadorPartido(partido);
                let jugadorGanador = jugadores.find(jugador => jugador.usuario_id == idGanador);
                jugadorGanador.partidosGanados++;
            });
            // ordenamos
            jugadores.sort((jugadorA, jugadorB) => {
            // ordenar por partidosGanados (mayor a menor)
                if (jugadorB.partidosGanados !== jugadorA.partidosGanados) {
                    return jugadorB.partidosGanados - jugadorA.partidosGanados;
                } 
                else {
                    if(this.esEmpateDoble(jugadores, jugadorA.partidosGanados)) {
                      console.log('empate doble')
                      // si tienen la misma cantidad de partidosGanados y solo empataron estos dos jugadores, nos fijamos el ganador
                        const partidoEntreJugadores = grupo.partidos.find(partido =>
                            (partido.jugador1.usuario_id === jugadorA.usuario_id && partido.jugador2.usuario_id === jugadorB.usuario_id) ||
                            (partido.jugador1.usuario_id === jugadorB.usuario_id && partido.jugador2.usuario_id === jugadorA.usuario_id)
                        );

                        // ordenamos por resultado del partido (mayor a menor)
                        return this.getIdGanadorPartido(partidoEntreJugadores) === jugadorA.usuario_id ? -1 : 1;
                    } 
                    else {
                      console.log('empate triple o mayor')
                      //calculamos el coeficinte y pasa primero el de mayor coeficiente
                      const coeficienteJugadorA = this.getCoeficiente(jugadorA, grupo.partidos)
                      const coeficienteJugadorB = this.getCoeficiente(jugadorB, grupo.partidos)

                      // ordenamos por coeficiente (mayor a menor)
                      return coeficienteJugadorA > coeficienteJugadorB ? -1 : 1;
                    }
                }
            });

            grupo.jugadoresDelGrupo = jugadores
      });

      return self.categoria.listaGrupos;
    },

    esEmpateDoble(jugadores, partidosGanados) {
      let jugadoresEmpatados = 0;

      jugadores?.forEach(jugador => {
        if(jugador.partidosGanados == partidosGanados) {
            jugadoresEmpatados++
        }
      })

      if(jugadoresEmpatados == 2) {
        return true
      }

      return false
    },

    getCoeficiente(jugador, partidos) {
      const setGanados = this.getSetGanados(jugador, partidos);
      const setPerdidos = this.getSetPerdidos(jugador, partidos);

      return setPerdidos == 0 ? setGanados : (setGanados / setPerdidos)
    },

    getIdGanadorPartido(partido) {
            const setsJugador1 = parseInt(partido.setsJugador1);
            const setsJugador2 = parseInt(partido.setsJugador2);

            if(setsJugador1 > setsJugador2) {
                return partido.jugador1.usuario_id;
            } else {
                return partido.jugador2.usuario_id;
            }
        },

        getSetGanados(jugador, partidos) {
            let totalSetsGanados = 0
            partidos?.forEach(partido => {
                if(jugador.usuario_id == partido.jugador1.usuario_id) {
                    totalSetsGanados += parseInt(partido.setsJugador1)
                }

                if(jugador.usuario_id == partido.jugador2.usuario_id) {
                    totalSetsGanados += parseInt(partido.setsJugador2)
                }
            });

            return totalSetsGanados;
        },

        getSetPerdidos(jugador, partidos) {
            let totalSetsPerdidos = 0
            partidos?.forEach(partido => {
                if(jugador.usuario_id == partido.jugador1.usuario_id) {
                    totalSetsPerdidos += parseInt(partido.setsJugador2) //los sets perdidos serian los del otro jugador
                }

                if(jugador.usuario_id == partido.jugador2.usuario_id) {
                    totalSetsPerdidos += parseInt(partido.setsJugador1)
                }
            });

            return totalSetsPerdidos;
        },

    partidosGanados(grupo, jugador) {
      let count = 0;
      grupo.partidos.forEach((partido) => {
        if (
          partido.jugador1.usuario_id == jugador.usuario_id &&
          partido.setsJugador1 > partido.setsJugador2
        ) {
          count++;
        } else if (
          partido.jugador2.usuario_id == jugador.usuario_id &&
          partido.setsJugador2 > partido.setsJugador1
        ) {
          count++;
        }
      });
      return count;
    },

    calcularGanadorEntre(grupo, jugador1, jugador2) {
      //TODO true si gana el primero pasado por parametro
      grupo.partidos.forEach((partido) => {
        if (partido.jugador1 == jugador1 && partido.jugador2 == jugador2) {
          if (partido.setsJugador1 > partido.setsJugador2) {
            return true;
          } else {
            return false;
          }
        }
        if (partido.jugador2 == jugador1 && partido.jugador1 == jugador2) {
          if (partido.setsJugador1 < partido.setsJugador2) {
            return true;
          } else {
            return false;
          }
        }
      });
    },
  },
};
</script>