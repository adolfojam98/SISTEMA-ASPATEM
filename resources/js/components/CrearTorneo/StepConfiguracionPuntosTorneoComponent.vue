<template>
  <div>
    <v-card class="d-flex elevation-0" justify="center">
      <v-form>
        <v-card-title class="headline" style="justify-content: center">
          Jugadores de la misma categoria
        </v-card-title>
        <v-container>
          <v-row>
            <v-col cols="12" sm="6">
              <center>
                <h5 class="mb-6">El ganador tiene mayor nivel de juego</h5>
              </center>

              <v-row justify="center">
                <v-col sm="5">
                  <small>El ganador suma</small>
                  <v-text-field
                    :rules="puntosRules"
                    class="mr-2"
                    solo
                    label="Ej: 8"
                    v-model="gestionPuntos.mismaCat_MayorNivel_Ganador"
                  ></v-text-field>
                </v-col>

                <v-col sm="5">
                  <small>El perdedor resta</small>
                  <v-text-field
                    :rules="puntosRules"
                    class="mr-2"
                    solo
                    label="Ej: 8"
                    v-model="gestionPuntos.mismaCat_MayorNivel_Perdedor"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-col>

            <v-col cols="12" sm="6">
              <center>
                <h5 class="mb-6">El ganador tiene menor nivel de juego</h5>
              </center>

              <v-row justify="center">
                <v-col sm="5">
                  <small>El ganador suma</small>
                  <v-text-field
                    :rules="puntosRules"
                    class="mr-2"
                    solo
                    label="Ej: 11"
                    v-model="gestionPuntos.mismaCat_MenorNivel_Ganador"
                  ></v-text-field>
                </v-col>

                <v-col sm="5">
                  <small>El perdedor resta</small>
                  <v-text-field
                    :rules="puntosRules"
                    class="mr-2"
                    solo
                    label="Ej: 11"
                    v-model="gestionPuntos.mismaCat_MenorNivel_Perdedor"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </v-form>

      <hr />

      <v-form>
        <v-card-title class="headline" style="justify-content: center">
          Jugadores de diferentes categorias
        </v-card-title>
        <v-container>
          <v-row>
            <v-col cols="12" sm="6">
              <center>
                <h5 class="mb-6">El ganador tiene mayor categoria</h5>
              </center>

              <v-row justify="center">
                <v-col sm="5">
                  <small>El ganador suma</small>
                  <v-text-field
                    :rules="puntosRules"
                    class="mr-2"
                    solo
                    label="Ej: 5"
                    v-model="gestionPuntos.diferenteCat_MayorNivel_Ganador"
                  ></v-text-field>
                </v-col>

                <v-col sm="5">
                  <small>El perdedor resta</small>
                  <v-text-field
                    :rules="puntosRules"
                    class="mr-2"
                    solo
                    label="Ej: 0"
                    v-model="gestionPuntos.diferenteCat_MayorNivel_Perdedor"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-col>

            <v-col cols="12" sm="6">
              <center>
                <h5 class="mb-6">El ganador tiene menor categoria</h5>
              </center>

              <v-row justify="center">
                <v-col sm="5">
                  <small>El ganador suma</small>
                  <v-text-field
                    :rules="puntosRules"
                    class="mr-2"
                    solo
                    label="Ej: 11"
                    v-model="gestionPuntos.diferenteCat_MenorNivel_Ganador"
                  ></v-text-field>
                </v-col>

                <v-col sm="5">
                  <small>El perdedor resta</small>
                  <v-text-field
                    :rules="puntosRules"
                    class="mr-2"
                    solo
                    label="Ej: 0"
                    v-model="gestionPuntos.diferenteCat_MenorNivel_Perdedor"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </v-form>
    </v-card>
  </div>
</template>


<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      puntosRules: [
        (v) => !!v || "Puntos requeridos",
        (v) =>
          /^([0-9]*)?[0-9]+$/.test(v) || "Los puntos deben ser numeros enteros",
      ],
    };
  },

  methods: {},
  computed: {
    ...mapState("CrearTorneo", ["gestionPuntos"]),
  },
};
</script>