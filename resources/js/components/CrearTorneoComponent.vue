<template>
    <div>
        <v-stepper :value="e6" vertical>
            <v-stepper-step :complete="e6 > 1" editable step="1">
                Nombre del torneo
            </v-stepper-step>
            <v-stepper-content  step="1">
                <step-nombre-torneo></step-nombre-torneo>
            </v-stepper-content>

            <v-stepper-step :complete="e6 > 2" editable step="2">
                Categorías
            </v-stepper-step>

            <v-stepper-content step="2">
                <step-categorias-torneo-component></step-categorias-torneo-component>
            </v-stepper-content>

            <v-stepper-step :complete="e6 > 3" editable step="3">
                Lista de Jugadores
            </v-stepper-step>

            <v-stepper-content step="3">
                <step-lista-jugadores-torneo></step-lista-jugadores-torneo>
            </v-stepper-content>

            <v-stepper-step step="4" editable>
                Configuracion de puntos
            </v-stepper-step>
            <v-stepper-content step="4">
                <step-configuracion-puntos-torneo></step-configuracion-puntos-torneo>
                <v-btn
                    color="primary"
                    @click="generarTorneo()"
                    :disabled="!valid"
                >
                    Guardar
                </v-btn>
            </v-stepper-content>
        </v-stepper>

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

<!--como wea hacer para ver el progreso de un socio en los torneos si son independientes (relacionamos los terneos?)
podria ser sino una nueva fecha de un torneo anterior entonces aqui aplicamos una relacion-->

<script>
import { mapMutations, mapState } from "vuex";
import StepCategoriasTorneoComponent from "./CrearTorneo/StepCategoriasTorneoComponent.vue";
import StepListaJugadoresTorneoComponent from "./CrearTorneo/StepListaJugadoresTorneoComponent.vue";
import StepNombreTorneoComponent from "./CrearTorneo/StepNombreTorneoComponent.vue";
export default {
    components: {
        StepNombreTorneoComponent,
        StepCategoriasTorneoComponent,
        StepListaJugadoresTorneoComponent
    },
    data: () => ({
        valid: true,
        snackbar: false,
        message: ""
    }),
    computed: {
        store() {
            return this.$store.state;
        },
        ...mapState("CrearTorneo", [
            "e6",
            "nombreTorneo",
            "gestionPuntos",
            "listaJugadores",
            "arrayCategorias"
        ])
    },

    methods: {
        ...mapMutations("CrearTorneo", ["setStep"]),

        generarTorneo() {
            let me = this;
            const nombreTorneo = this.nombreTorneo;
            const gestionPuntos = this.gestionPuntos;
            var nombreDeTorneoOcupado;
            axios.get(`/torneos/nombreOcupado/${nombreTorneo}`).then(res => {
                nombreDeTorneoOcupado = res.data;

                if (nombreDeTorneoOcupado == true) {
                    this.message = "El nombre del torneo ya esta en uso";
                    this.snackbar = true;
                } else {
                    axios
                        .post("/torneo", {
                            nombreTorneo: this.nombreTorneo,
                            gestionPuntos: this.gestionPuntos
                        })
                        .then(res => {
                            axios.post("/jugadores", {
                                id_torneo: res.data,
                                jugadores: this.listaJugadores
                            });

                            axios
                                .post("/categorias", {
                                    id_torneo: res.data,
                                    categorias: this.arrayCategorias
                                })
                                .then(res => {
                                    console.log(res.data);
                                });
                        });

                    this.message = "Torneo agregado";
                    this.snackbar = true;
                }
            });
        }
    }
};
</script>

<style></style>
