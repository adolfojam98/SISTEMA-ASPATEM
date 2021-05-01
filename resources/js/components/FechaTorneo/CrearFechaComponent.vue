<template>
    <div>
        <v-card>
            <v-container>
                <datos-fecha></datos-fecha>
                <v-card elevation="4" class="rounded-sm">
                    <jugadores-fecha></jugadores-fecha>
                </v-card>
            </v-container>
        </v-card>
        <v-card v-if="listaCategorias.length > 0" dark>
            <grupos-fecha></grupos-fecha>
        </v-card>
    </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";
export default {
    computed: {
        ...mapState("crearFecha", ["listaCategorias"])
    },
    methods: {
        ...mapMutations("crearFecha", ["setTorneos"]),

        
    },
    created() {
        axios.get("/torneos").then(res => {
            this.setTorneos(res.data);


            
        });
    }
};
</script>

<style></style>
