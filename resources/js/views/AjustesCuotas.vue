<template>
  <div>
    <v-select
      :items="tiposCuotaDetalles"
      v-model="tipoCuotaDetalleSeleccionado"
      label="Seleccione el tipo de detalle"
      return-object
      item-text="nombre"
    ></v-select
    ><v-btn @click="buscarCuotaTipoDetalle()"> Buscar </v-btn>
    <div v-if="tipoCuotaDetalleSeleccionado">
      <v-form @submit.prevent="actualizarTipoCuotaDetalle()">
        <v-container>
          <v-row>
            <v-col cols="12" md="4">
              <v-text-field
                v-model="tipoCuotaDetalleSeleccionado.nombre"
                :counter="10"
                label="Nombre"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                v-model="tipoCuotaDetalleSeleccionado.porcentaje"
                :counter="10"
                label="Porcentaje"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                v-model="tipoCuotaDetalleSeleccionado.valor"
                label="monto"
                required
              ></v-text-field>
            </v-col>
          </v-row>
          <v-btn class="mr-4" type="submit"> submit </v-btn>
        </v-container>
      </v-form>
    </div>
  </div>
</template>


<!-- TODO se tiene que consultar a la api para traer los tipos de detalles de cuotas y mostrar, permitiendo modificar cada uno de ellos -->


<script>
export default {
  data() {
    return {
      tiposCuotaDetalles: [],
      tipoCuotaDetalleSeleccionado: null,
    };
  },
  created() {
    this.getTiposCuotaDetalles();
  },
  methods: {
    async getTiposCuotaDetalles() {
      const response = await axios.get("/cuota/detalle/tipo");
      this.tiposCuotaDetalles = response.data.body;
    },

    actualizarTipoCuotaDetalle() {
      axios
        .put(`/cuota/detalle/tipo/${this.tipoCuotaDetalleSeleccionado.id}`,this.tipoCuotaDetalleSeleccionado)
        .then((response) => {
          this.getTiposCuotaDetalles();
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>