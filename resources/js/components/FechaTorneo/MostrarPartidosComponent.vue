<template>
  <div>
    <v-container>
      <v-row>
        <v-col
          v-if="
            cantidadPartidos != 1 &&
            cantidadPartidos != 3 &&
            cantidadPartidos != 7 &&
            cantidadPartidos != 15 &&
            cantidadPartidos != 31 &&
            cantidadPartidos != 63
          "
        >
          <h1>Partidos de ajuste</h1>
          <v-row v-for="partido in partidos" :key="partido.id">
            <v-col v-if="partido.fase == 'ajuste'">
              <v-card class="mb-3" width="255px" height="200px" elevation="6">
                <v-col>
                  <div v-if="esNodoHoja(partido.id)">
                    <v-row
                      ><v-col cols="12" md="8" class="pa-2">
                        <v-select
                          single-line
                          @input="
                            [
                              calcularSiguienteJugador(),
                              limpiarSiguientePartido(partido),
                            ]
                          "
                          v-model="partido.jugador1"
                          :items="jugadores"
                          :item-text="nombreCompleto"
                          return-object
                          dense
                        ></v-select>
                      </v-col>

                      <v-col md="4" class="pa-2">
                        <v-text-field
                          single-line
                          type="number"
                          min="0"
                          max="7"
                          oninput="validity.valid||(value='');"
                          class="soloNumeros pa-0 ma-0"
                          style="width: 80px"
                          label="Sets"
                          v-model="partido.set1"
                          v-on:change="
                            [
                              calcularSiguienteJugador(),
                              limpiarSiguientePartido(partido),
                            ]
                          "
                        /> </v-col
                    ></v-row>

                    <v-row
                      ><v-col cols="12" md="8" class="pa-2">
                        <v-select
                          single-line
                          @input="
                            [
                              calcularSiguienteJugador(),
                              limpiarSiguientePartido(partido),
                            ]
                          "
                          v-model="partido.jugador2"
                          :items="jugadores"
                          :item-text="nombreCompleto"
                          return-object
                          dense
                        ></v-select>
                      </v-col>

                      <v-col md="4" class="pa-2">
                        <v-text-field
                          single-line
                          type="number"
                          min="0"
                          max="7"
                          oninput="validity.valid||(value='');"
                          class="soloNumeros pa-0 ma-0"
                          style="width: 80px"
                          v-model="partido.set2"
                          label="Sets"
                          v-on:change="
                            [
                              calcularSiguienteJugador(),
                              limpiarSiguientePartido(partido),
                            ]
                          "
                        /> </v-col
                    ></v-row>

                    <v-row>
                      <v-col cols="12" md="8" class="pa-2">
                        <center>
                          <p><b>Sig. partido:</b></p>
                        </center> </v-col
                      ><v-col class="pa-2">
                        <v-select
                          class="pa-0 ma-0"
                          single-line
                          @input="
                            [
                              calcularNodosHojas(),
                              calcularSiguienteJugador(),
                              limpiarSiguientePartido(partido),
                            ]
                          "
                          :items="posiblesSigsPartidos"
                          v-model="partido.sigPartidoID"
                          :rules="[maximoDosPartidosApuntando]"
                          dense
                        ></v-select> </v-col
                    ></v-row>
                  </div>
                </v-col>
              </v-card>
            </v-col>
          </v-row>
        </v-col>

        <v-col v-if="cantidadPartidos >= 125">
          <h1>64vo de final</h1>
          <v-row v-for="partido in partidos" :key="partido.id">
            <v-col v-if="partido.fase == '64vo de final'">
              <un-partido
                :partido="partido"
                :jugadores="jugadores"
                :partidos="partidos"
                :cantidadPartidos="cantidadPartidos"
                :nodosHojasID="nodosHojasID"
                :nodosMediasHojasID="nodosMediasHojasID"
                :posiblesSigsPartidos="posiblesSigsPartidos"
              ></un-partido>
            </v-col>
          </v-row>
        </v-col>

        <v-col v-if="cantidadPartidos >= 63">
          <h1>32vo de final'</h1>
          <v-row v-for="partido in partidos" :key="partido.id">
            <v-col v-if="partido.fase == '32vo de final'">
              <un-partido
                :partido="partido"
                :jugadores="jugadores"
                :partidos="partidos"
                :cantidadPartidos="cantidadPartidos"
                :nodosHojasID="nodosHojasID"
                :nodosMediasHojasID="nodosMediasHojasID"
                :posiblesSigsPartidos="posiblesSigsPartidos"
              ></un-partido>
            </v-col>
          </v-row>
        </v-col>

        <v-col v-if="cantidadPartidos >= 31">
          <h1>16vo de final</h1>
          <v-row v-for="partido in partidos" :key="partido.id">
            <v-col v-if="partido.fase == '16vo de final'">
              <un-partido
                :partido="partido"
                :jugadores="jugadores"
                :partidos="partidos"
                :cantidadPartidos="cantidadPartidos"
                :nodosHojasID="nodosHojasID"
                :nodosMediasHojasID="nodosMediasHojasID"
                :posiblesSigsPartidos="posiblesSigsPartidos"
              ></un-partido>
            </v-col>
          </v-row>
        </v-col>

        <v-col v-if="cantidadPartidos >= 15">
          <h1>8vo de final</h1>
          <v-row v-for="partido in partidos" :key="partido.id">
            <v-col v-if="partido.fase == '8vo de final'">
              <un-partido
                :partido="partido"
                :jugadores="jugadores"
                :partidos="partidos"
                :cantidadPartidos="cantidadPartidos"
                :nodosHojasID="nodosHojasID"
                :nodosMediasHojasID="nodosMediasHojasID"
                :posiblesSigsPartidos="posiblesSigsPartidos"
              ></un-partido>
            </v-col>
          </v-row>
        </v-col>

        <v-col v-if="cantidadPartidos >= 7">
          <h1>4tos de final</h1>
          <v-row v-for="partido in partidos" :key="partido.id">
            <v-col v-if="partido.fase == '4tos de final'">
              <un-partido
                :partido="partido"
                :jugadores="jugadores"
                :partidos="partidos"
                :cantidadPartidos="cantidadPartidos"
                :nodosHojasID="nodosHojasID"
                :nodosMediasHojasID="nodosMediasHojasID"
                :posiblesSigsPartidos="posiblesSigsPartidos"
              ></un-partido>
            </v-col>
          </v-row>
        </v-col>

        <v-col v-if="cantidadPartidos >= 3">
          <h1>Semifinal</h1>
          <v-row v-for="partido in partidos" :key="partido.id">
            <v-col v-if="partido.fase == 'semifinal'">
              <un-partido
                :partido="partido"
                :jugadores="jugadores"
                :partidos="partidos"
                :cantidadPartidos="cantidadPartidos"
                :nodosHojasID="nodosHojasID"
                :nodosMediasHojasID="nodosMediasHojasID"
                :posiblesSigsPartidos="posiblesSigsPartidos"
              ></un-partido>
            </v-col>
          </v-row>
        </v-col>

        <v-col v-if="cantidadPartidos >= 1">
          <h1>Final</h1>
          <v-row v-for="partido in partidos" :key="partido.id">
            <v-col v-if="partido.fase == 'final'">
              <un-partido
                :partido="partido"
                :jugadores="jugadores"
                :partidos="partidos"
                :cantidadPartidos="cantidadPartidos"
                :nodosHojasID="nodosHojasID"
                :nodosMediasHojasID="nodosMediasHojasID"
                :posiblesSigsPartidos="posiblesSigsPartidos"
              ></un-partido>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  props: ["partidos", "jugadores"],
  data: () => ({
    cantidadPartidos: 0,
    nodosHojasID: [],
    nodosMediasHojasID: [],
    posiblesSigsPartidos: [],
  }),
  created() {
    this.cantidadPartidos = this.partidos.length;
  },

  mounted() {
    this.calcularNodosHojas();
  },

  methods: {
    nombreCompleto: (item) => item.apellido + " " + item.nombre,
    calcularNodosHojas() {
      this.nodosHojasID = [];
      this.nodosMediasHojasID = [];

      let me = this;

      var cantVecesEncontrado = 0;
      this.partidos.forEach((partido1) => {
        this.partidos.forEach((partido2) => {
          if (partido2.sigPartidoID == partido1.id) {
            cantVecesEncontrado++;
          }

          if (me.partidos[me.partidos.length - 1] == partido2) {
            if (cantVecesEncontrado == 0) {
              me.nodosHojasID.push(partido1.id);
              console.log("push");
            } else if (cantVecesEncontrado == 1) {
              me.nodosMediasHojasID.push(partido1.id);
            }
            cantVecesEncontrado = 0;
          }
        });
      });

      if (this.posiblesSigsPartidos.length == 0)
        this.calcularPosiblesSigPartidos();
    },

    esNodoHoja(id) {
      //TODO hay dos partidos que apuntan a este
      console.log(this.nodosHojasID);
      return this.nodosHojasID.includes(id);
    },

    esMedioNodoHoja(id) {
      //TODO solo un partidos que apuntan a este
      return this.nodosMediasHojasID.includes(id);
    },

    sigIDaEntero(partido) {
      partido.sigPartidoID = parseInt(partido.sigPartidoID);
      console.log(partido);
      this.calcularNodosHojas();
    },

    calcularPosiblesSigPartidos() {
      let me = this;
      this.posiblesSigsPartidos = [];

      this.nodosHojasID.forEach((nodo) => {
        if (
          me.partidos.find((partido) => partido.id == nodo).sigPartidoID != null
        ) {
          me.posiblesSigsPartidos.push(nodo);
        }
      });
      this.nodosMediasHojasID.forEach((nodo) => {
        me.posiblesSigsPartidos.push(nodo);
      });
      if (me.posiblesSigsPartidos.length === 0) {
        me.posiblesSigsPartidos.push(0);
      }
    },

    calcularSiguienteJugador() {
      let me = this;

      this.partidos.forEach((partido) => {
        if (
          partido.jugador1 != null &&
          partido.jugador2 != null &&
          partido.set1 != null &&
          partido.set2 != null &&
          partido.sigPartidoID != null
        ) {
          //TODO calculamos el ganador primero...
          if (partido.set1 > partido.set2) {
            var ganador = partido.jugador1;
          } else if (partido.set2 > partido.set1) {
            var ganador = partido.jugador2;
          }

          //TODO calculamos donde va el ganador y lo asignamos
          var sigPartido = me.partidos.find(
            (unPartido) => unPartido.id == partido.sigPartidoID
          );
          if (me.nodosMediasHojasID.includes(sigPartido.id)) {
            sigPartido.jugador2 = ganador;
          } else if (
            sigPartido.jugador1 == partido.jugador1 ||
            sigPartido.jugador1 == partido.jugador2
          ) {
            sigPartido.jugador1 = ganador;
          } else if (
            sigPartido.jugador2 == partido.jugador1 ||
            sigPartido.jugador2 == partido.jugador2
          ) {
            sigPartido.jugador2 = ganador;
          } else if (sigPartido.jugador1 == null) {
            sigPartido.jugador1 = ganador;
          } else if (sigPartido.jugador2 == null) {
            sigPartido.jugador2 = ganador;
          }
        }
      });
    },

    maximoDosPartidosApuntando(sigID) {
      var cant = 0;
      this.partidos.forEach((partido) => {
        if (partido.sigPartidoID == sigID) cant++;
      });
      if (cant > 2) {
        return false;
      } else {
        return true;
      }
    },

    limpiarSiguientePartido(partido) {
      if (!this.maximoDosPartidosApuntando(partido.sigPartidoID)) {
        setTimeout(function () {
          partido.sigPartidoID = null;
        }, 1500);
      } else {
        this.partidos.forEach((unPartido) => {
          if (unPartido.id == partido.sigPartidoID) {
            unPartido.jugador1 = null;
            unPartido.jugador2 = null;
            unPartido.set1 = null;
            unPartido.set2 = null;
            partido = this.partidos.find(
              (element) => element.id == partido.sigPartidoID
            );
            if (partido.sigPartidoID != null) {
              this.limpiarSiguientePartido(partido);
            }
          }
        });
        this.calcularSiguienteJugador();
      }
    },
  },

  watch: {
    partidos: function () {
      this.calcularNodosHojas();
      this.calcularSiguienteJugador();
    },
  },
};
</script>