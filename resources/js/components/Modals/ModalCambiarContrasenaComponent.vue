<template>
  <div>
    <v-dialog v-model="abierto" width="500" persistent>
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Cambiar Contraseña
        </v-card-title>
<v-form  ref="formulario">
   <v-card-text>
          <v-text-field
            v-model="contrasenaActual"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="show1 ? 'text' : 'password'"
            name="input-10-1"
            label="Contraseña Actual"
            hint="Al menos 8 caracteres"
            counter
            @click:append="show1 = !show1"
          ></v-text-field>
          <v-text-field
            v-model="contrasenaNueva"
            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="show2 ? 'text' : 'password'"
            name="input-10-1"
            label="Contraseña Nueva"
            hint="Al menos 8 caracteres"
            counter
            @click:append="show2 = !show2"
          ></v-text-field>

          <v-text-field
            v-model="contrasenaNueva2"
            :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="show3 ? 'text' : 'password'"
            name="input-10-1"
            label="Ingrese nuevamente contraseña Nueva"
            hint="Al menos 8 caracteres"
            counter
            @click:append="show3 = !show3"
          ></v-text-field>
        </v-card-text>
</v-form>
       

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" text @click="cerrarDialog()"> cancelar </v-btn>
          <v-btn color="primary" text @click="guardarNuevaContrasena()">
            aceptar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
import { mapActions } from "vuex";
export default {
  props: {
    isOpen: Boolean,
  },

  data() {
    return {
      abierto: false,
      show1: false,
      show2: false,
      show3: false,

      contrasenaActual: "",
      contrasenaNueva: "",
      contrasenaNueva2: "",
      rules: {
        required: (value) => !!value || "Requerido.",
        min: (v) => (v && v.length >= 8) || "Minimo 8 caracteres",
        emailMatch: () => `El correo electrónico y la contraseña que ingresó no coinciden`,
      },
    };
  },
  created() {
    this.abierto = this.isOpen;
  },
  watch: {
    isOpen: function (neww, old) {
      this.abierto = neww;
    },
  },

  methods: {
    ...mapActions(["callSnackbar"]),

    async guardarNuevaContrasena() {
      try {
        const resp = await axios.put("contrasena", {
          contrasena_actual: this.contrasenaActual,
          nueva_contrasena: this.contrasenaNueva,
          confirma_nueva_contrasena: this.contrasenaNueva2,
        });

        this.callSnackbar([resp.data, "success"]);
        this.cerrarDialog();
      } catch (e) {
        const errores = e.response.data.errors;
        if (errores["confirma_nueva_contrasena"])
          this.callSnackbar([errores["confirma_nueva_contrasena"][0], "error"]);
        if (errores["nueva_contrasena"])
          this.callSnackbar([errores["nueva_contrasena"][0], "error"]);
        if (errores["contrasena_actual"])
          this.callSnackbar([errores["contrasena_actual"][0], "error "]);
      }
    },
    resetForm() {
        this.$refs.formulario.reset();
    },
    cerrarDialog() {
      this.resetForm();
      this.abierto = !this.abierto;
      this.$emit("cerrado", this.abierto);
    },
  },
};
</script>