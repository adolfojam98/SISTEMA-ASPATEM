<template>
  <div>
    <v-card>
      <v-card-title class="justify-center">
        <p><b>Ingresos/Gastos</b></p>
      </v-card-title>
      <v-row>
        <v-col md="12">
          <v-card>
            <v-row>
              <v-col class="mt-2 ml-5 pl-3 pt-3" cols="12" md="1">
                <p><b>Desde: </b></p>
              </v-col>

              <v-col md="3">
                <v-menu
                  v-model="menuFechaInicio"
                  transition="scale-transition"
                  offset-y
                  min-width="200px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      style="width: 150px"
                      v-model="fechaInicio"
                      dense
                      append-icon="mdi-calendar"
                      outlined
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="fechaInicio"
                    @input="menuFechaInicio = false"
                  ></v-date-picker>
                </v-menu>
              </v-col>
              <v-col class="mt-2 ml-5" md="1">
                <p><b> Hasta: </b></p>
              </v-col>
              <v-col md="3" class="pa-0 pt-3">
                <v-menu
                  v-model="menuFechaFin"
                  transition="scale-transition"
                  offset-y
                  min-width="200px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      style="width: 150px"
                      v-model="fechaFin"
                      dense
                      append-icon="mdi-calendar"
                      outlined
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="fechaFin"
                    @input="menuFechaFin = false"
                  ></v-date-picker>
                </v-menu>
              </v-col>
              <v-col md="2">
                <v-btn
                  v-show="!showFiltrosAvanzados"
                  @click="filtro(true)"
                  class="primary"
                  >Filtrar</v-btn
                >
              </v-col>
            </v-row>
            <v-row>
              <v-col justify="left">
                <v-expansion-panels flat>
                  <v-expansion-panel
                    @click="showFiltrosAvanzados = !showFiltrosAvanzados"
                  >
                    <v-expansion-panel-header
                      ><b>Filtros avanzados</b></v-expansion-panel-header
                    >
                    <v-expansion-panel-content class="pa-0 ma-0">
                      <v-row class="ma-0 pa-0">
                        <v-col class="pa-0 mr-5">
                          <p class="mt-3">Tipo de ingreso:</p>
                          <v-select
                            v-model="tipo"
                            :items="tipoIngreso"
                            solo
                            label="Tipo"
                            v-on:change="resetTorneoFecha()"
                          ></v-select>
                        </v-col>
                        <v-col class="pa-0 mr-5">
                          <p class="mt-3">Torneo:</p>
                          <v-select
                            v-model="torneoId"
                            :items="torneos"
                            item-text="nombre"
                            item-value="id"
                            :disabled="tipo === 'Cuotas' || tipo === 'Todos'"
                            solo
                            label="Torneo"
                            v-on:change="getFechas()"
                          ></v-select>
                        </v-col>
                        <v-col class="pa-0">
                          <p class="mt-3">Fecha:</p>
                          <v-select
                            v-model="fechaId"
                            :items="fechas"
                            item-text="nombre"
                            item-value="id"
                            :disabled="tipo === 'Cuotas' || tipo === 'Torneos' || torneoId === 0 || tipo === 'Todos'"
                            solo
                            label="Fecha"
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col class="py-0">
                          <v-btn
                            v-show="showFiltrosAvanzados"
                            @click="filtro(false)"
                            class="primary"
                            >Filtrar</v-btn
                          >
                        </v-col>
                      </v-row>
                    </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
      <v-btn class="mt-5 ml-3 primary" @click="nuevoIngreso = true"
        >Nuevo ingreso o gasto</v-btn>
      <v-container>
          <p><b>Total: ${{totalIngresos}}</b></p>
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
          :items="transacciones"
          :search="search"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          item-key="id+tipo"
        >
          <template v-slot:[`item.monto`]="{ item }">
          <p class="mt-4">${{ item.monto }}</p>
        </template>
        </v-data-table>
      </v-container>
    </v-card>
    <v-dialog v-model="nuevoIngreso" max-width="350px">
      <v-card>
        <v-card-title class="justify-center">
          <p><b>Nuevo ingreso/gasto</b></p>
        </v-card-title>
        <v-form class="mr-3 ml-3 pa-3">
          <v-text-field
            prefix="$"
            min="-99999999"
            max="99999999"
            oninput="validity.valid||(value='');"
            single-line
            dense
            v-model="monto"
            :rules="montoRule"
            label="Monto"
            required
          />
          <v-textarea
            v-model="descripcion"
            :counter="100"
            rows="3"
            label="Descripción"
            required
          />
          <center class="mt-3">
            <v-btn
              class="mr-4"
              @click="[(nuevoIngreso = false), (monto = 0), (descripcion = '')]"
              >Cancelar</v-btn
            >
            <v-btn class="primary" @click="[setNuevoIngreso()]">Guardar</v-btn>
          </center>
        </v-form>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: [],
  data() {
    return {
      fechaFin: new Date().toISOString().substr(0, 10),
      fechaInicio: new Date(new Date().getFullYear(), 0, 1)
        .toISOString()
        .substr(0, 10),
      formatoFecha: this.darFormatoFecha(
        new Date().toISOString().substr(0, 10)
      ),
      totalIngresos: 0,
      menuFechaInicio: false,
      menuFechaFin: false,
      nuevoIngreso: false,
      monto: 0,
      descripcion: "",
      showFiltrosAvanzados: false,
      tipo: "Todos",
      tipoIngreso: ["Todos", "Cuotas", "Torneos", "Fechas", "Otros"],
      torneoId: 0,
      torneos: [],
      fechaId: 0,
      fechas: [],
      transacciones: [],
      search: "",
      sortBy: 'fecha',
      sortDesc: true,
      headers: [
        { text: "Tipo", value: "tipo" },
        { text: "Monto", value: "monto" },
        { text: "Descripción", value: "descripcion" },
        { text: "Fecha", value: "fecha" },
      ],
      montoRule: [
        (v) => !!v || "Importe requerido",
        (v) => (v >= -99999999 && v <= 999999999) || "Importe no valido",
      ],
    };
  },

  methods: {
    calcularTotal(){
        if(this.transacciones) {
            let montoTotal = 0;
            this.transacciones.forEach(transaccion => {
                montoTotal += parseFloat(transaccion.monto);
            });
            return this.totalIngresos = montoTotal;
        }
        else return this.totalIngresos = 0;
    },
    setNuevoIngreso() {
      if (this.monto !== 0 && this.descripcion !== "") {
        axios
          .post("/ingresos/setMonto", {
            monto: this.monto,
            descripcion: this.descripcion,
          })
          .then(
            (this.totalIngresos += parseFloat(this.monto)),
            (this.monto = 0),
            (this.descripcion = ""),
            (this.nuevoIngreso = false)
          );
      }
    },
    darFormatoFecha(fecha) {
      if (!fecha) return null;
      console.log(fecha);
      const [anio, mes, dia] = fecha.split("-");
      return `${dia}/${mes}/${anio}`;
    },
    getTorneos() {
      axios.get("/torneos").then((e) => {
        this.torneos = e.data;
        this.torneos.unshift({
          nombre: "Todos",
          id: 0,
        });
      });
    },
    getFechas() {
      if (this.torneoId !== 0) {
        axios.get(`/torneo/${this.torneoId}/fechas`).then((res) => {
          this.fechas = res.data;
          this.fechas.unshift({
            nombre: "Todos",
            id: 0,
          });
        });
      } else {
        this.getAllFechas();
        this.fechaId = 0;
      }
    },
    getAllFechas() {
      axios.get("/fechas").then((res) => {
        this.fechas = res.data;
        this.fechas.unshift({
          nombre: "Todos",
          id: 0,
        });
      });
    },
    filtro(simple) {
      simple
        ? axios
            .get(`/ingresos/${this.fechaInicio}/${this.fechaFin}`)
            .then((res) => {
              this.transacciones = res.data;
              this.calcularTotal();
            })
        : axios
            .get(
              `/ingresos/${this.fechaInicio}/${this.fechaFin}/${this.tipo}/${this.torneoId}/${this.fechaId}`
            )
            .then((res) => {
              this.transacciones = res.data;
              this.calcularTotal();
            });
    },
    resetTorneoFecha() {
      if (this.tipo === "Cuotas" || this.tipo === "Otros") {
        this.torneoId = 0;
        this.fechaId = 0;
        this.getAllFechas();
      } else if (this.tipo === "Torneo") {
        this.fechaId = 0;
        this.getAllFechas();
      }
    },
  },

  watch: {
    fechaInicio(val) {
      this.formatoFecha = this.darFormatoFecha(this.fechaInicio);
    },
    fechaFin(val) {
      this.formatoFecha = this.darFormatoFecha(this.fechaFin);
    },
  },

  created() {
    this.getTorneos();
    this.getAllFechas();
    this.filtro(true);
  },
};
</script>