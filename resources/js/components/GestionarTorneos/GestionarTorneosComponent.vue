<style>
.dialog-w-max-content {
  width: max-content;
}
</style>
<template>
  <div>
    <v-card elevation="0">
      <center>
        <h2>Gestión de torneos</h2>
      </center>

      <div class="d-flex align-center">
        <div class="mr-8">
          <v-select :value="torneoSeleccionado" @input="setTorneoSeleccionado" :items="torneos" item-text="nombre"
            return-object label="Seleccione un torneo" class="subheading font-weight-bold"
            v-on:change="getFechas(), getInfoGraficasCategorias(), getCategorias()"></v-select>
        </div>

        <div v-if="torneoSeleccionado" class="mr-8">
          <v-btn @click="[(editPuntos = true)]" :disabled="!torneoSeleccionado" color="primary" class="white--text">
            Configuración de puntos
          </v-btn>
        </div>

        <div v-if="torneoSeleccionado" class="mr-8">
          <agregar-jugador-torneo-fecha-modal
            @agregar-jugador="agregarNuevoJugadorTorneo" :categorias="categorias"></agregar-jugador-torneo-fecha-modal>
        </div>

        
        <div v-if="torneoSeleccionado">
          <importar-jugadores
                            @cargar-jugadores="cargarJugadoresImportados"
                            :categorias="categorias"
                        ></importar-jugadores>
        </div>
      </div>


      <v-dialog content-class="dialog-w-max-content" v-model="editPuntos" v-if="torneoSeleccionado"
        :style="{ width: 'max-content' }">
        <div class="d-flex" style="justify-content: center">
          <v-card class="elevation-0" :style="{ width: '800px' }">
            <v-form>
              <v-card-title class="headline" style="justify-content: center">
                Jugadores de la misma categoria
              </v-card-title>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6">
                    <center>
                      <h5 class="mb-6">
                        El ganador tiene mayor nivel de juego
                      </h5>
                    </center>

                    <v-row justify="center">
                      <v-col sm="5">
                        <small>El ganador suma</small>
                        <v-text-field type="number" min="0" max="999" class="mr-2" solo label="Ej: 8" v-model="torneoSeleccionado.misma_categoria_mayor_nivel_ganador
                          "></v-text-field>
                      </v-col>

                      <v-col sm="5">
                        <small>El perdedor resta</small>
                        <v-text-field type="number" min="0" max="999" class="mr-2" solo label="Ej: 8" v-model="torneoSeleccionado.misma_categoria_mayor_nivel_perdedor
                          "></v-text-field>
                      </v-col>
                    </v-row>
                  </v-col>

                  <v-col cols="12" sm="6">
                    <center>
                      <h5 class="mb-6">
                        El ganador tiene menor nivel de juego
                      </h5>
                    </center>

                    <v-row justify="center">
                      <v-col sm="5">
                        <small>El ganador suma</small>
                        <v-text-field type="number" min="0" max="999" class="mr-2" solo label="Ej: 11" v-model="torneoSeleccionado.misma_categoria_menor_nivel_ganador
                          "></v-text-field>
                      </v-col>

                      <v-col sm="5">
                        <small>El perdedor resta</small>
                        <v-text-field type="number" min="0" max="999" class="mr-2" solo label="Ej: 11" v-model="torneoSeleccionado.misma_categoria_menor_nivel_perdedor
                          "></v-text-field>
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
                        <v-text-field type="number" min="0" max="999" class="mr-2" solo label="Ej: 8"
                          v-model="torneoSeleccionado.mayor_categoria_ganador"></v-text-field>
                      </v-col>

                      <v-col sm="5">
                        <small>El perdedor resta</small>
                        <v-text-field type="number" min="0" max="999" class="mr-2" solo label="Ej: 8"
                          v-model="torneoSeleccionado.mayor_categoria_perdedor"></v-text-field>
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
                        <v-text-field type="number" min="0" max="999" class="mr-2" solo label="Ej: 11"
                          v-model="torneoSeleccionado.menor_categoria_ganador"></v-text-field>
                      </v-col>

                      <v-col sm="5">
                        <small>El perdedor resta</small>
                        <v-text-field type="number" min="0" max="999" class="mr-2" solo label="Ej: 11"
                          v-model="torneoSeleccionado.menor_categoria_perdedor"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>

            <div class="mb-5">
              <center>
                <v-btn @click="[(editPuntos = false)]"> Cancelar </v-btn>
                <v-btn class="ml-4" color="primary" @click="[(editPuntos = false), guardarPuntosTorneo()]">
                  Guardar
                </v-btn>
              </center>
            </div>
          </v-card>
        </div>
      </v-dialog>
    </v-card>

    <v-row>
      <v-col cols="6">
        <v-card class="ma-4" v-if="torneoSeleccionado">
          <v-text-field class="ma-2" v-model="search" append-icon="mdi-magnify" label="Buscar fecha" :items="torneos"
            single-line hide-details></v-text-field>
          <v-data-table :headers="headers" :items="fechas" :search="search" @click:row="goToViewResumenFecha"
            class="row-pointer">
            <template v-slot:[`item.ingresos`]="{ item }">
              <p class="mt-4">${{ item.ingresos }}</p>

              <p />
            </template>
            <template v-slot:[`item.date`]="{ item }">
              <p class="mt-4">{{ item.vigencia == 0 ? 'vigente' : 'no vigente' }}</p>

              <p />
            </template>
          </v-data-table>
        </v-card>
      </v-col>

      <v-col cols="6">
        <torneo-lista-jugadores v-if="torneoSeleccionado" />
      </v-col>
    </v-row>

    <grafico-donut v-if="renderComponent &&
      infoGraficas.data &&
      infoGraficas.labels.length > 0 &&
      infoGraficas.series.length > 0
      " v-bind:info="infoGraficas" />
  </div>
