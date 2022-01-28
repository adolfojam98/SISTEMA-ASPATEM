<template>
  <div>
    <v-form @submit.prevent="ingresar()">
      <v-text-field v-model="email" label="usuario" append-icon="mdi-account"></v-text-field>
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
      <v-btn class="mr-4" type="submit"> Ingresar </v-btn>
    <snackbar></snackbar>
    </v-form>
  </div>
</template>

<script>
import { mapActions} from "vuex";
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
          required: value => !!value || 'Requerido.',
          min: v => v.length >= 8 || 'Minimo 8 caracteres',
          emailMatch: () => (`The email and password you entered don't match`),}
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
        this.$router.go('/home')
      } catch (e) {
        console.log(e)
        this.callSnackbar(["Credenciales incorrectas", "error"]);
      }
    },
  },
};
</script>