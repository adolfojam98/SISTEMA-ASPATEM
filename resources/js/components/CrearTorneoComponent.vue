<template>
    <div>
        <v-stepper :value="e6" vertical>
            <v-stepper-step :complete="e6 > 1" editable step="1" @click="setStep(1)">
                Nombre del torneo
            </v-stepper-step>
            <v-stepper-content step="1" >
                <step-nombre-torneo></step-nombre-torneo>
            </v-stepper-content>

            <v-stepper-step :complete="e6 > 2" editable step="2" @click="setStep(2)">
                Categorías
            </v-stepper-step>

            <v-stepper-content step="2">
                <step-categorias-torneo-component></step-categorias-torneo-component>
            </v-stepper-content>

            <v-stepper-step :complete="e6 > 3" editable step="3" @click="setStep(3)">
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
    </div>
</template>

<!--como wea hacer para ver el progreso de un socio en los torneos si son independientes (relacionamos los terneos?)
podria ser sino una nueva fecha de un torneo anterior entonces aqui aplicamos una relacion-->

<script>
import { mapActions, mapGetters, mapMutations, mapState } from "vuex";
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
        valid: true
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
        ]),
        ...mapGetters("CrearTorneo", ["existeNombreTorneo"])
    },

    methods: {
        ...mapMutations("CrearTorneo", ["setStep","getTorneos"]),
        ...mapActions(["callSnackbar"]),

        
        async generarTorneo() {
            let me = this;
            

            if (this.existeNombreTorneo(this.nombreTorneo)) {
                
                this.callSnackbar([
                    "El nombre del torneo ya esta en uso",
                    "error"
                ]);
            } else {
                try {
                    const nuevoTorneo = await axios.post("/torneo", {
                        nombreTorneo: this.nombreTorneo,
                        gestionPuntos: this.gestionPuntos
                    });
                    const jugadores = await axios.post("/jugadores", {
                        id_torneo: nuevoTorneo.data,
                        jugadores: this.listaJugadores
                    });
                    const categorias = await axios.post("/categorias", {
                        id_torneo: res.data,
                        categorias: this.arrayCategorias
                    });
                    this.callSnackbar([
                        "Torneo agregado satisfactoriamente",
                        "success"
                    ]);
                } catch (e) {
                    this.callSnackbar([
                        "No se ha podido generar el torneo,verifique los datos ingresados. " + e,
                        "error"
                    ]);
                }
            }
        }
    },
    created() {
        this.getTorneos()
    },
};
</script>

<style></style>
