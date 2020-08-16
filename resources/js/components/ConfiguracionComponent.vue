<template>
    <div>
        <v-card>
<v-container grid-list-xs>
    
        <v-form v-model="valid" ref="form" lazy-validation>
            <v-container>
                <v-text-field
                    v-model="montoCuota"
                    :rules="montoRules"
                    prefix="$"
                    label="Monto de la cuota"
                    required
                ></v-text-field>

                <v-text-field
                    v-model="montoCuotaDescuento"
                    :rules="montoRules"
                    prefix="$"
                    label="Monto de la cuota con descuento"
                    required
                ></v-text-field>

                <v-btn
                    depressed
                    color="primary"
                    :disabled="!valid"
                    @click.prevent="guardarConfiguracion"
                    >Guardar cambios</v-btn
                >
            </v-container>
        </v-form>

</v-container>
        </v-card>
        <v-snackbar v-model="snackbar" timeout="3000">
            Configuracion guardada correctamente.

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
        snackbar: false,
        montoCuota:0,
        montoCuotaDescuento:0,
        
        
        montoRules: [
            v => !!v || "Monto requerido",
            v => /^([0-9]*[.])?[0-9]+$/.test(v) || "Debe ingresar un monto valido"
        ],
        
        
    }),



    methods: {
        cargarConfiguracion(){
            axios.get('/configuraciones')
            .then((res) => {
            this.montoCuota = res.data.montoCuota;
            this.montoCuotaDescuento = res.data.montoCuotaDescuento;
            });
        },

        guardarConfiguracion(){
            axios.put('/configuraciones',{
            montoCuota : this.montoCuota,
            montoCuotaDescuento : this.montoCuotaDescuento,
            })
            .then(
                this.cargarConfiguracion(),
                this.snackbar=true
            );
        }
    },


    mounted() {
        this.cargarConfiguracion();
    }
};
</script>