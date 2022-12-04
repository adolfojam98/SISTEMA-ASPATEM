<template>
    <div>
            <v-row>
                <v-col cols="8" md="4">
                    <v-container mb-0>
                        <v-form v-model="valid" lazy-validation>
                            <v-spacer></v-spacer>
                                <agregar-categoria-component></agregar-categoria-component>

                        </v-form>
                    </v-container>
                </v-col>

                <v-col>
                    <tabla-categorias-component></tabla-categorias-component>
                </v-col>
            </v-row>
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
