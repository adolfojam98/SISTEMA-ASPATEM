<template>
  <div class="pa-6 flex justify-between">
    <h1 class="text-2xl font-bold">Ajustes de cuotas</h1>
    <v-divider> </v-divider>
    <h2 class="text-2xl font-bold">Tipos de detalles de cuotas</h2>
    <v-row justify="space-between">
      <v-col cols="8">
        <v-select
          :items="tiposCuotaDetalles"
          v-model="tipoCuotaDetalleSeleccionado"
          label="Seleccione el tipo de detalle"
          return-object
          item-text="nombre"
        ></v-select>
      </v-col>
      <v-col cols="2">
        <v-btn
          @click="iniciarNuevoTipoDetalle()"
          class="mx-2"
          fab
          dark
          color="success"
        >
          <v-icon dark> mdi-plus </v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-divider></v-divider>
    <div v-if="tipoCuotaDetalleSeleccionado">
      <v-container>
        <v-row>
          <v-col cols="8">
            <v-text-field
              v-model="tipoCuotaDetalleSeleccionado.nombre"
              :counter="10"
              label="Nombre"
              required
            ></v-text-field>
          </v-col>

          <v-col cols="6">
            <h3>Calculo por:</h3>
            <v-radio-group v-model="tipoDescuento">
              <v-radio value="porcentaje" label="porcentaje"
                >Porcentaje
              </v-radio>
              <v-radio value="valor" label="Monto">Monto </v-radio>
            </v-radio-group>
          </v-col>

          <v-col cols="6">
            <h3>Tipo:</h3>
            <v-radio-group v-model="tipoPorcentaje">
              <v-radio value="descuento" label="descuento">Recargo </v-radio>
              <v-radio value="recargo" label="recargo">Recargo </v-radio>
            </v-radio-group>
          </v-col>

          <v-col cols="8">
            <v-text-field
              :disabled="tipoDescuento == 'valor'"
              v-model="tipoCuotaDetalleSeleccionado.porcentaje"
              suffix="%"
              type="number"
              label="ingrese un porcentaje"
              required
              min="0"
              max="100"
            ></v-text-field>
          </v-col>

          <v-col cols="8">
            <v-text-field
              :disabled="tipoDescuento == 'porcentaje'"
              v-model="tipoCuotaDetalleSeleccionado.valor"
              label="monto"
              required
            ></v-text-field>
          </v-col>
        </v-row>
        <v-btn
          v-if="tipoCuotaDetalleEsNuevo"
          class="mr-4"
          color="success"
          @click="agregarTipoCuotaDetalle()"
        >
          Generar <v-icon right dark> mdi-plus </v-icon>
        </v-btn>
        <v-btn
          v-if="!tipoCuotaDetalleEsNuevo"
          class="mr-4"
          color="primary"
          @click="actualizarTipoCuotaDetalle()"
        >
          Actualizar <v-icon right dark> mdi-update </v-icon>
        </v-btn>
        <v-btn
          v-if="!tipoCuotaDetalleEsNuevo"
          class="mr-4"
          color="error"
          @click="elminarTipoCuotaDetalle()"
        >
          Eliminar <v-icon right dark> mdi-delete </v-icon>
        </v-btn>
      </v-container>
    </div>
  </div>
</template>


<!-- TODO se tiene que consultar a la api para traer los tipos de detalles de cuotas y mostrar, permitiendo modificar cada uno de ellos -->


