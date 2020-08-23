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

            <div class="ml-3 mt-3">
                <div class="text-h6">Usuario</div>
                <div class="text-body-1 ml-1">
                    {{ usuario.nombre }} {{ usuario.apellido }}
                </div>

                <v-divider class="mt-3"></v-divider>

                <div class="text-h6">Mes al que corresponde</div>
                <div class="ml-1 text-body-1">
                    {{ cuota.mes }} del {{ cuota.anio }}
                </div>
                <v-divider class="mt-3"></v-divider>

                <div class="text-h6">Importe de la cuota</div>
                <div class="ml-1 text-body-1">${{ cuota.importe }}</div>
                <div v-if="cuota.descuento">
                    <h5 class="text--secondary">
                        (*)Se aplico el descuento de Familiar/Amigo
                    </h5>
                </div>

                <v-divider class="mt-3"></v-divider>

                <div class="text-h6">Fecha de pago</div>
                <div class="ml-1 text-body-1">{{ cuota.fechaPagoNombre }}</div>

                <br />
            </div>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ["usuario", "cuota"]
};
</script>
