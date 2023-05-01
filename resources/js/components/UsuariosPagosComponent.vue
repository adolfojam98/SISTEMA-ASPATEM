<template>
  <div>
    <h3 cols="12">Administración de pagos</h3>
    <v-container>
      <v-row>
        <v-col cols="8">
          <v-autocomplete
            v-model="usuarioSeleccionado"
            :items="usuarios"
            :item-text="nombreCompleto"
            return-object
            filled
            label="Ingrese el nombre del socio"
          ></v-autocomplete>
        </v-col>
      </v-row>

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
            <!-- <v-btn
              @click="[(CrearCuotaModal = true)]"
              large
              color="teal lighten-3"
              class="white--text"
            >
              Nueva cuota
            </v-btn> -->
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
                v-if="cuota?.pago?.fecha_pago != null"
                @click="
                  [(infoCuotaPaga = !infoCuotaPaga), (cuotaActual = cuota)]
                "
              >
                <td>{{ monthNames && monthNames[new Date(cuota.periodo).getMonth()] }}</td>
                <td>{{ new Date(cuota.periodo).getFullYear().toString() }}</td>
                <td>${{ cuota.monto_total }}</td>
                <td>{{ cuota?.pago?.fecha_pago && darFormatoFecha(cuota.pago.fecha_pago) }}</td>
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
                <td>{{ monthNames && monthNames[new Date(cuota.periodo).getMonth()] }}</td>
                <td>{{ new Date(cuota.periodo).getFullYear().toString() }}</td>
                <td>${{ cuota.monto_total }}</td>
                <td></td>
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
      cuotasUsuario: [],
      infoCuotaPaga: false,
      pagoCuota: false,
      CrearCuotaModal: false,
      busco: false,
      recargarCuotas: false,
      generarCuotasMasivas: false,
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
  computed: {
    ...mapState("cuotas", ["tipoCuotasDetalles"]),
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    ...mapMutations("cuotas", ["setTipoCuotasDetalles"]),

    nombreCompleto: (item) => `${item.nombre} ${item.apellido} (${item.dni})`,
    async buscarCuotasUsuario() {
      try {
        if (this.usuarioSeleccionado != "") {
          axios.get(
            `/usuario/${this.usuarioSeleccionado.id}/cuotas`
          ).then((res) => {
            this.cuotasUsuario = res.data.body
          })

          if (this.cuotasUsuario.length == 0) {
            this.callSnackbar(["No se encontraron cuotas", "error"]);
          }
          this.callSnackbar(["Cuotas listadas correctamente", "success"]);
          this.calcularPeriodoCuotas();
          this.busco = true;
        }
      } catch (error) {
        console.log("->error usuario cuota: ", e);
        this.callSnackbar(["error al buscar las cuotas"]);
      }
    },
    calcularPeriodoCuotas() {
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
    actualizarAbrirModalGenerarCuotasMasivas(valor) {
      this.abrirModalGenerarCuotasMasivas = valor;
    },
    filtrarUsuariosNoSocios() {
      this.usuarios = this.usuarios.filter((usuario) => usuario.socio.socio);
    },
  },

  watch: {
    recargarCuotas: function () {
      this.buscarCuotasUsuario();
      this.pagoCuota = false;
      this.recargarCuotas = false;
    },
  },
  async created() {
    console.log("parametros usuarios pagos component:", this.$route.query);
    const idUsuario = this.$route.query.usuario;

    const responseUsuarios = await axios.get("/usuario");
    this.usuarios = responseUsuarios.data;
    console.log('->usuarios: ',this.usuarios);
    this.filtrarUsuariosNoSocios();

    const resp = await axios.get("/tipo-detalles");
    console.log("->tipo_cuota: ", resp.data);
    this.setTipoCuotasDetalles(resp.data.body);

    if (idUsuario) {
      this.usuarioSeleccionado = this.usuarios.find(
        (usuario) => usuario.id == idUsuario
      );
      if (this.usuarioSeleccionado) {
        this.buscarCuotasUsuario();
      }
    }
  },
};
</script>
