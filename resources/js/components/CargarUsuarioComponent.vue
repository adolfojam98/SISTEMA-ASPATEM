<template>
  <div>
    <v-card elevation ="0"
      ><v-card-title>
        
      </v-card-title>
      <v-container grid-list-xs>
        <v-form v-model="valid" ref="form" v-if="es_socio" lazy-validation>
          <v-container fluid>
                <h3 cols="12">Ingresar nuevo socio</h3>
            <v-row align="center">
              
              <v-col cols="6">
                <v-text-field
                  v-model="nombre"
                  :rules="nombreRules"
                  :counter="20"
                  label="Nombre*"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="apellido"
                  :rules="apellidoRules"
                  :counter="20"
                  label="Apellido*"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="dni"
                  :rules="dniRules"
                  label="DNI*"
                  type="number"
                  required
                ></v-text-field>
              </v-col>

              <v-col cols="6">
                <v-text-field
                  v-model="email"
                  :rules="emailRules"
                  label="E-mail"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="telefono"
                  label="Telefono"
                  type="number"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="importe"
                  label="Importe del corriente mes"
                  :rules="importeRules"
                  prefix="$"
                  type="number"
                ></v-text-field>
              </v-col>

              <div class="ml-2">
                <v-btn
                  center
                  large
                  depressed
                  color="primary"
                  :disabled="!valid"
                  @click.prevent="cargarUsuario"
                  >Dar de alta</v-btn
                >
              </div>
            </v-row>
          </v-container>
        </v-form>
      </v-container>

      <v-container grid-list-xs>
        <v-form v-model="valid" ref="form" v-if="!es_socio" lazy-validation>
          <v-container>
            <h1>no es socio</h1>
            <v-text-field
              v-model="nombre"
              :rules="nombreRules"
              :counter="20"
              label="Nombre"
              required
            ></v-text-field>

            <v-text-field
              v-model="apellido"
              :rules="apellidoRules"
              :counter="20"
              label="Apellido"
              required
            ></v-text-field>

            <v-text-field
              v-model="dni"
              :rules="dniRules"
              label="DNI"
              type="number"
              required
            ></v-text-field>

            <v-text-field
              v-model="email"
              :rules="emailRules"
              label="E-mail"
            ></v-text-field>

            <v-text-field
              v-model="telefono"
              label="Telefono"
              type="number"
            ></v-text-field>
            <div >
              <v-btn 
                large
                depressed
                color="primary"
                :disabled="!valid"
                @click.prevent="cargarUsuario"
                >Dar de Alta</v-btn
              >
            </div>
          </v-container>
        </v-form>
      </v-container>
    </v-card>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  props: {
    es_socio: Boolean,
  },
  data: () => ({
    valid: false,
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
    cargarUsuario() {
      if (this.valid) {
        axios
          .post("/usuario", {
            nombre: this.nombre,
            apellido: this.apellido,
            mail: this.email,
            telefono: this.telefono,
            socio: this.es_socio,
            dni: this.dni,
          })
          .then((response) => {
            this.callSnackbar([response.data.message, "success"]);
            this.id_usuario = response.data.id;

            if (this.es_socio && this.id_usuario != null) {
              this.generarCuota();
            }
            console.log('la mama del gonza')
             this.$router.push({ path: '/usuarios/lista', replace: true })
          })
          .catch((error) => {
              console.log(error)
            const errores = error.response.data.errors;
           if (errores["nombre"])
          this.callSnackbar([errores["nombre"][0], "error"]);
           if (errores["apellido"])
          this.callSnackbar([errores["apellido"][0], "error"]);
           if (errores["dni"])
          this.callSnackbar([errores["dni"][0], "error"]);
            console.log(errores.response);
          });
      }
    },

  async  generarCuota() {
      if (!this.importe || this.importe == "" || this.importe < 0) {
        this.importe = 0;
      }
      axios
        .post(`/cuota/ingreso/${this.id_usuario}`, {
          importe: this.importe,
        })
        .then((response) => {
          this.id_cuota = response.data.body.id;
          this.pagarCuota();
        })
        .catch(function (error) {
          this.callSnackbar(["Error al guardar cuota", "error"]);
        });

      this.$refs.form.reset();
    },
     pagarCuota() {
      axios.post(`/pago/store/${this.id_cuota}`);
    },
  },
};
</script>
