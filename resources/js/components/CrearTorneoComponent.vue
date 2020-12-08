  <template>
  <div>
    <v-stepper v-model="e6" vertical>
      <v-stepper-step :complete="e6 > 1" editable step="1">
        Nombre y Categorias
      </v-stepper-step>

      <v-stepper-content step="1">
        <v-card color="#546E7A" class="mb-12" min-height="360px">
          <v-row>
            <v-col cols="8" md="4">
              <v-container>
                <v-form v-model="valid" lazy-validation>
                  <v-card
                  color="#90A4AE"
                  elevation="4"
                  class="rounded-sm"
                  >
                  <v-text-field
                  dense
                  dark
                    filled
                    color="#212121"
                    v-model="nombreTorneo"
                    class="subheading font-weight-bold"
                    label="Nombre del Torneo"
                    :rules="nombreTorneoRules"
                    required
                  ></v-text-field>
                  </v-card>

                  <!--<v-checkbox
                      v-model="gruposConEliminatoria"
                      class="subheading font-weight-bold"
                      label="Fase de grupos con eliminatoria - esto va en fechas no aca"
                  ></v-checkbox>-->

                  <v-spacer></v-spacer>
                  <v-btn
                    v-if="!nuevaCategoria"
                    dark
                    color="#212121"
                  block

                    v-model="nuevaCategoria"
                    @click="[(nuevaCategoria = !nuevaCategoria)]"
                  >
                    <v-icon>mdi-plus</v-icon>
                    Nueva categoria
                  </v-btn>

                <v-card v-if="nuevaCategoria"
                color="#90A4AE"
                elevation="4"
                class="rounded-b-xl"
                >
                  <div v-if="nuevaCategoria">
                    <v-text-field
                    dark
                      color="#212121"
                      v-model="nombreNuevaCategoria"
                      :rules="nombreCategoriaRules"
                      class="subheading font-weight-bold"
                      label="Nombre de la Categoria"
                      required
                    ></v-text-field>
                    <v-text-field
                    dark
                      color="#212121"
                      v-model="puntosMinimos"
                      :rules="puntosRules"
                      class="subheading font-weight-bold"
                      label="Puntos Minimos de la Categoria"
                      required
                    ></v-text-field>

                    <v-btn
                      block
                      class="rounded-pill"
                        color="primary"
                      v-model="nuevaCategoria"
                      @click="
                        [
                          ((nuevaCategoria = !nuevaCategoria),
                          agregarCategoria()),
                        ]
                      "
                      :disabled="!valid"
                      >Agregar</v-btn
                    >

                    <v-btn
                      block
                       color="error"
                       class="rounded-pill mt-3 mb-3"
                    
                      v-model="nuevaCategoria"
                      @click="
                        [
                          ((nuevaCategoria = !nuevaCategoria),
                          (puntosMinimos = null),
                          (nombreNuevaCategoria = '')),
                        ]
                      "
                      >Cancelar</v-btn
                    >
                  </div>
                  </v-card>
                </v-form>
              </v-container>
            </v-col>

            <v-col>
              <v-container :fluid="true">
                <v-simple-table dark>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-center">Categoria</th>
                        <th class="text-center">Rango de puntos</th>
                        <th class="text-center">Eliminar</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(categoria, indice) in arrayCategorias"
                        :key="categoria.nombre"
                      >
                        <td>
                          <center>{{ categoria.nombre }}</center>
                        </td>

                        <td v-if="arrayCategorias[indice + 1] != null">
                          <center>
                            {{ categoria.puntosMinimo }} -
                            {{ arrayCategorias[indice + 1].puntosMinimo - 1 }}
                          </center>
                        </td>
                        <td v-if="arrayCategorias[indice + 1] == null">
                          <center>{{ categoria.puntosMinimo }} - ∞</center>
                        </td>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <center>
                              <v-icon
                                v-bind="attrs"
                                v-on="on"
                                @click="[eliminarCategoria(indice)]"
                                color="error"
                                >mdi-delete</v-icon
                              >
                            </center>
                          </template>
                          <span>Eliminar</span>
                        </v-tooltip>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-container>
            </v-col>
          </v-row>
        </v-card>
        <v-btn
          color="primary"
          @click="e6 = 2"
          :rules="rulesCategorias()"
          :disabled="!valid"
        >
          Continuar
        </v-btn>
      </v-stepper-content>

      <v-stepper-step :complete="e6 > 2" editable step="2">
        Importar lista de jugadores
      </v-stepper-step>

      <v-stepper-content step="2">
        <v-card color="#546E7A" class="mb-12" min-height="390">
          <v-row>
            <v-col cols="8" md="4">
              <v-container></v-container>
              <v-container>
                <importar-jugadores @nuevosJugadores = 'nuevosJugadores = $event'></importar-jugadores>

                <v-container></v-container>
                
                <v-btn
                block
                  v-if="!nuevoJugador"
                  dark
                  color="#212121"
                  @click="[(nuevoJugador = !nuevoJugador)]"
                >
                  <v-icon></v-icon>
                  Agregar jugador a la lista
                </v-btn>

                <v-card
                color="#90A4AE"
                elevation="4"
                class="rounded-b-xl"
                >
                <v-form v-if="nuevoJugador" v-model="valid" lazy-validation>
                  <v-text-field
                  dark
                    color="#212121"
                    v-model="apellidoJugador"
                    :rules="aynRules"
                    class="subheading font-weight-bold"
                    label="Apellido del jugador"
                    required
                  ></v-text-field>
                  <v-text-field
                  dark
                    color="#212121"
                    v-model="nombreJugador"
                    :rules="aynRules"
                    class="subheading font-weight-bold"
                    label="Nombre del jugador"
                    required
                  ></v-text-field>
                  <v-text-field
                  dark
                    color="#212121"
                    v-model="dniJugador"
                    :rules="dniRules"
                    class="subheading font-weight-bold"
                    label="DNI del jugador"
                    required
                  ></v-text-field>
                  <v-text-field
                  dark
                    color="#212121"
                    v-model="puntosJugador"
                    class="subheading font-weight-bold"
                    label="Puntos del jugador"
                    :rules="puntosRules"
                    required
                  ></v-text-field>

                  <v-btn
                    dark
                    block
                    class="rounded-pill"
                    color="primary"
                    v-model="nuevoJugador"
                    :disabled="!valid"
                    @click="
                      [((nuevoJugador = !nuevoJugador), agregarJugador())]
                    "
                    >Agregar</v-btn
                  >

                  <v-btn
                  block
                    color="error"
                    class="rounded-pill mt-3 mb-3"
                    dark
                    v-model="nuevoJugador"
                    @click="
                      [
                        ((apellidoJugador = ''),
                        (nombreJugador = ''),
                        (dniJugador = null),
                        (puntosJugador = null),
                        (nuevoJugador = false)),
                      ]
                    "
                    >Cancelar</v-btn
                  >
                </v-form>

                </v-card>
              </v-container>
            </v-col>

            <v-col>
              <template>
                <v-data-table
                  dense
                  :headers="headers"
                  :items="listaJugadores"
                  item-key="dni"
                  class="elevation-1 mr-2"
                  dark
                  :items-per-page="5"
                >
                  <template v-slot:[`item.actions`]="{ item }">
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on, attrs }">
                        <v-icon
                          class="ml-4"
                          v-bind="attrs"
                          v-on="on"
                          @click="eliminarJugador(item)"
                          color="error"
                          >mdi-delete</v-icon
                        >
                      </template>
                      <span>Eliminar</span>
                    </v-tooltip>
                  </template>
                </v-data-table>
              </template>
            </v-col>
          </v-row>
        </v-card>
        <v-btn color="primary" @click="e6 = 3" :disabled="!valid">
          Continue
        </v-btn>
      </v-stepper-content>

      <v-stepper-step step="3" editable>
        Configuracion de puntos
      </v-stepper-step>
      <v-stepper-content step="3">
        <v-card color="#546E7A" class="mb-12" min-height="625px">
          <v-container>
            <v-card class="elevation-2" color="#90A4AE" dark>
              <v-form v-model="valid" lazy-validation>
                <center>
                  <h2 style="color: '#FAFAFA'">
                    Jugadores de la misma categoria
                  </h2>
                </center>

                <v-row>
                  <v-col cols="12" md="4"> </v-col>

                  <v-col> El ganador suma </v-col>

                  <v-col> El perdedor resta </v-col>
                </v-row>

                <v-row>
                  <v-col cols="12" md="4">
                    <v-container>
                      El ganador tiene mayor nivel de juego
                    </v-container>
                  </v-col>

                  <v-col>
                    <v-text-field
                      :rules="puntosRules"
                      class="mr-2"
                      solo
                      label="Ej: 8"
                      v-model="gestionPuntos.mismaCat_MayorNivel_Ganador"
                    ></v-text-field>
                  </v-col>

                  <v-col>
                    <v-text-field
                      :rules="puntosRules"
                      class="mr-2"
                      solo
                      label="Ej: 8"
                      v-model="gestionPuntos.mismaCat_MayorNivel_Perdedor"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="12" md="4">
                    <v-container>
                      El ganador tiene menor nivel de juego
                    </v-container>
                  </v-col>

                  <v-col>
                    <v-text-field
                      :rules="puntosRules"
                      class="mr-2"
                      solo
                      label="Ej: 11"
                      v-model="gestionPuntos.mismaCat_MenorNivel_Ganador"
                    ></v-text-field>
                  </v-col>

                  <v-col>
                    <v-text-field
                      :rules="puntosRules"
                      class="mr-2"
                      solo
                      label="Ej: 11"
                      v-model="gestionPuntos.mismaCat_MenorNivel_Perdedor"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-form>
            </v-card>
          </v-container>

          <!--segunda tabla-->
          <v-container>
            <v-card class="elevation-2" color="#90A4AE" dark>
              <v-form v-model="valid" lazy-validation>
                <center>
                  <h2 style="color: '#FAFAFA'">
                    Jugadores de diferentes categorias
                  </h2>
                </center>

                <v-row>
                  <v-col cols="12" md="4"> </v-col>

                  <v-col> El ganador suma </v-col>

                  <v-col> El perdedor resta </v-col>
                </v-row>

                <v-row>
                  <v-col cols="12" md="4">
                    <v-container>
                      El ganador tiene mayor categoria
                    </v-container>
                  </v-col>

                  <v-col>
                    <v-text-field
                      :rules="puntosRules"
                      class="mr-2"
                      solo
                      label="Ej: 5"
                      v-model="gestionPuntos.diferenteCat_MayorNivel_Ganador"
                    ></v-text-field>
                  </v-col>

                  <v-col>
                    <v-text-field
                      :rules="puntosRules"
                      class="mr-2"
                      solo
                      label="Ej: 0"
                      v-model="gestionPuntos.diferenteCat_MayorNivel_Perdedor"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="12" md="4">
                    <v-container>
                      El ganador tiene menor categoria
                    </v-container>
                  </v-col>

                  <v-col>
                    <v-text-field
                      :rules="puntosRules"
                      class="mr-2"
                      solo
                      label="Ej: 11"
                      v-model="gestionPuntos.diferenteCat_MenorNivel_Ganador"
                    ></v-text-field>
                  </v-col>

                  <v-col>
                    <v-text-field
                      :rules="puntosRules"
                      class="mr-2"
                      solo
                      label="Ej: 0"
                      v-model="gestionPuntos.diferenteCat_MenorNivel_Perdedor"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-form>
            </v-card>
          </v-container>
        </v-card>
        <v-btn color="primary" @click="generarTorneo()" :disabled="!valid">
          Guardar
        </v-btn>
      </v-stepper-content>
    </v-stepper>

    <v-snackbar v-model="snackbar" timeout="3000">
      <div v-text="message"></div>

      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<!--como wea hacer para ver el progreso de un socio en los torneos si son independientes (relacionamos los terneos?)
