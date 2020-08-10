<template>
    <div>
        <v-form v-model="valid" ref="form" lazy-validation>
            <v-container>
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
                    v-model="email"
                    :rules="emailRules"
                    label="E-mail"
                    required
                ></v-text-field>

                <v-text-field
                    v-model="telefono"
                    label="Telefono"
                    :rules="telefonoRules"
                    required
                ></v-text-field>

                <v-text-field
                    v-model="importe"
                    label="Importe del corriente mes"
                    :rules="importeRules"
                    required
                ></v-text-field>

                <v-text-field
                    v-if="puntosPersonalizados"
                    v-model="puntos"
                    :rules="puntosRules"
                    label="puntos"
                    required
                ></v-text-field>

                <v-switch
                    v-model="puntosPersonalizados"
                    label="Ingresar puntos personalizados"
                ></v-switch>

                <v-btn
                    depressed
                    color="primary"
                    :disabled="!valid"
                    @click.prevent="cargarUsuario"
                    >Dar de alta y pagar</v-btn
                >
            </v-container>
            <v-btn @click = 'generarCuotas'> generar cuotas</v-btn>
        </v-form>

        

        <v-snackbar v-model="snackbar" timeout="3000">
            Usuario agregado corectamente

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="blue"
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                >
                    Cerrar
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    data: () => ({
        valid: false,
        id_usuario: null,

        nombre: "",
        nombreRules: [
            v => !!v || "Nombre requerido",
            v =>
                (v && v.length <= 20) ||
                "El nombre debe ser menor a 20 caracteres"
        ],
        apellido: "",
        apellidoRules: [
            v => !!v || "Apellido requerido",
            v =>
                (v && v.length <= 20) ||
                "El apellido debe ser menor a 20 caracteres"
        ],
        puntosPersonalizados: false,
        puntos: 0,
        puntosRules: [
            v => !!v || "Puntos requeridos",
            v => /^([0-9])*$/.test(v) || "Debe ingresar solo numeros"
        ],
        telefono: "",
        telefonoRules: [v => !!v || "Telefono requerido"],

        importe: 0,
        importeRules: [
            v => !!v || "Importe requerido",
            v => v >= 0 || "Importe no valido"
        ],

        email: "",
        emailRules: [
            v => !!v || "E-mail requerido",
            v => /.+@.+/.test(v) || "E-mail no valido"
        ],
        snackbar: false
    }),
    methods: {
        cargarUsuario() {
            if (this.valid) {
                axios
                    .post("/usuario", {
                        nombre: this.nombre,
                        apellido: this.apellido,
                        mail: this.email,
                        puntos: this.puntos,
                        telefono: this.telefono,
                        socio: true
                    })
                    .then(response => {
                        this.snackbar = true;
                        this.id_usuario = response.data.id;

                        this.generarCuota();
                    })
                    .catch(error => {
                        console.log(error.response);
                    });
            }
        },

        generarCuota() {
            axios
                .post("/cuota", {
                    id_usuario: this.id_usuario,
                    importe: this.importe
                })
                .then(response => {
                    this.id_cuota = response.data.id;
                    this.pagarCuota();
                })
                .catch(function(error) {
                    console.log(error);
                });

            this.$refs.form.reset();
        },

        pagarCuota() {
            axios.put("/cuota", {
                id: this.id_cuota,
                descuento: false
            });
        },
        generarCuotas() {
            axios.post("/cuotas")
            .then(res => console.log(res.data))
        }
    }
};
</script>
