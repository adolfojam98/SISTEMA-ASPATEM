<template>
    <div>
        <v-autocomplete v-model="jugadoresSeleccionados">

        </v-autocomplete>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
 data() {
     return {
         jugadoresSeleccionados : null

     }
 },   

 computed: {
     ...mapState('CrearTorneo',['listaJugadores'])
 },
}
</script>