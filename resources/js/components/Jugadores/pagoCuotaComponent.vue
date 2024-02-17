<template>
  <div>
    <v-card>
      <div class="pa-2" outlined style="background-color: lightgrey" tile>
        <h1 style="color: blue">
          <center>ASPATEM</center>
        </h1>
      </div>

      <div class="ml-3 mt-3">
        <div class="text-h6">Socio</div>
        <div class="text-body-1 ml-1">
          {{ usuario.nombre }} {{ usuario.apellido }}
        </div>

        <v-divider class="mt-3"></v-divider>

        <div class="text-h6">Mes al que corresponde</div>
        <div class="ml-1 text-body-1">{{ fecha && monthNames && monthNames[new Date(fecha).getMonth()] }} del {{ new
          Date(fecha).getFullYear() }}</div>

        <v-divider class="mt-3"></v-divider>

        <div class="text-h6">Detalles</div>
        <v-simple-table>
          <thead>
            <tr>
              <th class="text-left">nombre</th>
              <th class="text-left">descripcion</th>
              <th class="text-left">monto</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="detalle in cuota.cuota_detalle" :key="detalle.cuota_detalle_tipo[0].id">
              <td v-for="detalleTipo in detalle.cuota_detalle_tipo" :key="detalleTipo.id">
                {{ detalleTipo.nombre }}
              </td>
              <td>{{ detalle.descripcion }}</td>
              <td>${{ detalle.monto }}</td>
              <td v-if="detalleEsBorrable(detalle)">
                <v-btn @click="quitarDetalleCuota(detalle)" class="mx-2" fab dark small color="error">
                  <v-icon dark> mdi-minus </v-icon>
                </v-btn>
              </td>
            </tr>
            <tr>
              <td>
                <v-select v-model="detalleSeleccionado" :return-object="true" :items="tipoCuotasDetallesFaltantes"
                  item-text="nombre" label="Tipo de detalle" required></v-select>
              </td>
              <td>
                <v-text-field v-model="descripcion" label="Ingrese una descripcion" required></v-text-field>
              </td>
              <td v-if="detalleSeleccionado">
                ${{ montoDetalleSeleccionado }}
              </td>
              <td>
                <v-btn :disabled="!detalleSeleccionado" @click="agregarDetalleCuota" class="mx-2" fab dark small
                  color="success">
                  <v-icon dark> mdi-plus </v-icon>
                </v-btn>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <p class="font-weight-black">total:</p>
              </td>
              <td>${{ montoTotal }}</td>
            </tr>
          </tbody>
        </v-simple-table>

        <v-divider></v-divider>

        <div class="text-h6">Fecha de pago</div>

        <v-row no-gutters>
          <v-col dense cols="7">
            <v-menu v-model="menuFecha" transition="scale-transition" offset-y min-width="290px">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field v-model="formatoFecha" dense append-icon="mdi-calendar" outlined readonly v-bind="attrs"
                  v-on="on"></v-text-field>
              </template>
              <v-date-picker v-model="fecha" @input="menuFecha = false"></v-date-picker>
            </v-menu>
          </v-col>
        </v-row>

        <center>
          <v-btn @click="pagarCuota" large color="primary"> Pagar cuota </v-btn>
        </center>
        <br />
      </div>
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
//TODO ver el tema de que cuando queda el monto total menor a 0
export default {
  props: ["cuota", "usuario"],
  data() {
    return {
      fecha: new Date().toISOString().substr(0, 10),
      formatoFecha: this.darFormatoFecha(
        new Date().toISOString().substr(0, 10)
      ),
      menuFecha: false,

      importePersonalizado: "",
      importeRules: [
        (v) => !!v || "Importe requerido",
        (v) => v >= 0 || "Importe no valido",
      ],
      editarMonto: true,
      observacion: null,
      detalleSeleccionado: null,
      descripcion: null,
      monthNames: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
      ]
    };
  },
  watch: {
    fecha(val) {
      this.formatoFecha = this.darFormatoFecha(this.fecha);
    },
    cuota() {
      this.fecha = new Date().toISOString().substr(0, 10);
      this.editarMonto = true;
    },
    editarMonto() {
      this.observacion = null;
    },
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    agregarDetalleCuota() {
      console.log("ingresando a agregar detalle");
      if (this.detalleSeleccionado) {
        console.log("hay detalle seleccionado");
        this.cuota.cuota_detalle.push({
          cuota_detalle_tipo: [this.detalleSeleccionado],
          descripcion: this.descripcion,
          monto: this.montoDetalleSeleccionado,
        });
        this.detalleSeleccionado = null;
        this.descripcion = null;
      }
    },
    quitarDetalleCuota(detalle) {
      console.log("->quitar detalle cuota", detalle);

      this.cuota.cuota_detalle = this.cuota.cuota_detalle.filter(
        (d) =>
          d.cuota_detalle_tipo[0].id != detalle.cuota_detalle_tipo[0].id
      );
    },

    pagarCuota() {
      this.cuota.montoTotal = this.montoTotal;
      console.log(this.cuota.cuota_detalle);
      axios
        .post(`/pago/store/${this.cuota.id}`, {
          cuotaDetalles: JSON.stringify(this.cuota.cuota_detalle),
          fechaPago: this.fecha
        })
        .then((res) => {
          this.importePersonalizado = null;
          this.callSnackbar(["Cuota Pagada", "success"]);
          this.$emit("recargarCuotas", true);
        })
        .catch((error) => {
          console.log(error);
          this.callSnackbar([
            "No se pudo realizar el pago. Intente nuevamente",
            "error",
          ]);
        });
    },

    darFormatoFecha(fecha) {
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
    detalleEsBorrable(detalle) {
      if (detalle.cuota_detalle_tipo[0].codigo === 'precio_base') {
        return false;
      }
      return true;
    }
  },
  computed: {
    ...mapState("cuotas", ["tipoCuotasDetalles"]),

    tipoCuotasDetallesFaltantes() {
      return this.tipoCuotasDetalles.filter(
        (tipo) =>
          !this.cuota.cuota_detalle.find(
            (detalle) => detalle.cuota_detalle_tipo[0].id == tipo.id
          )
      );
    },
    montoDetalleSeleccionado() {
      const detalleBase = this.tipoCuotasDetalles.find(
        (tipo) => tipo.codigo == "precio_base"
      );
      if (!this.detalleSeleccionado) return null;
      if (this.detalleSeleccionado.porcentaje) {
        return Number.parseFloat(
          detalleBase.valor * (this.detalleSeleccionado.porcentaje)
        ).toFixed(2);
      }
      if (this.detalleSeleccionado.valor) {

        return this.detalleSeleccionado.valor;
      }

      return null;
    },
    montoTotal() {
      let total = 0;
      this.cuota.cuota_detalle.forEach((detalle) => {
        total += Number.parseFloat(detalle.monto);
      });
      return total.toFixed(2);
    },
  },
};
</script>
