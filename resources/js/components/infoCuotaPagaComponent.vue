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

                <v-card class="pa-2" outlined tile>
                    Clave de usuario: {{ usuario.nombre }} {{usuario.apellido}}<br />
                </v-card>

                <v-card class="pa-2" outlined tile>
                    Mes al que corresponde: {{ cuota.mes }}/{{
                        cuota.anio
                    }}<br />
                </v-card>

                <v-card class="pa-2" outlined tile>
                    Importe de la cuota: ${{ cuota.importe }}<br />
                </v-card>

                <v-card class="pa-2" outlined tile>
                    Fecha de pago: {{ cuota.fechaPago }}<br />
                </v-card>

                <div v-if="cuota.descuento">
                    <v-card class="pa-2" outlined tile>
                        <h2>Se aplico el descuento de Familiar/Amigo</h2>
                    </v-card>
                </div>
            </v-card>
    </div>
</template>

<script>
export default {
    props : ['usuario','cuota']
}
</script>