<script>
import { mapActions } from "vuex";
export default {
  data() {
    return {
      tiposCuotaDetalles: [],
      tipoCuotaDetalleSeleccionado: null,
      tipoDescuento: "",
      tipoPorcentaje: "",
      tipoCuotaDetalleEsNuevo: false,
    };
  },
  created() {
    this.getTiposCuotaDetalles();
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    async getTiposCuotaDetalles() {
      const response = await axios.get("/cuota/detalle/tipo");
      this.tiposCuotaDetalles = response.data.body;
      this.parsearPorcentajesCuotaTipoDetalles();
    },
    parsearPorcentajesCuotaTipoDetalles() {
      this.tiposCuotaDetalles.forEach((tipoCuotaDetalle) => {
        if (tipoCuotaDetalle.porcentaje) {
          tipoCuotaDetalle.porcentaje = tipoCuotaDetalle.porcentaje * 100;
        }
      });
    },
    iniciarNuevoTipoDetalle() {
      this.tipoCuotaDetalleEsNuevo = true;
      this.tipoCuotaDetalleSeleccionado = {
        nombre: "",
        porcentaje: 0,
        valor: 0,
        tipo: "",
      };
    },
    agregarTipoCuotaDetalle() {
      axios
        .post("/cuota/detalle/tipo", this.tipoCuotaDetalleSeleccionado)
        .then((response) => {
          this.getTiposCuotaDetalles();
          this.tipoCuotaDetalleEsNuevo = false;
          this.tipoCuotaDetalleSeleccionado = null;

          this.callSnackbar(["Tipo de cuota detalle agregado", "success"]);
        });
    },

    actualizarTipoCuotaDetalle() {
      axios
        .put(
          `/cuota/detalle/tipo/${this.tipoCuotaDetalleSeleccionado.id}`,
          this.getCuotaActualizar()
        )
        .then((response) => {
          console.log("holii");
          this.getTiposCuotaDetalles();
          this.getTipoCuotaDetalles(this.tipoCuotaDetalleSeleccionado.id);
          this.callSnackbar(["Tipo de cuota detalle actualizado", "success"]);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    elminarTipoCuotaDetalle() {
      axios
        .delete(`/cuota/detalle/tipo/${this.tipoCuotaDetalleSeleccionado.id}`)
        .then((response) => {
          this.getTiposCuotaDetalles();
          this.tipoCuotaDetalleSeleccionado = null;
          this.callSnackbar(["Tipo de cuota detalle eliminado", "success"]);
        })
        .catch((error) => {
          const msj = JSON.parse(error.request.response)?.message
          this.callSnackbar([msj || 'Error al eliminar.', "error"]);
        });
    },

    getTipoCuotaDetalles(id) {
      console.log(id);
      axios
        .get(`/cuota/detalle/tipo/${id}`)
        .then((response) => {
          console.log(response.data.body);
          this.tipoCuotaDetalleSeleccionado = response.data.body;
          this.tipoCuotaDetalleSeleccionado.porcentaje =
            this.tipoCuotaDetalleSeleccionado.porcentaje * 100;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getCuotaActualizar() {
      let cuotaActualizada = {
        id: this.tipoCuotaDetalleSeleccionado.id,
        nombre: this.tipoCuotaDetalleSeleccionado.nombre,
        porcentaje: this.tipoCuotaDetalleSeleccionado.porcentaje,
        valor: this.tipoCuotaDetalleSeleccionado.valor,
      };

      this.tipoDescuento == "porcentaje"
        ? (cuotaActualizada.valor = null)
        : (cuotaActualizada.porcentaje = null);

      if (this.tipoDescuento == "porcentaje") {
        cuotaActualizada.porcentaje = cuotaActualizada.porcentaje / 100;
      }
      if (this.tipoPorcentaje == "descuento") {
        cuotaActualizada.valor = cuotaActualizada.valor * -1;
      }
      return cuotaActualizada;
    },
  },
  watch: {
    tipoCuotaDetalleSeleccionado() {
      if (this.tipoCuotaDetalleSeleccionado) {
        this.tipoDescuento = this.tipoCuotaDetalleSeleccionado.porcentaje
          ? "porcentaje"
          : "valor";

        if (this.tipoDescuento == "porcentaje") {
          this.tipoPorcentaje =
            this.tipoCuotaDetalleSeleccionado.porcentaje > 0
              ? "descuento"
              : "recargo";
        }
      }
    },
  },
};
</script>