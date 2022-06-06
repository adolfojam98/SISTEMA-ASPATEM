<template>
  <div>
    <v-container>
      <v-autocomplete
        v-model="usuarioSeleccionado"
        :items="usuarios"
        :item-text="nombreCompleto"
        return-object
        filled
        label="Ingrese el nombre del socio"
      ></v-autocomplete>
      <v-row>
        <v-col>
          <v-btn @click="buscarCuotasUsuario" large color="primary">
            Buscar
          </v-btn>
        </v-col>
        <v-col>
          <v-btn @click="generarCuotasMasivas = true" large color="primary">
            Generar cuotas masivamente
          </v-btn>
        </v-col>

        <v-spacer></v-spacer>
        <v-col>
          <div v-show="busco">
            <v-btn
              @click="[(CrearCuotaModal = true)]"
              large
              color="teal lighten-3"
              class="white--text"
            >
              Nueva cuota
            </v-btn>
          </div>
        </v-col>

        <v-spacer></v-spacer>
      </v-row>
    </v-container>

    <v-container>
      <template>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr v-if="cuotasUsuario != ''">
                <th class="text-left">Mes</th>
                <th class="text-left">Año</th>
                <th class="text-left">Monto</th>
                <th class="text-left">Fecha Pago</th>
                <th class="text-left"></th>
              </tr>
            </thead>
            <tbody v-for="(cuota, index) in cuotasUsuario" :key="index">
              <tr
                style="cursor: pointer"
                v-if="cuota.pago"
                @click="
                  [(infoCuotaPaga = !infoCuotaPaga), (cuotaActual = cuota)]
                "
              >
                <td>{{ cuota.mes }}</td>
                <td>{{ cuota.anio }}</td>
                <td>${{ cuota.monto_total }}</td>
                <td>{{ cuota.pago.fecha_pago }}</td>
                <td>
                  <div class="text-center">
                    <v-chip color="success" dark> Pagado </v-chip>
                  </div>
                </td>
              </tr>

              <tr
                v-else
                style="cursor: pointer"
                @click="[(pagoCuota = !pagoCuota), , (cuotaActual = cuota)]"
              >
                <td>{{ cuota.mes }}</td>
                <td>{{ cuota.anio }}</td>
                <td>${{ cuota.monto_total }}</td>
                <td>{{ cuota.fechaPago }}</td>
                <td>
                  <div class="text-center">
                    <v-chip color="error" dark> Pagar </v-chip>
                  </div>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </template>
    </v-container>

    <v-dialog v-model="CrearCuotaModal" max-width="600px">
      <crear-cuota
        :usuarioID="usuarioSeleccionado.id"
        @recargarCuotas="recargarCuotas = $event"
      ></crear-cuota>
    </v-dialog>

    <v-dialog v-model="infoCuotaPaga" max-width="350px">
      <info-cuota-paga
        :usuario="usuarioSeleccionado"
        :cuota="cuotaActual"
      ></info-cuota-paga>
    </v-dialog>

    <v-dialog v-model="pagoCuota" max-width="700px">
      <pago-cuota
        :cuota="cuotaActual"
        :usuario="usuarioSeleccionado"
        @recargarCuotas="recargarCuotas = $event"
      ></pago-cuota>
    </v-dialog>
    <v-dialog v-model="generarCuotasMasivas" max-width="400px">
      <generar-cuotas-masivas
        @cerrarDialog="generarCuotasMasivas = !generarCuotasMasivas"
      ></generar-cuotas-masivas>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      cuotaActual: "",
      usuarioSeleccionado: "",
      usuarios: [],
      cuotasUsuario: "",
      infoCuotaPaga: false,
      pagoCuota: false,
      CrearCuotaModal: false,
      busco: false,
      recargarCuotas: false,
      generarCuotasMasivas: false,
    };
  },
  computed: {
     ...mapState("cuotas", ["tipoCuotasDetalles"]),
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    ...mapMutations('cuotas', ['setTipoCuotasDetalles']),
     
    nombreCompleto: (item) => item.apellido + " " + item.nombre,
    async buscarCuotasUsuario() {
      try {
        if (this.usuarioSeleccionado != "") {
          this.cuotasUsuario = await axios.get(
            `/usuario/${this.usuarioSeleccionado.id}/cuotas`
          );
          this.cuotasUsuario = await this.cuotasUsuario.data.body;
          this.calcularPeriodoCuota();
          console.log(this.cuotasUsuario);
          // .then((res) => {
          //   console.log(res.data.body);
          //   console.log("->usuario cuota: ", ...res.data.body);
          //   console.log("->cuotas: ", this.cuotasUsuario);
          //   this.castearCuotasParaTabla(res.data.body);

          this.busco = true;
          // })
        }
      } catch (error) {
        console.log("->error usuario cuota: ", e);
        this.callSnackbar(["error al buscar las cuotas"]);
      }
    },
    calcularPeriodoCuota() {
      this.cuotasUsuario.forEach((cuota) => {
        let periodoDate = new Date(cuota.periodo);
        cuota.mes = periodoDate.getMonth() + 1;
        cuota.anio = periodoDate.getFullYear();
      });
    },

    castearCuotasParaTabla(cuotasResponse) {
      cuotasResponse.forEach((cuota) => {
        this.cuotasUsuario.push({
          mes: periodoDate.getMonth() + 1,
          anio: periodoDate.getFullYear(),
          importe: this.calcularImporte(cuota),
          fechaPago: this.calcularFechaPago(cuota),
          detalles: cuota.cuota_detalle,
        });
      });
    },

    calcularImporte(cuota) {
      if (cuota.pago) return cuota.pago.monto_total;
      return cuota.cuota_detalle.reduce((total, cuotaDetalle) => {
        return Number.parseFloat(total) + Number.parseFloat(cuotaDetalle.monto);
      }, 0);
    },
    calcularFechaPago(cuota) {
      if (cuota.pago) return cuota.pago.fecha_pago;
      return "";
    },

    darFormatoFecha(fecha) {
      if (!fecha) return null;
      console.log(fecha);
      fecha = fecha.substr(0, 10);
      const [anio, mes, dia] = fecha.split("-");
      return `${dia}/${mes}/${anio}`;
    },
    actualizarAbrirModalGenerarCuotasMasivas(valor) {
      this.abrirModalGenerarCuotasMasivas = valor;
    },
  },

  watch: {
    recargarCuotas: function () {
      this.buscarCuotasUsuario();
      this.pagoCuota = false;

      this.recargarCuotas = false;
    },
  },
  created() {
    axios.get("/usuario").then((res) => {
      console.log("->usuarios: ", res.data);
      this.usuarios = res.data;
    });

    axios.get("/tipo-detalles").then((res) => {
      console.log("->tipo_cuota: ", res.data);
      this.setTipoCuotasDetalles(res.data);
    });
  },
};
</script>
