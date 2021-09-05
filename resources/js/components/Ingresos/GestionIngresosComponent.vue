<template>
  <div>
    <v-card>
      <v-card-title class="justify-center">
        <p><b>Ingresos/Gastos</b></p>
      </v-card-title>
      <v-btn class="mt-2 ml-2 mb-2 primary" @click="nuevoIngreso = true"
        >Nuevo ingreso o gasto</v-btn
      >

      <v-row class="ma-0 pa-0">
        <v-col class="mt-5 ml-2 pa-0 ma-0">
          <p><b>Filtrar por fechas: </b></p>
        </v-col>
      </v-row>

      <v-row>
        <v-col class="pa-0 mt-2" cols="12" md="1" offset="2">
          <p><b>Desde: </b></p>
        </v-col>

        <v-col class="pa-0" md="3">
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
        <v-col class="pa-0 mt-2" md="1">
          <p><b> Hasta: </b></p>
        </v-col>
        <v-col class="pa-0" md="3">
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
        <v-col md="2 pa-0">
          <v-btn class="primary">Filtrar</v-btn>
        </v-col>
      </v-row>
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
      fechaInicio: new Date('2021-01-01').toISOString().substr(0, 10),
      fechaFin: new Date().toISOString().substr(0, 10),
      formatoFecha: this.darFormatoFecha(
        new Date().toISOString().substr(0, 10)
      ),
      menuFechaInicio: false,
      menuFechaFin: false,
      nuevoIngreso: false,
      monto: 0,
      descripcion: "",
      search: "",
      headers: [
        { text: "Descripcion", value: "descripcion" },
        { text: "Monto", value: "monto" },
        { text: "Fecha", value: "fecha" },
        { text: "Puntos", value: "puntos" },
      ],
      montoRule: [
        (v) => !!v || "Importe requerido",
        (v) => (v >= -99999999 && v <= 999999999) || "Importe no valido",
      ],
    };
  },

  methods: {
    setNuevoIngreso() {
      if (this.monto !== 0 && this.descripcion !== "") {
        axios
          .post("/ingreso/setMonto", {
            monto: this.monto,
            descripcion: this.descripcion,
          })
          .then(
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
  },

  watch: {
    fechaInicio(val) {
      this.formatoFecha = this.darFormatoFecha(this.fechaInicio);
    },
    fechaFin(val) {
      this.formatoFecha = this.darFormatoFecha(this.fechaFin);
    },
  },

  mounted() {
        console.log(this.fechaFin)
    },
};
</script>