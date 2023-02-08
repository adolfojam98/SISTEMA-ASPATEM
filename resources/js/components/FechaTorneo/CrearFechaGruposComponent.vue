<template>
  <div>
    <v-toolbar>
      <template>
        <v-tabs v-model="tab" align-with-title>
          <v-tabs-slider color="yellow"></v-tabs-slider>

          <v-tab v-for="item in listaCategorias" :key="item.id">
            {{ item.nombre }}
          </v-tab>
        </v-tabs>
      </template>
    </v-toolbar>

    <v-tabs-items v-model="tab">
      <v-tab-item v-for="item in listaCategorias" :key="item.id">
        <v-card flat class="rounded-0">
          <v-container v-if="gruposGenerados(item)">
            <v-row>
              <v-form v-model="valid" lazy-validation>
                <v-col>
                  <v-text-field
                    label="Cantidad de Grupos"
                    v-model="item.cantidadGrupos"
                    :rules="cantidadGruposRules"
                    required
                    class="mb-0 ml-2"
                  ></v-text-field>
                  <p class="text--secondary d-flex justify-end">
                    Jugadores anotados: {{ item.jugadoresAnotados.length }}
                  </p>
                  <p class="text--secondary d-flex justify-end" v-if="item.cantidadGrupos && item.cantidadGrupos%1 == 0">
                    Cantidad minima de jugadores: {{ item.cantidadGrupos * 3 }}
                  </p>
                  <v-switch
                    v-model="item.gruposConEliminatoria"
                    label="Fase de grupos con eliminatoria"
                    class="ml-2 mt-0"
                  ></v-switch>

                  <v-btn
                    class="ml-2 mr-4"
                    dark
                    :disabled="!valid"
                    @click="[generarGrupos(item)]"
                    color="blue"
                    >Generar grupos</v-btn
                  >
                </v-col>
              </v-form>

              <v-col>
                <v-simple-table>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-left">Apellido</th>
                        <th class="text-left">Nombre</th>
                        <th class="text-left">DNI</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="jugador in item.jugadoresAnotados"
                        :key="jugador.id"
                      >
                        <td>{{ jugador.apellido }}</td>
                        <td>{{ jugador.nombre }}</td>
                        <td>{{ jugador.dni }}</td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
          </v-container>

          <div v-else>
            <div v-if="!item.llavesGeneradas">
              <v-btn
                class="ml-6 mt-6 mt-6"
                dark
                @click="deshacerGrupos(item)"
                color="blue"
                >Deshacer grupos</v-btn
              >

              <div class="container">
                <v-row class="fill-height" align="center" justify="center">
                  <v-card
                    elevation="12"
                    width="46%"
                    flat
                    class="lista-grupos rounded-0 mt-2 ml-2"
                    v-for="grupo in item.listaGrupos"
                    :key="grupo.nombre"
                  >
                    <v-card>
                      <center>
                        <h4 class="title">{{ grupo.nombre }}</h4>
                        <hr />
                      </center>

                      <ol class="mt-2">
                        <v-container
                          class="lista-jugadores d-flex flex-column flex-nowrap"
                          v-for="partido in grupo.partidos"
                          :key="partido.id"
                        >
                          <v-row>
                            <v-col cols="12" md="4">
                              <v-row class="mt-2 ml-2">
                                <v-tooltip bottom>
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-icon
                                      class="mb-4"
                                      v-if="partido.jugador1.dni"
                                      v-bind="attrs"
                                      v-on="on"
                                      >mdi-account-question</v-icon
                                    >
                                  </template>
                                  <span v-if="partido.jugador1.dni">
                                    {{ partido.jugador1.nombre }}
                                    {{ partido.jugador1.dni }}</span
                                  >
                                </v-tooltip>

                                <p class="ml-1">
                                  {{ partido.jugador1.apellido }}
                                </p>
                              </v-row>
                            </v-col>

                            <v-col md="2">
                              <v-text-field
                                min="0"
                                max="7"
                                oninput="validity.valid||(value='');"
                                hide-details
                                v-model="partido.setsJugador1"
                                solo
                                dense
                                style="width: 100px"
                                type="number"
                                label="Sets"
                              />
                            </v-col>

                            <v-col md="2">
                              <v-text-field
                                min="0"
                                max="7"
                                oninput="validity.valid||(value='');"
                                hide-details
                                v-model="partido.setsJugador2"
                                solo
                                dense
                                style="width: 100px"
                                type="number"
                                label="Sets"
                              />
                            </v-col>

                            <v-col>
                              <v-row class="mt-2">
                                <p class="align-right mr-1">
                                  {{ partido.jugador2.apellido }}
                                </p>

                                <v-tooltip bottom>
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-icon
                                      class="mb-4 mr-2"
                                      align="right"
                                      v-if="partido.jugador2.dni"
                                      v-bind="attrs"
                                      v-on="on"
                                      >mdi-account-question</v-icon
                                    >
                                  </template>
                                  <span v-if="partido.jugador2.dni">
                                    {{ partido.jugador2.nombre }}
                                    {{ partido.jugador2.dni }}</span
                                  >
                                </v-tooltip>
                              </v-row>
                            </v-col>
                          </v-row>
                        </v-container>
                      </ol>
                    </v-card>
                  </v-card>
                </v-row>
              </div>

              <!-- <v-row class="mt-6">
                <v-card
                  v-for="grupo in item.listaGrupos"
                  :key="grupo.nombre"
                  flat
                  class="rounded-0"
                >
                  <v-col cols="12">
                    <v-card class="mt-6 mb-6 ml-12 mr-12">
                      <center>
                        <h1>{{ grupo.nombre }}</h1>
                      </center>
                      <v-divider></v-divider>

                      <v-container
                        v-for="partido in grupo.partidos"
                        :key="partido.id"
                      >
                        <v-row>
                          <v-col>
                            <v-row>
                              <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                  <h4 class="mt-2 ml-2">
                                    {{ partido.jugador1.apellido }}
                                    <v-icon
                                      class="mb-1"
                                      v-bind="attrs"
                                      v-on="on"
                                      >mdi-account-question</v-icon
                                    >
                                  </h4>
                                </template>
                                <span
                                  >{{ partido.jugador1.nombre }}
                                  -
                                  {{ partido.jugador1.dni }}</span
                                >
                              </v-tooltip>

                              <v-select
                                class="ml-4 mr-2"
                                :items="cantidadSets"
                                v-model="partido.setsJugador1"
                                align="rigth"
                                solo
                                dense
                                style="width: 40px"
                              ></v-select>

                              <v-select
                                class="ml-2 mr-2"
                                align="left"
                                :items="cantidadSets"
                                v-model="partido.setsJugador2"
                                dense
                                solo
                                style="width: 40px"
                              ></v-select>

                              <v-tooltip bottom right>
                                <template v-slot:activator="{ on, attrs }">
                                  <h4 class="mt-2 ml-2">
                                    {{ partido.jugador2.apellido }}
                                    <v-icon
                                      class="mb-1 mr-2"
                                      v-bind="attrs"
                                      v-on="on"
                                      >mdi-account-question</v-icon
                                    >
                                  </h4>
                                </template>
                                <span
                                  >{{ partido.jugador2.nombre }}
                                  -
                                  {{ partido.jugador2.dni }}</span
                                >
                              </v-tooltip>
                            </v-row>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card>
                  </v-col>
                        </div>

                        <div v-if="!item.llavesGeneradas">
                        <center>
                            <v-btn
                                class="center mb-6"
                                dark
                                @click= validarPartidos(item)
                                color="blue"
                            >
                                Generar llaves
                            </v-btn>
                        </center>
                        </div>

                        <div v-else>
                            <v-btn
                            class="ml-6 mt-6 mt-6"
                            dark
                            @click="deshacerLlaves(item)"
                            color="blue"
                            >Deshacer llaves</v-btn
                        >
                        </div>

                        <generar-llaves
                            v-if="item.llavesGeneradas"
                            :categoria="item"
                        ></generar-llaves>
                    </div>
                </v-card>
              </v-row> -->
            </div>

            <div v-if="!item.llavesGeneradas">
              <center>
                <v-btn
                  class="center mb-6 mt-4"
                  dark
                  @click="validarPartidos(item)"
                  color="blue"
                >
                  Generar llaves
                </v-btn>
              </center>
            </div>

            <div v-else>
              <v-btn
                class="ml-6 mt-6 mt-6 mb-6"
                dark
                @click="deshacerLlaves(item)"
                color="blue"
                >Deshacer llaves</v-btn
              >

              <resultados-grupos v-if="item.llavesGeneradas" :categoria="item">
              </resultados-grupos>
            </div>

            <generar-llaves
              v-if="item.llavesGeneradas"
              :categoria="item"
            ></generar-llaves>
          </div>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>

