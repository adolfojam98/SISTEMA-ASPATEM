<template>
  <div>
    <v-card>
      <h2><center>Generar cuotas masivas</center></h2>
      <br />
      <v-form v-model="valid" ref="form" lazy-validation>
        <v-col>
          <v-select
            v-model="mes"
            :items="meses"
            item-text="nombre"
            item-value="id"
            filled
            label="Mes"
          ></v-select>

          <v-select v-model="anio" :items="anios" filled label="Año"></v-select>
          <div class="d-flex justify-space-between">
            <v-btn
              depressed
              color="error"
              :disabled="!valid"
              @click.prevent="cerrarDialog"
              >Cancelar
            </v-btn>
            <v-btn
              depressed
              color="primary"
              :disabled="!valid"
              @click.prevent="generarCuota"
              >Generar cuota
            </v-btn>
          </div>
        </v-col>
      </v-form>
    </v-card>
  </div>
</template>


<script>
import { mapActions } from "vuex";

export default {
  props: ["isOpen"],

  data() {
    return {
      valid: false,
      abierto: this.isOpen,
      mes: new Date().getMonth() + 1,
      anio: new Date().getFullYear(),
      meses: [
        { id: 1, nombre: "Enero" },
        { id: 2, nombre: "Febrero" },
        { id: 3, nombre: "Marzo" },
        { id: 4, nombre: "Abril" },
        { id: 5, nombre: "Mayo" },
        { id: 6, nombre: "Junio" },
        { id: 7, nombre: "Julio" },
        { id: 8, nombre: "Agosto" },
        { id: 9, nombre: "Septiembre" },
        { id: 10, nombre: "Octubre" },
        { id: 11, nombre: "Noviembre" },
        { id: 12, nombre: "Diciembre" },
      ],
      anios: [
        new Date().getFullYear() + 1,
        new Date().getFullYear(),
        new Date().getFullYear() - 1,
      ],
    };
  },

  methods: {
    ...mapActions(["callSnackbar"]),
    cerrarDialog() {
      return this.$emit("cerrarDialog");
    },
    async generarCuota() {
      //TODO revisar el pago de cuotas, debe controlar los errores
      try {
        console.log("anio" + this.anio);
        console.log("mes" + this.mes);

        const fecha = `${this.anio}-${this.mes}-01`;
        console.log("->fecha parceada: ", fecha);

        const nuevaCuota = await axios.post("/cuota/generarCuotasMasivas", {
          fecha: fecha,
        });
        this.cerrarDialog();
        console.log("->nueva cuota: ", nuevaCuota);
        this.callSnackbar([nuevaCuota.data.message, "success"]);
      } catch (e) {
        this.callSnackbar([e.response.data.message, "error"]);
      }
    },
  },
};
</script>