podria ser sino una nueva fecha de un torneo anterior entonces aqui aplicamos una relacion-->



<script>
export default {
  data: () => ({
    e6: 1,
    valid: false,
    nombreTorneo: "",
    nuevaCategoria: false,
    arrayCategorias: [],
    nombreNuevaCategoria: "",
    puntosMinimos: null,
    importarJugadores: false,
    listaJugadores: [],
    apellidoJugador: "",
    nombreJugador: "",
    dniJugador: null,
    puntosJugador: null,
    nuevoJugador: false,
    snackbar: false,
    message: "",
    nuevosJugadores: [],
    gestionPuntos: {
      mismaCat_MayorNivel_Ganador: null,
      mismaCat_MayorNivel_Perdedor: null,
      mismaCat_MenorNivel_Ganador: null,
      mismaCat_MenorNivel_Perdedor: null,
      diferenteCat_MayorNivel_Ganador: null,
      diferenteCat_MayorNivel_Perdedor: null,
      diferenteCat_MenorNivel_Ganador: null,
      diferenteCat_MenorNivel_Perdedor: null,
    },

    headers: [
      { text: "Apellido", value: "apellido" },
      { text: "Nombre", value: "nombre" },
      { text: "DNI", value: "dni" },
      { text: "Puntos", value: "puntos" },
      {
        text: "Eliminar",
        value: "actions",
        sortable: false,
        filterable: false,
      },
    ],

    puntosRules: [
      (v) => !!v || "Puntos requeridos",
      (v) =>
        /^([0-9]*)?[0-9]+$/.test(v) || "Los puntos deben ser numeros enteros",
    ],

    nombreTorneoRules: [
      (v) => !!v || "Nombre requerido",
      (v) =>
        /^([A-Za-z]([A-Za-z0-9]*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(v) ||
        "Nombre invalido",
      (v) => v.length <= 30 || "Demasiado largo",
    ],

    nombreCategoriaRules: [
      (v) => !!v || "Nombre requerido",
      (v) =>
        /^([A-Za-z]([A-Za-z0-9]*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(v) ||
        "Nombre invalido",
      (v) => v.length <= 30 || "Demasiado largo",
    ],

    aynRules: [
      (v) => !!v || "Campo obligatorio",
      (v) =>
        /^([A-Z][a-z]*([ \t\n\r\f]?[A-Za-z])*)+$/.test(v) || "Nombre invalido",
      (v) => v.length <= 30 || "Demasiado largo",
    ],

    dniRules: [
      (v) => !!v || "Campo obligatorio",
      (v) => /^([0-9]*)+$/.test(v) || "Solo numeros",
      (v) =>
        (!!v && v.length == 8) || "El DNI debe estar compuesto por 8 numeros",
    ],

  }),

  methods: {
    agregarCategoria() {
      if (this.arrayCategorias.length < 6) {
        var categoria = {
          nombre: this.nombreNuevaCategoria,
          puntosMinimo: this.puntosMinimos,
          puntosMaximo: 999999,
        };

        this.arrayCategorias.push(categoria);

        if (this.arrayCategorias.length > 1) {
          this.arrayCategorias[this.arrayCategorias.length - 2].puntosMaximo =
            categoria.puntosMinimo - 1;
        }
        this.nombreNuevaCategoria = "";
        this.puntosMinimos = "";
      } else {
        this.message = "Limite de categorias alcanzado";
        this.snackbar = true;
      }
    },

    eliminarCategoria(indice) {
      this.arrayCategorias.splice(indice, 1);
    },

    agregarJugador() {
      var jugador = {
        apellido: this.apellidoJugador,
        nombre: this.nombreJugador,
        dni: this.dniJugador,
        puntos: this.puntosJugador,
      };
      this.listaJugadores.push(jugador);
      this.apellidoJugador = "";
      this.nombreJugador = "";
      this.dniJugador = null;
      this.puntosJugador = null;
    },

    eliminarJugador(indice) {
      this.listaJugadores.splice(indice, 1);
    },

    generarTorneo() {
      axios
        .post("/torneo", {
          nombreTorneo: this.nombreTorneo,
          gestionPuntos: this.gestionPuntos,
        })
        .then((res) => {
        axios.post("/jugadores",{
          id_torneo : res.data,
          jugadores : this.listaJugadores
        }).then((res)=> console.log(res.data))



          axios.post("/categorias", {
              id_torneo: res.data,
              categorias: this.arrayCategorias,
            })
            .then((res) => {
              
            });
        }).catch((error)=> console.log(error));
    },

    rulesCategorias() {
      if (this.arrayCategorias.length >= 1) {
        return true;
      } else {
        return false;
      }
    },
  },
  watch: {
    nuevosJugadores: function () {
      console.log(this.nuevosJugadores);
       this.nuevosJugadores.forEach((e) => {
        this.listaJugadores.push(e);
       });
    },
  },


};
</script>

<style>
</style>