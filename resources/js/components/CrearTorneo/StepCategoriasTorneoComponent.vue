<template>
    <div>
        <v-card class="mb-12" min-height="360px">
            <v-row>
                <v-col cols="8" md="4">
                    <v-container>
                        <v-form v-model="valid" lazy-validation>
                            <v-card elevation="4" class="rounded-sm"> </v-card>

                            <v-spacer></v-spacer>

                            <v-card elevation="8">
                                <agregar-categoria-component></agregar-categoria-component>
                            </v-card>
                        </v-form>
                    </v-container>
                </v-col>

                <v-col>
                    <tabla-categorias-component></tabla-categorias-component>
                </v-col>
            </v-row>
        </v-card>
        <v-btn
            color="primary"
            @click="setStep(3)"
            :rules="rulesCategorias()"
            :disabled="!valid"
        >
            Continuar
        </v-btn>
    </div>
</template>

<script>
import { mapMutations , mapState} from 'vuex';

import AgregarCategoriaComponent from "./AgregarCategoriaComponent.vue";
import TablaCategoriasComponent from "./TablaCategoriasComponent.vue";
export default {
    components: { AgregarCategoriaComponent, TablaCategoriasComponent },
    data() {
        return {
            valid: false,
           
            //RULES
            nombreCategoriaRules: [
                v => !!v || "Nombre requerido",
                v =>
                    /^([A-Za-z]([A-Za-z0-9]*[ \t\n\r\f]?[A-Za-z0-9])*)+$/.test(
                        v
                    ) || "Nombre invalido",
                v => v.length <= 30 || "Demasiado largo"
            ],
            puntosRules: [
                v => !!v || "Puntos requeridos",
                v =>
                    /^([0-9]*)?[0-9]+$/.test(v) ||
                    "Los puntos deben ser numeros enteros"
            ]
        };
    },
    computed: {
        ...mapState("CrearTorneo", ["arrayCategorias"])
    },

    methods: {
        ...mapMutations('CrearTorneo',['setStep']),
        rulesCategorias() {
            if (this.arrayCategorias.length >= 1) {
                return true;
            } else {
                return false;
            }
        }
    }
};
</script>
