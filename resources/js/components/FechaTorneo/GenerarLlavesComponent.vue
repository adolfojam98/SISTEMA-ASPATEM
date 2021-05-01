<template>
    <div>
       
        <v-container  v-for="(jugador,indice) in categoria.jugadoresAnotados" :key="jugador.id">
            <v-container v-if="indice%2==0">
                <partidos :jugador1="jugador" :jugador2="categoria.jugadoresAnotados[indice+1]"></partidos>
            </v-container>
        </v-container>

    </div>
</template>



<script>
export default {
    props: ["categoria"],
    data: () => ({
        
    })
};
</script>
