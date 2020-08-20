<template>
    <div>
         <v-card>
                <v-card
                    class="pa-2"
                    outlined
                    style="background-color: lightgrey;"
                    tile
                >
                    <h1 style="color:blue"><center>ASPATEM</center></h1>
                </v-card>

                <v-text-field
                    v-model="importePersonalizado"
                    label="Monto personalizado(opcional)"
                    :rules="importeRules"
                    prefix="$"
                    required
                ></v-text-field>

                <center>
                    <v-btn @click="pagarCuota" large color="primary">
                        Pagar cuota
                    </v-btn>
                </center>
            </v-card>

           
            
    </div>
</template>

<script>
export default {
    props : ['cuota', 'usuario'],
    data() {
        return {
            importePersonalizado : '',
            importeRules: [
                v => !!v || "Importe requerido",
                v => v >= 0 || "Importe no valido"
            ],
            
            }
        },
        methods: {
             pagarCuota() {
            axios
                .put("/pagarCuota", {
                    importe: this.importePersonalizado,
                    id: this.cuota.id
                })
                .then(
                    this.importePersonalizado = null,
                    this.$emit('recargarCuotas', true),
                    this.snackbar = true,
                    
                );
        }
        },

}
</script>