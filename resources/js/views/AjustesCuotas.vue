<template>
  <div class="pa-6 flex justify-around">
    <center>
      <h2>Ajustes de cuotas</h2>
    </center>

    <v-container>
      <h3>Tipos de detalles de cuotas</h3>
      <div class="d-flex mt-4 align-center" style="justify-content: around">
        
        <div style="width: 300px">
            <v-select
              :items="tiposCuotaDetalles"
              v-model="tipoCuotaDetalleSeleccionado"
              label="Seleccione el tipo de detalle"
              return-object
              item-text="nombre"
            ></v-select>
          </div>
          
            <v-btn
              @click="iniciarNuevotipoImporteDR()"
              class="mx-2"
              small
              fab
              dark
              color="success"
            >
              <v-icon dark> mdi-plus </v-icon>
            </v-btn>
          
      </div>

      <div v-if="tipoCuotaDetalleSeleccionado">
        <v-divider></v-divider>
        <v-container>
          <div class="justify-center" style="display: grid">
            <div style="width: 300px">
                <v-text-field
                  v-model="tipoCuotaDetalleSeleccionado.nombre"
                  label="Nombre"
                  required
                ></v-text-field>
              </div>
          <v-row class="mx-0 mt-2" align="end">
            <div style="width: 150px">
              <small>Calculo por:</small>
              <div class="d-flex justify-left">
                <v-radio-group v-model="tipoUnidad">
                  <v-radio value="porcentaje" label="Porcentaje" :disabled="tipoCuotaDetalleSeleccionado?.codigo === 'precio_base'">Porcentaje</v-radio>
                  <v-radio value="valor" label="Monto" :disabled="tipoCuotaDetalleSeleccionado?.codigo === 'precio_base'"> Monto </v-radio>
                </v-radio-group>
              </div>
            </div>

            <div style="width: 150px">
              <small>Tipo:</small>
              <div class="d-flex" style="justify-content: right">
                <v-radio-group v-model="tipoImporteDR">
                  <v-radio value="descuento" label="Descuento" :disabled="tipoCuotaDetalleSeleccionado?.codigo === 'precio_base'">
                    Descuento
                  </v-radio>
                  <v-radio value="recargo" label="Recargo" :disabled="tipoCuotaDetalleSeleccionado?.codigo === 'precio_base'"> Recargo </v-radio>
                </v-radio-group>
              </div>
            </div>
          </v-row>

          <v-row>
            <v-col v-if="tipoUnidad == 'porcentaje'">
              <div>
              <v-text-field
                :disabled="tipoUnidad == 'valor'"
                v-model="tipoCuotaDetalleSeleccionado.porcentaje"
                suffix="%"
                type="number"
                label="Ingrese un porcentaje"
              ></v-text-field>
              </div>
            </v-col>

            <v-col v-if="tipoUnidad == 'valor'">
              <div>
              <v-text-field
                :disabled="tipoUnidad == 'porcentaje'"
                v-model="tipoCuotaDetalleSeleccionado.valor"
                label="Ingrese un monto"
                required
              ></v-text-field>
              </div>
            </v-col>
          </v-row>

          <div style="display: grid">
            <v-btn
              v-if="tipoCuotaDetalleSeleccionado?.id == undefined"
              color="primary"
              @click="agregarTipoCuotaDetalle()"
            >
              Generar <v-icon right dark> mdi-plus </v-icon>
            </v-btn>
            <v-btn
              v-if="tipoCuotaDetalleSeleccionado?.id != undefined"
              color="primary"
              @click="actualizarTipoCuotaDetalle()"
            >
              Actualizar <v-icon right dark> mdi-update </v-icon>
            </v-btn>
            <v-btn
              class="mt-2"
              v-if="tipoCuotaDetalleSeleccionado?.id != undefined"
              color="error"
              @click="elminarTipoCuotaDetalle()"
            >
              Eliminar <v-icon right dark> mdi-delete </v-icon>
            </v-btn>
          </div>
          </div>
        </v-container>
      </div>
    </v-container>
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
      tipoUnidad: "",
      tipoImporteDR: "recargo",
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
      this.parsearPorcentajesCuotatipoImporteDRs();
    },
    parsearPorcentajesCuotatipoImporteDRs() {
      this.tiposCuotaDetalles.forEach((tipoCuotaDetalle) => {
        if (tipoCuotaDetalle.porcentaje) {
          tipoCuotaDetalle.porcentaje = tipoCuotaDetalle.porcentaje * 100;
        }
      });
    },
    iniciarNuevotipoImporteDR() {
      this.tipoCuotaDetalleEsNuevo = true;
      this.tipoCuotaDetalleSeleccionado = {
        nombre: "",
        porcentaje: null,
        valor: null,
        tipo: "",
      };
    },
    agregarTipoCuotaDetalle() {
      axios
        .post("/cuota/detalle/tipo", this.getCuotaActualizar())
        .then((response) => {
          this.getTiposCuotaDetalles();
          this.tipoCuotaDetalleEsNuevo = false;
          this.tipoCuotaDetalleSeleccionado = null;
          this.callSnackbar(["Tipo de cuota detalle agregada", "success"]);
        })
        .catch((error) => {
          const msj = JSON.parse(error.request.response)?.message;
          this.callSnackbar([msj || "Error al agregar.", "error"]);
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
          this.callSnackbar(["Tipo de cuota detalle actualizada", "success"]);
        })
        .catch((error) => {
          this.callSnackbar([msj || "Error al agregar.", "error"]);
        });
    },
    elminarTipoCuotaDetalle() {
      axios
        .delete(`/cuota/detalle/tipo/${this.tipoCuotaDetalleSeleccionado.id}`)
        .then((response) => {
          this.getTiposCuotaDetalles();
          this.tipoCuotaDetalleSeleccionado = null;
          this.callSnackbar(["Tipo de cuota detalle eliminada", "success"]);
        })
        .catch((error) => {
          const msj = JSON.parse(error.request.response)?.message;
          this.callSnackbar([msj || "Error al eliminar", "error"]);
        });
    },

    getTipoCuotaDetalles(id) {
      axios
        .get(`/cuota/detalle/tipo/${id}`)
        .then((response) => {
          console.log(response.data.body);
          this.tipoCuotaDetalleSeleccionado = response.data.body;
          if(this.tipoCuotaDetalleSeleccionado.porcentaje !== null) {
            this.tipoCuotaDetalleSeleccionado.porcentaje = this.tipoCuotaDetalleSeleccionado.porcentaje * 100;
          }
        })
        .catch((error) => {});
    },

    getCuotaActualizar() {
      let cuotaActualizada = {
        id: this.tipoCuotaDetalleSeleccionado.id,
        nombre: this.tipoCuotaDetalleSeleccionado.nombre,
        // porcentaje: this.tipoCuotaDetalleSeleccionado.porcentaje,
        // valor: this.tipoCuotaDetalleSeleccionado.valor,
      };

      this.tipoUnidad == "porcentaje"
        ? (cuotaActualizada.porcentaje = parseInt(this.tipoCuotaDetalleSeleccionado.porcentaje) )
        : (cuotaActualizada.valor = parseInt(this.tipoCuotaDetalleSeleccionado.valor));

      if (this.tipoUnidad == "porcentaje") {
        cuotaActualizada.porcentaje = cuotaActualizada.porcentaje / 100;
      }

      if (this.tipoImporteDR == "descuento") {
        cuotaActualizada.valor = cuotaActualizada.valor > 0 ? cuotaActualizada.valor * -1 : cuotaActualizada.valor;
        cuotaActualizada.porcentaje = cuotaActualizada.porcentaje > 0 ? cuotaActualizada.porcentaje * -1 : cuotaActualizada.porcentaje;
      } else {
        cuotaActualizada.valor = cuotaActualizada.valor < 0 ? cuotaActualizada.valor * -1 : cuotaActualizada.valor;
        cuotaActualizada.porcentaje = cuotaActualizada.porcentaje < 0 ? cuotaActualizada.porcentaje * -1 : cuotaActualizada.porcentaje;
      }

       this.tipoUnidad == "porcentaje"
        ? (cuotaActualizada.valor = null )
        : (cuotaActualizada.porcentaje = null);

      return cuotaActualizada;
    },
  },
  watch: {
    tipoImporteDR() {
      console.log(this.tipoImporteDR)
    },
    tipoCuotaDetalleSeleccionado() {
      if (this.tipoCuotaDetalleSeleccionado) {
        this.tipoUnidad = this.tipoCuotaDetalleSeleccionado.porcentaje != null
          ? "porcentaje"
          : "valor";

        if(this.tipoUnidad == "porcentaje") {
          this.tipoImporteDR = parseInt(this.tipoCuotaDetalleSeleccionado?.porcentaje) > 0
            ? "recargo"
            : "descuento";
        } else {
          this.tipoImporteDR = parseInt(this.tipoCuotaDetalleSeleccionado?.valor) > 0
            ? "recargo"
            : "descuento";
        }

        if(this.tipoCuotaDetalleSeleccionado?.codigo === 'precio_base') {
          this.tipoImporteDR = 'recargo'
        }
      }
    },
  },
};
</script>