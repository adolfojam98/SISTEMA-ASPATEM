<template>
  <v-card>
    <v-card elevation="0">
      <v-select
        :value="torneoSeleccionado"
        @input="setTorneoSeleccionado"
        :items="torneos"
        item-text="nombre"
        return-object
        filled
        label="Seleccione un torneo"
        class="subheading font-weight-bold"
        v-on:change="getFechas(), getInfoGraficasCategorias()"
      ></v-select>

      <center>
        <v-btn
          v-if="!editPuntos"
          @click="[(editPuntos = true)]"
          :disabled="!torneoSeleccionado"
          color="primary"
          class="white--text"
        >
          Configuración de puntos
        </v-btn>
      </center>
      <div v-if="editPuntos">
        <v-card class="elevation-2 ma-4">
          <v-form>
            <center>
              <h2>Jugadores de la misma categoria</h2>
              <hr />
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
                  type="number"
                  min="0"
                  max="999"
                  class="mr-2"
                  solo
                  label="Ej: 8"
                  v-model="
                    torneoSeleccionado.misma_categoria_mayor_nivel_ganador
                  "
                ></v-text-field>
              </v-col>

              <v-col>
                <v-text-field
                  type="number"
                  min="0"
                  max="999"
                  class="mr-2"
                  solo
                  label="Ej: 8"
                  v-model="
                    torneoSeleccionado.misma_categoria_mayor_nivel_perdedor
                  "
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
                  type="number"
                  min="0"
                  max="999"
                  class="mr-2"
                  solo
                  label="Ej: 11"
                  v-model="
                    torneoSeleccionado.misma_categoria_menor_nivel_ganador
                  "
                ></v-text-field>
              </v-col>

              <v-col>
                <v-text-field
                  type="number"
                  min="0"
                  max="999"
                  class="mr-2"
                  solo
                  label="Ej: 11"
                  v-model="
                    torneoSeleccionado.misma_categoria_menor_nivel_perdedor
                  "
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card>
        <v-card class="elevation-2 ma-4">
          <v-form>
            <center>
              <h2>Jugadores de diferentes categorias</h2>
            </center>

            <v-row>
              <v-col cols="12" md="4"> </v-col>

              <v-col> El ganador suma </v-col>

              <v-col> El perdedor resta </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-container> El ganador tiene mayor categoria </v-container>
              </v-col>

              <v-col>
                <v-text-field
                  type="number"
                  min="0"
                  max="999"
                  class="mr-2"
                  solo
                  label="Ej: 5"
                  v-model="torneoSeleccionado.mayor_categoria_ganador"
                ></v-text-field>
              </v-col>

              <v-col>
                <v-text-field
                  type="number"
                  min="0"
                  max="999"
                  class="mr-2"
                  solo
                  label="Ej: 0"
                  v-model="torneoSeleccionado.mayor_categoria_perdedor"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-container> El ganador tiene menor categoria </v-container>
              </v-col>

              <v-col>
                <v-text-field
                  type="number"
                  min="0"
                  max="999"
                  class="mr-2"
                  solo
                  label="Ej: 11"
                  v-model="torneoSeleccionado.menor_categoria_ganador"
                ></v-text-field>
              </v-col>

              <v-col>
                <v-text-field
                  type="number"
                  min="0"
                  max="999"
                  class="mr-2"
                  solo
                  label="Ej: 0"
                  v-model="torneoSeleccionado.menor_categoria_perdedor"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card>
        <center>
          <v-btn class="mt-2" @click="[(editPuntos = false)]"> Cancelar </v-btn>
          <v-btn
            class="mt-2"
            color="primary"
            @click="[(editPuntos = false), guardarPuntosTorneo()]"
          >
            Guardar
          </v-btn>
        </center>
      </div>
    </v-card>
    <v-card class="ma-4">
      <v-text-field
        class="ma-2"
        v-model="search"
        append-icon="mdi-magnify"
        label="Buscar"
        :items="torneos"
        single-line
        hide-details
      ></v-text-field>
      <v-data-table
        :headers="headers"
        :items="fechas"
        :search="search"
        @click:row="goToViewResumenFecha"
        class="row-pointer"
      >
        <template v-slot:[`item.ingresos`]="{ item }">
          <p class="mt-4">${{ item.ingresos }}</p>

          <p />
        </template>
      </v-data-table>
    </v-card>

    <grafico-donut v-if="renderComponent && infoGraficas.data && infoGraficas.labels.length > 0 && infoGraficas.series.length > 0" v-bind:info="infoGraficas" />
        
  </v-card>
</template>
<style lang="css" scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
</style>

<script>
import { mapMutations, mapState } from "vuex";
export default {
  props: [],

  data() {
    return {
      editPuntos: false,
      verFechaModal: false,
      search: "",
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Cantidad de participantes", value: "participantes" },
        { text: "Ingresos", value: "ingresos" },
        { text: "Cerrada", value: "date" },
      ],
      renderComponent: true,
    };
  },

  computed: {
    ...mapState("gestionarTorneos", [
      "torneos",
      "torneoSeleccionado",
      "fechas",
      "infoGraficas"
    ]),
  },
  methods: {
    ...mapMutations("gestionarTorneos", [
      "setTorneos",
      "setTorneoSeleccionado",
      "setFechas",
      "setInfoGraficas"
    ]),

    guardarPuntosTorneo() {
      axios.post("/torneo/puntos", {
        torneo: this.torneoSeleccionado,
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

    forceRerender() {
        this.renderComponent = false;

        this.$nextTick(() => {
          this.renderComponent = true;
        });
    },

    async getInfoGraficasCategorias() {
        await axios.get(`/torneo/${this.torneoSeleccionado.id}/getInfoGraficasCategorias`).then((res) => {
            this.setInfoGraficas({data: res.data})
        });
        this.formateCategories()
    },

    formateCategories() {
        if(this.infoGraficas.data) {
            let infoGraficas = {
                title: "Cantidad de jugadores por categorias",
                labels: [],
                series: []
            }
            this.infoGraficas.data.forEach(category => {
                infoGraficas.labels.push(category.nombre)
                infoGraficas.series.push(category.total_jugadores)
            });
            this.setInfoGraficas(infoGraficas)
            this.forceRerender()
        }
    }
  },

  created() {
    axios.get("/torneos").then((res) => {
      this.setTorneos(res.data);
    });
  },
};
</script>
