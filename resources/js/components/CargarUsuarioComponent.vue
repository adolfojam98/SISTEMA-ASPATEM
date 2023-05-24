<template>
  <div>

    <v-container>
      <v-row>
        <div class="d-flex mx-auto">
          <h2 v-if="es_socio">Ingresar nuevo socio</h2>
          <h2 v-else>Ingresar nuevo jugador</h2>
        </div>

      </v-row>
      <v-row justify="center">
        <v-col cols="6">
          <v-form ref="form" v-model="valid">
            <v-text-field v-model="nombre" :rules="nombreRules" :counter="20" label="Nombre*" required></v-text-field>
            <v-text-field v-model="apellido" :rules="apellidoRules" :counter="20" label="Apellido*" required>
            </v-text-field>
            <v-text-field v-model="dni" :rules="dniRules" label="DNI*" type="number" required></v-text-field>
            <v-text-field v-model="email" :rules="emailRules" label="E-mail"></v-text-field>
            <v-text-field v-model="telefono" label="Telefono" type="number"></v-text-field>
            <div v-if="es_socio">
              <v-text-field v-model="importe" label="Importe del corriente mes" :rules="importeRules" prefix="$"
                type="number">
              </v-text-field>
            </div>

            <v-btn :disabled="!valid" color="success" class="mr-4" @click.prevent="cargarUsuario">
              Dar de alta
            </v-btn>

            <v-btn color="error" class="mr-4" @click="resetearFormulario">
              Limpiar Formulario
            </v-btn>

          </v-form>
        </v-col>

      </v-row>
    </v-container>

  </div>
</template>
 

<script>
import { mapActions } from "vuex";
export default {
  props: {
    es_socio: Boolean,
  },
  data: () => ({
    valid: true,
    id_usuario: null,

    nombre: "",
    nombreRules: [
      (v) => !!v || "Nombre requerido",
      (v) =>
        (v && v.length <= 20) || "El nombre debe ser menor a 20 caracteres",
    ],
    apellido: "",
    apellidoRules: [
      (v) => !!v || "Apellido requerido",
      (v) =>
        (v && v.length <= 20) || "El apellido debe ser menor a 20 caracteres",
    ],

    telefono: "",
    telefonoRules: [(v) => !!v || "Telefono requerido"],

    importe: "",
    importeRules: [
      //v => !!v || "Importe requerido",
      (v) => v >= 0 || "Importe no valido",
    ],

    dni: "",
    dniRules: [
      (v) => !!v || "DNI requerido",
      (v) => v >= 10000000 || "El DNI debe tener 8 caracteres",
      (v) => v < 100000000 || "El DNI debe tener 8 caracteres",
    ],

    email: "",
    emailRules: [
      //v => !!v || "E-mail requerido",
      //v => /.+@.+[.].+/.test(v) || "E-mail no valido"
    ],
  }),
  methods: {
    ...mapActions(["callSnackbar"]),
    async cargarUsuario() {
      if (this.validarFormulario()) {
        try {
          const response = await axios
            .post("/usuario", {
              nombre: this.nombre,
              apellido: this.apellido,
              mail: this.email,
              telefono: this.telefono,
              socio: this.es_socio,
              dni: this.dni,
            });
          this.callSnackbar([response.data.message, "success"]);
          this.id_usuario = response.data.id;

          if (this.es_socio && this.id_usuario != null) {
           await this.generarCuota();
          }
          console.log("la mama del gonza");
          this.$router.push({ path: "/usuarios/lista", replace: true });

        } catch (error) {
          console.log(error);
          const errores = error.response.data.errors;
          if (errores["nombre"])
            this.callSnackbar([errores["nombre"][0], "error"]);
          if (errores["apellido"])
            this.callSnackbar([errores["apellido"][0], "error"]);
          if (errores["dni"]) this.callSnackbar([errores["dni"][0], "error"]);
          console.log(errores.response);

        }



      }
    },
    validarFormulario() {
      return this.$refs.form.validate();
    },

    async generarCuota() {
      if (!this.importe || this.importe == "" || this.importe < 0) {
        this.importe = 0;
      }

      try {
        const response = await axios.post(`/cuota/ingreso/${this.id_usuario}`, {
          importe: this.importe,
        });
        this.id_cuota = response.data.body.id;
       await this.pagarCuota();
      } catch (error) {
        this.callSnackbar(["Error al guardar cuota", "error"]);
      }
      this.resetearFormulario();
    },

    resetearFormulario() {
      this.$refs.form.reset();
    },
   async pagarCuota() {
     await axios.post(`/pago/store/${this.id_cuota}`);
    },
  },
};
</script>
