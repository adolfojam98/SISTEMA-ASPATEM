<template>
  <div>
    <div>
      <v-app>
        <v-container fill-height>
          <v-row justify="center" align="center">
            <v-col cols="12" sm="4">
              <v-card style="width: 600px; padding: 30px; margin-bottom: 200px">
                <div
                  class="pa-2"
                  outlined
                  style="background-color: rgb(26, 35, 126)"
                  tile
                >
                  <h1 style="color: white"><center>ASPATEM</center></h1>
                </div>

                <v-form @submit.prevent="ingresar()" style="margin-top: 50px">
                  <v-text-field
                    v-model="email"
                    label="Usuario"
                    append-icon="mdi-account"
                  ></v-text-field>
                  <v-text-field
                    v-model="password"
                    :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                    :rules="[rules.required, rules.min]"
                    :type="show1 ? 'text' : 'password'"
                    name="input-10-1"
                    label="Contraseña"
                    hint="Al menos 8 caracteres"
                    counter
                    @click:append="show1 = !show1"
                  ></v-text-field>
                  <center>
                    <v-btn class="mr-4 mt-4" type="submit"> Ingresar </v-btn>
                  </center>
                  <snackbar></snackbar>
                </v-form>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-app>
    </div>

    <v-footer color="#1A237E" app class="hover">
      <span class="white--text" style="color: white"
        >&copy; {{ new Date().getFullYear() }}</span
      >
    </v-footer>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  mounted() {
    console.log("Component mounted.");
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
        var resp = await axios.post("login", {
          email: this.email,
          password: this.password,
        });
        this.$router.go("/");
      } catch (e) {
        console.log(e);
        this.callSnackbar(["Credenciales incorrectas", "error"]);
      }
    },
  },
};
</script>