<style scoped>
ol {
  margin: 0px;
  padding: 0px;
}
ul {
  margin: 0px;
  padding: 0px;
}
.align-right {
  display: block;
  text-decoration: none;
  text-align: right;
  width: 65%;
}
</style>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      tab: null,
      valid: false,
      cantidadSets: [0, 1, 2, 3, 4, 5, 6, 7],
      cantidadGruposRules: [
        (v) => !!v || "Cantidad de grupos requerido",
        (v) => /^([0-9]*)?[0-9]+$/.test(v) || "Deben ser solo numeros enteros",
      ],
    };
  },

  computed: {
    ...mapState("crearFecha", ["listaCategorias"]),
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    validarPartidos(item) {
      const grupos = item.listaGrupos;
      let valid = true;

      grupos.forEach((grupo) => {
        console.log(grupo);
        grupo.partidos.forEach((partido) => {
          if (partido.setsJugador1 == partido.setsJugador2) {
            this.callSnackbar([
              "Existe al menos un partido con empate",
              "warning",
            ]);
            valid = false;
          }
          if (partido.setsJugador1 == null || partido.setsJugador1 == '' || partido.setsJugador2 == null || partido.setsJugador2 == '') {
            this.callSnackbar([
              "Existe al menos un partido sin resultado",
              "warning",
            ]);
            valid = false;
          }
        });
      });
      item.llavesGeneradas = valid;
    },
    gruposGenerados(categoria) {
      return !categoria.gruposGenerados;
    },
    generarGrupos(categoria) {
      console.log("Ejecucionn generarGrupos");
      if (
        categoria.jugadoresAnotados.length /
          parseInt(categoria.cantidadGrupos) >=
        3
      ) {
        const letras = [
          "A",
          "B",
          "C",
          "D",
          "E",
          "F",
          "G",
          "H",
          "I",
          "J",
          "K",
          "L",
          "M",
          "N",
          "O",
          "P",
          "Q",
          "R",
          "S",
          "T",
          "U",
          "V",
          "W",
          "X",
          "Y",
          "Z",
        ];
        for (let i = 0; i < categoria.cantidadGrupos; i++) {
          const unGrupo = {
            nombre: letras[i],
            jugadoresDelGrupo: [],
            partidos: [],
          };
          categoria.listaGrupos.push(unGrupo);
        }

        categoria.jugadoresAnotados = categoria.jugadoresAnotados.sort(
          (a, b) => b.pivot.puntos - a.pivot.puntos
        );

        console.log(categoria.jugadoresAnotados);

        const indiceMaxGrupos = categoria.listaGrupos.length - 1;
        let indiceGrupo = 0;
        let alReves = false;

        categoria.jugadoresAnotados.forEach(function (jugador, indiceJugador) {
          console.log(alReves);

          categoria.listaGrupos[indiceGrupo].jugadoresDelGrupo.push(jugador);
          console.log(indiceGrupo);
          if (alReves) {
            indiceGrupo--;
            if (indiceGrupo < 0) {
              indiceGrupo++;
              alReves = false;
            }
          } else {
            indiceGrupo++;
            if (indiceGrupo > indiceMaxGrupos) {
              indiceGrupo--;
              alReves = true;
            }
          }
        });

        this.generarPartidosGrupos(categoria.listaGrupos);
        categoria.gruposGenerados = true;
        this.callSnackbar(["Grupos generados con exito", "success"]);
      } else {
        this.callSnackbar([
          "La cantidad de jugadores es insuficiente para la cantidad de grupos",
          "warning",
        ]);
      }
    },

    deshacerGrupos(categoria) {
      console.log("Ejecucion deshacerGrupos");
      categoria.gruposGenerados = false;
      categoria.listaGrupos = [];
    },
    deshacerLlaves(categoria) {
      categoria.partidosLlaves = [];
      categoria.llavesGeneradas = false;
    },

    generarPartidosGrupos(grupos) {
      console.log("generarPartidosGrupos");
      var IDPartido = 0;
      grupos.forEach((grupo) => {
        for (var i = 0; i < grupo.jugadoresDelGrupo.length; i++) {
          for (var j = 0; j < grupo.jugadoresDelGrupo.length; j++) {
            if (j > i) {
              var partido = {
                id: IDPartido,
                jugador1: grupo.jugadoresDelGrupo[i],
                jugador2: grupo.jugadoresDelGrupo[j],
                setsJugador1: null,
                setsJugador2: null,
              };
              IDPartido += 1;
              grupo.partidos.push(partido);
            }
          }
        }
      });
    },
  },
};
</script>
