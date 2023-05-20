<template>
  <div>
      <div class="d-flex justify-center mb-5">
        <h2>Ingresos</h2>
      </div>

            <v-container>
            <v-btn class="primary mb-5" @click="nuevoIngreso=true">Nuevo ingreso</v-btn>
                      <v-row class="ma-0 pa-0 align-end">
                        <div class="mr-5 mb-1" style="width: 225px">
                <small>Desde: </small>
                <v-menu
                  v-model="menuFechaInicio"
                  transition="scale-transition"
                  min-width="200px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      style="width: 150px"
                      v-model="fechaInicio"
                      dense
                      append-icon="mdi-calendar"
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
              </div>
              <div class="mr-5 mb-1" style="width: 225px">
                <small> Hasta: </small>
                <v-menu
                  v-model="menuFechaFin"
                  transition="scale-transition"
                  min-width="200px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      style="width: 150px"
                      v-model="fechaFin"
                      dense
                      append-icon="mdi-calendar"
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
              </div>
                        <div class="mr-5 mt-1" style="width: 225px">
                          <v-select
                            v-model="tipo"
                            :items="tipoIngreso"
                            label="Tipo de ingreso"
                            v-on:change="resetTorneoFecha()"
                          ></v-select>
                        </div>
                        <div v-if="tipo === 'Torneos' || tipo === 'Fechas'" class="mr-5 mt-1" style="width: 225px">
                          <v-select
                            v-model="torneoId"
                            :items="torneos"
                            item-text="nombre"
                            item-value="id"
                            :disabled="tipo === 'Cuotas' || tipo === 'Todos'"
                            label="Torneo"
                            v-on:change="getFechas()"
                          ></v-select>
                          
                        </div>
                        <div v-if="tipo === 'Fechas'" class="mr-5 mt-1" style="width: 225px">
                          <v-select
                            v-model="fechaId"
                            :items="fechas"
                            item-text="nombre"
                            item-value="id"
                            :disabled="tipo === 'Cuotas' || tipo === 'Torneos' || torneoId === null || tipo === 'Todos'"
                            label="Fecha"
                          ></v-select>
                          
                        </div>
                        <v-col class="d-flex align-center mb-2">
                          <v-btn
                            @click="filtro(false)"
                            class="primary"
                            >Filtrar</v-btn
                          >
                        </v-col>
                      </v-row>                    
            </v-container>


      
      <v-container>
          <p><b>Total: ${{totalIngresos}}</b></p>
          <v-card>
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
        </v-card>
      </v-container>

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
      torneoId: null,
      torneos: [],
      fechaId: null,
      fechas: [],
      transacciones: [],
      search: "",
      sortBy: 'fecha',
      sortDesc: true,
      headers: [
        { text: "Tipo", value: "tipo", width: '75px' },
        { text: "Monto", value: "monto", width: '75px' },
        { text: "Descripción", value: "descripcion", width: '300px' },
        { text: "Fecha", value: "fecha", width: '90px' },
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
          .then(() => {
              this.tipo = "Todos"
              this.torneoId = null
              this.fechaId = null
              this.filtro(false)
              this.monto = 0
              this.descripcion = ""
              this.nuevoIngreso = false
            }
          );
      }
    },
    darFormatoFechaNew(fecha) {
      if (!fecha || typeof fecha === "undefined") {
        return null;
      }

      const date = new Date(fecha);
      const day = date.getDate().toString().padStart(2, '0');
      const month = (date.getMonth() + 1).toString().padStart(2, '0');
      const year = date.getFullYear().toString();
      const outputDateString = `${day}/${month}/${year}`;

      return outputDateString;
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
      if (this.torneoId !== null) {
        axios.get(`/torneo/${this.torneoId}/fechas`).then((res) => {
          this.fechas = res.data;
          this.fechas.unshift({
            nombre: "Todos",
            id: 0,
          });
        });
      } else {
        this.getAllFechas();
        this.fechaId = null;
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

axios.get('/ingresos',{
  params: {
    fecha_inicio : this.fechaInicio,
    fecha_fin : this.fechaFin,
    tipo : this.tipo,
    torneo_id : this.torneoId,
    fecha_id : this.fechaId
  }
}).then(res => {
  console.log(res);
  this.transacciones =res.data;
  this.calcularTotal();
}
);

return;

    },
    resetTorneoFecha() {
      if (this.tipo === "Cuotas" || this.tipo === "Otros") {
        this.torneoId = null;
        this.fechaId = null;
        this.getAllFechas();
      } else if (this.tipo === "Torneo") {
        this.fechaId = null;
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