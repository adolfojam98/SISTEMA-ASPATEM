<template>
    <v-col cols="12" md="6">
        <v-row>
            <div>
                <v-container
                color="#546E7A">
                    <h1>{{jugador1.apellido}}</h1>
                    <h1>{{jugador2.apellido}}</h1>
                </v-container>
            </div>
        </v-row>
    </v-col>
</template>




<script>
export default {
    props: ["jugador1", "jugador2"],
    data: () => ({
        
    })
};
</script>