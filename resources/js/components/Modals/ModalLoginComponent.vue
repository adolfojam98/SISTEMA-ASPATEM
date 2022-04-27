<template>
  <div>
    <v-row justify="center">
      <v-dialog v-model="isOpen" persistent max-width="500">
        <v-card class="p-3" style="padding-bottom:40px">
          <center><h2 style="margin-bottom: 15px; padding-top: 15px;">Inicar sesión</h2></center>
          <hr>
          
          <v-form @submit.prevent="ingresar()" style="margin: 25px;">
            <v-text-field
              class="m-3"
              v-model="email"
              label="Email"
              append-icon="mdi-account"
              style="maxWidth: 450px"
            ></v-text-field>
            <v-text-field
              class="m-3"
              v-model="password"
              :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              :rules="[rules.required, rules.min]"
              :type="show1 ? 'text' : 'password'"
              name="input-10-1"
              label="Contraseña"
              hint="Al menos 8 caracteres"
              style="maxWidth: 450px"
              @click:append="show1 = !show1"
            ></v-text-field>
            <v-btn type="submit" class="p-3 float-right"> Ingresar </v-btn>
          </v-form>
        </v-card>
      </v-dialog>
    </v-row>

    <snackbar></snackbar>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  mounted() {
    console.log("Component mounted.");
  },
  props: {
    isOpen: Boolean,
    getSessionStatus: Function
  },
  data() {
    return {
      email: "",
      password: "",
      show1: false,

      rules: {
        required: (value) => !!value || "Requerido.",
        min: (v) => v.length >= 8 || "Minimo 8 caracteres",
        emailMatch: () => `The email and password you entered don't match`,
      },
    };
  },
  methods: {
    ...mapActions(["callSnackbar"]),
    async ingresar() {
      try {
        var resp = await axios.post("/login", {
          email: this.email,
          password: this.password,
        });
        this.getSessionStatus()
      } catch (e) {
        console.log(e);
        this.callSnackbar(["Credenciales incorrectas", "error"]);
      }
    },
  },
};
</script>