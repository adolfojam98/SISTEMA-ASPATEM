<template>
    <div class="text-center">
      <v-dialog
        v-model="dialog"
        width="500"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            color="primary"
            v-bind="attrs"
            v-on="on"
          >
            Generar cuota
          </v-btn>
        </template>
  
        <v-card>
        <h2 class="pt-3"><center>Generar cuota socio</center></h2>
        <br />
        <v-form v-model="valid" ref="form" lazy-validation>
          <div class="px-9 pb-5">
            <v-select
              v-model="mes"
              :items="meses"
              item-text="nombre"
              item-value="id"
              label="Mes"
            ></v-select>
  
            <v-select v-model="anio" :items="anios" label="Año"></v-select>
            <div class="d-flex justify-space-between">
              <v-btn
                depressed
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
          </div>
        </v-form>
      </v-card>
      </v-dialog>
    </div>
  </template>

  
  <script>
  import { mapActions } from "vuex";
  
  export default {
    props: ["usuario"],
  
    data() {
      return {
        dialog: false,
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
  
          const nuevaCuota = await axios.post(`/generarCuota/${this.usuario.id}`, {
            fecha: fecha,
          });
          this.cerrarDialog();
          console.log("->nueva cuota: ", nuevaCuota);
          this.callSnackbar([nuevaCuota.data.message, "success"]);
          this.$emit("recargarCuotas", true);
          this.dialog = false;
        } catch (e) {
          this.callSnackbar([e.response.data.message, "error"]);
        }
      },
    },
  };
  </script>