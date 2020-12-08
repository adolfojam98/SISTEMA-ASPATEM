<template>
    <div>
        <v-card>
<v-container grid-list-xs>
    
        <v-form v-model="valid" ref="form" v-if="es_socio" lazy-validation >
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
                    v-model="dni"
                    :rules="dniRules"
                    label="DNI"
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
                     prefix="$"
                    required
                ></v-text-field>

                

                <v-btn
                    block 
                    large
                    depressed
                    color="primary"
                    :disabled="!valid"
                    @click.prevent="cargarUsuario"
                    >Dar de alta y pagar</v-btn
                >
            </v-container>
        </v-form>

</v-container>



<v-container grid-list-xs>
    
        <v-form v-model="valid" ref="form" v-if="!es_socio" lazy-validation >
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
                    :rules="telefonoRules"
                    
                ></v-text-field>

                


                <v-btn
                    block 
                    large
                    depressed
                    color="primary"
                    :disabled="!valid"
                    @click.prevent="cargarUsuario"
                    >Dar de Alta</v-btn
                >
            </v-container>
        </v-form>

</v-container>



        </v-card>

        

        <v-snackbar v-model="snackbar" timeout="3000">
            <div v-text="message"></div>

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
    props : {
      es_socio : Boolean  
    },
    data: () => ({
        valid: false,
        id_usuario: null,
        message: "",

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
       
        telefono: "",
        telefonoRules: [v => !!v || "Telefono requerido"],

        importe : '',
        importeRules: [
            v => !!v || "Importe requerido",
            v => v >= 0 || "Importe no valido"
        ],

        dni: "",
        dniRules:[
            v => !!v || "Importe requerido",
            v => v >= 10000000 || "El DNI debe tener 8 caracteres",
            v => v < 100000000 || "El DNI debe tener 8 caracteres"
        ],

        email: "",
        emailRules: [
            v => !!v || "E-mail requerido",
            v => /.+@.+[.].+/.test(v) || "E-mail no valido"
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
                        telefono: this.telefono,
                        socio: this.es_socio,
                        dni: this.dni
                    })
                    .then(response => {
                        
                        this.id_usuario = response.data.id;
                        this.message = response.data.message;
                            if(this.es_socio && this.id_usuario!=null){
                                this.generarCuota();
                            }
                        this.snackbar = true;
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
    }
};
</script>
