<template>
    <div>
        <v-card class="mb-12" min-height="625px">
                    <v-container>
                        <v-card class="elevation-2"  >
                            <v-form  lazy-validation>
                                <center>
                                    <h2 >
                                        Jugadores de la misma categoria
                                    </h2>
                                </center>

                                <v-row>
                                    <v-col cols="12" md="4"> </v-col>

                                    <v-col> El ganador suma </v-col>

                                    <v-col> El perdedor resta </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" md="4">
                                        <v-container>
                                            El ganador tiene mayor nivel de
                                            juego
                                        </v-container>
                                    </v-col>

                                    <v-col>
                                        <v-text-field
                                            :rules="puntosRules"
                                            class="mr-2"
                                            solo
                                            label="Ej: 8"
                                            v-model="
                                                gestionPuntos.mismaCat_MayorNivel_Ganador
                                            "
                                        ></v-text-field>
                                    </v-col>

                                    <v-col>
                                        <v-text-field
                                            :rules="puntosRules"
                                            class="mr-2"
                                            solo
                                            label="Ej: 8"
                                            v-model="
                                                gestionPuntos.mismaCat_MayorNivel_Perdedor
                                            "
                                        ></v-text-field>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" md="4">
                                        <v-container>
                                            El ganador tiene menor nivel de
                                            juego
                                        </v-container>
                                    </v-col>

                                    <v-col>
                                        <v-text-field
                                        
                                            :rules="puntosRules"
                                            class="mr-2"
                                            solo
                                            label="Ej: 11"
                                            v-model="
                                                gestionPuntos.mismaCat_MenorNivel_Ganador
                                            "
                                        ></v-text-field>
                                    </v-col>

                                    <v-col>
                                        <v-text-field
                                            :rules="puntosRules"
                                            class="mr-2"
                                            solo
                                            label="Ej: 11"
                                            v-model="
                                                gestionPuntos.mismaCat_MenorNivel_Perdedor
                                            "
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-card>
                    </v-container>

                    <!--segunda tabla-->
                    <v-container>
                        <v-card class="elevation-2" >
                            <v-form  lazy-validation>
                                <center>
                                    <h2>
                                        Jugadores de diferentes categorias
                                    </h2>
                                </center>

                                <v-row>
                                    <v-col cols="12" md="4"> </v-col>

                                    <v-col> El ganador suma </v-col>

                                    <v-col> El perdedor resta </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" md="4">
                                        <v-container>
                                            El ganador tiene mayor categoria
                                        </v-container>
                                    </v-col>

                                    <v-col>
                                        <v-text-field
                                            :rules="puntosRules"
                                            class="mr-2"
                                            solo
                                            label="Ej: 5"
                                            v-model="
                                                gestionPuntos.diferenteCat_MayorNivel_Ganador
                                            "
                                        ></v-text-field>
                                    </v-col>

                                    <v-col>
                                        <v-text-field
                                            :rules="puntosRules"
                                            class="mr-2"
                                            solo
                                            label="Ej: 0"
                                            v-model="
                                                gestionPuntos.diferenteCat_MayorNivel_Perdedor
                                            "
                                        ></v-text-field>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" md="4">
                                        <v-container>
                                            El ganador tiene menor categoria
                                        </v-container>
                                    </v-col>

                                    <v-col>
                                        <v-text-field
                                            :rules="puntosRules"
                                            class="mr-2"
                                            solo
                                            label="Ej: 11"
                                            v-model="
                                                gestionPuntos.diferenteCat_MenorNivel_Ganador
                                            "
                                        ></v-text-field>
                                    </v-col>

                                    <v-col>
                                        <v-text-field
                                            :rules="puntosRules"
                                            class="mr-2"
                                            solo
                                            label="Ej: 0"
                                            v-model="
                                                gestionPuntos.diferenteCat_MenorNivel_Perdedor
                                            "
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                
                            </v-form>
                        </v-card>
                    </v-container>
                    
                </v-card>
    </div>
</template>


<script>
import { mapState } from 'vuex'
export default {
    data() {
        return {
            puntosRules: [
            v => !!v || "Puntos requeridos",
            v =>
                /^([0-9]*)?[0-9]+$/.test(v) ||
                "Los puntos deben ser numeros enteros"
        ], 
        }
    },

    methods: {
        
    },
    computed: {
        ...mapState('CrearTorneo',['gestionPuntos'])
    },
   
}
</script>