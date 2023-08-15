<template>
  <div>
    <v-container class="pt-0 mx-0">
      <div class="d-flex mx-auto" style="justify-content: center;">
        <h2>Administración de pagos</h2>
      </div>

      <div class="d-flex mt-4" style="align-items: center">
        <div style="width: 300px; ">
          <v-autocomplete v-model="usuarioSeleccionado" :items="usuarios" :item-text="nombreCompleto" return-object
            label="Ingrese el nombre del socio" :search-input.sync="search" :loading="isLoading">
            <template v-slot:no-data>
              <v-list-item>
                <v-list-item-title>
                  Busque por nombre/apellido o DNI
                </v-list-item-title>
              </v-list-item>
            </template>
          </v-autocomplete>

        </div>

        <v-btn class="mx-4" @click="buscarCuotasUsuario" color="primary">
          Buscar
        </v-btn>

        <v-btn class="mx-4" @click="generarCuotasMasivas = true" color="primary">
          Generar cuotas masivamente
        </v-btn>
        <div v-if="busco">
          <anular-cuotas @anularTodasLasCuotas="anularTodasLasCuotas"></anular-cuotas>
        </div>

      </div>


      <!-- <v-row class="mt-2">
        <v-col cols="8">
          
        </v-col>
      </v-row> -->

      <v-row>
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

    <v-container class="d-flex" style="justify-content: center;">
      <v-card>
        <template>
          <div style="width: 600px">
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
                  <tr style="cursor: pointer" v-if="cuota?.pago?.fecha_pago != null" @click="
                    [(infoCuotaPaga = !infoCuotaPaga), (cuotaActual = cuota)]
                    ">
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

                  <tr v-else style="cursor: pointer" @click="[(pagoCuota = !pagoCuota), , (cuotaActual = cuota)]">
                    <td>{{ monthNames && monthNames[new Date(cuota.periodo).getMonth()] }}</td>
                    <td>{{ new Date(cuota.periodo).getFullYear().toString() }}</td>
                    <td>${{ cuota.monto_total }}</td>
                    <td></td>

                    <td>
                      <div class="text-center">
                        <v-chip class="mr-2" color="error" dark> Pagar </v-chip>
                        <v-chip color="error" dark @click.stop="[modalAnularCuota = true, , (cuotaActual = cuota)]">
                          Anular </v-chip>
                      </div>
                    </td>

                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </div>
        </template>
      </v-card>
    </v-container>

    <v-dialog v-model="CrearCuotaModal" max-width="600px">
      <crear-cuota :usuarioID="usuarioSeleccionado.id" @recargarCuotas="recargarCuotas = $event"></crear-cuota>
    </v-dialog>

    <v-dialog v-model="infoCuotaPaga" max-width="350px">
      <info-cuota-paga :usuario="usuarioSeleccionado" :cuota="cuotaActual"></info-cuota-paga>
    </v-dialog>

    <v-dialog v-model="pagoCuota" max-width="700px">
      <pago-cuota :cuota="cuotaActual" :usuario="usuarioSeleccionado"
        @recargarCuotas="recargarCuotas = $event"></pago-cuota>
    </v-dialog>
    <v-dialog v-model="generarCuotasMasivas" max-width="400px">
      <generar-cuotas-masivas @cerrarDialog="generarCuotasMasivas = !generarCuotasMasivas"></generar-cuotas-masivas>
    </v-dialog>

    <v-dialog v-model="modalAnularCuota" max-width="600">
      <v-card>
        <template v-slot:activator="{ on, attrs }">

        </template>
        <h2 class="pa-3">
          <center>Desea anular esta cuota?</center>
        </h2>
        <v-card-text>
          <v-textarea name="input-7-1" label="Observación" v-model="observacion" rows="2"></v-textarea>

        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" @click="modalAnularCuota = false">
            Cancelar
          </v-btn>
          <v-btn @click="[modalAnularCuota = false, anularCuota()]">
            APLICAR
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from "axios";
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      search: null,
      isLoading: false,
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
      modalAnularCuota: false,
      observacion: "",
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
    anularCuota() {
      console.log("anular");
      axios.post(`/cuotas/${this.cuotaActual.id}/cancelar`, {
        "descripcion": this.observacion
      }).then((res) => {
        this.callSnackbar(["Cuota Anulada correctamente", "success"]);
        this.buscarCuotasUsuario();
      }).catch((error) => {
        console.log(error);
        this.callSnackbar(["No se pudo anular la cuota", "error"])
      })
    },
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
          seleccionada: false,
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
    anularTodasLasCuotas(descripcion) {
      this.cuotasUsuario.forEach(cuota => {
        if (cuota?.pago?.fecha_pago == null) {
          axios.post(`/cuotas/${cuota.id}/cancelar`, {
            'descripcion': descripcion
          }).then(() => {
            this.callSnackbar(["Cuotas anuladas correctamente", "success"]);
            this.buscarCuotasUsuario();
          }).catch(e => this.callSnackbar(["Hubo un error en cancelar cuotas", "error"]));
        }
      });
    },
    async getJugadores(idUsuario) {
      this.isLoading = true;
      const responseUsuarios = await axios.get("/usuario", { params: { search: this.search, socio: true, id: idUsuario } });
      this.usuarios = responseUsuarios.data.usuarios.data;
      console.log('->usuarios: ', this.usuarios);
      if (idUsuario) {
        this.usuarioSeleccionado = this.usuarios[0];
        if (this.usuarioSeleccionado) {
          this.buscarCuotasUsuario();
        }
      }
      setTimeout(() => {
          this.isLoading = false;
      }, 20);

    },
  },

  watch: {
    recargarCuotas: function () {
      this.buscarCuotasUsuario();
      this.pagoCuota = false;
      this.recargarCuotas = false;
    },
    async search(val) {

      if (!val || val.length < 1) return;
      console.log(val);
      // Items have already been requested
      if (this.isLoading) return;
      this.getJugadores();

    },
  },
  async created() {
    console.log("parametros usuarios pagos component:", this.$route.query);
    const idUsuario = this.$route.query.usuario;
    if (idUsuario) {
      this.getJugadores(idUsuario);
    }

    const resp = await axios.get("/tipo-detalles");
    console.log("->tipo_cuota: ", resp.data);
    this.setTipoCuotasDetalles(resp.data.body);
  },

};
</script>