</template>
<style lang="css" scoped>
.row-pointer>>>tbody tr :hover {
  cursor: pointer;
}
</style>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  props: [],

  data() {
    return {
      categorias : [],
      editPuntos: false,
      verFechaModal: false,
      search: "",
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Cantidad de participantes", value: "participantes" },
        { text: "Ingresos", value: "ingresos" },
        { text: "Vigencia", value: "date" },
      ],
      renderComponent: true,
    };
  },

  computed: {
    ...mapState("gestionarTorneos", [
      "torneos",
      "torneoSeleccionado",
      "fechas",
      "infoGraficas",
    ]),
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    ...mapMutations("gestionarTorneos", [
      "setTorneos",
      "setTorneoSeleccionado",
      "setFechas",
      "setInfoGraficas",
    ]),
    getCategorias(){
    axios.get(`/torneos/${this.torneoSeleccionado.id}/categorias`).then(res => this.categorias = res.data);
    },

    guardarPuntosTorneo() {
      axios
        .post("/torneo/puntos", {
          torneo: this.torneoSeleccionado,
        })
        .then((response) => {
          this.callSnackbar(["Puntos actualizados con exito", "success"]);
        });
    },

    getFechas() {
      axios.get(`/torneo/${this.torneoSeleccionado.id}/fechas`).then((res) => {
        this.setFechas(res.data);
      });
    },

    goToViewResumenFecha(fecha) {
      this.$router.push({ path: `/resumen/torneo/fecha/${fecha.id}` });
    },
    async cargarJugadoresImportados(jugadores){
      try{
        const res = await axios.post("/jugadores", {
          id_torneo: this.torneoSeleccionado.id,
          jugadores: jugadores
        });
        this.notificarJugadorNuevo(); 
      }catch(e){

      }
      
    },

    forceRerender() {
      this.renderComponent = false;

      this.$nextTick(() => {
        this.renderComponent = true;
      });
    },
    async agregarNuevoJugadorTorneo(jugador) {
      try {
        const jugadores = await axios.post("/jugador", {
          id_torneo: this.torneoSeleccionado.id,
          jugadores: [jugador]
        });
        this.callSnackbar(['jugador se anotó correctamente', 'success']);
        this.notificarJugadorNuevo();
      } catch (error) {
        this.callSnackbar([error.response.data.message, 'error'])
      }
    },
    notificarJugadorNuevo() {
      this.setTorneoSeleccionado({ ...this.torneoSeleccionado });
    },

    async getInfoGraficasCategorias() {
      await axios
        .get(`/torneo/${this.torneoSeleccionado.id}/getInfoGraficasCategorias`)
        .then((res) => {
          this.setInfoGraficas({ data: res.data });
        });
      this.formateCategories();
    },

    formateCategories() {
      if (this.infoGraficas.data) {
        let infoGraficas = {
          title: "Cantidad de jugadores por categorias",
          labels: [],
          series: [],
        };
        this.infoGraficas.data.forEach((category) => {
          infoGraficas.labels.push(category.nombre);
          infoGraficas.series.push(category.total_jugadores);
        });
        this.setInfoGraficas(infoGraficas);
        this.forceRerender();
      }
    },
  },

  created() {
    axios.get("/torneos").then((res) => {
      this.setTorneos(res.data);
    });
  },
};
</script>
