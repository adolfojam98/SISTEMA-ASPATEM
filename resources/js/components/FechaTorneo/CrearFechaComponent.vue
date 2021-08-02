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
        <v-btn @click="guardarFechaComponent()">Guardar</v-btn>

        <v-btn @click="guardarLocalStorage()">SAVE STORAGE</v-btn>
        <v-btn @click="cargarLocalStorage()">CARGAR STORAGE</v-btn>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
    computed: {
        ...mapState("crearFecha", ["listaCategorias"]),

        store() {
            return this.$store.state;
        }
    },
    methods: {
        ...mapMutations("crearFecha", ["setTorneos"]),
        ...mapActions(["callSnackbar"]),
        async guardarFechaComponent() {
            try {
                await axios.post("/torneo/fecha/guardar", {
                    categorias: this.store.crearFecha.listaCategorias,
                    montos: {
                        montoSociosUnaCategoria: this.store.crearFecha
                            .montoSociosUnaCategoria,
                        montoSociosDosCategorias: this.store.crearFecha
                            .montoSociosDosCategorias,
                        montoNoSociosUnaCategoria: this.store.crearFecha
                            .montoNoSociosUnaCategoria,
                        montoNoSociosDosCategorias: this.store.crearFecha
                            .montoNoSociosDosCategorias
                    },
                    nombreFecha: this.store.crearFecha.nombreFecha
                });
            } catch (e) {
                this.callSnackbar(["No se ha podido guardar. " + e, "error"]);
            }
        },

        guardarLocalStorage() {
            localStorage.crearFecha = JSON.stringify(this.store.crearFecha) ;
        },
        cargarLocalStorage() {
            this.store.crearFecha = JSON.parse(localStorage.crearFecha) ;
            console.log(this.store.crearFecha);
        }
    },

    created() {
        axios.get("/torneos").then(res => {
            this.setTorneos(res.data);
        });
    }
};
</script>

<style></style>
